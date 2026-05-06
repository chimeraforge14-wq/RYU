@extends('layouts.app')
@section('title', 'Profil Pengguna - e-Rapor SD')
@section('header_title', 'Profil Saya')

@section('content')
<div class="animate-slide-up" style="max-width: 700px;">
    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.1); color: #34d399; padding: 0.85rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; border: 1px solid rgba(16, 185, 129, 0.15); font-size: 0.85rem;">
            ✓ {{ session('success') }}
        </div>
    @endif
    <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 2rem;">
        <div style="display: flex; gap: 2rem; align-items: flex-start; flex-wrap: wrap;">
            <div style="width: 100px; height: 100px; border-radius: 50%; background: var(--accent-gradient); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: 800; flex-shrink: 0; box-shadow: 0 8px 24px rgba(99,102,241,0.3); color: white;">
                {{ substr(session('nama', 'A'), 0, 1) }}
            </div>
            <div style="flex: 1; min-width: 280px;">
                <h2 style="font-size: 1.35rem; font-weight: 700; margin-bottom: 0.15rem; letter-spacing: -0.3px;">{{ session('nama', 'Administrator') }}</h2>
                <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem;">{{ session('username', 'admin@erapor.local') }}</p>
                <span style="background: var(--accent-light); color: #818cf8; padding: 0.2rem 0.7rem; border-radius: 99px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">{{ session('role', 'admin') }}</span>

                <div style="border-top: var(--glass-border); margin-top: 1.5rem; padding-top: 1.5rem;">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">Nama Lengkap</label>
                            <input type="text" value="{{ session('nama') }}"
                                   style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.65rem 1rem; border-radius: var(--radius-sm); font-size: 0.9rem; outline: none;">
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">Username</label>
                            <input type="text" value="{{ session('username') }}" readonly
                                   style="width: 100%; background: rgba(0,0,0,0.15); border: 1px solid var(--border-color); color: var(--text-muted); padding: 0.65rem 1rem; border-radius: var(--radius-sm); font-size: 0.9rem; outline: none; cursor: not-allowed;">
                        </div>
                        <div style="margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: flex-start; flex-wrap: wrap;">
                            <div style="width: 100px; height: 70px; background: var(--bg-tertiary); border-radius: var(--radius-md); border: 1px dashed rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                @if(isset($signature))
                                    <img src="{{ Storage::url($signature) }}" style="width: 100%; height: 100%; object-fit: contain;">
                                @else
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="opacity: 0.2;"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                @endif
                            </div>
                            <div style="flex: 1; min-width: 220px;">
                                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.3rem; text-transform: uppercase; letter-spacing: 0.5px;">Tanda Tangan (Khusus Wali Kelas)</label>
                                <p style="font-size: 0.7rem; color: var(--text-muted); margin-bottom: 0.75rem;">Akan ditampilkan di Rapor. Gambar (PNG/JPG/SVG/WEBP) transparan disarankan.</p>
                                <input type="file" name="signature" accept="image/*" style="font-size: 0.8rem; color: var(--text-secondary);">
                            </div>
                        </div>
                        <button type="submit" style="background: var(--accent-gradient); color: white; border: none; padding: 0.65rem 2rem; border-radius: var(--radius-md); font-weight: 600; font-size: 0.85rem; cursor: pointer; box-shadow: 0 4px 12px rgba(99,102,241,0.3);">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
