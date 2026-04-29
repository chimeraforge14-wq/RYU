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

        // Fetch data from local Dapodik via API
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

        // Calculate statistics
        $totalPengguna = is_array($pengguna) ? count($pengguna) : 0;
        $totalRombel = is_array($rombonganBelajar) ? count($rombonganBelajar) : 0;
        $totalGuru = is_array($ptk) ? count($ptk) : 0;
        $totalSiswa = is_array($pesertaDidik) ? count($pesertaDidik) : 0;
        
        $totalNilai = \App\Models\Nilai::count();
        $totalProyek = \App\Models\P5Proyek::count();
        $totalPenilaianP5 = \App\Models\P5Penilaian::count();

        return view('dashboard', [
            'sekolah' => $sekolah,
            'pengguna' => $pengguna,
            'rombonganBelajar' => $rombonganBelajar,
            'totalPengguna' => $totalPengguna,
            'totalRombel' => $totalRombel,
            'totalGuru' => $totalGuru,
            'totalSiswa' => $totalSiswa,
            'totalNilai' => $totalNilai,
            'totalProyek' => $totalProyek,
            'totalPenilaianP5' => $totalPenilaianP5
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
