@extends('layouts.app')
@section('title', 'Profil Pengguna - e-Rapor SD')
@section('header_title', 'Pengaturan Profil')

@section('content')
<div class="animate-slide-up" style="max-width: 800px;">
    <div style="background: var(--card-bg); border: var(--glass-border); border-radius: 16px; padding: 2rem; backdrop-filter: blur(10px);">
        <div style="display: flex; gap: 2rem; align-items: flex-start; flex-wrap: wrap;">
            <div style="width: 120px; height: 120px; border-radius: 50%; background: var(--accent-gradient); display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: bold; flex-shrink: 0; box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);">
                {{ substr(session('nama', 'A'), 0, 1) }}
            </div>
            <div style="flex: 1; min-width: 300px;">
                <h2 style="font-size: 1.5rem; margin-bottom: 0.25rem;">{{ session('nama', 'Administrator') }}</h2>
                <p style="color: var(--text-secondary); margin-bottom: 2rem;">{{ session('username', 'admin@erapor.local') }}</p>
                
                <form onsubmit="event.preventDefault(); alert('Pembaruan profil berhasil disimpan!');">
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Nama Lengkap</label>
                        <input type="text" value="{{ session('nama') }}" style="width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.2); color: white; outline: none; font-size: 0.9rem;">
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Username / Email</label>
                        <input type="text" value="{{ session('username') }}" readonly style="width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.1); color: var(--text-secondary); outline: none; font-size: 0.9rem; cursor: not-allowed;">
                    </div>
                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Password Baru (Opsional)</label>
                        <input type="password" placeholder="Biarkan kosong jika tidak ingin mengubah password" style="width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.2); color: white; outline: none; font-size: 0.9rem;">
                    </div>
                    <button type="submit" style="background: var(--accent-gradient); color: white; border: none; padding: 0.75rem 2rem; border-radius: 8px; font-weight: 500; cursor: pointer; transition: opacity 0.3s;" onmouseover="this.style.opacity=0.9" onmouseout="this.style.opacity=1">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
