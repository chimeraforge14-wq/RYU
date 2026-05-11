@extends('layouts.app')

@section('title', 'Global Dashboard - Superadmin')
@section('header_title', 'Global Overview')
@section('header_subtitle', 'Monitoring Seluruh Tenant Sekolah')

@section('content')
    {{-- Global Stats --}}
    <div class="animate-slide-up" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; padding: 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">Pusat Kendali e-Rapor</h2>
            <p style="opacity: 0.7; font-size: 0.95rem;">Selamat datang, Super Administrator. Anda memiliki akses penuh ke seluruh ekosistem sekolah.</p>
        </div>
        <div style="background: rgba(255,255,255,0.1); padding: 1rem 2rem; border-radius: var(--radius-lg); text-align: center; border: 1px solid rgba(255,255,255,0.1);">
            <div style="font-size: 0.8rem; opacity: 0.8; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em;">Total Tenant</div>
            <div style="font-size: 2.5rem; font-weight: 800;">{{ $totalSchools }}</div>
        </div>
    </div>

    <div class="section-header animate-slide-up delay-1">
        <div>🏫 Daftar Sekolah Terdaftar</div>
        <a href="{{ route('super.schools') }}" class="btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Sekolah Baru
        </a>
    </div>

    <div class="animate-slide-up delay-2" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        @forelse($schools as $school)
            <div class="stat-card" style="display: block; padding: 0; overflow: hidden; transition: transform 0.3s, box-shadow 0.3s;">
                <div style="padding: 1.5rem; border-bottom: 1px solid rgba(0,0,0,0.05);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div style="background: var(--accent-light); color: var(--accent); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.25rem;">
                            {{ substr($school->name, 0, 1) }}
                        </div>
                        <span style="font-size: 0.65rem; background: #f1f5f9; padding: 0.25rem 0.6rem; border-radius: 99px; color: #64748b; font-weight: 600;">NPSN: {{ $school->npsn }}</span>
                    </div>
                    <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.25rem;">{{ $school->name }}</h3>
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.4rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                        {{ $school->users_count }} Pengguna Aplikasi
                    </p>

                    @if($school->stats)
                        <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
                            <span style="font-size: 0.7rem; background: rgba(99,102,241,0.08); color: #818cf8; padding: 3px 8px; border-radius: 6px; border: 1px solid rgba(99,102,241,0.15); font-weight: 600;">{{ $school->stats['total_ptk'] }} PTK</span>
                            <span style="font-size: 0.7rem; background: rgba(16,185,129,0.08); color: #10b981; padding: 3px 8px; border-radius: 6px; border: 1px solid rgba(16,185,129,0.15); font-weight: 600;">{{ $school->stats['total_rombel'] }} Rombel</span>
                            <span style="font-size: 0.7rem; background: rgba(245,158,11,0.08); color: #f59e0b; padding: 3px 8px; border-radius: 6px; border: 1px solid rgba(245,158,11,0.15); font-weight: 600;">{{ $school->stats['total_siswa'] }} Siswa</span>
                        </div>
                    @else
                        <div style="font-size: 0.7rem; color: #ef4444; background: rgba(239,68,68,0.05); padding: 5px 10px; border-radius: 6px; margin-bottom: 1rem; border: 1px dashed rgba(239,68,68,0.2);">
                            ⚠️ Data Dapodik belum disinkronkan
                        </div>
                    @endif
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        <div style="background: #f8fafc; padding: 0.75rem; border-radius: 8px; border: 1px solid #f1f5f9;">
                            <div style="font-size: 0.65rem; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.25rem;">Kode Registrasi</div>
                            <code style="font-size: 0.85rem; color: var(--text-primary); font-weight: 600;">{{ $school->registration_code }}</code>
                        </div>
                        <div style="background: #f8fafc; padding: 0.75rem; border-radius: 8px; border: 1px solid #f1f5f9;">
                            <div style="font-size: 0.65rem; color: #94a3b8; text-transform: uppercase; margin-bottom: 0.25rem;">Status</div>
                            <div style="font-size: 0.85rem; color: #10b981; font-weight: 600; display: flex; align-items: center; gap: 0.25rem;">
                                <span style="width: 6px; height: 6px; background: #10b981; border-radius: 50%;"></span>
                                Aktif
                            </div>
                        </div>
                    </div>
                </div>
                <div style="padding: 1rem 1.5rem; background: #fafafa; display: flex; justify-content: space-between; align-items: center;">
                    @if($school->npsn)
                        <a href="{{ route('super.schools.enter', ['npsn' => $school->npsn]) }}" style="text-decoration: none; color: white; background: var(--accent); padding: 0.5rem 1.25rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; flex: 1; text-align: center; transition: background 0.2s;">
                            Masuk Kelola Sekolah
                        </a>
                    @else
                        <div style="color: #ef4444; font-size: 0.75rem; font-weight: 600; text-align: center; flex: 1; background: #fee2e2; padding: 0.5rem; border-radius: 8px; border: 1px solid #fecaca;">
                            Data NPSN Tidak Valid
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; background: var(--card-bg); border: 2px dashed #e2e8f0; border-radius: var(--radius-lg); padding: 4rem 2rem; text-align: center;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" style="margin-bottom: 1rem;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                <h3 style="color: var(--text-primary); margin-bottom: 0.5rem;">Belum Ada Sekolah</h3>
                <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1.5rem;">Silakan tambahkan sekolah baru untuk mulai mengelola tenant.</p>
                <a href="{{ route('super.schools') }}" class="btn-primary" style="text-decoration: none; padding: 0.6rem 1.5rem;">Mulai Sekarang</a>
            </div>
        @endforelse
    </div>
@endsection
