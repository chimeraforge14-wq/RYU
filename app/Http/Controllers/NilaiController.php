<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nilai;
use App\Services\DapodikService;

class NilaiController extends Controller
{
    protected DapodikService $dapodikService;

    public function __construct(DapodikService $dapodikService)
    {
        $this->dapodikService = $dapodikService;
    }

    public function index()
    {
        $rombonganBelajar = $this->dapodikService->getFilteredRombonganBelajar(session('ptk_id'), session('role'));
        return view('nilai', compact('rombonganBelajar'));
    }

    public function getMapelByRombel(Request $request)
    {
        $rombelId = $request->get('rombongan_belajar_id');
        if (!$rombelId) {
            return response()->json(['success' => false, 'message' => 'Rombel ID missing']);
        }

        $rombonganBelajar = $this->dapodikService->getRombonganBelajar();
        $rombelData = collect($rombonganBelajar)->firstWhere('rombongan_belajar_id', $rombelId);
        
        $mapels = [];
        $ptkId = session('ptk_id');
        $role = session('role');

        // Check if user is assigned as "Guru Kelas" manually
        $isGuruKelasManual = \App\Models\PembelajaranManual::where('ptk_id', $ptkId)
                            ->where('rombongan_belajar_id', $rombelId)
                            ->where('nama_mata_pelajaran', 'Guru Kelas')
                            ->exists();

        if ($rombelData && isset($rombelData['pembelajaran'])) {
            foreach ($rombelData['pembelajaran'] as $p) {
                $namaMapel = strtolower($p['nama_mata_pelajaran'] ?? $p['mata_pelajaran_id_str'] ?? '');
                $subjectTeacherId = $p['ptk_id'] ?? '';
                
                $showMapel = false;
                if ($role === 'admin') {
                    $showMapel = true;
                } else if ($ptkId && $subjectTeacherId === $ptkId) {
                    $showMapel = true;
                } else if ($isGuruKelasManual) {
                    // Logic for Guru Kelas: Show all except special subjects usually handled by others
                    $specialSubjects = ['agama', 'pendidikan jasmani', 'pjok', 'olahraga', 'inggris'];
                    $isSpecial = false;
                    foreach ($specialSubjects as $s) {
                        if (str_contains($namaMapel, $s)) { $isSpecial = true; break; }
                    }
                    
                    if (!$isSpecial) {
                        $showMapel = true;
                    }
                }

                if ($showMapel) {
                    $mapels[] = [
                        'id' => $p['mata_pelajaran_id'] ?? $p['pembelajaran_id'],
                        'nama' => $p['nama_mata_pelajaran'] ?? $p['nama_mapel'] ?? $p['mata_pelajaran_id_str']
                    ];
                }
            }
        }

        // Jika data Dapodik masih kosong atau penugasan manual murni
        if (empty($mapels) && $isGuruKelasManual) {
            $mapels = [
                ['id' => 'mapel_1', 'nama' => 'Pendidikan Pancasila'],
                ['id' => 'mapel_2', 'nama' => 'Bahasa Indonesia'],
                ['id' => 'mapel_3', 'nama' => 'Matematika'],
                ['id' => 'mapel_4', 'nama' => 'IPAS'],
                ['id' => 'mapel_5', 'nama' => 'Seni dan Budaya'],
            ];
        }

        return response()->json(['success' => true, 'mapels' => array_values(collect($mapels)->unique('id')->toArray())]);
    }

    public function getNilaiData(Request $request)
    {
        try {
            $rombelId = $request->get('rombongan_belajar_id');
            $mapelId = $request->get('mata_pelajaran_id');

            if (!$rombelId || !$mapelId) {
                return response()->json(['success' => false, 'message' => 'Missing parameters']);
            }

            $rombonganBelajar = $this->dapodikService->getRombonganBelajar();
            $semuaSiswa = $this->dapodikService->getPesertaDidik();
            
            // Buat map pencarian cepat untuk data master siswa
            $siswaMap = collect($semuaSiswa)->keyBy('peserta_didik_id');
            
            $siswaDalamRombel = [];
            
            // Cari rombel terpilih
            $rombelData = collect($rombonganBelajar)->firstWhere('rombongan_belajar_id', $rombelId);
            
            if ($rombelData && isset($rombelData['anggota_rombel'])) {
                foreach ($rombelData['anggota_rombel'] as $ar) {
                    $pdId = $ar['peserta_didik_id'] ?? '';
                    if ($pdId && $siswaMap->has($pdId)) {
                        $fullData = array_merge((array)$ar, (array)$siswaMap->get($pdId));
                        
                        // Robust name resolution
                        $namaRaw = $fullData['nama'] ?? $ar['peserta_didik_id_str'] ?? null;
                        if (!$namaRaw || strtolower($namaRaw) === 'undefined') {
                            $namaRaw = 'Siswa (' . substr($pdId, 0, 8) . ')';
                        }
                        $fullData['nama'] = $namaRaw;
                        
                        $siswaDalamRombel[] = $fullData;
                    } else if ($pdId) {
                        $namaFallback = $ar['nama'] ?? $ar['peserta_didik_id_str'] ?? null;
                        if (!$namaFallback || strtolower($namaFallback) === 'undefined') {
                            $namaFallback = 'Siswa Belum Sinkron (' . substr($pdId, 0, 8) . ')';
                        }
                        $ar['nama'] = $namaFallback;
                        $siswaDalamRombel[] = (array)$ar;
                    }
                }
            }

            // Jika anggota_rombel kosong, coba fallback ke pencarian manual di pesertaDidik
            if (empty($siswaDalamRombel)) {
                $siswaDalamRombel = collect($semuaSiswa)->where('rombongan_belajar_id', $rombelId)->toArray();
            }

            // Ambil data nilai dari database
            $nilaiDb = Nilai::where('rombongan_belajar_id', $rombelId)
                            ->where('mata_pelajaran_id', $mapelId)
                            ->get()
                            ->keyBy('peserta_didik_id');

            return response()->json([
                'success' => true,
                'students' => array_values($siswaDalamRombel),
                'grades' => $nilaiDb
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Internal Error: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function save(Request $request)
    {
        $rombelId = $request->input('rombongan_belajar_id');
        $mapelId = $request->input('mata_pelajaran_id');
        $grades = $request->input('grades', []); // [peserta_didik_id => [nilai_tp1 => x, ...]]

        if (!$rombelId || !$mapelId) {
            return response()->json(['success' => false, 'message' => 'Rombel atau Mapel tidak valid.']);
        }

        foreach ($grades as $pesertaDidikId => $data) {
            Nilai::updateOrCreate(
                [
                    'rombongan_belajar_id' => $rombelId,
                    'mata_pelajaran_id' => $mapelId,
                    'peserta_didik_id' => $pesertaDidikId
                ],
                [
                    'nilai_tp1' => $data['tp1'] ?? null,
                    'nilai_tp2' => $data['tp2'] ?? null,
                    'nilai_sas' => $data['sas'] ?? null,
                    'nilai_akhir' => $data['akhir'] ?? null,
                    'deskripsi_capaian' => $data['deskripsi'] ?? null
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Nilai berhasil disimpan otomatis ke Database!']);
    }
}
