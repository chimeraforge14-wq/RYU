<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DapodikService;
use App\Models\StudentOverride;
use App\Models\RombelOverride;

class StudentController extends Controller
{
    protected DapodikService $dapodikService;

    public function __construct(DapodikService $dapodikService)
    {
        $this->dapodikService = $dapodikService;
    }

    public function index()
    {
        $students = $this->dapodikService->getPesertaDidik();
        return view('pages.students.index', compact('students'));
    }

    public function create()
    {
        return view('pages.students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nisn' => 'nullable',
            'nik' => 'nullable',
        ]);

        $id = \Illuminate\Support\Str::uuid();
        
        \App\Models\StudentOverride::create(array_merge($request->all(), [
            'peserta_didik_id' => $id
        ]));

        return redirect()->route('students.index')->with('success', 'Siswa baru berhasil ditambahkan secara manual.');
    }

    public function edit($id)
    {
        $students = $this->dapodikService->getPesertaDidik();
        $student = collect($students)->firstWhere('peserta_didik_id', $id);
        
        if (!$student) return abort(404);

        return view('pages.students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);
        
        foreach ($data as $key => $value) {
            StudentOverride::updateOrCreate(
                ['peserta_didik_id' => $id, 'field_name' => $key],
                ['field_value' => $value]
            );
        }

        return redirect()->route('students.index')->with('success', 'Identitas peserta didik berhasil diperbarui.');
    }

    public function manageRombel($id)
    {
        $students = $this->dapodikService->getPesertaDidik();
        $student = collect($students)->firstWhere('peserta_didik_id', $id);
        $rombels = $this->dapodikService->getRombonganBelajar();
        
        if (!$student) return abort(404);

        // Find current rombel
        $currentRombel = collect($rombels)->filter(function($r) use ($id) {
            return collect($r['anggota_rombel'] ?? [])->contains('peserta_didik_id', $id);
        })->first();

        return view('pages.students.rombel', compact('student', 'rombels', 'currentRombel'));
    }

    public function updateRombel(Request $request, $id)
    {
        $request->validate([
            'rombongan_belajar_id' => 'required',
            'action' => 'required|in:add,remove,transfer'
        ]);

        RombelOverride::updateOrCreate(
            ['peserta_didik_id' => $id],
            [
                'rombongan_belajar_id' => $request->rombongan_belajar_id,
                'action' => $request->action,
                'from_rombongan_belajar_id' => $request->from_rombongan_belajar_id
            ]
        );

        return back()->with('success', 'Rombongan belajar berhasil diperbarui.');
    }

    public function editData($id)
    {
        $students = $this->dapodikService->getPesertaDidik();
        $student = collect($students)->firstWhere('peserta_didik_id', $id);

        if (!$student) return abort(404);

        // Ambil semua override yang sudah tersimpan untuk siswa ini
        $overrides = StudentOverride::where('peserta_didik_id', $id)
            ->pluck('field_value', 'field_name')
            ->toArray();

        // Merge override ke data siswa (override lebih prioritas)
        $student = array_merge($student, $overrides);

        return view('pages.students.edit_data', compact('student'));
    }

    public function updateData(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            StudentOverride::updateOrCreate(
                ['peserta_didik_id' => $id, 'field_name' => $key],
                ['field_value' => $value]
            );
        }

        return redirect()->route('students.edit_data', $id)
            ->with('success', 'Data peserta didik berhasil diperbarui.');
    }
}
