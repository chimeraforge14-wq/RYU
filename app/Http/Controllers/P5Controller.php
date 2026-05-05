<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\P5Proyek;
use App\Models\P5Tema;
use App\Models\P5ProyekRombel;
use App\Models\P5Penilaian;
use App\Services\DapodikService;

class P5Controller extends Controller
{
    protected DapodikService $dapodikService;

    public function __construct(DapodikService $dapodikService)
    {
        $this->dapodikService = $dapodikService;
    }

    public function perencanaan()
    {
        $proyeks = P5Proyek::with(['tema', 'rombel'])->get();
        $temas = P5Tema::all();
        $rombels = $this->dapodikService->getFilteredRombonganBelajar(session('ptk_id'), session('role'));

        return view('pages.p5.perencanaan', compact('proyeks', 'temas', 'rombels'));
    }

    public function storeProyek(Request $request)
    {
        $request->validate([
            'tema_id' => 'required|exists:p5_tema,id',
            'nama_proyek' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'rombongan_belajar_id' => 'required|array',
            'semester' => 'required|string|max:10'
        ]);

        $proyek = P5Proyek::create([
            'tema_id' => $request->tema_id,
            'nama_proyek' => $request->nama_proyek,
            'deskripsi' => $request->deskripsi,
            'semester' => $request->semester
        ]);

        foreach ($request->rombongan_belajar_id as $rombelId) {
            P5ProyekRombel::create([
                'proyek_id' => $proyek->id,
                'rombongan_belajar_id' => $rombelId
            ]);
        }

        return back()->with('success', 'Proyek P5 berhasil ditambahkan.');
    }

    public function penilaian(Request $request)
    {
        $proyekId = $request->get('proyek_id');
        $rombelId = $request->get('rombongan_belajar_id');
        $proyeks = P5Proyek::all();
        $rombels = $this->dapodikService->getFilteredRombonganBelajar(session('ptk_id'), session('role'));

        // Auto-select jika cuma ada 1 rombel (khusus Guru)
        if (!$rombelId && count($rombels) === 1 && session('role') !== 'admin') {
            $rombelId = $rombels[0]['rombongan_belajar_id'] ?? $rombels[0]['id'] ?? null;
        }
        
        $siswaData = [];
        $penilaianMap = [];
        $selectedProyek = null;
        $selectedRombelName = '';

        if ($proyekId && $rombelId) {
            $selectedProyek = P5Proyek::find($proyekId);
            
            // Get Rombel Name
            foreach ($rombels as $r) {
                if (($r['rombongan_belajar_id'] ?? $r['id'] ?? '') === $rombelId) {
                    $selectedRombelName = $r['nama'] ?? '';
                    
                    // Fetch students for this rombel
                    $allSiswa = $this->dapodikService->getPesertaDidik();
                    if (isset($r['anggota_rombel']) && is_array($r['anggota_rombel'])) {
                        // Use anggota_rombel
                        $siswaMap = [];
                        foreach ($allSiswa as $s) {
                            $pd_id = $s['peserta_didik_id'] ?? '';
                            if ($pd_id) {
                                $siswaMap[$pd_id] = $s;
                            }
                        }
                        foreach ($r['anggota_rombel'] as $ar) {
                            $pd_id = $ar['peserta_didik_id'] ?? '';
                            if (isset($siswaMap[$pd_id])) {
                                $siswaData[] = array_merge($ar, $siswaMap[$pd_id]);
                            } else {
                                $ar['nama'] = $ar['nama'] ?? $ar['peserta_didik_id_str'] ?? ('ID: ' . substr($pd_id, 0, 8));
                                $siswaData[] = $ar;
                            }
                        }
                    } else {
                        // Fallback
                        foreach ($allSiswa as $s) {
                            if (($s['rombongan_belajar_id'] ?? '') === $rombelId) {
                                $siswaData[] = $s;
                            }
                        }
                    }
                    break;
                }
            }

            // Get existing penilaian
            $penilaians = P5Penilaian::where('proyek_id', $proyekId)
                                     ->where('rombongan_belajar_id', $rombelId)
                                     ->get();
            foreach ($penilaians as $p) {
                $penilaianMap[$p->peserta_didik_id] = [
                    'nilai' => $p->nilai,
                    'catatan_proses' => $p->catatan_proses
                ];
            }
        }

        return view('pages.p5.penilaian', compact(
            'proyeks', 'rombels', 'proyekId', 'rombelId', 
            'siswaData', 'penilaianMap', 'selectedProyek', 'selectedRombelName'
        ));
    }

    public function storePenilaian(Request $request)
    {
        $request->validate([
            'proyek_id' => 'required|exists:p5_proyek,id',
            'rombongan_belajar_id' => 'required|string',
            'penilaian' => 'required|array'
        ]);

        $proyekId = $request->proyek_id;
        $rombelId = $request->rombongan_belajar_id;

        foreach ($request->penilaian as $pesertaDidikId => $data) {
            P5Penilaian::updateOrCreate(
                [
                    'proyek_id' => $proyekId,
                    'peserta_didik_id' => $pesertaDidikId
                ],
                [
                    'rombongan_belajar_id' => $rombelId,
                    'nilai' => $data['nilai'] ?? null,
                    'catatan_proses' => $data['catatan_proses'] ?? null
                ]
            );
        }

        return back()->with('success', 'Nilai P5 berhasil disimpan.');
    }

    // New methods for Groups and Activities
    public function manageGroups()
    {
        $groups = \App\Models\KokurikulerGroup::with('activities')->get();
        $ptks = $this->dapodikService->getPTK();
        return view('pages.p5.groups', compact('groups', 'ptks'));
    }

    public function storeGroup(Request $request)
    {
        $request->validate(['name' => 'required']);
        \App\Models\KokurikulerGroup::create($request->all());
        return back()->with('success', 'Kelompok kokurikuler berhasil ditambahkan.');
    }

    public function storeActivity(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:kokurikuler_groups,id',
            'theme' => 'required',
            'activity_name' => 'required'
        ]);
        \App\Models\KokurikulerActivity::create($request->all());
        return back()->with('success', 'Kegiatan berhasil ditambahkan.');
    }
}
