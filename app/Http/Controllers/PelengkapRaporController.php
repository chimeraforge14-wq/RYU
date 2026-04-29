<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PelengkapRapor;
use App\Services\DapodikService;

class PelengkapRaporController extends Controller
{
    protected DapodikService $dapodikService;

    public function __construct(DapodikService $dapodikService)
    {
        $this->dapodikService = $dapodikService;
    }

    public function index(Request $request)
    {
        $rombelId = $request->get('rombongan_belajar_id');
        $rombonganBelajar = $this->dapodikService->getFilteredRombonganBelajar(session('ptk_id'), session('role'));
        
        // Filter tambahan khusus Pelengkap Rapor: Hanya tampilkan jika dia Wali Kelas
        // (Berbeda dengan Nilai yang bisa untuk Guru Mapel)
        if (session('role') !== 'admin' && session('ptk_id')) {
            $rombonganBelajar = collect($rombonganBelajar)->filter(function($r) {
                return ($r['ptk_id'] ?? '') === session('ptk_id');
            })->values()->toArray();
        }

        // Auto-select jika cuma ada 1 rombel (khusus Guru)
        if (!$rombelId && count($rombonganBelajar) === 1) {
            $rombelId = $rombonganBelajar[0]['rombongan_belajar_id'] ?? $rombonganBelajar[0]['id'] ?? null;
        }
        
        $siswaData = [];
        $pelengkapMap = [];

        if ($rombelId) {
            $semuaSiswa = $this->dapodikService->getPesertaDidik();
            
            // Cari rombel terpilih
            $rombelData = collect($rombonganBelajar)->firstWhere('rombongan_belajar_id', $rombelId) ?? collect($rombonganBelajar)->firstWhere('id', $rombelId);
            
            if ($rombelData) {
                if (isset($rombelData['anggota_rombel']) && is_array($rombelData['anggota_rombel'])) {
                    $siswaMap = collect($semuaSiswa)->keyBy('peserta_didik_id')->toArray();
                    foreach ($rombelData['anggota_rombel'] as $ar) {
                        $pdId = $ar['peserta_didik_id'] ?? '';
                        if (isset($siswaMap[$pdId])) {
                            $siswaData[] = array_merge($ar, $siswaMap[$pdId]);
                        } else {
                            $siswaData[] = $ar;
                        }
                    }
                } else {
                    $siswaData = collect($semuaSiswa)->where('rombongan_belajar_id', $rombelId)->toArray();
                }
            }

            $pelengkapMap = PelengkapRapor::where('rombongan_belajar_id', $rombelId)
                            ->get()
                            ->keyBy('peserta_didik_id');
        }

        return view('pages.pelengkap_rapor', compact('rombonganBelajar', 'rombelId', 'siswaData', 'pelengkapMap'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'rombongan_belajar_id' => 'required',
                'data' => 'required|array'
            ]);

            $rombelId = $request->rombongan_belajar_id;

            foreach ($request->data as $pdId => $val) {
                PelengkapRapor::updateOrCreate(
                    [
                        'rombongan_belajar_id' => $rombelId,
                        'peserta_didik_id' => $pdId
                    ],
                    [
                        'sakit' => $val['sakit'] ?? 0,
                        'izin' => $val['izin'] ?? 0,
                        'tanpa_keterangan' => $val['tanpa_keterangan'] ?? 0,
                        'catatan_wali_kelas' => $val['catatan_wali_kelas'] ?? null,
                    ]
                );
            }

            return response()->json(['success' => true, 'message' => 'Data pelengkap rapor berhasil disimpan.']);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }
}
