@extends('layouts.app')
@section('title', 'Penilaian Kokurikuler - e-Rapor SD')
@section('header_title', 'Penilaian Kokurikuler')
@section('header_subtitle', 'Input nilai kokurikuler siswa per kelas')

@section('content')
{{-- Filter Kelas --}}
<div class="animate-slide-up" style="background:var(--card-bg); border:var(--glass-border); border-radius:var(--radius-lg); padding:1.25rem 1.5rem; margin-bottom:1.5rem;">
    <form action="{{ route('kokurikuler.penilaian') }}" method="GET" style="display:flex; gap:1rem; align-items:end; flex-wrap:wrap;">
        <div style="flex:1; min-width:240px;">
            <label style="display:block; font-size:0.7rem; font-weight:600; color:var(--text-secondary); margin-bottom:0.3rem; text-transform:uppercase;">Rombongan Belajar</label>
            <select name="rombongan_belajar_id" onchange="this.form.submit()" style="width:100%; background:var(--bg-tertiary); border:1px solid var(--border-color); color:var(--text-primary); padding:0.6rem 0.85rem; border-radius:var(--radius-sm); font-size:0.85rem; outline:none;">
                <option value="">-- Pilih Kelas --</option>
                @foreach($rombels as $r)
                <option value="{{ $r['rombongan_belajar_id'] ?? $r['id'] }}" {{ $rombelId == ($r['rombongan_belajar_id'] ?? $r['id']) ? 'selected' : '' }}>{{ $r['nama'] }}</option>
                @endforeach
            </select>
        </div>
        @if($rombelId)
        <a href="{{ route('kokurikuler.index') }}" style="text-decoration:none; font-size:0.8rem; color:var(--text-secondary); padding:0.6rem 1rem; border:1px solid var(--border-color); border-radius:var(--radius-sm);">⚙ Kelola Grup</a>
        @endif
    </form>
</div>

@if(session('success'))
<div style="background:rgba(16,185,129,0.1); color:#34d399; padding:0.85rem 1.25rem; border-radius:var(--radius-md); margin-bottom:1.5rem; border:1px solid rgba(16,185,129,0.15); font-size:0.85rem;">✓ {{ session('success') }}</div>
@endif

@if(!$rombelId)
<div class="animate-slide-up delay-1" style="background:var(--card-bg); border:var(--glass-border); border-radius:var(--radius-lg); padding:3rem; text-align:center; color:var(--text-secondary);">
    Pilih rombongan belajar terlebih dahulu untuk mulai menilai.
</div>
@elseif($groups->count() === 0)
<div class="animate-slide-up delay-1" style="background:var(--card-bg); border:var(--glass-border); border-radius:var(--radius-lg); padding:3rem; text-align:center; color:var(--text-secondary);">
    Belum ada grup kokurikuler. <a href="{{ route('kokurikuler.index') }}" style="color:#818cf8;">Buat dulu di sini</a>.
</div>
@elseif(empty($students))
<div class="animate-slide-up delay-1" style="background:var(--card-bg); border:var(--glass-border); border-radius:var(--radius-lg); padding:3rem; text-align:center; color:var(--text-secondary);">
    Tidak ada siswa di kelas ini.
</div>
@else
<form action="{{ route('kokurikuler.penilaian.store') }}" method="POST">
    @csrf
    <input type="hidden" name="rombongan_belajar_id" value="{{ $rombelId }}">

    @foreach($groups as $group)
    <div class="animate-slide-up delay-1" style="background:var(--card-bg); border:var(--glass-border); border-radius:var(--radius-lg); margin-bottom:1.5rem; overflow:hidden;">
        <div style="padding:1rem 1.5rem; border-bottom:var(--glass-border); background:rgba(99,102,241,0.07);">
            <div style="font-weight:700; font-size:1rem;">{{ $group->name }}</div>
            @if($group->fase)<span style="font-size:0.7rem; background:rgba(99,102,241,0.12); color:#818cf8; padding:0.2rem 0.6rem; border-radius:6px;">Fase {{ $group->fase }}</span>@endif
        </div>

        @foreach($group->activities as $act)
        <div style="padding:1rem 1.5rem; border-bottom:1px solid rgba(255,255,255,0.04);">
            <div style="font-size:0.85rem; font-weight:600; margin-bottom:1rem; display:flex; align-items:center; gap:0.75rem;">
                <span style="background:rgba(6,182,212,0.1); color:#06b6d4; padding:0.2rem 0.6rem; border-radius:5px; font-size:0.7rem;">{{ $act->theme }}</span>
                {{ $act->activity_name }}
            </div>

            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-size:0.82rem;">
                    <thead>
                        <tr style="background:rgba(255,255,255,0.03);">
                            <th style="padding:0.5rem 0.75rem; text-align:left; color:var(--text-secondary); font-weight:600; border-bottom:1px solid var(--border-color);">Nama Siswa</th>
                            <th style="padding:0.5rem 0.75rem; text-align:center; color:var(--text-secondary); font-weight:600; border-bottom:1px solid var(--border-color); width:160px;">Nilai (BB/MB/BSH/SB)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $siswa)
                        @php
                            $pdId        = $siswa['peserta_didik_id'];
                            $existing    = $nilaiMap[$pdId][$act->id] ?? null;
                            $currentNilai = $existing?->nilai ?? '';
                        @endphp
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.04);">
                            <td style="padding:0.5rem 0.75rem; color:var(--text-primary);">{{ $siswa['nama'] ?? '-' }}</td>
                            <td style="padding:0.5rem 0.75rem; text-align:center;">
                                <select name="nilai[{{ $pdId }}][{{ $act->id }}]"
                                        style="background:var(--bg-tertiary); border:1px solid var(--border-color); color:var(--text-primary); padding:0.35rem 0.75rem; border-radius:var(--radius-sm); font-size:0.8rem; outline:none; font-weight:600;">
                                    <option value="">-</option>
                                    @foreach(['BB','MB','BSH','SB'] as $nilaiOpt)
                                    <option value="{{ $nilaiOpt }}" {{ $currentNilai === $nilaiOpt ? 'selected' : '' }}
                                            style="color:{{ $nilaiOpt==='SB' ? '#34d399' : ($nilaiOpt==='BSH' ? '#60a5fa' : ($nilaiOpt==='MB' ? '#f59e0b' : '#f87171')) }}">
                                        {{ $nilaiOpt }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach

    <div style="position:sticky; bottom:1rem; z-index:100; text-align:right; padding:1rem 0;">
        <button type="submit" style="background:var(--accent-gradient); color:white; border:none; padding:0.85rem 2.5rem; border-radius:var(--radius-md); font-weight:700; font-size:0.95rem; cursor:pointer; box-shadow:0 4px 20px rgba(99,102,241,0.4);">
            💾 Simpan Semua Penilaian
        </button>
    </div>
</form>
@endif

<div style="margin-top:0.5rem;">
    <p style="font-size:0.75rem; color:var(--text-muted);">
        Keterangan: <strong>BB</strong> = Belum Berkembang &nbsp;·&nbsp; <strong>MB</strong> = Mulai Berkembang &nbsp;·&nbsp; <strong>BSH</strong> = Berkembang Sesuai Harapan &nbsp;·&nbsp; <strong>SB</strong> = Sangat Berkembang
    </p>
</div>
@endsection
