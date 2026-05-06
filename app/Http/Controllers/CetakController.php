<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DapodikService;
use App\Models\Nilai;
use App\Models\PelengkapRapor;
use App\Models\P5Penilaian;
use App\Models\P5Proyek;
use App\Models\KokurikulerGroup;
use App\Models\KokurikulerNilai;
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

        // DB menyimpan base64 data URI langsung — tinggal pakai
        foreach (['school_logo', 'headmaster_signature'] as $key) {
            $val = $settings[$key] ?? null;
            // Support format lama (path file) maupun baru (base64)
            if ($val && str_starts_with($val, 'data:image')) {
                $identity[$key] = $val; // base64 langsung
            } elseif ($val && Storage::exists($val)) {
                // Format lama: konversi path ke base64
                $path = Storage::path($val);
                $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                $mime = match($ext) { 'jpg','jpeg'=>'jpeg','png'=>'png','webp'=>'webp','svg'=>'svg+xml',default=>$ext };
                $identity[$key] = 'data:image/'.$mime.';base64,'.base64_encode(file_get_contents($path));
            } else {
                $identity[$key] = null;
            }
        }

        $identity['headmaster_name']  = $settings['headmaster_name'] ?? '..........................';
        $identity['headmaster_nip']   = $settings['headmaster_nip'] ?? '-';
        $identity['titimangsa_rapor'] = $settings['titimangsa_rapor'] ?? null;
        $identity['koreg_unik']       = $settings['koreg_unik'] ?? null;

        // Utamakan data semester & tahun pelajaran dari Dapodik
        $semesterInfo = $this->dapodikService->getSemesterInfo();

        // Semester: dari Dapodik, fallback ke setting manual
        $identity['semester'] = $semesterInfo['semester'] !== '-'
            ? $semesterInfo['semester']
            : ($settings['semester_aktif'] ?? 'Ganjil');

        // Tahun Pelajaran: dari Dapodik, fallback ke setting manual
        $identity['tahun_pelajaran'] = $semesterInfo['tahun_pelajaran'] !== '-'
            ? $semesterInfo['tahun_pelajaran']
            : ($settings['tahun_pelajaran'] ?? '-');

        return $identity;
    }


    public function index($type)
    {
        $titles = [
            'leger'     => 'Cetak Leger Rapor',
            'pelengkap' => 'Cetak Dokumen Pelengkap',
            'nilai'     => 'Cetak Nilai Rapor'
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
        $semuaSiswa       = $this->dapodikService->getPesertaDidik();
        $sekolah          = $this->dapodikService->getSekolah();
        $identity         = $this->getIdentitySettings();

        $rombelData = collect($rombonganBelajar)->firstWhere('rombongan_belajar_id', $rombelId)
                   ?? collect($rombonganBelajar)->firstWhere('id', $rombelId);
        if (!$rombelData) return abort(404);

        $mapels = [];
        if (isset($rombelData['pembelajaran'])) {
            foreach ($rombelData['pembelajaran'] as $p) {
                $mId   = $p['mata_pelajaran_id'] ?? $p['pembelajaran_id'];
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
        $grades  = [];
        foreach ($nilaiDb as $n) {
            $grades[$n->peserta_didik_id][$n->mata_pelajaran_id] = $n;
        }

        $pdf = Pdf::loadView('pages.cetak.print_leger', compact(
            'rombelData', 'siswaDalamRombel', 'grades', 'mapels', 'sekolah', 'identity'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream('Leger_Nilai_' . ($rombelData['nama'] ?? 'Kelas') . '.pdf');
    }

    public function printRapor($rombel_id, $peserta_didik_id)
    {
        $rombonganBelajar = $this->dapodikService->getRombonganBelajar();
        $semuaSiswa       = $this->dapodikService->getPesertaDidik();
        $sekolah          = $this->dapodikService->getSekolah();
        $identity         = $this->getIdentitySettings();

        $rombelData = collect($rombonganBelajar)->firstWhere('rombongan_belajar_id', $rombel_id)
                   ?? collect($rombonganBelajar)->firstWhere('id', $rombel_id);

        $siswaMap  = collect($semuaSiswa)->keyBy('peserta_didik_id')->toArray();
        $siswaData = $siswaMap[$peserta_didik_id] ?? ['nama' => 'Data tidak ditemukan', 'nisn' => '-'];

        $mapels = [];
        if ($rombelData && isset($rombelData['pembelajaran'])) {
            foreach ($rombelData['pembelajaran'] as $p) {
                $mId   = $p['mata_pelajaran_id'] ?? $p['pembelajaran_id'];
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

        // P5
        $p5Proyeks    = P5Proyek::all()->keyBy('id');
        $p5Penilaians = P5Penilaian::where('rombongan_belajar_id', $rombel_id)
                            ->where('peserta_didik_id', $peserta_didik_id)
                            ->get()->keyBy('proyek_id');

        // Kokurikuler
        $kokurikulerGroups = KokurikulerGroup::with('activities')->get();
        $kokurikulerNilai  = KokurikulerNilai::where('rombongan_belajar_id', $rombel_id)
                                ->where('peserta_didik_id', $peserta_didik_id)
                                ->get()->keyBy('activity_id');

        $waliKelasSignature = $this->getWaliKelasSignature($rombelData);

        $pdf = Pdf::loadView('pages.cetak.print_rapor', compact(
            'rombelData', 'siswaData', 'nilaiDb', 'mapels', 'sekolah',
            'pelengkap', 'identity', 'waliKelasSignature',
            'p5Proyeks', 'p5Penilaians',
            'kokurikulerGroups', 'kokurikulerNilai'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('Rapor_' . ($siswaData['nama'] ?? 'Siswa') . '.pdf');
    }

    /**
     * Cetak massal semua rapor dalam 1 rombel → ZIP berisi banyak PDF
     */
    public function printRaporMassal(Request $request, $rombel_id)
    {
        set_time_limit(300);
        ini_set('memory_limit', '256M');

        $rombonganBelajar = $this->dapodikService->getRombonganBelajar();

        // ✅ RBAC: Pastikan guru hanya bisa cetak kelas miliknya
        if (session('role') === 'guru') {
            $myRombelIds = collect($rombonganBelajar)
                ->pluck('rombongan_belajar_id')
                ->merge(collect($rombonganBelajar)->pluck('id'))
                ->unique()->toArray();
            if (!in_array($rombel_id, $myRombelIds)) {
                abort(403, 'Anda tidak memiliki akses ke kelas ini.');
            }
        }

        $rombonganBelajar = $this->dapodikService->getRombonganBelajar();
        $semuaSiswa       = $this->dapodikService->getPesertaDidik();
        $sekolah          = $this->dapodikService->getSekolah();
        $identity         = $this->getIdentitySettings();

        $rombelData = collect($rombonganBelajar)->firstWhere('rombongan_belajar_id', $rombel_id)
                   ?? collect($rombonganBelajar)->firstWhere('id', $rombel_id);

        if (!$rombelData) {
            return response()->json(['error' => 'Rombongan belajar tidak ditemukan'], 404);
        }

        $siswaMap = collect($semuaSiswa)->keyBy('peserta_didik_id');

        $mapels = [];
        if (isset($rombelData['pembelajaran'])) {
            foreach ($rombelData['pembelajaran'] as $p) {
                $mId   = $p['mata_pelajaran_id'] ?? $p['pembelajaran_id'];
                $mName = $p['nama_mata_pelajaran'] ?? $p['mata_pelajaran_id_str'];
                $mapels[$mId] = $mName;
            }
        }

        $anggotaRombel = $rombelData['anggota_rombel'] ?? [];
        if (empty($anggotaRombel)) {
            return response()->json(['error' => 'Tidak ada siswa di kelas ini.'], 422);
        }

        // Pre-load semua data dari DB (1 query per tabel)
        $pdIds = array_column($anggotaRombel, 'peserta_didik_id');

        $semuaNilai = Nilai::where('rombongan_belajar_id', $rombel_id)
                          ->whereIn('peserta_didik_id', $pdIds)
                          ->get()
                          ->groupBy('peserta_didik_id');

        $semuaPelengkap = PelengkapRapor::where('rombongan_belajar_id', $rombel_id)
                              ->whereIn('peserta_didik_id', $pdIds)
                              ->get()
                              ->keyBy('peserta_didik_id');

        $p5Proyeks = P5Proyek::all()->keyBy('id');
        $semuaP5   = P5Penilaian::where('rombongan_belajar_id', $rombel_id)
                          ->whereIn('peserta_didik_id', $pdIds)
                          ->get()
                          ->groupBy('peserta_didik_id');

        $waliKelasSignature = $this->getWaliKelasSignature($rombelData);

        // Buat temporary directory unik
        $uniqueId = uniqid('rapor_', true);
        $tmpDir   = storage_path('app/' . $uniqueId);

        // Nama ZIP: sanitize spasi dan karakter aneh
        $namaKelas    = $rombelData['nama'] ?? 'Kelas';
        $safeKelasName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $namaKelas);
        $zipPath      = storage_path('app/' . $safeKelasName . '_' . date('Ymd') . '.zip');

        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        // Generate PDF per siswa
        $generatedFiles = [];
        foreach ($anggotaRombel as $no => $ar) {
            $pdId      = $ar['peserta_didik_id'] ?? '';
            if (!$pdId) continue;

            $siswaData    = $siswaMap->has($pdId) ? (array)$siswaMap->get($pdId) : array_merge((array)$ar, ['nama' => 'Siswa_' . substr($pdId, 0, 8)]);
            $nilaiDb      = ($semuaNilai[$pdId] ?? collect())->keyBy('mata_pelajaran_id');
            $pelengkap    = $semuaPelengkap[$pdId] ?? null;
            $p5Penilaians = ($semuaP5[$pdId] ?? collect())->keyBy('proyek_id');

            try {
                $pdf = Pdf::loadView('pages.cetak.print_rapor', compact(
                    'rombelData', 'siswaData', 'nilaiDb', 'mapels', 'sekolah',
                    'pelengkap', 'identity', 'waliKelasSignature',
                    'p5Proyeks', 'p5Penilaians'
                ))->setPaper('a4', 'portrait');

                // Nomor urut + nama siswa, semua karakter aman
                $nomor    = str_pad($no + 1, 2, '0', STR_PAD_LEFT);
                $namaSiswa = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $siswaData['nama'] ?? 'Siswa');
                $pdfPath  = $tmpDir . '/' . $nomor . '_' . $namaSiswa . '.pdf';

                $pdf->save($pdfPath);
                $generatedFiles[] = $pdfPath;
            } catch (\Throwable $e) {
                // Lewati siswa yang gagal, lanjutkan yang lain
                \Illuminate\Support\Facades\Log::warning("Gagal generate PDF siswa $pdId: " . $e->getMessage());
            }
        }

        if (empty($generatedFiles)) {
            // Cleanup dan beri tahu user
            @rmdir($tmpDir);
            return response()->json(['error' => 'Tidak ada PDF yang berhasil di-generate.'], 500);
        }

        // Buat file ZIP
        $zip = new \ZipArchive();
        $zipOpened = $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        if ($zipOpened !== true) {
            // Cleanup temp
            foreach ($generatedFiles as $f) @unlink($f);
            @rmdir($tmpDir);
            return response()->json(['error' => 'Gagal membuat file ZIP. Kode: ' . $zipOpened], 500);
        }

        foreach ($generatedFiles as $file) {
            // Gunakan realpath() agar path absolute dan valid
            $realFile = realpath($file);
            if ($realFile && file_exists($realFile)) {
                $zip->addFile($realFile, basename($realFile));
            }
        }
        $zip->close();

        // Bersihkan file PDF sementara
        foreach ($generatedFiles as $f) @unlink($f);
        @rmdir($tmpDir);

        if (!file_exists($zipPath)) {
            return response()->json(['error' => 'File ZIP gagal dibuat.'], 500);
        }

        return response()->download($zipPath, "Rapor_Massal_{$safeKelasName}.zip")->deleteFileAfterSend(true);
    }

    private function getWaliKelasSignature(?array $rombelData): ?string
    {
        $waliKelasId = $rombelData['ptk_id'] ?? null;
        if (!$waliKelasId) return null;

        $val = Setting::where('key', 'signature_' . $waliKelasId)->value('value');
        if (!$val) return null;

        // Format baru: base64 langsung
        if (str_starts_with($val, 'data:image')) {
            return $val;
        }

        // Format lama: path file
        if (Storage::exists($val)) {
            $path = Storage::path($val);
            $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $mime = match($ext) { 'jpg','jpeg'=>'jpeg','png'=>'png','webp'=>'webp','svg'=>'svg+xml',default=>$ext };
            return 'data:image/'.$mime.';base64,'.base64_encode(file_get_contents($path));
        }

        return null;
    }


    /**
     * Export Leger ke format CSV (Excel-compatible, tanpa package tambahan)
     */
    public function exportLegerExcel(Request $request)
    {
        $rombelId = $request->query('rombongan_belajar_id');
        if (!$rombelId) return abort(400, 'Rombongan belajar tidak dipilih');

        $rombonganBelajar = $this->dapodikService->getRombonganBelajar();
        $semuaSiswa       = $this->dapodikService->getPesertaDidik();
        $identity         = $this->getIdentitySettings();

        $rombelData = collect($rombonganBelajar)->firstWhere('rombongan_belajar_id', $rombelId)
                   ?? collect($rombonganBelajar)->firstWhere('id', $rombelId);

        if (!$rombelData) return abort(404);

        $mapels = [];
        if (isset($rombelData['pembelajaran'])) {
            foreach ($rombelData['pembelajaran'] as $p) {
                $mId   = $p['mata_pelajaran_id'] ?? $p['pembelajaran_id'];
                $mName = $p['nama_mata_pelajaran'] ?? $p['mata_pelajaran_id_str'];
                $mapels[$mId] = $mName;
            }
        }

        $siswaMap     = collect($semuaSiswa)->keyBy('peserta_didik_id');
        $anggota      = $rombelData['anggota_rombel'] ?? [];
        $pdIds        = array_column($anggota, 'peserta_didik_id');

        $nilaiAll = Nilai::where('rombongan_belajar_id', $rombelId)
                        ->whereIn('peserta_didik_id', $pdIds)
                        ->get()
                        ->groupBy('peserta_didik_id');

        $namaKelas = $rombelData['nama'] ?? 'Kelas';
        $safeNama  = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $namaKelas);

        $callback = function () use ($anggota, $siswaMap, $mapels, $nilaiAll, $namaKelas, $identity) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM agar Excel bisa baca karakter Indonesia
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Info Leger
            fputcsv($handle, ['Leger Nilai', $namaKelas]);
            fputcsv($handle, ['Semester', $identity['semester'] ?? '-', 'Tahun Pelajaran', $identity['tahun_pelajaran'] ?? '-']);
            fputcsv($handle, []);

            // Header kolom
            $header = ['No', 'Nama Siswa', 'NISN'];
            foreach ($mapels as $name) $header[] = $name;
            $header[] = 'Rata-rata';
            fputcsv($handle, $header);

            // Data siswa
            foreach ($anggota as $i => $ar) {
                $pdId  = $ar['peserta_didik_id'] ?? '';
                $siswa = $siswaMap->has($pdId) ? $siswaMap->get($pdId) : [];
                $row   = [$i + 1, $siswa['nama'] ?? '-', $siswa['nisn'] ?? '-'];

                $total = 0; $count = 0;
                foreach ($mapels as $mId => $mName) {
                    $n = ($nilaiAll[$pdId] ?? collect())->firstWhere('mata_pelajaran_id', $mId);
                    $val = $n ? $n->nilai_akhir : 0;
                    $row[] = $val ?: '';
                    if ($val > 0) { $total += $val; $count++; }
                }
                $row[] = $count > 0 ? round($total / $count, 1) : '';
                fputcsv($handle, $row);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=Leger_{$safeNama}.csv",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate',
        ]);
    }
}
