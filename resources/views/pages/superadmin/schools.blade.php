@extends('layouts.app')

@section('title', 'Kelola Sekolah - e-Rapor Superadmin')
@section('header_title', 'Kelola Tenant / Sekolah')
@section('header_subtitle', 'Generate kode registrasi dan pantau tenant sekolah aktif')

@section('content')
<div class="animate-slide-up">
    {{-- Form Tambah Sekolah --}}
    <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 2rem;">
        <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1.25rem; color: var(--accent);">Daftarkan Sekolah Baru</h3>
        <form action="{{ route('super.schools.store') }}" method="POST" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
            @csrf
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">NPSN Sekolah</label>
                <input type="text" name="npsn" required placeholder="Masukkan NPSN" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.2); color: white; outline: none;">
            </div>
            <div style="flex: 2; min-width: 300px;">
                <label style="display: block; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Nama Sekolah</label>
                <input type="text" name="name" required placeholder="Masukkan Nama Lengkap Sekolah" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.2); color: white; outline: none;">
            </div>
            <button type="submit" style="background: var(--accent-gradient); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; height: 42px;">
                Generate Kode Registrasi
            </button>
        </form>
    </div>

    @if(session('success'))
        <div style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #10b981; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Daftar Sekolah --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>NPSN</th>
                    <th>Nama Sekolah</th>
                    <th>Kode Registrasi (Index)</th>
                    <th>Database</th>
                    <th>Terdaftar Pada</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schools as $school)
                    <tr>
                        <td style="font-family: monospace; font-weight: 600;">{{ $school->npsn }}</td>
                        <td>{{ $school->name }}</td>
                        <td>
                            <span style="background: rgba(225,29,72,0.1); color: var(--accent); padding: 4px 10px; border-radius: 6px; font-family: monospace; font-weight: 700;">
                                {{ $school->registration_code }}
                            </span>
                        </td>
                        <td style="font-size: 0.85rem; color: var(--text-secondary);">
                            erapor_{{ strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $school->registration_code)) }}
                        </td>
                        <td style="font-size: 0.85rem; color: var(--text-secondary);">
                            {{ $school->created_at->format('d M Y H:i') }}
                        </td>
                        <td style="text-align: right; display: flex; gap: 0.5rem; justify-content: flex-end;">
                            @if($school->npsn)
                                <a href="{{ route('super.schools.enter', ['npsn' => $school->npsn]) }}" 
                                   style="background: rgba(59, 130, 246, 0.1); color: #60a5fa; padding: 6px 12px; border-radius: 8px; text-decoration: none; font-size: 0.75rem; font-weight: 700; border: 1px solid rgba(59,130,246,0.2); transition: all 0.2s;"
                                   onmouseover="this.style.background='rgba(59,130,246,0.2)'"
                                   onmouseout="this.style.background='rgba(59,130,246,0.1)'">
                                    <span style="display: flex; align-items: center; gap: 0.4rem;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                                        Kelola Tenant
                                    </span>
                                </a>
                            @endif
                            <form action="{{ route('super.schools.destroy', $school->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tenant ini? Semua data di database terpisah akan tetap ada namun tidak dapat diakses.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 5px; opacity: 0.7; transition: opacity 0.2s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.7'">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                            Belum ada sekolah yang didaftarkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
