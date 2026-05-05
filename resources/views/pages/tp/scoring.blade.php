@extends('layouts.app')

@section('title', 'Input Nilai TP - e-Rapor SD')
@section('header_title', 'Input Nilai Tujuan Pembelajaran')
@section('header_subtitle', 'Masukkan nilai per TP, nilai akhir dihitung otomatis')

@section('content')
    <!-- Filter -->
    <div class="animate-slide-up" style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.25rem 1.5rem; margin-bottom: 1.5rem;">
        <form action="{{ route('tp.scoring') }}" method="GET" style="display: flex; gap: 1rem; align-items: end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 0.7rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.3rem; text-transform: uppercase; letter-spacing: 0.5px;">Rombongan Belajar</label>
                <select name="rombongan_belajar_id" onchange="this.form.submit()"
                        style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.6rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                    <option value="">-- Pilih Rombel --</option>
                    @foreach($rombels as $r)
                        <option value="{{ $r['rombongan_belajar_id'] ?? $r['id'] }}" {{ $rombelId == ($r['rombongan_belajar_id'] ?? $r['id']) ? 'selected' : '' }}>{{ $r['nama'] }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 0.7rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.3rem; text-transform: uppercase; letter-spacing: 0.5px;">Mata Pelajaran</label>
                <select name="mata_pelajaran_id" onchange="this.form.submit()"
                        style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.6rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                    <option value="">-- Pilih Mapel --</option>
                    @php $selectedRombel = collect($rombels)->firstWhere(fn($r) => ($r['rombongan_belajar_id'] ?? $r['id']) == $rombelId); @endphp
                    @if($selectedRombel && isset($selectedRombel['pembelajaran']))
                        @foreach($selectedRombel['pembelajaran'] as $p)
                            <option value="{{ $p['mata_pelajaran_id'] }}" {{ $mapelId == $p['mata_pelajaran_id'] ? 'selected' : '' }}>{{ $p['nama_mata_pelajaran'] }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </form>
    </div>

    @if($rombelId && $mapelId)
    <!-- Scoring Table -->
    <div class="animate-slide-up delay-1">
        <div class="section-header">
            <div>Daftar Siswa & Nilai TP</div>
            <span style="background: var(--accent-light); color: #818cf8; padding: 0.2rem 0.7rem; border-radius: 99px; font-size: 0.8rem; font-weight: 600;">{{ count($tpData) }} TP</span>
        </div>
        <div class="table-container">
            <form action="{{ route('tp.scores.store') }}" method="POST">
                @csrf
                <input type="hidden" name="rombongan_belajar_id" value="{{ $rombelId }}">
                <input type="hidden" name="mata_pelajaran_id" value="{{ $mapelId }}">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            @foreach($tpData as $tp)
                                <th style="text-align: center;" title="{{ $tp->content }}">{{ $tp->kode ?? 'TP'.$loop->iteration }}</th>
                            @endforeach
                            <th style="text-align: center; color: #34d399;">Tertinggi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $siswa)
                        @php $pdId = $siswa['peserta_didik_id']; @endphp
                        <tr>
                            <td>
                                <div style="font-weight: 500;">{{ $siswa['nama'] }}</div>
                                <div style="color: var(--text-muted); font-size: 0.7rem;">{{ $siswa['nisn'] ?? '-' }}</div>
                            </td>
                            @php $maxScore = 0; @endphp
                            @foreach($tpData as $tp)
                                @php
                                    $scoreObj = $scores->has($pdId) ? $scores->get($pdId)->firstWhere('tp_id', $tp->id) : null;
                                    $val = $scoreObj ? $scoreObj->score : null;
                                    if($val > $maxScore) $maxScore = $val;
                                @endphp
                                <td style="text-align: center;">
                                    <input type="number" name="scores[{{ $pdId }}][{{ $tp->id }}]" value="{{ $val }}" min="0" max="100"
                                           style="width: 52px; text-align: center; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.4rem; border-radius: 6px; font-size: 0.85rem; outline: none;">
                                </td>
                            @endforeach
                            <td style="text-align: center; font-weight: 700; color: #34d399; font-size: 1.1rem;">{{ $maxScore ?: '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="padding: 1rem 1.5rem; text-align: right; border-top: var(--glass-border);">
                    <button type="submit" style="background: var(--accent-gradient); color: white; border: none; padding: 0.65rem 2rem; border-radius: var(--radius-md); font-weight: 600; font-size: 0.85rem; cursor: pointer; box-shadow: 0 4px 12px rgba(99,102,241,0.3);">
                        Simpan & Kalkulasi Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
    @else
    <div class="animate-slide-up delay-1" style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 3rem; text-align: center; color: var(--text-secondary);">
        Pilih Rombongan Belajar dan Mata Pelajaran terlebih dahulu untuk menampilkan tabel penilaian.
    </div>
    @endif
@endsection
