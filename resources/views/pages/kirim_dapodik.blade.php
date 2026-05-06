@extends('layouts.app')

@section('title', 'Kirim ke Dapodik - e-Rapor SD')
@section('header_title', 'Kirim Data ke Dapodik')
@section('header_subtitle', 'Sinkronisasi nilai rapor menuju server Dapodik Pusat')

@section('content')
<div class="animate-slide-up" style="max-width: 720px;">

    {{-- Info Banner --}}
    <div style="background: linear-gradient(135deg, rgba(245,158,11,0.12), rgba(234,88,12,0.08)); border: 1px solid rgba(245,158,11,0.25); border-radius: var(--radius-xl); padding: 2rem; margin-bottom: 2rem; display: flex; gap: 1.5rem; align-items: flex-start;">
        <div style="width: 56px; height: 56px; background: rgba(245,158,11,0.15); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>
        <div>
            <h3 style="font-size: 1.05rem; font-weight: 700; color: #fbbf24; margin-bottom: 0.5rem;">Fitur Dalam Pengembangan</h3>
            <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6;">
                Fitur <strong style="color: var(--text-primary);">Kirim ke Dapodik</strong> saat ini masih dalam tahap pengembangan.
                Fitur ini akan memungkinkan pengiriman data nilai rapor secara otomatis ke server Dapodik Pusat.
            </p>
        </div>
    </div>

    {{-- Cara Manual --}}
    <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.75rem; margin-bottom: 1.25rem;">
        <h3 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 1.25rem; color: var(--accent);">
            📋 Cara Kirim Data Secara Manual (Saat Ini)
        </h3>
        <ol style="padding-left: 1.25rem; line-height: 2.1; font-size: 0.875rem; color: var(--text-secondary);">
            <li>Buka aplikasi <strong style="color: var(--text-primary);">Dapodik</strong> di komputer sekolah</li>
            <li>Masuk ke menu <strong style="color: var(--text-primary);">Nilai → Input Nilai</strong></li>
            <li>Pilih kelas dan mata pelajaran yang sesuai</li>
            <li>Masukkan nilai berdasarkan data dari e-Rapor ini (gunakan fitur <strong style="color: var(--text-primary);">Leger Nilai</strong> sebagai referensi)</li>
            <li>Lakukan sinkronisasi Dapodik setelah semua nilai selesai diinput</li>
        </ol>
    </div>

    {{-- Quick Actions --}}
    <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.75rem;">
        <h3 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 1.25rem; color: var(--accent);">
            ⚡ Aksi Cepat yang Tersedia
        </h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <a href="{{ route('cetak', 'leger') }}" style="text-decoration: none; background: rgba(99,102,241,0.08); border: 1px solid rgba(99,102,241,0.2); border-radius: var(--radius-md); padding: 1.25rem; display: flex; gap: 0.85rem; align-items: center; transition: all 0.2s;" onmouseover="this.style.background='rgba(99,102,241,0.15)'" onmouseout="this.style.background='rgba(99,102,241,0.08)'">
                <div style="width: 40px; height: 40px; background: rgba(99,102,241,0.15); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <div>
                    <div style="font-weight: 600; font-size: 0.875rem; color: var(--text-primary);">Cetak Leger Nilai</div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.15rem;">Rekap nilai semua siswa per kelas</div>
                </div>
            </a>
            <a href="{{ route('cetak', 'nilai') }}" style="text-decoration: none; background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.2); border-radius: var(--radius-md); padding: 1.25rem; display: flex; gap: 0.85rem; align-items: center; transition: all 0.2s;" onmouseover="this.style.background='rgba(16,185,129,0.15)'" onmouseout="this.style.background='rgba(16,185,129,0.08)'">
                <div style="width: 40px; height: 40px; background: rgba(16,185,129,0.15); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                </div>
                <div>
                    <div style="font-weight: 600; font-size: 0.875rem; color: var(--text-primary);">Cetak Rapor Siswa</div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.15rem;">Cetak individual atau massal per kelas</div>
                </div>
            </a>
            <a href="{{ route('backup.export') }}" style="text-decoration: none; background: rgba(6,182,212,0.08); border: 1px solid rgba(6,182,212,0.2); border-radius: var(--radius-md); padding: 1.25rem; display: flex; gap: 0.85rem; align-items: center; transition: all 0.2s;" onmouseover="this.style.background='rgba(6,182,212,0.15)'" onmouseout="this.style.background='rgba(6,182,212,0.08)'">
                <div style="width: 40px; height: 40px; background: rgba(6,182,212,0.15); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                </div>
                <div>
                    <div style="font-weight: 600; font-size: 0.875rem; color: var(--text-primary);">Ekspor Backup Data</div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.15rem;">Unduh semua data nilai sebagai JSON</div>
                </div>
            </a>
            <a href="{{ route('status_penilaian', 'input') }}" style="text-decoration: none; background: rgba(139,92,246,0.08); border: 1px solid rgba(139,92,246,0.2); border-radius: var(--radius-md); padding: 1.25rem; display: flex; gap: 0.85rem; align-items: center; transition: all 0.2s;" onmouseover="this.style.background='rgba(139,92,246,0.15)'" onmouseout="this.style.background='rgba(139,92,246,0.08)'">
                <div style="width: 40px; height: 40px; background: rgba(139,92,246,0.15); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <div>
                    <div style="font-weight: 600; font-size: 0.875rem; color: var(--text-primary);">Status Penilaian</div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.15rem;">Cek kelengkapan nilai per kelas/mapel</div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
