@extends('layouts.app')

@section('title', 'Log Statistik - e-Rapor SD')
@section('header_title', 'Log Statistik & Dashboard')
@section('header_subtitle', 'Ringkasan data pengambilan dari Dapodik')

@section('content')
    <!-- Stats Row -->
    <div class="stats-grid animate-slide-up">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(99,102,241,0.12); color: #818cf8;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
            </div>
            <div>
                <div class="stat-label">Total PTK</div>
                <div class="stat-value">{{ $stats['total_ptk'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(16,185,129,0.12); color: #34d399;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
            <div>
                <div class="stat-label">Total Rombel</div>
                <div class="stat-value">{{ $stats['total_rombel'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245,158,11,0.12); color: #fbbf24;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 14c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5z"></path><path d="M2 20.66C2 18.09 6.48 16 12 16s10 2.09 10 4.66V22H2v-1.34z"></path></svg>
            </div>
            <div>
                <div class="stat-label">Total Siswa</div>
                <div class="stat-value">{{ $stats['total_siswa'] }}</div>
            </div>
        </div>
    </div>

    <!-- Profil Sekolah -->
    <div class="section-header animate-slide-up delay-1">
        <div>Profil Sekolah</div>
        <a href="{{ route('settings') }}" style="color: var(--accent); text-decoration: none; font-size: 0.85rem; font-weight: 500;">Edit Profil →</a>
    </div>
    <div class="table-container animate-slide-up delay-1" style="margin-bottom: 2rem;">
        <table>
            <tbody>
                <tr><td style="color: var(--text-secondary); width: 180px;">Nama Sekolah</td><td style="font-weight: 600;">{{ $sekolah['nama'] ?? '-' }}</td></tr>
                <tr><td style="color: var(--text-secondary);">NPSN</td><td>{{ $sekolah['npsn'] ?? '-' }}</td></tr>
                <tr><td style="color: var(--text-secondary);">Bentuk Pendidikan</td><td>{{ $sekolah['bentuk_pendidikan_id_str'] ?? $sekolah['bentuk_pendidikan'] ?? '-' }}</td></tr>
                <tr><td style="color: var(--text-secondary);">Status Sekolah</td><td>{{ $sekolah['status_sekolah_id_str'] ?? $sekolah['status_sekolah'] ?? '-' }}</td></tr>
                <tr><td style="color: var(--text-secondary);">Alamat</td><td>{{ $sekolah['alamat_jalan'] ?? '-' }}, {{ $sekolah['desa_kelurahan'] ?? '' }}</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Daftar Wali Kelas -->
    <div class="section-header animate-slide-up delay-2">
        <div>Daftar Wali Kelas</div>
        <div class="badge-dapodik">DARI DAPODIK</div>
    </div>
    <div class="table-container animate-slide-up delay-2">
        <table>
            <thead>
                <tr>
                    <th>Nama Guru</th>
                    <th>Jabatan</th>
                    <th>NUPTK / NIK</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ptks as $ptk)
                <tr>
                    <td style="font-weight: 500;">{{ $ptk['nama'] }}</td>
                    <td style="color: var(--text-secondary);">{{ $ptk['jenis_ptk_id_str'] ?? 'Guru' }}</td>
                    <td>{{ $ptk['nuptk'] ?? $ptk['nik'] ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align: center; color: var(--text-secondary);">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Daftar Rombel -->
    <div class="section-header animate-slide-up delay-3">
        <div>Daftar Rombongan Belajar</div>
    </div>
    <div class="table-container animate-slide-up delay-3">
        <table>
            <thead>
                <tr>
                    <th>Nama Rombel</th>
                    <th>Wali Kelas</th>
                    <th>Jumlah Siswa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rombels as $rombel)
                <tr>
                    <td style="font-weight: 500; color: var(--accent);">{{ $rombel['nama'] }}</td>
                    <td>{{ $rombel['ptk_id_str'] ?? '-' }}</td>
                    <td>
                        <span style="background: var(--accent-light); color: #818cf8; padding: 0.2rem 0.7rem; border-radius: 99px; font-size: 0.8rem; font-weight: 600;">
                            {{ count($rombel['anggota_rombel'] ?? []) }} siswa
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align: center; color: var(--text-secondary);">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
