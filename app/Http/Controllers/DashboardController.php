<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DapodikService;

class DashboardController extends Controller
{
    protected DapodikService $dapodikService;

    public function __construct(DapodikService $dapodikService)
    {
        $this->dapodikService = $dapodikService;
    }

    public function index()
    {
        $ptkId = session('ptk_id');
        $role = session('role');

        $sekolah = $this->dapodikService->getSekolah();
        $pengguna = $this->dapodikService->getPengguna();
        $rombonganBelajar = $this->dapodikService->getFilteredRombonganBelajar($ptkId, $role);
        $ptk = $this->dapodikService->getPTK();
        $pesertaDidik = $this->dapodikService->getPesertaDidik();

        // Filter Peserta Didik for teachers
        if ($role !== 'admin' && $ptkId) {
            $myRombels = collect($rombonganBelajar)->pluck('rombongan_belajar_id')->toArray();
            $pesertaDidik = collect($pesertaDidik)->filter(function($s) use ($myRombels) {
                return in_array($s['rombongan_belajar_id'] ?? '', $myRombels);
            })->values()->toArray();
        }

        // Calculate basic statistics
        $totalPengguna = is_array($pengguna) ? count($pengguna) : 0;
        $totalRombel   = is_array($rombonganBelajar) ? count($rombonganBelajar) : 0;
        $totalGuru     = is_array($ptk) ? count($ptk) : 0;
        $totalSiswa    = is_array($pesertaDidik) ? count($pesertaDidik) : 0;

        $totalNilai       = \App\Models\Nilai::count();
        $totalProyek      = \App\Models\P5Proyek::count();
        $totalPenilaianP5 = \App\Models\P5Penilaian::count();

        // === ANALYTICS: Progress Pengisian Nilai per Rombel ===
        $rombelIds = collect($rombonganBelajar)->map(fn($r) => $r['rombongan_belajar_id'] ?? $r['id'] ?? null)->filter()->toArray();

        // Hitung total siswa per rombel dari Dapodik
        $siswaPerRombel = [];
        foreach ($rombonganBelajar as $r) {
            $rid = $r['rombongan_belajar_id'] ?? $r['id'] ?? null;
            if ($rid) {
                $siswaPerRombel[$rid] = count($r['anggota_rombel'] ?? []);
            }
        }

        // Hitung total mapel unik per rombel
        $mapelPerRombel = [];
        foreach ($rombonganBelajar as $r) {
            $rid = $r['rombongan_belajar_id'] ?? $r['id'] ?? null;
            if ($rid && isset($r['pembelajaran'])) {
                $mapelPerRombel[$rid] = count(array_unique(array_column($r['pembelajaran'], 'mata_pelajaran_id')));
            }
        }

        // Ambil count nilai terisi per rombel dari DB (1 query saja)
        $nilaiTerisiPerRombel = \App\Models\Nilai::whereIn('rombongan_belajar_id', $rombelIds)
            ->whereNotNull('nilai_akhir')
            ->selectRaw('rombongan_belajar_id, COUNT(DISTINCT peserta_didik_id) as siswa_terisi, COUNT(DISTINCT mata_pelajaran_id) as mapel_terisi')
            ->groupBy('rombongan_belajar_id')
            ->get()
            ->keyBy('rombongan_belajar_id');

        // Ambil rata-rata nilai per rombel
        $avgNilaiPerRombel = \App\Models\Nilai::whereIn('rombongan_belajar_id', $rombelIds)
            ->whereNotNull('nilai_akhir')
            ->selectRaw('rombongan_belajar_id, AVG(nilai_akhir) as rata_rata')
            ->groupBy('rombongan_belajar_id')
            ->get()
            ->keyBy('rombongan_belajar_id');

        // Build chart data per rombel
        $chartLabels   = [];
        $chartProgress = [];
        $chartAvgNilai = [];
        $rombelProgress = [];

        foreach ($rombonganBelajar as $r) {
            $rid       = $r['rombongan_belajar_id'] ?? $r['id'] ?? null;
            $namaRombel = $r['nama'] ?? 'Kelas';
            $jmlSiswa  = $siswaPerRombel[$rid] ?? 0;
            $jmlMapel  = $mapelPerRombel[$rid] ?? 0;
            $target    = $jmlSiswa * $jmlMapel; // Total sel nilai yang harus terisi

            $nilaiData   = $nilaiTerisiPerRombel->get($rid);
            $terisi      = $nilaiData ? (int)($nilaiData->siswa_terisi * ($nilaiData->mapel_terisi)) : 0;
            $persenTerisi = $target > 0 ? min(100, round(($terisi / $target) * 100)) : 0;

            $avgData  = $avgNilaiPerRombel->get($rid);
            $avgNilai = $avgData ? round($avgData->rata_rata, 1) : 0;

            $chartLabels[]   = $namaRombel;
            $chartProgress[] = $persenTerisi;
            $chartAvgNilai[] = $avgNilai;

            $rombelProgress[] = [
                'nama'         => $namaRombel,
                'jml_siswa'    => $jmlSiswa,
                'jml_mapel'    => $jmlMapel,
                'terisi'       => $terisi,
                'target'       => $target,
                'persen'       => $persenTerisi,
                'avg_nilai'    => $avgNilai,
                'rombel_id'    => $rid,
            ];
        }

        // Hitung total siswa sudah pernah ada nilai (minimal 1 mapel)
        $siswaLengkap = \App\Models\Nilai::whereIn('rombongan_belajar_id', $rombelIds)
            ->whereNotNull('nilai_akhir')
            ->selectRaw('peserta_didik_id, COUNT(DISTINCT mata_pelajaran_id) as jml_mapel')
            ->groupBy('peserta_didik_id')
            ->havingRaw('COUNT(DISTINCT mata_pelajaran_id) >= 1')
            ->count();

        if ($role === 'superadmin' && !session('school_id')) {
            $schools = \App\Models\School::withCount(['users'])->get();
            
            // Enrich schools with basic stats from JSON
            foreach ($schools as $school) {
                $school->stats = $this->dapodikService->getStatsByNpsn($school->npsn);
            }
            
            // Global statistics across all schools
            $totalSchools = $schools->count();
            
            return view('pages.superadmin.dashboard', [
                'schools' => $schools,
                'totalSchools' => $totalSchools,
            ]);
        }

        return view('dashboard', [
            'sekolah'          => $sekolah,
            'pengguna'         => $pengguna,
            'rombonganBelajar' => $rombonganBelajar,
            'totalPengguna'    => $totalPengguna,
            'totalRombel'      => $totalRombel,
            'totalGuru'        => $totalGuru,
            'totalSiswa'       => $totalSiswa,
            'totalNilai'       => $totalNilai,
            'totalProyek'      => $totalProyek,
            'totalPenilaianP5' => $totalPenilaianP5,
            // Analytics
            'rombelProgress'   => $rombelProgress,
            'chartLabels'      => json_encode($chartLabels),
            'chartProgress'    => json_encode($chartProgress),
            'chartAvgNilai'    => json_encode($chartAvgNilai),
            'siswaLengkap'     => $siswaLengkap,
        ]);
    }

    public function guru()
    {
        $ptk = $this->dapodikService->getPTK();
        return view('guru', compact('ptk'));
    }

    public function siswa()
    {
        $rombonganBelajar = $this->dapodikService->getRombonganBelajar();
        return view('siswa', ['rombonganBelajar' => $rombonganBelajar]);
    }

    public function nilai()
    {
        return view('nilai');
    }
}
