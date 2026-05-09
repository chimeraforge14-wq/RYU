<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <title>@yield('title', 'Dashboard e-Rapor SD')</title>
    @livewireStyles
    <style>
        {!! file_get_contents(resource_path('css/app.css')) !!}
    </style>
</head>
<body>

    <!-- Mobile Header -->
    <div class="mobile-header">
        <div class="brand" style="font-size: 1.25rem;">e-Rapor SD</div>
        <button class="menu-toggle" id="menuToggle">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        </button>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--accent)"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>
            e-Rapor SD
        </div>
        <ul class="nav-links">
            <li><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                Dashboard
            </a></li>
            <li><a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                Profile
            </a></li>
            @if(session('role') === 'admin' || session('role') === 'superadmin')
            <li><a href="{{ route('admin.log') }}" class="nav-link {{ request()->routeIs('admin.log') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20v-6M6 20V10M18 20V4"></path></svg>
                Log Statistik & Dashboard
            </a></li>
            @endif

            @if(session('role') === 'superadmin')
            <li><a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                Kelola Peserta Didik
            </a></li>
            @endif

            @if(session('role') === 'admin' || session('role') === 'superadmin')
            <li><a href="{{ route('sync') }}" class="nav-link {{ request()->routeIs('sync') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M2.13 15.57a10 10 0 1 0 14.3-11.4l-3.2 3.1"></path></svg>
                Ambil Data Dapodik
            </a></li>
            <li><a href="{{ route('settings') }}" class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                Identitas Sekolah
            </a></li>
            <li><a href="{{ route('pengguna') }}" class="nav-link {{ request()->routeIs('pengguna') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                Data Pengguna
            </a></li>
            @endif
            
            <li class="nav-item">
                <a href="#" class="nav-link submenu-toggle">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    Data Referensi
                    <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </a>
                <ul class="submenu">
                    @if(session('role') === 'admin' || session('role') === 'superadmin')
                    <li><a href="{{ route('referensi', 'sekolah') }}">Data Sekolah</a></li>
                    <li><a href="{{ route('guru') }}">Data Guru</a></li>
                    @endif
                    <li><a href="{{ route('siswa') }}">Data Siswa</a></li>
                    <li><a href="{{ route('referensi', 'kelas') }}">Data Kelas</a></li>
                    <li><a href="{{ route('referensi', 'mapel') }}">Data Mata Pelajaran</a></li>
                    @if(session('role') === 'admin' || session('role') === 'superadmin')
                    <li><a href="{{ route('subjects.index') }}" style="color: var(--accent);">Mapel Manual/Lokal</a></li>
                    @endif
                    <li><a href="{{ route('referensi', 'pembelajaran') }}">Data Pembelajaran</a></li>
                    <li><a href="{{ route('referensi', 'ekstrakurikuler') }}">Data Ekstrakurikuler</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link submenu-toggle">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon></svg>
                    Data Kokurikuler
                    <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </a>
                <ul class="submenu">
                    @if(session('role') === 'admin' || session('role') === 'superadmin')
                    <li><a href="{{ route('kokurikuler.groups') }}" style="color: var(--accent);">Kelompok & Koordinator</a></li>
                    <li><a href="{{ route('kokurikuler.perencanaan') }}">Perencanaan P5</a></li>
                    @endif
                    <li><a href="{{ route('kokurikuler.penilaian') }}">Penilaian P5</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link submenu-toggle">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    Status Penilaian
                    <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('status_penilaian', 'input') }}">Status Input Nilai</a></li>
                    <li><a href="{{ route('status_penilaian', 'kompetensi') }}">Pencapaian Kompetensi</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link submenu-toggle">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                    Perkembangan Nilai
                    <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('perkembangan', 'tabel') }}">Tabel Nilai</a></li>
                    <li><a href="{{ route('perkembangan', 'grafik') }}">Grafik Rapor</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link submenu-toggle {{ request()->routeIs('cetak*') || request()->routeIs('pelengkap_rapor') || request()->routeIs('nilai') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    Input & Cetak
                    <svg class="chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('tp.index') }}" style="color: var(--accent);">Input Tujuan Pembelajaran</a></li>
                    <li><a href="{{ route('tp.scoring') }}" style="color: var(--accent);">Input Nilai TP/CP</a></li>
                    <li><a href="{{ route('nilai') }}" class="{{ request()->routeIs('nilai') ? 'active' : '' }}">Input Nilai Rapor</a></li>
                    <li><a href="{{ route('pelengkap_rapor') }}" class="{{ request()->routeIs('pelengkap_rapor') ? 'active' : '' }}">Input Absensi & Catatan</a></li>
                    <li><hr style="border:0; border-top:1px solid rgba(255,255,255,0.05); margin: 0.5rem 0;"></li>
                    <li><a href="{{ route('cetak', 'leger') }}">Cetak Leger Rapor</a></li>
                    <li><a href="{{ route('cetak', 'nilai') }}">Cetak Rapor Siswa</a></li>
                </ul>
            </li>

            <li style="margin-top: 1rem;"><div style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 0.5rem; padding-left: 1rem; font-weight: 600; letter-spacing: 0.05em;">Utility</div></li>
            
            <li><a href="{{ route('backup') }}" class="nav-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                Backup & Kirim Data
            </a></li>

            @if(session('role') === 'admin' || session('role') === 'superadmin')
            <li><a href="{{ route('kirim_dapodik') }}" class="nav-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.92-12.28l5.08 5.08"></path></svg>
                Kirim Nilai Ke Dapodik
            </a></li>
            @endif

            <li><a href="{{ route('logout') }}" class="nav-link" style="color: #ef4444;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                Keluar
            </a></li>
        </ul>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">
        <header class="header animate-slide-up">
            <div>
                <h1>@yield('header_title', 'Overview')</h1>
                <p style="color: var(--text-secondary); margin-top: 0.5rem;">
                    @yield('header_subtitle', '')
                </p>
            </div>
            <div class="user-profile">
                <div class="avatar">{{ substr(session('nama', 'A'), 0, 1) }}</div>
                <div style="text-align: right;">
                    <div style="font-size: 0.875rem; font-weight: 600;">{{ session('nama', 'Administrator') }}</div>
                    <div style="font-size: 0.65rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">{{ session('role', 'admin') }}</div>
                </div>
            </div>
        </header>

        @if(session('info'))
            <div style="background: var(--accent-light); color: var(--accent); padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid rgba(225,29,72,0.1); display:flex; align-items:center; gap:0.75rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                {{ session('info') }}
            </div>
        @endif

        @yield('content')

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Submenu toggles
            const toggles = document.querySelectorAll('.submenu-toggle');
            toggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    document.querySelectorAll('.nav-item').forEach(item => {
                        if(item !== parent) item.classList.remove('open');
                    });
                    parent.classList.toggle('open');
                });
            });

            // Mobile Sidebar Toggle
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (menuToggle && sidebar && overlay) {
                menuToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('open');
                    overlay.classList.toggle('show');
                });

                overlay.addEventListener('click', () => {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('show');
                });

                // Close sidebar on link click (mobile)
                const navLinks = document.querySelectorAll('.nav-link:not(.submenu-toggle), .submenu a');
                navLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth <= 1024) {
                            sidebar.classList.remove('open');
                            overlay.classList.remove('show');
                        }
                    });
                });
            }
        });
    </script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((registration) => {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    })
                    .catch((err) => {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }
    </script>
    @livewireScripts
</body>
</html>
