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

    public function index(Request $request)
    {
        $role = session('role');
        $selectedNpsn = $request->query('npsn', session('npsn'));
        
        // If superadmin but no specific tenant selected in session or query,
        // and we want to avoid showing "everything" (default), we can either
        // show a list of schools or pick the first one.
        $schools = [];
        if ($role === 'superadmin') {
            $schools = \App\Models\School::all();
            if (!$selectedNpsn && $schools->isNotEmpty()) {
                $selectedNpsn = $schools->first()->npsn;
            }
        }

        // Set NPSN temporarily in session if passed via query for this request
        if ($request->has('npsn')) {
            session(['npsn' => $selectedNpsn]);
            // Also need to set reg code if we want to query DB stats
            $school = \App\Models\School::where('npsn', $selectedNpsn)->first();
            if ($school) {
                session(['registration_code' => $school->registration_code, 'school_id' => $school->id]);
            }
        }

        $sekolah = $this->dapodikService->getSekolah();
        $ptks = $this->dapodikService->getPTKClassTeachers();
        $rombels = $this->dapodikService->getRombonganBelajar();
        $students = $this->dapodikService->getPesertaDidik();

        $stats = [
            'total_ptk' => count($this->dapodikService->getPTK()),
            'total_rombel' => count($rombels),
            'total_siswa' => count($students),
        ];

        return view('pages.admin.log', compact('sekolah', 'ptks', 'rombels', 'students', 'stats', 'schools', 'selectedNpsn'));
    }
}
