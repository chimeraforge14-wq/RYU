<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KokurikulerGroup;
use App\Models\KokurikulerActivity;
use App\Models\KokurikulerNilai;
use App\Services\DapodikService;

class KokurikulerController extends Controller
{
    protected DapodikService $dapodikService;

    public function __construct(DapodikService $dapodikService)
    {
        $this->dapodikService = $dapodikService;
    }

    /**
     * Halaman manajemen grup & aktivitas kokurikuler
     */
    public function index()
    {
        $groups = KokurikulerGroup::with('activities')->get();
        return view('pages.kokurikuler.index', compact('groups'));
    }

    /**
     * Simpan grup kokurikuler baru
     */
    public function storeGroup(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'fase'           => 'nullable|string|max:10',
            'coordinator_id' => 'nullable|string',
        ]);

        KokurikulerGroup::create($request->only('name', 'fase', 'coordinator_id'));
        return back()->with('success', 'Grup kokurikuler berhasil ditambahkan.');
    }

    /**
     * Simpan aktivitas di dalam grup
     */
    public function storeActivity(Request $request)
    {
        $request->validate([
            'group_id'      => 'required|exists:kokurikuler_groups,id',
            'theme'         => 'required|string|max:255',
            'activity_name' => 'required|string',
            'description'   => 'nullable|string',
        ]);

        KokurikulerActivity::create($request->only('group_id', 'theme', 'activity_name', 'description'));
        return back()->with('success', 'Aktivitas kokurikuler berhasil ditambahkan.');
    }

    /**
     * Hapus grup (cascade ke activities & nilai)
     */
    public function destroyGroup($id)
    {
        KokurikulerGroup::findOrFail($id)->delete();
        return back()->with('success', 'Grup kokurikuler berhasil dihapus.');
    }

    /**
     * Halaman penilaian kokurikuler per siswa
     */
    public function penilaian(Request $request)
    {
        $rombelId = $request->get('rombongan_belajar_id');

        $rombels   = $this->dapodikService->getFilteredRombonganBelajar(session('ptk_id'), session('role'));
        $groups    = KokurikulerGroup::with('activities')->get();
        $students  = [];
        $nilaiMap  = [];

        if ($rombelId) {
            $allStudents = $this->dapodikService->getPesertaDidik();
            $rombel      = collect($this->dapodikService->getRombonganBelajar())
                               ->firstWhere('rombongan_belajar_id', $rombelId);

            if ($rombel && isset($rombel['anggota_rombel'])) {
                $pdIds    = collect($rombel['anggota_rombel'])->pluck('peserta_didik_id');
                $siswaMap = collect($allStudents)->keyBy('peserta_didik_id');
                foreach ($pdIds as $pdId) {
                    if ($siswaMap->has($pdId)) {
                        $students[] = $siswaMap->get($pdId);
                    }
                }
            }

            // Pre-load semua nilai kokurikuler untuk rombel ini
            $nilaiDb = KokurikulerNilai::where('rombongan_belajar_id', $rombelId)
                           ->get();
            foreach ($nilaiDb as $n) {
                $nilaiMap[$n->peserta_didik_id][$n->activity_id] = $n;
            }
        }

        return view('pages.kokurikuler.penilaian', compact(
            'rombels', 'rombelId', 'groups', 'students', 'nilaiMap'
        ));
    }

    /**
     * Simpan penilaian kokurikuler massal
     */
    public function storePenilaian(Request $request)
    {
        $request->validate([
            'rombongan_belajar_id' => 'required|string',
            'nilai'                => 'required|array',
        ]);

        $rombelId = $request->rombongan_belajar_id;

        foreach ($request->nilai as $pdId => $activities) {
            foreach ($activities as $actId => $val) {
                if ($val !== null && $val !== '') {
                    KokurikulerNilai::updateOrCreate(
                        ['activity_id' => $actId, 'peserta_didik_id' => $pdId],
                        ['rombongan_belajar_id' => $rombelId, 'nilai' => strtoupper($val)]
                    );
                }
            }
        }

        // Simpan catatan jika ada
        if ($request->has('catatan')) {
            foreach ($request->catatan as $pdId => $activities) {
                foreach ($activities as $actId => $catatan) {
                    KokurikulerNilai::where(['activity_id' => $actId, 'peserta_didik_id' => $pdId])
                        ->update(['catatan' => $catatan]);
                }
            }
        }

        return back()->with('success', 'Penilaian kokurikuler berhasil disimpan.');
    }
}
