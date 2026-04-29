<?php

namespace App\Http\Controllers;

use App\Services\DapodikService;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    protected DapodikService $dapodikService;

    public function __construct(DapodikService $dapodikService)
    {
        $this->dapodikService = $dapodikService;
    }

    public function index()
    {
        $lastSync = $this->dapodikService->getLastSync();
        
        // Ambil config terakhir dari data lokal
        $sekolah = $this->dapodikService->getSekolah();
        $lastConfig = [
            'url' => session('last_dapodik_url', 'http://localhost:5774'),
            'token' => session('last_dapodik_token', ''),
            'npsn' => $sekolah['npsn'] ?? session('last_dapodik_npsn', ''),
            'semester' => session('last_dapodik_semester', '20251')
        ];

        return view('sync', compact('lastSync', 'lastConfig'));
    }

    public function processSync(Request $request)
    {
        $request->validate([
            'dapodik_url' => 'required|url',
            'token' => 'required|string',
            'npsn' => 'required|string',
            'semester' => 'required|string'
        ]);

        // Simpan ke session untuk kenyamanan
        session([
            'last_dapodik_url' => $request->dapodik_url,
            'last_dapodik_token' => $request->token,
            'last_dapodik_npsn' => $request->npsn,
            'last_dapodik_semester' => $request->semester,
        ]);

        $result = $this->dapodikService->syncData(
            $request->input('dapodik_url'),
            $request->input('token'),
            $request->input('npsn'),
            $request->input('semester')
        );

        if ($result['success']) {
            return back()->with('success', $result['message']);
        } else {
            return back()->with('error', $result['message']);
        }
    }
}
