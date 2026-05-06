<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TpCpData;
use App\Models\TpScore;
use App\Models\Nilai;
use App\Services\DapodikService;

class TPController extends Controller
{
    protected DapodikService $dapodikService;

    public function __construct(DapodikService $dapodikService)
    {
        $this->dapodikService = $dapodikService;
    }

    public function index(Request $request)
    {
        $rombelId = $request->get('rombongan_belajar_id');
        $mapelId = $request->get('mata_pelajaran_id');
        
        $rombels = $this->dapodikService->getFilteredRombonganBelajar(session('ptk_id'), session('role'));
        $tpData = [];
        
        if ($rombelId && $mapelId) {
            $tpData = TpCpData::where('rombongan_belajar_id', $rombelId)
                             ->where('mata_pelajaran_id', $mapelId)
                             ->get();
        }

        return view('pages.tp.index', compact('rombels', 'rombelId', 'mapelId', 'tpData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rombongan_belajar_id' => 'required',
            'mata_pelajaran_id'    => 'required',
            'type'                 => 'required|in:tp,cp',
            'content'              => 'required'
        ]);

        // ptk_id bisa null untuk superadmin — gunakan fallback
        $ptkId = session('ptk_id') ?? session('user_id') ?? 'system';

        TpCpData::create([
            'rombongan_belajar_id' => $request->rombongan_belajar_id,
            'mata_pelajaran_id'    => $request->mata_pelajaran_id,
            'type'                 => $request->type,
            'kode'                 => $request->kode,
            'content'              => $request->content,
            'ptk_id'               => $ptkId,
        ]);

        return back()->with('success', 'Data TP/CP berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $tp = TpCpData::findOrFail($id);
        // Hapus semua skor terkait dulu (meski ada cascade, eksplisit lebih aman)
        TpScore::where('tp_id', $id)->delete();
        $tp->delete();

        return back()->with('success', 'TP berhasil dihapus.');
    }

    public function scoring(Request $request)
    {
        $rombelId = $request->get('rombongan_belajar_id');
        $mapelId = $request->get('mata_pelajaran_id');
        
        $rombels = $this->dapodikService->getFilteredRombonganBelajar(session('ptk_id'), session('role'));
        $tpData = [];
        $students = [];
        $scores = [];

        if ($rombelId && $mapelId) {
            $tpData = TpCpData::where('rombongan_belajar_id', $rombelId)
                             ->where('mata_pelajaran_id', $mapelId)
                             ->where('type', 'tp')
                             ->get();
            
            $allStudents = $this->dapodikService->getPesertaDidik();
            $rombel = collect($this->dapodikService->getRombonganBelajar())->firstWhere('rombongan_belajar_id', $rombelId);
            
            if ($rombel && isset($rombel['anggota_rombel'])) {
                $pdIds = collect($rombel['anggota_rombel'])->pluck('peserta_didik_id');
                $students = collect($allStudents)->whereIn('peserta_didik_id', $pdIds);
            }

            $scores = TpScore::whereIn('tp_id', $tpData->pluck('id'))->get()->groupBy('peserta_didik_id');
        }

        return view('pages.tp.scoring', compact('rombels', 'rombelId', 'mapelId', 'tpData', 'students', 'scores'));
    }

    public function storeScores(Request $request)
    {
        $request->validate([
            'scores' => 'required|array',
            'rombongan_belajar_id' => 'required',
            'mata_pelajaran_id' => 'required'
        ]);

        foreach ($request->scores as $studentId => $tpScores) {
            $total = 0;
            $count = 0;
            $max = 0;

            foreach ($tpScores as $tpId => $score) {
                if ($score !== null) {
                    TpScore::updateOrCreate(
                        ['tp_id' => $tpId, 'peserta_didik_id' => $studentId],
                        ['score' => $score]
                    );
                    $total += $score;
                    $count++;
                    $max = max($max, $score);
                }
            }

            // Calculate final grade: Highest Value (as per request)
            if ($count > 0) {
                $avg = round($total / $count);
                $finalGrade = $max; // "di nilai akhir ambil dari nilai tertinggi"

                Nilai::updateOrCreate(
                    [
                        'rombongan_belajar_id' => $request->rombongan_belajar_id,
                        'mata_pelajaran_id' => $request->mata_pelajaran_id,
                        'peserta_didik_id' => $studentId
                    ],
                    [
                        'nilai_akhir' => $finalGrade,
                        'deskripsi_capaian' => "Siswa telah mencapai rata-rata " . $avg . "% dalam tujuan pembelajaran."
                    ]
                );
            }
        }

        return back()->with('success', 'Nilai TP berhasil disimpan dan Nilai Akhir telah dikalkulasi.');
    }

    public function exportTemplate()
    {
        $headers = ['Kode', 'Type (tp/cp)', 'Konten'];
        $callback = function() use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=template_tp_cp.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
            'rombongan_belajar_id' => 'required',
            'mata_pelajaran_id' => 'required'
        ]);

        $file = $request->file('file');
        $data = array_map('str_getcsv', file($file->getPathname()));
        array_shift($data); // Remove header

        foreach ($data as $row) {
            if (count($row) >= 3) {
                TpCpData::create([
                    'rombongan_belajar_id' => $request->rombongan_belajar_id,
                    'mata_pelajaran_id' => $request->mata_pelajaran_id,
                    'ptk_id' => session('ptk_id'),
                    'kode' => $row[0],
                    'type' => strtolower($row[1]),
                    'content' => $row[2]
                ]);
            }
        }

        return back()->with('success', 'Import TP/CP berhasil.');
    }
}
