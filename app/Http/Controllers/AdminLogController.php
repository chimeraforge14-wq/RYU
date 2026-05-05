<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DapodikService;

class AdminLogController extends Controller
{
    protected DapodikService $dapodikService;

    public function __construct(DapodikService $dapodikService)
    {
        $this->dapodikService = $dapodikService;
    }

    public function index()
    {
        $sekolah = $this->dapodikService->getSekolah();
        $ptks = $this->dapodikService->getPTKClassTeachers();
        $rombels = $this->dapodikService->getRombonganBelajar();
        $students = $this->dapodikService->getPesertaDidik();

        $stats = [
            'total_ptk' => count($this->dapodikService->getPTK()),
            'total_rombel' => count($rombels),
            'total_siswa' => count($students),
        ];

        return view('pages.admin.log', compact('sekolah', 'ptks', 'rombels', 'students', 'stats'));
    }
}
