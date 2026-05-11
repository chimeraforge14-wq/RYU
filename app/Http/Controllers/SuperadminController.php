<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SuperadminController extends Controller
{
    public function manageSchools()
    {
        $schools = School::orderBy('created_at', 'desc')->get();
        return view('pages.superadmin.schools', compact('schools'));
    }

    public function storeSchool(Request $request)
    {
        $request->validate([
            'npsn' => 'required|string|unique:schools,npsn',
            'name' => 'required|string|max:255',
        ]);

        $registrationCode = 'RYU-' . strtoupper(Str::random(8));

        School::create([
            'npsn' => $request->npsn,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'registration_code' => $registrationCode,
        ]);

        return back()->with('success', "Sekolah berhasil didaftarkan! Kode Registrasi: $registrationCode");
    }

    public function destroySchool($id)
    {
        $school = School::findOrFail($id);
        $school->delete();

        return back()->with('success', 'Sekolah berhasil dihapus.');
    }

    public function enterSchool($npsn)
    {
        $school = School::where('npsn', $npsn)->firstOrFail();
        
        session([
            'npsn' => $school->npsn,
            'registration_code' => $school->registration_code,
            'school_id' => $school->id
        ]);

        return redirect()->route('dashboard')->with('success', "Sekarang mengelola tenant: {$school->name}");
    }

    public function exitSchool()
    {
        session()->forget(['npsn', 'registration_code', 'school_id']);
        return redirect()->route('dashboard')->with('info', "Kembali ke Dashboard Superadmin.");
    }
}
