@extends('layouts.app')

@section('title', 'Perpindahan Rombel — ' . ($student['nama'] ?? '') . ' - e-Rapor SD')
@section('header_title', 'Perpindahan Rombongan Belajar')
@section('header_subtitle', 'Pindahkan siswa ke kelas yang berbeda tanpa mengubah data Dapodik')

@section('content')
<div class="animate-slide-up">

    {{-- Breadcrumb --}}
    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.75rem; font-size: 0.82rem; color: var(--text-muted);">
        <a href="{{ route('students.index') }}" style="color: var(--text-secondary); text-decoration: none; display:flex; align-items:center; gap:0.3rem; transition: color 0.2s;"
           onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-secondary)'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Kelola Peserta Didik
        </a>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        <a href="{{ route('students.edit', $student['peserta_didik_id']) }}" style="color: var(--text-secondary); text-decoration: none; transition: color 0.2s;"
           onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-secondary)'">{{ $student['nama'] }}</a>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        <span style="color: var(--text-primary); font-weight: 500;">Perpindahan Rombel</span>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
    <div style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.25); color: #34d399;
                padding: 0.85rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.25rem;
                display: flex; align-items: center; gap: 0.6rem; font-size: 0.875rem; font-weight: 500;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div style="display: grid; grid-template-columns: 320px 1fr; gap: 1.5rem; align-items: start;">

        {{-- Kartu Profil Siswa (kiri) --}}
        <div>
            <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.75rem; margin-bottom: 1rem;">

                {{-- Avatar --}}
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 1rem;
                                background: {{ ($student['jenis_kelamin'] ?? '') == 'L' ? 'linear-gradient(135deg, #3b82f6, #60a5fa)' : 'linear-gradient(135deg, #f43f5e, #fb7185)' }};
                                display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700; color: white;
                                box-shadow: 0 8px 24px {{ ($student['jenis_kelamin'] ?? '') == 'L' ? 'rgba(59,130,246,0.3)' : 'rgba(244,63,94,0.3)' }};">
                        {{ strtoupper(substr($student['nama'] ?? 'S', 0, 1)) }}
                    </div>
                    <h2 style="font-size: 1rem; font-weight: 700; margin-bottom: 0.3rem; line-height: 1.3;">{{ $student['nama'] ?? '-' }}</h2>
                    @if(($student['jenis_kelamin'] ?? '') == 'L')
                        <span style="background: rgba(59,130,246,0.1); color: #3b82f6; padding: 0.2rem 0.75rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600;">Laki-laki</span>
                    @else
                        <span style="background: rgba(244,63,94,0.1); color: #f43f5e; padding: 0.2rem 0.75rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600;">Perempuan</span>
                    @endif
                </div>

                {{-- Info --}}
                <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                    <div style="background: var(--bg-tertiary); border-radius: var(--radius-md); padding: 0.65rem 0.9rem;">
                        <div style="font-size: 0.68rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.2rem;">NISN</div>
                        <div style="font-weight: 600; font-size: 0.85rem; font-family: monospace;">{{ $student['nisn'] ?? '-' }}</div>
                    </div>

                    {{-- Rombel Saat Ini --}}
                    <div style="background: {{ $currentRombel ? 'rgba(16,185,129,0.08)' : 'rgba(239,68,68,0.08)' }}; border: 1px solid {{ $currentRombel ? 'rgba(16,185,129,0.2)' : 'rgba(239,68,68,0.2)' }}; border-radius: var(--radius-md); padding: 0.75rem 0.9rem;">
                        <div style="font-size: 0.68rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.3rem;">Rombel Saat Ini</div>
                        @if($currentRombel)
                            <div style="font-weight: 700; font-size: 0.95rem; color: #34d399; display: flex; align-items: center; gap: 0.4rem;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                {{ $currentRombel['nama'] }}
                            </div>
                        @else
                            <div style="font-weight: 600; font-size: 0.85rem; color: #f87171;">Belum terdaftar di rombel manapun</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tombol Edit Identitas --}}
            <a href="{{ route('students.edit', $student['peserta_didik_id']) }}"
               style="display: flex; align-items: center; justify-content: center; gap: 0.6rem;
                      background: var(--accent-light);
                      border: 1px solid rgba(225,29,72,0.15); color: var(--accent);
                      padding: 0.75rem 1rem; border-radius: var(--radius-md);
                      text-decoration: none; font-weight: 600; font-size: 0.85rem;
                      transition: all 0.25s; width: 100%; box-sizing: border-box;"
               onmouseover="this.style.background='rgba(225,29,72,0.15)'"
               onmouseout="this.style.background='var(--accent-light)'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit Identitas Siswa
            </a>

            {{-- Info notice --}}
            <div style="background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.15); border-radius: var(--radius-md); padding: 0.85rem 1rem; margin-top: 1rem; font-size: 0.78rem; color: #6ee7b7; line-height: 1.5;">
                <div style="font-weight: 600; margin-bottom: 0.3rem; display:flex; align-items:center; gap:0.4rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    Cara Kerja
                </div>
                Perpindahan rombel disimpan sebagai <strong>override lokal</strong>. Siswa akan muncul di rombel baru tanpa mengubah data Dapodik.
            </div>
        </div>

        {{-- Panel Utama (kanan) --}}
        <div>

            {{-- Visualisasi Alur Perpindahan --}}
            <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.75rem; margin-bottom: 1.25rem;">
                <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 1.25rem;">Alur Perpindahan</div>

                <div style="display: flex; align-items: center; gap: 1rem;">
                    {{-- Rombel Asal --}}
                    <div style="flex: 1; background: var(--bg-tertiary); border-radius: var(--radius-md); padding: 1rem; text-align: center; border: 1px solid {{ $currentRombel ? 'rgba(225,29,72,0.2)' : 'var(--border-color)' }};">
                        <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;">Dari Kelas</div>
                        <div style="font-weight: 700; color: {{ $currentRombel ? 'var(--accent)' : 'var(--text-muted)' }}; font-size: 0.95rem;">
                            {{ $currentRombel['nama'] ?? '—' }}
                        </div>
                    </div>

                    {{-- Panah --}}
                    <div style="color: #34d399; flex-shrink: 0;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                            <path d="M17 1l4 4-4 4"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/>
                            <path d="M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/>
                        </svg>
                    </div>

                    {{-- Rombel Tujuan (dinamis) --}}
                    <div id="rombel-tujuan-preview" style="flex: 1; background: rgba(16,185,129,0.06); border-radius: var(--radius-md); padding: 1rem; text-align: center; border: 1px solid rgba(16,185,129,0.2);">
                        <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.4rem;">Ke Kelas</div>
                        <div id="rombel-tujuan-nama" style="font-weight: 700; color: #34d399; font-size: 0.95rem;">— Pilih Dulu —</div>
                    </div>
                </div>
            </div>

            {{-- Form Perpindahan --}}
            <form action="{{ route('students.rombel.update', $student['peserta_didik_id']) }}" method="POST" id="rombelForm">
                @csrf
                <input type="hidden" name="from_rombongan_belajar_id" value="{{ $currentRombel['rombongan_belajar_id'] ?? $currentRombel['id'] ?? '' }}">
                <input type="hidden" name="action" id="rombelAction" value="{{ $currentRombel ? 'transfer' : 'add' }}">

                <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.75rem; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.25rem; padding-bottom: 1rem; border-bottom: var(--glass-border);">
                        <div style="width: 32px; height: 32px; background: rgba(16,185,129,0.12); border-radius: 8px; display:flex; align-items:center; justify-content:center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div>
                            <div style="font-weight: 700; font-size: 0.9rem;">Pilih Rombel Tujuan</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">Siswa akan dipindahkan ke rombel yang dipilih</div>
                        </div>
                    </div>

                    {{-- Daftar Rombel sebagai kartu --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;" id="rombelGrid">
                        @foreach($rombels as $r)
                            @php
                                $rid = $r['rombongan_belajar_id'] ?? $r['id'];
                                $isCurrent = ($currentRombel['rombongan_belajar_id'] ?? $currentRombel['id'] ?? null) == $rid;
                                $jmlSiswa = count($r['anggota_rombel'] ?? []);
                            @endphp
                            <label class="rombel-card {{ $isCurrent ? 'rombel-current' : '' }}"
                                   data-id="{{ $rid }}"
                                   data-nama="{{ $r['nama'] }}"
                                   style="display: flex; align-items: center; gap: 0.75rem;
                                          background: {{ $isCurrent ? 'rgba(0,0,0,0.03)' : 'var(--bg-tertiary)' }};
                                          border: 1px solid {{ $isCurrent ? 'rgba(0,0,0,0.1)' : 'var(--border-color)' }};
                                          border-radius: var(--radius-md); padding: 0.85rem 1rem;
                                          cursor: {{ $isCurrent ? 'not-allowed' : 'pointer' }};
                                          transition: all 0.2s; opacity: {{ $isCurrent ? '0.5' : '1' }};">
                                <input type="radio" name="rombongan_belajar_id" value="{{ $rid }}" {{ $isCurrent ? 'disabled' : '' }}
                                       style="accent-color: #34d399; flex-shrink:0;" class="rombel-radio"
                                       onchange="updateTujuan('{{ $rid }}', '{{ $r['nama'] }}')">
                                <div style="flex:1; min-width:0;">
                                    <div style="font-weight: 600; font-size: 0.88rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $r['nama'] }}</div>
                                    <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 0.15rem;">{{ $jmlSiswa }} siswa terdaftar</div>
                                </div>
                                @if($isCurrent)
                                <span style="font-size: 0.65rem; background: rgba(0,0,0,0.05); color: var(--text-muted); padding: 0.15rem 0.5rem; border-radius: 99px; white-space: nowrap; font-weight: 600;">Kelas Ini</span>
                                @endif
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                    <a href="{{ route('students.index') }}"
                       style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--text-secondary); text-decoration: none; padding: 0.65rem 1.5rem; border-radius: var(--radius-md); background: var(--bg-tertiary); border: var(--glass-border); font-size: 0.85rem; font-weight: 500; transition: all 0.2s;"
                       onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                        Kembali
                    </a>
                    <button type="submit" id="submitBtn" disabled
                            style="display: inline-flex; align-items: center; gap: 0.6rem;
                                   background: linear-gradient(135deg, #10b981, #059669); color: white; border: none;
                                   padding: 0.7rem 2rem; border-radius: var(--radius-md); font-weight: 700; font-size: 0.9rem;
                                   cursor: not-allowed; opacity: 0.5;
                                   box-shadow: 0 4px 16px rgba(16,185,129,0.3); transition: all 0.25s;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 1l4 4-4 4"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><path d="M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                        Proses Perpindahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateTujuan(id, nama) {
    // Update preview
    document.getElementById('rombel-tujuan-nama').textContent = nama;

    // Enable tombol submit
    const btn = document.getElementById('submitBtn');
    btn.disabled = false;
    btn.style.opacity = '1';
    btn.style.cursor = 'pointer';

    // Update style tiap kartu
    document.querySelectorAll('.rombel-card:not(.rombel-current)').forEach(card => {
        const isSelected = card.dataset.id === id;
        card.style.background = isSelected ? 'rgba(16,185,129,0.08)' : 'var(--bg-tertiary)';
        card.style.borderColor = isSelected ? 'rgba(16,185,129,0.4)' : 'var(--border-color)';
        card.style.boxShadow = isSelected ? '0 0 0 2px rgba(16,185,129,0.1)' : 'none';
    });
}

// Konfirmasi sebelum submit
document.getElementById('rombelForm').addEventListener('submit', function(e) {
    const nama = document.getElementById('rombel-tujuan-nama').textContent;
    const currentNama = "{{ $currentRombel['nama'] ?? 'Belum terdaftar' }}";
    if (!confirm(`Konfirmasi:\nPindahkan "${@json($student['nama'])}" dari "${currentNama}" ke "${nama}"?\n\nLanjutkan?`)) {
        e.preventDefault();
    }
});
</script>

<style>
.rombel-card:not(.rombel-current):hover {
    background: rgba(16,185,129,0.05) !important;
    border-color: rgba(16,185,129,0.25) !important;
}
</style>
@endsection
