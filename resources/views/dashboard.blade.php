@extends('layouts.app')

@section('title', 'Dashboard - e-Rapor SD')
@section('header_title', 'Dashboard')

@section('header_subtitle')
    @if($sekolah)
        {{ $sekolah['nama'] ?? 'Menunggu Data Sekolah' }} | NPSN: {{ $sekolah['npsn'] ?? '-' }}
    @else
        Sinkronisasi Gagal - Periksa Koneksi Dapodik Lokal
    @endif
@endsection

@section('content')
    <!-- Welcome Alert & Input Control -->
    <div class="animate-slide-up" style="background: var(--accent-gradient); color: white; padding: 1.5rem 2rem; border-radius: 16px; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 10px 20px rgba(59, 130, 246, 0.2); flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem;">Selamat Datang di e-Rapor SD</h2>
            <p style="opacity: 0.9;">Anda login sebagai <strong>{{ session('role') === 'admin' ? 'Administrator' : 'Guru' }}</strong>. Silakan gunakan menu di sebelah kiri untuk mengelola data.</p>
        </div>
        <div style="text-align: right; background: rgba(0,0,0,0.15); padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);">
            <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem; text-align: center;">Status Input Nilai Guru</div>
            <div style="display: flex; align-items: center; gap: 0.75rem; justify-content: center;">
                <span style="width: 10px; height: 10px; background: #10b981; border-radius: 50%; box-shadow: 0 0 8px #10b981; animation: pulse 2s infinite;"></span>
                <span style="font-weight: 600; font-size: 1.1rem; letter-spacing: 1px;">TERBUKA</span>
                @if(session('role') === 'admin')
                <a href="#" style="color: white; text-decoration: none; font-size: 0.75rem; background: rgba(255,255,255,0.2); padding: 0.25rem 0.75rem; border-radius: 6px; margin-left: 0.5rem; transition: background 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">Tutup</a>
                @endif
            </div>
        </div>
    </div>

    <!-- 8 Stats Overview -->
    <div class="stats-grid animate-slide-up delay-1" style="grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));">
        <!-- Widget 1: Siswa -->
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 14c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5z"></path><path d="M2 20.66C2 18.09 6.48 16 12 16s10 2.09 10 4.66V22H2v-1.34z"></path></svg>
            </div>
            <div>
                <div class="stat-label">Siswa Aktif</div>
                <div class="stat-value">{{ $totalSiswa }}</div>
            </div>
        </div>

        <!-- Widget 2: Rombel -->
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(236, 72, 153, 0.1); color: #ec4899;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
            <div>
                <div class="stat-label">Rombongan Belajar</div>
                <div class="stat-value">{{ $totalRombel }}</div>
            </div>
        </div>

        @if(session('role') === 'admin')
        <!-- Widget 3: Guru -->
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
            </div>
            <div>
                <div class="stat-label">Guru & Tenaga Kependidikan</div>
                <div class="stat-value">{{ $totalGuru }}</div>
            </div>
        </div>
        @endif

        <!-- Widget 4: Nilai -->
        <div class="stat-card" style="border: 1px solid rgba(16, 185, 129, 0.3);">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <div>
                <div class="stat-label">Nilai Akademik Terisi</div>
                <div class="stat-value" style="color: #10b981;">{{ $totalNilai }}</div>
            </div>
        </div>

        <!-- Widget 5: Proyek P5 -->
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(6, 182, 212, 0.1); color: #06b6d4;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon></svg>
            </div>
            <div>
                <div class="stat-label">Proyek P5</div>
                <div class="stat-value">{{ $totalProyek }}</div>
            </div>
        </div>

        <!-- Widget 6: Penilaian P5 -->
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div>
                <div class="stat-label">Penilaian P5 Terisi</div>
                <div class="stat-value">{{ $totalPenilaianP5 }}</div>
            </div>
        </div>
    </div>

    @if(session('role') === 'admin')
    <!-- Data Pengguna Table (Optional Preview) -->
    <div class="section-header animate-slide-up delay-2">
        <div>Daftar Pengguna Aktif (Preview)</div>
        <div class="badge-dapodik">LIVE DARI DAPODIK</div>
    </div>
    
    <div class="table-container animate-slide-up delay-2">
        <table>
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Username / Email</th>
                    <th>Peran</th>
                    <th>No HP</th>
                </tr>
            </thead>
            <tbody>
                @forelse(array_slice($pengguna, 0, 5) as $user)
                    <tr>
                        <td style="font-weight: 500;">{{ $user['nama'] }}</td>
                        <td style="color: var(--text-secondary);">{{ $user['username'] }}</td>
                        <td>
                            <span class="role-badge {{ str_contains(strtolower($user['peran_id_str'] ?? ''), 'operator') ? 'role-op' : 'role-guru' }}">
                                {{ $user['peran_id_str'] ?? 'Guru' }}
                            </span>
                        </td>
                        <td>{{ $user['no_hp'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-secondary);">Tidak ada data pengguna</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

    <!-- Data Kelas Table (Preview) -->
    <div class="section-header animate-slide-up delay-3">
        <div>Daftar Rombongan Belajar (Preview)</div>
    </div>
    
    <div class="table-container animate-slide-up delay-3">
        <table>
            <thead>
                <tr>
                    <th>Nama Kelas</th>
                    <th>Wali Kelas</th>
                    <th>Kurikulum</th>
                    <th>Ruang</th>
                </tr>
            </thead>
            <tbody>
                @forelse(array_slice($rombonganBelajar, 0, 5) as $rombel)
                    <tr>
                        <td style="font-weight: 500; font-size: 1.1rem; color: var(--accent);">{{ $rombel['nama'] ?? '-' }}</td>
                        <td>{{ $rombel['ptk_id_str'] ?? '-' }}</td>
                        <td style="color: var(--text-secondary);">{{ $rombel['kurikulum_id_str'] ?? '-' }}</td>
                        <td>{{ $rombel['id_ruang_str'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-secondary);">Tidak ada data rombongan belajar</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
