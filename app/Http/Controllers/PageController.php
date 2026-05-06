<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DapodikService;

class PageController extends Controller
{
    protected DapodikService $dapodikService;

    public function __construct(DapodikService $dapodikService)
    {
        $this->dapodikService = $dapodikService;
    }

    public function profile()
    {
        $username = session('username');
        $ptk_id = session('ptk_id');
        $signature_key = $ptk_id ? "signature_{$ptk_id}" : "signature_{$username}";
        $signature = \App\Models\Setting::where('key', $signature_key)->value('value');
        
        return view('pages.profile', compact('signature'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'signature' => 'nullable|image|mimes:png|max:2048'
        ]);

        $username      = session('username');
        $ptk_id        = session('ptk_id');
        $signature_key = $ptk_id ? "signature_{$ptk_id}" : "signature_{$username}";

        if ($request->hasFile('signature') && $request->file('signature')->isValid()) {
            $file   = $request->file('signature');
            $base64 = 'data:image/png;base64,' . base64_encode(file_get_contents($file->getRealPath()));

            \App\Models\Setting::updateOrCreate(
                ['key' => $signature_key],
                ['value' => $base64]
            );

            return back()->with('success', 'Tanda tangan berhasil disimpan!');
        }

        return back()->with('info', 'Tidak ada file yang diupload.');
    }

    public function pengguna()
    {
        $pengguna = $this->dapodikService->getPengguna();
        return view('pages.pengguna', compact('pengguna'));
    }

    public function referensi($type)
    {
        $data = [];
        $title = 'Data Referensi';
        
        switch (strtolower($type)) {
            case 'sekolah':
                $title = 'Data Sekolah';
                $sekolah = $this->dapodikService->getSekolah();
                // Sekolah usually returns a single associative array, wrap it to avoid array key 0 error
                if (!empty($sekolah) && !isset($sekolah[0])) {
                    $data = [$sekolah];
                } else {
                    $data = $sekolah ?: [];
                }
                break;
            case 'guru':
                $title = 'Data Pendidik & Tenaga Kependidikan';
                $data = $this->dapodikService->getPTK();
                break;
            case 'siswa':
                $title = 'Data Peserta Didik';
                $ptkId = session('ptk_id');
                $role = session('role');
                $allSiswa = $this->dapodikService->getPesertaDidik();
                
                if ($role !== 'admin' && $ptkId) {
                    $myRombels = collect($this->dapodikService->getFilteredRombonganBelajar($ptkId, $role))->pluck('rombongan_belajar_id')->toArray();
                    $data = collect($allSiswa)->filter(function($s) use ($myRombels) {
                        return in_array($s['rombongan_belajar_id'] ?? '', $myRombels);
                    })->values()->toArray();
                } else {
                    $data = $allSiswa;
                }
                break;
            case 'kelas':
                $title = 'Data Rombongan Belajar';
                $data = $this->dapodikService->getFilteredRombonganBelajar(session('ptk_id'), session('role'));
                break;
            case 'mapel':
                $title = 'Data Mata Pelajaran';
                $rombels = $this->dapodikService->getFilteredRombonganBelajar(session('ptk_id'), session('role'));
                $mapelList = [];
                foreach ($rombels as $rombel) {
                    if (isset($rombel['pembelajaran'])) {
                        foreach ($rombel['pembelajaran'] as $p) {
                            $id = $p['mata_pelajaran_id'] ?? $p['id_mapel'];
                            $mapelList[$id] = [
                                'mata_pelajaran_id' => $id,
                                'nama_mata_pelajaran' => $p['nama_mata_pelajaran'] ?? $p['mata_pelajaran_id_str']
                            ];
                        }
                    }
                }
                $data = array_values($mapelList);
                break;
            case 'pembelajaran':
                $title = 'Data Pembelajaran (Mata Pelajaran per Kelas)';
                $rombels = $this->dapodikService->getFilteredRombonganBelajar(session('ptk_id'), session('role'));
                $pembelajaranList = [];
                foreach ($rombels as $rombel) {
                    if (isset($rombel['pembelajaran'])) {
                        foreach ($rombel['pembelajaran'] as $p) {
                            $p['nama_rombel'] = $rombel['nama']; // Tambahkan info rombel
                            $pembelajaranList[] = $p;
                        }
                    }
                }
                $data = $pembelajaranList;
                break;
            case 'ekstrakurikuler':
                $title = 'Data Ekstrakurikuler';
                $rombels = $this->dapodikService->getFilteredRombonganBelajar(session('ptk_id'), session('role'));
                // Filter rombel yang jenisnya Ekstrakurikuler (biasanya jenis_rombel = 5 atau ada kata 'Ekskul' di nama)
                $data = array_filter($rombels, function($r) {
                    $jenis = strtolower($r['jenis_rombel_str'] ?? '');
                    return str_contains($jenis, 'ekstrakurikuler') || ($r['jenis_rombel'] ?? 0) == 5;
                });
                break;
        }

        return view('pages.referensi', array_merge(compact('type', 'title', 'data'), [
            'rombels' => ($type == 'pembelajaran') ? $this->dapodikService->getRombonganBelajar() : [],
            'ptks' => ($type == 'pembelajaran') ? $this->dapodikService->getPTK() : []
        ]));
    }

    public function anggotaRombel($id)
    {
        $rombels = $this->dapodikService->getRombonganBelajar();
        $siswaData = $this->dapodikService->getPesertaDidik();
        
        $rombel = null;
        foreach ($rombels as $r) {
            if (($r['rombongan_belajar_id'] ?? $r['id'] ?? '') === $id) {
                $rombel = $r;
                break;
            }
        }
        
        if (!$rombel) {
            return redirect()->route('referensi', ['type' => 'kelas'])->with('error', 'Rombongan belajar tidak ditemukan');
        }

        $anggota = [];
        if (isset($rombel['anggota_rombel']) && is_array($rombel['anggota_rombel'])) {
            // Buat index pencarian cepat untuk data siswa
            $siswaMap = [];
            foreach ($siswaData as $s) {
                $pd_id = $s['peserta_didik_id'] ?? '';
                if ($pd_id) {
                    $siswaMap[$pd_id] = $s;
                }
            }

            foreach ($rombel['anggota_rombel'] as $ar) {
                $pd_id = $ar['peserta_didik_id'] ?? '';
                if (isset($siswaMap[$pd_id])) {
                    // Gabungkan data relasi (ar) dengan master (s)
                    $anggota[] = array_merge($ar, $siswaMap[$pd_id]);
                } else {
                    // Jika data master belum tersinkronisasi
                    $ar['nama'] = $ar['nama'] ?? $ar['peserta_didik_id_str'] ?? ('ID: ' . substr($pd_id, 0, 8) . ' (Siswa tidak ditemukan)');
                    $anggota[] = $ar;
                }
            }
        } else {
            // Fallback jika tidak ada anggota_rombel, cari dari master siswa
            foreach ($siswaData as $s) {
                if (($s['rombongan_belajar_id'] ?? '') === $id) {
                    $anggota[] = $s;
                }
            }
        }

        return view('pages.anggota_rombel', compact('rombel', 'anggota'));
    }


    public function statusPenilaian($type)
    {
        $title = $type == 'input' ? 'Status Input Nilai' : 'Pencapaian Kompetensi';
        
        $rombels = $this->dapodikService->getFilteredRombonganBelajar(session('ptk_id'), session('role'));
        
        $rombelIds = collect($rombels)->map(function($r) { return $r['rombongan_belajar_id'] ?? $r['id'] ?? null; })->filter()->unique()->toArray();
        
        $nilaiCounts = \App\Models\Nilai::whereIn('rombongan_belajar_id', $rombelIds)
            ->whereNotNull('nilai_akhir')
            ->selectRaw('rombongan_belajar_id, mata_pelajaran_id, count(*) as total')
            ->groupBy('rombongan_belajar_id', 'mata_pelajaran_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->rombongan_belajar_id . '_' . $item->mata_pelajaran_id => $item->total];
            });

        $statusData = [];

        foreach ($rombels as $rombel) {
            $rombelId = $rombel['rombongan_belajar_id'] ?? $rombel['id'];
            $jmlSiswa = count($rombel['anggota_rombel'] ?? []);
            
            if (isset($rombel['pembelajaran'])) {
                foreach ($rombel['pembelajaran'] as $p) {
                    $mapelId = $p['mata_pelajaran_id'] ?? $p['pembelajaran_id'];
                    
                    // Ambil nama guru, coba beberapa kemungkinan key dari Dapodik
                    $namaGuru = $p['ptk_id_str'] ?? $p['nama_guru'] ?? $rombel['ptk_id_str'] ?? '-';
                    
                    // Hitung jumlah nilai yang sudah terisi di DB (diambil dari memori untuk optimasi)
                    $terisi = $nilaiCounts->get($rombelId . '_' . $mapelId, 0);
                    
                    $statusData[] = [
                        'rombel' => $rombel['nama'],
                        'mapel' => $p['nama_mata_pelajaran'] ?? $p['mata_pelajaran_id_str'],
                        'guru' => $namaGuru,
                        'jml_siswa' => $jmlSiswa,
                        'terisi' => $terisi,
                        'persen' => $jmlSiswa > 0 ? round(($terisi / $jmlSiswa) * 100) : 0
                    ];
                }
            }
        }

        return view('pages.status_penilaian', compact('title', 'type', 'statusData'));
    }

    public function perkembangan(Request $request, $type)
    {
        $title = $type == 'tabel' ? 'Tabel Perkembangan Nilai' : 'Grafik Perkembangan Rapor';
        $rombelId = $request->get('rombongan_belajar_id');
        $rombels = $this->dapodikService->getFilteredRombonganBelajar(session('ptk_id'), session('role'));

        // Auto-select jika cuma ada 1 rombel (khusus Guru)
        if (!$rombelId && count($rombels) === 1 && session('role') !== 'admin') {
            $rombelId = $rombels[0]['rombongan_belajar_id'] ?? $rombels[0]['id'] ?? null;
        }
        
        $data = [
            'students' => [],
            'subjects' => [],
            'grades' => []
        ];

        if ($rombelId) {
            $rombelData = collect($rombels)->firstWhere('rombongan_belajar_id', $rombelId);
            
            if ($rombelData) {
                // Ambil daftar mapel dari rombel
                if (isset($rombelData['pembelajaran'])) {
                    foreach ($rombelData['pembelajaran'] as $p) {
                        $data['subjects'][$p['mata_pelajaran_id']] = $p['nama_mata_pelajaran'];
                    }
                }

                // Ambil data siswa
                $allSiswa = $this->dapodikService->getPesertaDidik();
                $siswaMap = collect($allSiswa)->keyBy('peserta_didik_id');
                
                if (isset($rombelData['anggota_rombel'])) {
                    foreach ($rombelData['anggota_rombel'] as $ar) {
                        $pdId = $ar['peserta_didik_id'];
                        if ($siswaMap->has($pdId)) {
                            $data['students'][] = $siswaMap->get($pdId);
                        }
                    }
                }

                // Ambil semua nilai di rombel ini
                $data['grades'] = \App\Models\Nilai::where('rombongan_belajar_id', $rombelId)
                                    ->get()
                                    ->groupBy('peserta_didik_id');
            }
        }

        return view('pages.perkembangan', compact('title', 'type', 'rombels', 'rombelId', 'data'));
    }

    public function cetak($type)
    {
        $titles = [
            'leger' => 'Cetak Leger Rapor',
            'pelengkap' => 'Cetak Dokumen Pelengkap',
            'nilai' => 'Cetak Nilai Rapor'
        ];
        $title = $titles[$type] ?? 'Cetak Dokumen';
        return view('pages.blank', ['title' => $title, 'icon' => 'cetak']);
    }

    public function utility()
    {
        $lastBackup = session('last_backup_time', 'Belum pernah');
        return view('pages.utility', compact('lastBackup'));
    }

    public function kirimDapodik()
    {
        return view('pages.kirim_dapodik');
    }

    public function exportData()
    {
        $data = [
            'nilai_sumatif' => \App\Models\Nilai::all(),
            'p5_proyek' => \App\Models\P5Proyek::with('rombel')->get(),
            'p5_penilaian' => \App\Models\P5Penilaian::all(),
            'pelengkap_rapor' => \App\Models\PelengkapRapor::all(),
            'exported_at' => now()->toDateTimeString()
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT);
        $filename = 'backup_erapor_' . date('Ymd_His') . '.json';

        session(['last_backup_time' => now()->diffForHumans()]);

        return response($json)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function restoreData(Request $request)
    {
        try {
            $request->validate([
                'backup_file' => 'required|file|mimes:json'
            ]);

            $content = file_get_contents($request->file('backup_file')->getRealPath());
            $data = json_decode($content, true);

            if (!$data) {
                return back()->with('error', 'File JSON tidak valid.');
            }

            // Import Nilai
            if (isset($data['nilai_sumatif'])) {
                foreach ($data['nilai_sumatif'] as $n) {
                    \App\Models\Nilai::updateOrCreate(
                        [
                            'rombongan_belajar_id' => $n['rombongan_belajar_id'],
                            'mata_pelajaran_id' => $n['mata_pelajaran_id'],
                            'peserta_didik_id' => $n['peserta_didik_id'],
                        ],
                        [
                            'nilai_tp1' => $n['nilai_tp1'] ?? null,
                            'nilai_tp2' => $n['nilai_tp2'] ?? null,
                            'nilai_sas' => $n['nilai_sas'] ?? null,
                            'nilai_akhir' => $n['nilai_akhir'] ?? null,
                            'deskripsi_capaian' => $n['deskripsi_capaian'] ?? null,
                        ]
                    );
                }
            }

            // Import P5 Penilaian
            if (isset($data['p5_penilaian'])) {
                foreach ($data['p5_penilaian'] as $p) {
                    \App\Models\P5Penilaian::updateOrCreate(
                        [
                            'p5_proyek_id' => $p['p5_proyek_id'],
                            'rombongan_belajar_id' => $p['rombongan_belajar_id'],
                            'peserta_didik_id' => $p['peserta_didik_id'],
                        ],
                        [
                            'nilai' => $p['nilai'] ?? '',
                            'catatan_proses' => $p['catatan_proses'] ?? null,
                        ]
                    );
                }
            }

            // Import Pelengkap Rapor
            if (isset($data['pelengkap_rapor'])) {
                foreach ($data['pelengkap_rapor'] as $pl) {
                    \App\Models\PelengkapRapor::updateOrCreate(
                        [
                            'rombongan_belajar_id' => $pl['rombongan_belajar_id'],
                            'peserta_didik_id' => $pl['peserta_didik_id'],
                        ],
                        [
                            'sakit' => $pl['sakit'] ?? 0,
                            'izin' => $pl['izin'] ?? 0,
                            'tanpa_keterangan' => $pl['tanpa_keterangan'] ?? 0,
                            'catatan_wali_kelas' => $pl['catatan_wali_kelas'] ?? null,
                        ]
                    );
                }
            }

            return back()->with('success', 'Data berhasil di-restore (Ambil) ke dalam database lokal!');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal restore data: ' . $e->getMessage());
        }
    }
}
