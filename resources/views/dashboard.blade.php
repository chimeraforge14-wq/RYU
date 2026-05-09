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
    {{-- Welcome Banner --}}
    <div class="animate-slide-up" style="background: var(--accent-gradient); color: white; padding: 1.5rem 2rem; border-radius: var(--radius-xl); margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 8px 32px rgba(225,29,72,0.2), inset 0 1px 0 rgba(255,255,255,0.1); flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 0.4rem; letter-spacing: -0.3px;">Selamat Datang di e-Rapor SD</h2>
            <p style="opacity: 0.85; font-size: 0.9rem;">Anda login sebagai <strong>{{ session('role') === 'superadmin' ? 'Super Administrator' : (session('role') === 'admin' ? 'Administrator' : 'Guru') }}</strong>. Silakan gunakan menu di sebelah kiri untuk mengelola data.</p>
        </div>
        <div style="text-align: center; background: rgba(0,0,0,0.2); padding: 0.85rem 1.5rem; border-radius: var(--radius-md); border: 1px solid rgba(255,255,255,0.1);">
            <div style="font-size: 0.75rem; opacity: 0.85; margin-bottom: 0.4rem;">Siswa Sudah Ada Nilai</div>
            <div style="font-size: 2rem; font-weight: 800; letter-spacing: -1px;">{{ $siswaLengkap }}</div>
            <div style="font-size: 0.7rem; opacity: 0.75;">dari {{ $totalSiswa }} siswa</div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="stats-grid animate-slide-up delay-1" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); margin-bottom: 2rem;">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div><div class="stat-label">Siswa Aktif</div><div class="stat-value">{{ $totalSiswa }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(236,72,153,0.1); color: #ec4899;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div><div class="stat-label">Rombongan Belajar</div><div class="stat-value">{{ $totalRombel }}</div></div>
        </div>
        @if(session('role') !== 'guru')
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(139,92,246,0.1); color: #8b5cf6;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            </div>
            <div><div class="stat-label">Guru & Tenaga Kependidikan</div><div class="stat-value">{{ $totalGuru }}</div></div>
        </div>
        @endif
        <div class="stat-card" style="border: 1px solid rgba(16,185,129,0.3);">
            <div class="stat-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <div><div class="stat-label">Nilai Akademik Terisi</div><div class="stat-value" style="color: #10b981;">{{ $totalNilai }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(6,182,212,0.1); color: #06b6d4;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>
            </div>
            <div><div class="stat-label">Proyek P5</div><div class="stat-value">{{ $totalProyek }}</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--accent-light); color: var(--accent);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div><div class="stat-label">Penilaian P5 Terisi</div><div class="stat-value">{{ $totalPenilaianP5 }}</div></div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="section-header animate-slide-up delay-2"><div>⚡ Aksi Cepat</div></div>
    <div class="animate-slide-up delay-2" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 0.85rem; margin-bottom: 2rem;">
        <a href="{{ route('nilai') }}" style="text-decoration:none; background:var(--card-bg); border:var(--glass-border); border-radius:var(--radius-md); padding:1.1rem 1.25rem; display:flex; align-items:center; gap:0.85rem; transition:all 0.2s; border-left:3px solid var(--accent);" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(225,29,72,0.1)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            <div><div style="font-weight:600;font-size:0.82rem;color:var(--text-primary)">Input Nilai</div><div style="font-size:0.7rem;color:var(--text-secondary)">Nilai sumatif siswa</div></div>
        </a>
        <a href="{{ route('cetak', 'nilai') }}" style="text-decoration:none; background:var(--card-bg); border:var(--glass-border); border-radius:var(--radius-md); padding:1.1rem 1.25rem; display:flex; align-items:center; gap:0.85rem; transition:all 0.2s; border-left:3px solid #10b981;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(16,185,129,0.15)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            <div><div style="font-weight:600;font-size:0.82rem;color:var(--text-primary)">Cetak Rapor</div><div style="font-size:0.7rem;color:var(--text-secondary)">PDF individual/massal</div></div>
        </a>
        <a href="{{ route('pelengkap_rapor') }}" style="text-decoration:none; background:var(--card-bg); border:var(--glass-border); border-radius:var(--radius-md); padding:1.1rem 1.25rem; display:flex; align-items:center; gap:0.85rem; transition:all 0.2s; border-left:3px solid #f59e0b;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(245,158,11,0.15)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/></svg>
            <div><div style="font-weight:600;font-size:0.82rem;color:var(--text-primary)">Pelengkap Rapor</div><div style="font-size:0.7rem;color:var(--text-secondary)">Absensi & catatan</div></div>
        </a>
        <a href="{{ route('kokurikuler.penilaian') }}" style="text-decoration:none; background:var(--card-bg); border:var(--glass-border); border-radius:var(--radius-md); padding:1.1rem 1.25rem; display:flex; align-items:center; gap:0.85rem; transition:all 0.2s; border-left:3px solid #06b6d4;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(6,182,212,0.15)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#06b6d4" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>
            <div><div style="font-weight:600;font-size:0.82rem;color:var(--text-primary)">Penilaian P5</div><div style="font-size:0.7rem;color:var(--text-secondary)">Profil pelajar Pancasila</div></div>
        </a>
        <a href="{{ route('status_penilaian', 'input') }}" style="text-decoration:none; background:var(--card-bg); border:var(--glass-border); border-radius:var(--radius-md); padding:1.1rem 1.25rem; display:flex; align-items:center; gap:0.85rem; transition:all 0.2s; border-left:3px solid #0891b2;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(8,145,178,0.1)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0891b2" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <div><div style="font-weight:600;font-size:0.82rem;color:var(--text-primary)">Status Penilaian</div><div style="font-size:0.7rem;color:var(--text-secondary)">Progress per kelas/mapel</div></div>
        </a>
        <a href="{{ route('cetak', 'leger') }}" style="text-decoration:none; background:var(--card-bg); border:var(--glass-border); border-radius:var(--radius-md); padding:1.1rem 1.25rem; display:flex; align-items:center; gap:0.85rem; transition:all 0.2s; border-left:3px solid #ec4899;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(236,72,153,0.15)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ec4899" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
            <div><div style="font-weight:600;font-size:0.82rem;color:var(--text-primary)">Cetak Leger</div><div style="font-size:0.7rem;color:var(--text-secondary)">Rekap nilai per kelas</div></div>
        </a>
    </div>

    {{-- Analytics Section --}}
    @if(count($rombelProgress) > 0)
    <div class="section-header animate-slide-up delay-3">
        <div>📊 Progress Pengisian Nilai per Rombongan Belajar</div>
    </div>

    <div class="animate-slide-up delay-3" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 2rem; align-items: start;">
        <div style="background:var(--card-bg); border:var(--glass-border); border-radius:var(--radius-lg); padding:1.5rem;">
            <div style="font-size:0.8rem; font-weight:600; color:var(--text-secondary); margin-bottom:1rem; text-transform:uppercase; letter-spacing:0.5px;">% Kelengkapan Nilai</div>
            <canvas id="progressChart" style="max-height:220px;"></canvas>
        </div>
        <div style="background:var(--card-bg); border:var(--glass-border); border-radius:var(--radius-lg); padding:1.5rem;">
            <div style="font-size:0.8rem; font-weight:600; color:var(--text-secondary); margin-bottom:1rem; text-transform:uppercase; letter-spacing:0.5px;">Rata-rata Nilai Akhir</div>
            <canvas id="avgChart" style="max-height:220px;"></canvas>
        </div>
    </div>

    <div class="animate-slide-up delay-3" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:1rem; margin-bottom:2rem;">
        @foreach($rombelProgress as $rp)
        <div style="background:var(--card-bg); border:var(--glass-border); border-radius:var(--radius-lg); padding:1.25rem;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                <div>
                    <div style="font-weight:700; font-size:1rem; color:var(--text-primary);">{{ $rp['nama'] }}</div>
                    <div style="font-size:0.75rem; color:var(--text-secondary); margin-top:0.2rem;">{{ $rp['jml_siswa'] }} siswa · {{ $rp['jml_mapel'] }} mapel</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:1.5rem; font-weight:800; color:{{ $rp['persen'] >= 80 ? '#10b981' : ($rp['persen'] >= 40 ? '#f59e0b' : '#ef4444') }};">{{ $rp['persen'] }}%</div>
                    @if($rp['avg_nilai'] > 0)
                    <div style="font-size:0.7rem; color:var(--text-muted);">rata-rata: <strong style="color:var(--text-secondary)">{{ $rp['avg_nilai'] }}</strong></div>
                    @endif
                </div>
            </div>
            <div style="background:rgba(0,0,0,0.05); border-radius:99px; height:8px; overflow:hidden;">
                <div style="height:100%; width:{{ $rp['persen'] }}%; border-radius:99px; background:{{ $rp['persen'] >= 80 ? 'linear-gradient(90deg,#10b981,#34d399)' : ($rp['persen'] >= 40 ? 'linear-gradient(90deg,#f59e0b,#fbbf24)' : 'linear-gradient(90deg,#ef4444,#f87171)') }}; transition:width 1s ease;"></div>
            </div>
            <div style="display:flex; justify-content:space-between; margin-top:0.65rem; font-size:0.7rem; color:var(--text-muted);">
                <span>{{ $rp['terisi'] }} nilai terisi</span>
                <span>target {{ $rp['target'] }} nilai</span>
            </div>
            @if($rp['persen'] < 100)
            <a href="{{ route('nilai') }}?rombongan_belajar_id={{ $rp['rombel_id'] }}" style="display:inline-block; margin-top:0.75rem; font-size:0.75rem; color:var(--accent); text-decoration:none; background:var(--accent-light); padding:0.3rem 0.75rem; border-radius:6px; border:1px solid rgba(225,29,72,0.1);">
                → Input Nilai Kelas Ini
            </a>
            @else
            <div style="margin-top:0.75rem; font-size:0.75rem; color:#34d399; background:rgba(16,185,129,0.1); padding:0.3rem 0.75rem; border-radius:6px; display:inline-block;">✓ Nilai Lengkap</div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    {{-- Tabel Pengguna (Admin) --}}
    @if(session('role') === 'admin' || session('role') === 'superadmin')
    <div class="section-header animate-slide-up delay-4">
        <div>Daftar Pengguna Aktif (Preview)</div>
        <div class="badge-dapodik">LIVE DARI DAPODIK</div>
    </div>
    <div class="table-container animate-slide-up delay-4">
        <table>
            <thead>
                <tr><th>Nama Lengkap</th><th>Username / Email</th><th>Peran</th><th>No HP</th></tr>
            </thead>
            <tbody>
                @forelse(array_slice($pengguna, 0, 5) as $user)
                    <tr>
                        <td style="font-weight:500;">{{ $user['nama'] }}</td>
                        <td style="color:var(--text-secondary)">{{ $user['username'] }}</td>
                        <td><span class="role-badge {{ str_contains(strtolower($user['peran_id_str'] ?? ''), 'operator') ? 'role-op' : 'role-guru' }}">{{ $user['peran_id_str'] ?? 'Guru' }}</span></td>
                        <td>{{ $user['no_hp'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center; color:var(--text-secondary)">Tidak ada data pengguna</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        const labels   = {!! $chartLabels !!};
        const progress = {!! $chartProgress !!};
        const avgNilai = {!! $chartAvgNilai !!};

        const baseOpts = {
            responsive: true, maintainAspectRatio: true,
            plugins: { legend: { display: false }, tooltip: { backgroundColor: '#ffffff', titleColor: '#0f172a', bodyColor: '#475569', borderColor: 'rgba(0,0,0,0.1)', borderWidth: 1 } },
            scales: {
                x: { ticks: { color: '#64748b', font: { size: 11 } }, grid: { color: 'rgba(0,0,0,0.04)' } },
                y: { ticks: { color: '#64748b', font: { size: 11 } }, grid: { color: 'rgba(0,0,0,0.04)' } }
            }
        };

        const ctx1 = document.getElementById('progressChart');
        if (ctx1 && labels.length > 0) {
            new Chart(ctx1, {
                type: 'bar',
                data: { labels, datasets: [{ data: progress, backgroundColor: progress.map(v => v >= 80 ? 'rgba(16,185,129,0.7)' : v >= 40 ? 'rgba(245,158,11,0.7)' : 'rgba(239,68,68,0.7)'), borderRadius: 6, borderSkipped: false }] },
                options: { ...baseOpts, scales: { ...baseOpts.scales, y: { ...baseOpts.scales.y, max: 100, ticks: { ...baseOpts.scales.y.ticks, callback: v => v + '%' } } } }
            });
        }
        const ctx2 = document.getElementById('avgChart');
        if (ctx2 && labels.length > 0) {
            new Chart(ctx2, {
                type: 'bar',
                data: { labels, datasets: [{ data: avgNilai, backgroundColor: avgNilai.map(v => v >= 75 ? 'rgba(225,29,72,0.7)' : v >= 60 ? 'rgba(225,29,72,0.5)' : 'rgba(225,29,72,0.3)'), borderRadius: 6, borderSkipped: false }] },
                options: { ...baseOpts, scales: { ...baseOpts.scales, y: { ...baseOpts.scales.y, min: 0, max: 100 } } }
            });
        }
    </script>
@endsection
