<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class DapodikService
{
    protected string $baseUrl;
    protected string $token;
    protected string $npsn;
    protected string $semester;

    public function __construct()
    {
        // Nilai akan di-set secara dinamis dari input UI
    }

    public array $fetchErrors = [];

    /**
     * Send HTTP Request to Dapodik Web Service
     */
    protected function fetch(string $endpoint)
    {
        try {
            // Pastikan URL base tidak memiliki trailing slash atau terformat aneh
            $url = rtrim($this->baseUrl, '/');
            // Tambahkan /WebService/ jika belum ada
            if (!str_contains(strtolower($url), '/webservice')) {
                $url .= '/WebService';
            }
            $url .= '/' . $endpoint;

            $response = Http::withToken($this->token)
                ->timeout(120)
                ->get($url, [
                    'npsn' => $this->npsn,
                    'semester_id' => $this->semester
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            $this->fetchErrors[] = "[$endpoint] HTTP " . $response->status() . " - " . substr($response->body(), 0, 100);
            return null;
        } catch (\Exception $e) {
            $this->fetchErrors[] = "[$endpoint] Error: " . $e->getMessage();
            return null;
        }
    }

    /**
     * Perform full sync from Dapodik Web Service to local storage
     */
    public function syncData($baseUrl, $token, $npsn, $semester)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
        $this->npsn = $npsn;
        $this->semester = $semester;

        try {
            // Tes Koneksi Dapodik dengan mengambil data sekolah
            $sekolahData = $this->fetch('getSekolah');
            
            if ($sekolahData === null) {
                return [
                    'success' => false,
                    'message' => 'Tes Koneksi Gagal: Periksa URL, Token, NPSN, dan pastikan layanan Dapodik Lokal berjalan.'
                ];
            }

            $sekolah = $sekolahData['rows'] ?? null;
            
            $penggunaData = $this->fetch('getPengguna');
            $pengguna = $penggunaData ? ($penggunaData['rows'] ?? []) : [];
            
            $rombelData = $this->fetch('getRombonganBelajar');
            $rombonganBelajar = $rombelData ? ($rombelData['rows'] ?? []) : [];
            
            $ptkData = $this->fetch('getGtk');
            $ptk = $ptkData ? ($ptkData['rows'] ?? []) : [];

            $pesertaDidikData = $this->fetch('getPesertaDidik');
            $pesertaDidik = $pesertaDidikData ? ($pesertaDidikData['rows'] ?? []) : [];

            $data = [
                'last_sync' => now()->toDateTimeString(),
                'semester' => $this->semester,
                'sekolah' => $sekolah,
                'pengguna' => $pengguna,
                'rombonganBelajar' => $rombonganBelajar,
                'ptk' => $ptk,
                'pesertaDidik' => $pesertaDidik
            ];

            Storage::disk('local')->put('dapodik_data.json', json_encode($data));
            Cache::forget('dapodik_local_data'); // Invalidate cache setelah sync
            
            $msg = 'Tes Koneksi Dapodik Berhasil! Data ditarik pada ' . $data['last_sync'];
            if (!empty($this->fetchErrors)) {
                $msg .= ' | PERINGATAN (Beberapa data gagal ditarik): ' . implode(', ', $this->fetchErrors);
            }

            return [
                'success' => true,
                'message' => $msg
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal sinkronisasi: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Read local synced data (cached for 30 minutes)
     */
    protected function getLocalData()
    {
        return Cache::remember('dapodik_local_data', 1800, function () {
            if (Storage::disk('local')->exists('dapodik_data.json')) {
                $content = Storage::disk('local')->get('dapodik_data.json');
                return json_decode($content, true);
            }
            return null;
        });
    }

    /**
     * Get School Information
     */
    public function getSekolah()
    {
        $data = $this->getLocalData();
        $sekolah = $data['sekolah'] ?? null;

        if (!$sekolah) return null;

        // Apply School Overrides
        $overrides = \App\Models\SchoolOverride::all();
        foreach ($overrides as $override) {
            $sekolah[$override->field_name] = $override->field_value;
        }

        return $sekolah;
    }

    /**
     * Get semester info (label + tahun pelajaran) dari data Dapodik yang tersimpan
     * Semester ID Dapodik: 1 = Ganjil, 2 = Genap
     */
    public function getSemesterInfo(): array
    {
        $data = $this->getLocalData();

        // Ambil semester ID dari data sync (disimpan saat sync)
        $semesterId = $data['semester'] ?? null;

        // Konversi ID ke label
        $semesterLabel = match((string)$semesterId) {
            '1'     => 'Ganjil',
            '2'     => 'Genap',
            default => $semesterId ?? '-',
        };

        // Coba ambil tahun pelajaran dari data sekolah Dapodik
        $sekolah        = $data['sekolah'] ?? [];
        $tahunPelajaran = $sekolah['tahun_pelajaran'] ?? null;

        // Jika tidak ada di sekolah, coba generate dari last_sync
        if (!$tahunPelajaran && isset($data['last_sync'])) {
            try {
                $syncDate = \Carbon\Carbon::parse($data['last_sync']);
                $year     = (int)$syncDate->format('Y');
                $month    = (int)$syncDate->format('m');
                // Tahun pelajaran baru mulai bulan Juli
                $startYear = $month >= 7 ? $year : $year - 1;
                $tahunPelajaran = $startYear . '/' . ($startYear + 1);
            } catch (\Throwable $e) {
                $tahunPelajaran = '-';
            }
        }

        return [
            'semester'       => $semesterLabel,
            'semester_id'    => $semesterId,
            'tahun_pelajaran' => $tahunPelajaran ?? '-',
        ];
    }

    /**
     * Get All Users
     */
    public function getPengguna()
    {
        $data = $this->getLocalData();
        return $data['pengguna'] ?? [];
    }

    /**
     * Get Study Groups / Classes
     */
    public function getRombonganBelajar()
    {
        $data = $this->getLocalData();
        $rombels = $data['rombonganBelajar'] ?? [];
        
        // Ambil pembelajaran manual
        $manuals = \App\Models\PembelajaranManual::all();
        
        // Ambil rombel overrides
        $rombelOverrides = \App\Models\RombelOverride::all();

        foreach ($rombels as &$rombel) {
            $rombelId = $rombel['rombongan_belajar_id'] ?? $rombel['id'];
            
            // 1. Handle Pembelajaran Manual
            $myManuals = $manuals->where('rombongan_belajar_id', $rombelId);
            if ($myManuals->isNotEmpty()) {
                if (!isset($rombel['pembelajaran'])) {
                    $rombel['pembelajaran'] = [];
                }
                foreach ($myManuals as $m) {
                    $rombel['pembelajaran'][] = [
                        'pembelajaran_id' => 'manual-' . $m->id,
                        'mata_pelajaran_id' => $m->mata_pelajaran_id,
                        'nama_mata_pelajaran' => $m->nama_mata_pelajaran,
                        'ptk_id' => $m->ptk_id,
                        'ptk_id_str' => $m->nama_guru,
                        'is_manual' => true
                    ];
                }
            }

            // 2. Handle Rombel Member Overrides (Add/Remove/Transfer)
            if (!isset($rombel['anggota_rombel'])) {
                $rombel['anggota_rombel'] = [];
            }

            $myOverrides = $rombelOverrides->where('rombongan_belajar_id', $rombelId);
            foreach ($myOverrides as $o) {
                if ($o->action === 'add' || $o->action === 'transfer') {
                    // Cek jika sudah ada
                    $exists = collect($rombel['anggota_rombel'])->firstWhere('peserta_didik_id', $o->peserta_didik_id);
                    if (!$exists) {
                        $rombel['anggota_rombel'][] = [
                            'peserta_didik_id' => $o->peserta_didik_id,
                            'rombongan_belajar_id' => $rombelId,
                            'is_manual' => true
                        ];
                    }
                } elseif ($o->action === 'remove') {
                    $rombel['anggota_rombel'] = collect($rombel['anggota_rombel'])
                        ->filter(fn($ar) => $ar['peserta_didik_id'] !== $o->peserta_didik_id)
                        ->values()
                        ->toArray();
                }
            }

            // Juga handle jika siswa di-remove dari rombel lain (transfer out)
            $transferOut = $rombelOverrides->where('action', 'transfer')->where('from_rombongan_belajar_id', $rombelId);
            foreach ($transferOut as $o) {
                $rombel['anggota_rombel'] = collect($rombel['anggota_rombel'])
                    ->filter(fn($ar) => $ar['peserta_didik_id'] !== $o->peserta_didik_id)
                    ->values()
                    ->toArray();
            }
        }
        
        return $rombels;
    }

    /**
     * Get Filtered Study Groups based on PTK ID and Role
     */
    public function getFilteredRombonganBelajar($ptkId = null, $role = 'admin')
    {
        $rombels = $this->getRombonganBelajar();
        
        if ($role === 'admin' || !$ptkId) {
            return $rombels;
        }

        return collect($rombels)->filter(function ($rombel) use ($ptkId) {
            // 1. Check if Wali Kelas
            if (($rombel['ptk_id'] ?? '') === $ptkId) {
                return true;
            }

            // 2. Check if Subject Teacher (Guru Mapel) in this rombel
            if (isset($rombel['pembelajaran']) && is_array($rombel['pembelajaran'])) {
                foreach ($rombel['pembelajaran'] as $p) {
                    if (($p['ptk_id'] ?? '') === $ptkId) {
                        return true;
                    }
                }
            }

            return false;
        })->values()->toArray();
    }
    
    /**
     * Get Last Sync Status
     */
    public function getLastSync()
    {
        $data = $this->getLocalData();
        return $data['last_sync'] ?? null;
    }

    /**
     * Get PTK (Pendidik dan Tenaga Kependidikan)
     */
    public function getPTK()
    {
        $data = $this->getLocalData();
        $ptkDapodik = $data['ptk'] ?? [];
        
        // Ambil guru manual dari database
        $ptkManual = \App\Models\Guru::all()->map(function($g) {
            return [
                'ptk_id' => $g->ptk_id,
                'nama' => $g->nama,
                'nuptk' => $g->nuptk,
                'nik' => $g->nik,
                'email' => $g->email,
                'jenis_ptk_id_str' => $g->jenis_ptk ?? 'Guru Manual',
                'is_manual' => true
            ];
        })->toArray();

        return array_merge($ptkDapodik, $ptkManual);
    }

    /**
     * Get Peserta Didik (Siswa)
     */
    public function getPesertaDidik()
    {
        $data = $this->getLocalData();
        $siswas = $data['pesertaDidik'] ?? [];

        // Apply Student Overrides
        $overrides = \App\Models\StudentOverride::all()->groupBy('peserta_didik_id');
        
        foreach ($siswas as &$siswa) {
            $pdId = $siswa['peserta_didik_id'];
            if (isset($overrides[$pdId])) {
                foreach ($overrides[$pdId] as $o) {
                    $siswa[$o->field_name] = $o->field_value;
                }
            }
        }

        return $siswas;
    }

    /**
     * Find a user or PTK by username, email, or identity (NUPTK/NIK)
     */
    public function findUser(string $identity)
    {
        $users = $this->getPengguna();
        foreach ($users as $u) {
            if (($u['username'] ?? '') === $identity || ($u['email'] ?? '') === $identity) {
                return [
                    'type' => 'pengguna',
                    'data' => $u
                ];
            }
        }

        $ptks = $this->getPTK();
        foreach ($ptks as $p) {
            // Check NUPTK, NIK, Email, or Name as fallback
            if (($p['nuptk'] ?? '') === $identity || 
                ($p['nik'] ?? '') === $identity || 
                ($p['email'] ?? '') === $identity) {
                return [
                    'type' => 'ptk',
                    'data' => $p
                ];
            }
        }

        return null;
    }

    /**
     * Get PTK who are Class Teachers (Wali Kelas)
     */
    public function getPTKClassTeachers()
    {
        $rombels = $this->getRombonganBelajar();
        $ptks = $this->getPTK();
        $ptkMap = collect($ptks)->keyBy('ptk_id');

        $waliIds = collect($rombels)->pluck('ptk_id')->filter()->unique();
        
        $result = [];
        foreach ($waliIds as $id) {
            if ($ptkMap->has($id)) {
                $result[] = $ptkMap->get($id);
            }
        }

        return $result;
    }
}

