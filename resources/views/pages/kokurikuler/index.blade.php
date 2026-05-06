@extends('layouts.app')
@section('title', 'Kokurikuler - e-Rapor SD')
@section('header_title', 'Manajemen Kokurikuler')
@section('header_subtitle', 'Kelola grup dan aktivitas kegiatan kokurikuler')

@section('content')
<div class="animate-slide-up" style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; align-items: start;">

    {{-- Form Tambah Grup --}}
    <div>
        @if(session('success'))
        <div style="background:rgba(16,185,129,0.1); color:#34d399; padding:0.85rem 1.25rem; border-radius:var(--radius-md); margin-bottom:1.25rem; border:1px solid rgba(16,185,129,0.15); font-size:0.85rem;">
            ✓ {{ session('success') }}
        </div>
        @endif

        <div style="background:var(--card-bg); border:var(--glass-border); border-radius:var(--radius-lg); padding:1.5rem; margin-bottom:1.25rem;">
            <h3 style="font-size:0.9rem; font-weight:700; margin-bottom:1.25rem; color:var(--accent);">➕ Tambah Grup Kokurikuler</h3>
            <form action="{{ route('kokurikuler.store_group') }}" method="POST">
                @csrf
                <div style="margin-bottom:0.85rem;">
                    <label style="display:block; font-size:0.72rem; font-weight:600; color:var(--text-secondary); margin-bottom:0.3rem; text-transform:uppercase;">Nama Grup / Kegiatan</label>
                    <input type="text" name="name" required placeholder="cth: Pramuka, Seni Budaya..." style="width:100%; background:var(--bg-tertiary); border:1px solid var(--border-color); color:var(--text-primary); padding:0.6rem 0.85rem; border-radius:var(--radius-sm); font-size:0.85rem; outline:none;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-size:0.72rem; font-weight:600; color:var(--text-secondary); margin-bottom:0.3rem; text-transform:uppercase;">Fase (Opsional)</label>
                    <select name="fase" style="width:100%; background:var(--bg-tertiary); border:1px solid var(--border-color); color:var(--text-primary); padding:0.6rem 0.85rem; border-radius:var(--radius-sm); font-size:0.85rem; outline:none;">
                        <option value="">-- Semua Fase --</option>
                        @foreach(['A','B','C','D','E','F'] as $f)
                        <option value="{{ $f }}">Fase {{ $f }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" style="width:100%; background:var(--accent-gradient); color:white; border:none; padding:0.65rem; border-radius:var(--radius-md); font-weight:600; font-size:0.85rem; cursor:pointer;">
                    Simpan Grup
                </button>
            </form>
        </div>

        {{-- Form Tambah Aktivitas --}}
        @if($groups->count() > 0)
        <div style="background:var(--card-bg); border:var(--glass-border); border-radius:var(--radius-lg); padding:1.5rem;">
            <h3 style="font-size:0.9rem; font-weight:700; margin-bottom:1.25rem; color:#10b981;">➕ Tambah Aktivitas</h3>
            <form action="{{ route('kokurikuler.store_activity') }}" method="POST">
                @csrf
                <div style="margin-bottom:0.85rem;">
                    <label style="display:block; font-size:0.72rem; font-weight:600; color:var(--text-secondary); margin-bottom:0.3rem; text-transform:uppercase;">Grup</label>
                    <select name="group_id" required style="width:100%; background:var(--bg-tertiary); border:1px solid var(--border-color); color:var(--text-primary); padding:0.6rem 0.85rem; border-radius:var(--radius-sm); font-size:0.85rem; outline:none;">
                        <option value="">-- Pilih Grup --</option>
                        @foreach($groups as $g)
                        <option value="{{ $g->id }}">{{ $g->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:0.85rem;">
                    <label style="display:block; font-size:0.72rem; font-weight:600; color:var(--text-secondary); margin-bottom:0.3rem; text-transform:uppercase;">Tema / Dimensi</label>
                    <input type="text" name="theme" required placeholder="cth: Gotong Royong, Berkebhinekaan..." style="width:100%; background:var(--bg-tertiary); border:1px solid var(--border-color); color:var(--text-primary); padding:0.6rem 0.85rem; border-radius:var(--radius-sm); font-size:0.85rem; outline:none;">
                </div>
                <div style="margin-bottom:0.85rem;">
                    <label style="display:block; font-size:0.72rem; font-weight:600; color:var(--text-secondary); margin-bottom:0.3rem; text-transform:uppercase;">Nama Aktivitas</label>
                    <input type="text" name="activity_name" required placeholder="cth: Kerja bakti lingkungan sekolah" style="width:100%; background:var(--bg-tertiary); border:1px solid var(--border-color); color:var(--text-primary); padding:0.6rem 0.85rem; border-radius:var(--radius-sm); font-size:0.85rem; outline:none;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-size:0.72rem; font-weight:600; color:var(--text-secondary); margin-bottom:0.3rem; text-transform:uppercase;">Keterangan (Opsional)</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi singkat..." style="width:100%; background:var(--bg-tertiary); border:1px solid var(--border-color); color:var(--text-primary); padding:0.6rem 0.85rem; border-radius:var(--radius-sm); font-size:0.85rem; outline:none; resize:vertical;"></textarea>
                </div>
                <button type="submit" style="width:100%; background:linear-gradient(135deg,#10b981,#059669); color:white; border:none; padding:0.65rem; border-radius:var(--radius-md); font-weight:600; font-size:0.85rem; cursor:pointer;">
                    Simpan Aktivitas
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Daftar Grup & Aktivitas --}}
    <div>
        @if($groups->count() === 0)
        <div style="background:var(--card-bg); border:var(--glass-border); border-radius:var(--radius-lg); padding:3rem; text-align:center; color:var(--text-secondary);">
            Belum ada grup kokurikuler. Tambahkan dari form di sebelah kiri.
        </div>
        @else
        @foreach($groups as $group)
        <div style="background:var(--card-bg); border:var(--glass-border); border-radius:var(--radius-lg); margin-bottom:1.25rem; overflow:hidden;">
            <div style="padding:1rem 1.5rem; display:flex; justify-content:space-between; align-items:center; border-bottom:var(--glass-border);">
                <div>
                    <div style="font-weight:700; font-size:1rem; color:var(--text-primary);">{{ $group->name }}</div>
                    @if($group->fase)<span style="font-size:0.7rem; background:rgba(99,102,241,0.1); color:#818cf8; padding:0.2rem 0.6rem; border-radius:6px;">Fase {{ $group->fase }}</span>@endif
                </div>
                <form action="{{ route('kokurikuler.destroy_group', $group->id) }}" method="POST" onsubmit="return confirm('Hapus grup ini beserta semua aktivitasnya?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:rgba(239,68,68,0.1); color:#f87171; border:1px solid rgba(239,68,68,0.2); padding:0.3rem 0.75rem; border-radius:6px; font-size:0.75rem; cursor:pointer;">Hapus</button>
                </form>
            </div>
            @if($group->activities->count() > 0)
            <div style="padding:1rem 1.5rem;">
                @foreach($group->activities as $act)
                <div style="background:var(--bg-tertiary); border:1px solid var(--border-color); border-radius:var(--radius-sm); padding:0.85rem 1rem; margin-bottom:0.5rem; display:flex; gap:1rem; align-items:flex-start;">
                    <div style="background:rgba(6,182,212,0.1); color:#06b6d4; padding:0.25rem 0.6rem; border-radius:5px; font-size:0.7rem; font-weight:700; white-space:nowrap;">{{ $act->theme }}</div>
                    <div>
                        <div style="font-size:0.875rem; font-weight:600; color:var(--text-primary);">{{ $act->activity_name }}</div>
                        @if($act->description)<div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.2rem;">{{ $act->description }}</div>@endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div style="padding:1rem 1.5rem; color:var(--text-muted); font-size:0.8rem; font-style:italic;">Belum ada aktivitas dalam grup ini.</div>
            @endif
        </div>
        @endforeach

        <div style="margin-top:1.5rem;">
            <a href="{{ route('kokurikuler.penilaian') }}" style="display:inline-flex; align-items:center; gap:0.75rem; text-decoration:none; background:var(--accent-gradient); color:white; padding:0.75rem 1.75rem; border-radius:var(--radius-md); font-weight:600; box-shadow:0 4px 16px rgba(99,102,241,0.3);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Mulai Penilaian Kokurikuler
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
