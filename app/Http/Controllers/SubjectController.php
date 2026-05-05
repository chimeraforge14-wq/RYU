<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManualSubject;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = ManualSubject::with('children')->whereNull('parent_id')->get();
        return view('pages.subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mata_pelajaran_id' => 'required|unique:manual_subjects',
            'nama_mata_pelajaran' => 'required',
            'kelompok' => 'nullable',
            'parent_id' => 'nullable'
        ]);

        ManualSubject::create($request->all());

        return back()->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $subject = ManualSubject::findOrFail($id);
        $subject->update($request->all());

        return back()->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        ManualSubject::findOrFail($id)->delete();
        return back()->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
