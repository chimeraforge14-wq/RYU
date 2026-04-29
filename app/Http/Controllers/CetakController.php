<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DapodikService;
use App\Models\Nilai;
use App\Models\PelengkapRapor;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakController extends Controller
{
    protected DapodikService $dapodikService;

    public function __construct(DapodikService $dapodikService)
    {
        $this->dapodikService = $dapodikService;
    }

    private function getIdentitySettings()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $identity = [];

        // Konversi ke base64 agar aman di PDF
        foreach (['school_logo', 'headmaster_signature'] as $key) {
            if (isset($settings[$key]) && Storage::exists($settings[$key])) {
                $path = Storage::path($settings[$key]);
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $identity[$key] = 'data:image/' . $type . ';base64,' . base64_encode($data);
            } else {
                $identity[$key] = null;
            }
        }

        $identity['headmaster_name'] = $settings['headmaster_name'] ?? '..........................';
        $identity['headmaster_nip'] = $settings['headmaster_nip'] ?? '-';
        
        return $identity;
    }

    public function index($type)
    {
        $titles = [
            'leger' => 'Cetak Leger Rapor',
            'pelengkap' => 'Cetak Dokumen Pelengkap',
            'nilai' => 'Cetak Nilai Rapor'
        ];
        
        $title = $titles[$type] ?? 'Cetak Dokumen';
        $rombonganBelajar = $this->dapodikService->getFilteredRombonganBelajar(session('ptk_id'), session('role'));
        $sekolah = $this->dapodikService->getSekolah();

        return view('pages.cetak.index', compact('title', 'type', 'rombonganBelajar', 'sekolah'));
    }

    public function printLeger(Request $request)
    {
        $rombelId = $request->query('rombongan_belajar_id');
        if (!$rombelId) return abort(404);

        $rombonganBelajar = $this->dapodikService->getRombonganBelajar();
        $semuaSiswa = $this->dapodikService->getPesertaDidik();
        $sekolah = $this->dapodikService->getSekolah();
        $identity = $this->getIdentitySettings();
        
        $rombelData = collect($rombonganBelajar)->firstWhere('rombongan_belajar_id', $rombelId) ?? collect($rombonganBelajar)->firstWhere('id', $rombelId);
        if (!$rombelData) return abort(404);

        $mapels = [];
        if (isset($rombelData['pembelajaran'])) {
            foreach ($rombelData['pembelajaran'] as $p) {
                $mId = $p['mata_pelajaran_id'] ?? $p['pembelajaran_id'];
                $mName = $p['nama_mata_pelajaran'] ?? $p['mata_pelajaran_id_str'];
                $mapels[$mId] = $mName;
            }
        }

        $siswaDalamRombel = [];
        $siswaMap = collect($semuaSiswa)->keyBy('peserta_didik_id');
        if (isset($rombelData['anggota_rombel'])) {
            foreach ($rombelData['anggota_rombel'] as $ar) {
                $pdId = $ar['peserta_didik_id'];
                if ($siswaMap->has($pdId)) {
                    $siswaDalamRombel[] = array_merge((array)$ar, (array)$siswaMap->get($pdId));
                } else {
                    $siswaDalamRombel[] = (array)$ar;
                }
            }
        }

        $nilaiDb = Nilai::where('rombongan_belajar_id', $rombelId)->get();
        
        $grades = [];
        foreach ($nilaiDb as $n) {
            $grades[$n->peserta_didik_id][$n->mata_pelajaran_id] = $n;
        }

        $pdf = Pdf::loadView('pages.cetak.print_leger', compact('rombelData', 'siswaDalamRombel', 'grades', 'mapels', 'sekolah', 'identity'))
                  ->setPaper('a4', 'landscape');

        return $pdf->stream('Leger_Nilai_' . ($rombelData['nama'] ?? 'Kelas') . '.pdf');
    }

    public function printRapor($rombel_id, $peserta_didik_id)
    {
        $rombonganBelajar = $this->dapodikService->getRombonganBelajar();
        $semuaSiswa = $this->dapodikService->getPesertaDidik();
        $sekolah = $this->dapodikService->getSekolah();
        $identity = $this->getIdentitySettings();
        
        $rombelData = collect($rombonganBelajar)->firstWhere('rombongan_belajar_id', $rombel_id) ?? collect($rombonganBelajar)->firstWhere('id', $rombel_id);
        
        $siswaMap = collect($semuaSiswa)->keyBy('peserta_didik_id')->toArray();
        $siswaData = $siswaMap[$peserta_didik_id] ?? ['nama' => 'Data tidak ditemukan', 'nisn' => '-'];

        $mapels = [];
        if ($rombelData && isset($rombelData['pembelajaran'])) {
            foreach ($rombelData['pembelajaran'] as $p) {
                $mId = $p['mata_pelajaran_id'] ?? $p['pembelajaran_id'];
                $mName = $p['nama_mata_pelajaran'] ?? $p['mata_pelajaran_id_str'];
                $mapels[$mId] = $mName;
            }
        }

        $nilaiDb = Nilai::where('rombongan_belajar_id', $rombel_id)
                        ->where('peserta_didik_id', $peserta_didik_id)
                        ->get()
                        ->keyBy('mata_pelajaran_id');
        
        $pelengkap = PelengkapRapor::where('rombongan_belajar_id', $rombel_id)
                        ->where('peserta_didik_id', $peserta_didik_id)
                        ->first();

        $pdf = Pdf::loadView('pages.cetak.print_rapor', compact('rombelData', 'siswaData', 'nilaiDb', 'mapels', 'sekolah', 'pelengkap', 'identity'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('Rapor_' . ($siswaData['nama'] ?? 'Siswa') . '.pdf');
    }
}
