@extends('layouts.app')
@section('title', 'Sistem & Utility - e-Rapor SD')
@section('header_title', 'Sistem & Utility')
@section('header_subtitle', 'Manajemen data, pencadangan, dan sinkronisasi lanjutan')

@section('content')
@section('content')
<div class="animate-slide-up">
    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid rgba(16, 185, 129, 0.2);">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid rgba(239, 68, 68, 0.2);">
            {{ session('error') }}
        </div>
    @endif
</div>

<div class="stats-grid animate-slide-up delay-1">
    <!-- Backup / Kirim -->
    <div class="stat-card" style="align-items: center; display: flex; flex-direction: column; padding: 3rem 2rem; background: linear-gradient(145deg, rgba(30, 41, 59, 0.4), rgba(15, 23, 42, 0.6)); border: 1px solid rgba(59, 130, 246, 0.2); position: relative; overflow: hidden;">
        <!-- Background Glow Deco -->
        <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%); z-index: 0;"></div>
        
        <div class="stat-icon" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.2)); color: #60a5fa; margin-bottom: 2rem; width: 80px; height: 80px; border-radius: 24px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(0,0,0,0.2); border: 1px solid rgba(59, 130, 246, 0.3); z-index: 1;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.5 19a3.5 3.5 0 0 0 0-7h-1.5a7 7 0 1 0-11.91 4.43"></path>
                <path d="M12 22V12"></path>
                <path d="M9 15l3-3 3 3"></path>
            </svg>
        </div>

        <div style="text-align: center; margin-bottom: 2.5rem; z-index: 1;">
            <h3 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 0.75rem; letter-spacing: -0.02em; background: linear-gradient(to right, #fff, #94a3b8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Kirim Nilai ke Cloud</h3>
            <p style="color: #94a3b8; font-size: 0.95rem; line-height: 1.6; max-width: 320px; margin: 0 auto;">
                Selesaikan tugas Anda dengan mengirimkan seluruh data penilaian langsung ke pusat data sekolah.
            </p>
        </div>
        
        <form action="{{ route('database.push') }}" method="POST" style="width: 100%; z-index: 1;">
            @csrf
            <button type="submit" class="btn-sync-premium" style="width: 100%; height: 60px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none; border-radius: 16px; color: white; font-weight: 700; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.75rem; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4), inset 0 1px 1px rgba(255,255,255,0.2);">
                <span>KIRIM DATA SEKARANG</span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
            </button>
        </form>

        <style>
            .btn-sync-premium:hover {
                transform: translateY(-3px);
                box-shadow: 0 20px 35px -10px rgba(37, 99, 235, 0.5), inset 0 1px 1px rgba(255,255,255,0.3);
                filter: brightness(1.1);
            }
            .btn-sync-premium:active {
                transform: translateY(1px);
            }
        </style>

        <div style="margin-top: 2rem; text-align: center; z-index: 1;">
            <a href="{{ route('backup.export') }}" style="color: #64748b; font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: color 0.2s;" onmouseover="this.style.color='#94a3b8'" onmouseout="this.style.color='#64748b'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Cadangkan ke file JSON (Offline)
            </a>
        </div>
    </div>

    @if(session('role') === 'admin')
    <!-- Restore / Ambil -->
    <div class="stat-card" style="align-items: flex-start; display: block; padding: 2rem;">
        <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; margin-bottom: 1.5rem;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
        </div>
        <div style="margin-bottom: 2rem;">
            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">Ambil Data (Impor)</h3>
            <p style="color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Masukkan data nilai dari file JSON yang dikirim oleh Guru ke dalam database pusat.</p>
        </div>
        <form action="{{ route('backup.import') }}" method="POST" enctype="multipart/form-data" id="restoreForm">
            @csrf
            <input type="file" name="backup_file" id="backup_file" style="display: none;" onchange="this.form.submit()">
            <button type="button" onclick="document.getElementById('backup_file').click()" style="width: 100%; background: #f59e0b; color: white; border: none; padding: 0.75rem; border-radius: 12px; font-weight: 700; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                PILIH FILE GURU
            </button>
        </form>
    </div>

    <!-- Kirim Dapodik -->
    <div class="stat-card" style="align-items: flex-start; display: block; padding: 2rem;">
        <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; margin-bottom: 1.5rem;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.92-12.28l5.08 5.08"></path></svg>
        </div>
        <div style="margin-bottom: 2rem;">
            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">Kirim Nilai Dapodik</h3>
            <p style="color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Kirim hasil akhir yang sudah terkumpul ke server Dapodik pusat.</p>
        </div>
        <a href="{{ route('kirim_dapodik') }}" class="btn-sync" style="width: 100%; display: flex; justify-content: center; text-decoration: none; font-weight: 700;">
            SINKRON KE DAPODIK
        </a>
    </div>
    @endif
</div>

@if(session('role') === 'admin')
<div class="stat-card animate-slide-up delay-2" style="margin-top: 2rem; padding: 2rem;">
    <h3 style="margin-bottom: 1rem; font-weight: 700; color: #ef4444; display: flex; align-items: center; gap: 0.5rem;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
        Zona Bahaya
    </h3>
    <p style="color: var(--text-secondary); margin-bottom: 1.5rem; font-size: 0.875rem;">Kosongkan database jika ingin memulai semester baru atau menghapus data simulasi.</p>
    <button onclick="if(confirm('Hapus seluruh data nilai?')) alert('Fitur hapus massal sedang disiapkan.')" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer;">Kosongkan Database Lokal</button>
</div>
@endif
@endsection
@endsection
