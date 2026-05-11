@extends('layouts.app')

@section('title', 'Sinkronisasi Dapodik - e-Rapor SD')
@section('header_title', 'Tarik Data Dapodik')
@section('header_subtitle', 'Proses sinkronisasi data master dari Dapodik Web Service secara manual')

@section('content')
    <div class="stats-grid animate-slide-up">
        <div class="stat-card" style="grid-column: 1 / -1; display: flex; flex-direction: row; justify-content: space-between; align-items: center; padding: 2rem;">
            <div>
                <h2 style="font-weight: 600; margin-bottom: 0.5rem; font-size: 1.5rem;">Status Sinkronisasi</h2>
                <p style="color: var(--text-secondary);">
                    Terakhir disinkronkan: <strong style="color: var(--text-primary);">{{ $lastSync ?? 'Belum pernah disinkronisasi' }}</strong>
                </p>
                @if(session('success'))
                    <div style="margin-top: 1rem; color: #10b981; font-weight: 500; display:flex; align-items:center; gap:0.5rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div style="margin-top: 1rem; color: #ef4444; font-weight: 500;">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
            
            <form action="{{ route('sync.process') }}" method="POST" id="syncForm" style="display: flex; flex-direction: column; gap: 1rem; align-items: flex-start; min-width: 320px; background: #f8fafc; padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(0,0,0,0.05);">
                @csrf
                <div style="width: 100%;">
                    <label for="dapodik_url" style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-align: left;">URL Dapodik</label>
                    <input type="url" id="dapodik_url" name="dapodik_url" value="{{ $lastConfig['url'] }}" placeholder="Contoh: http://localhost:5774" required style="width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid rgba(0,0,0,0.1); background: white; color: var(--text-primary); outline: none; font-family: monospace; font-size: 0.9rem;">
                </div>
                
                <div style="width: 100%;">
                    <label for="token" style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-align: left;">Token / Key</label>
                    <input type="text" id="token" name="token" value="{{ $lastConfig['token'] }}" placeholder="Masukkan Token / Key Dapodik" required style="width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid rgba(0,0,0,0.1); background: white; color: var(--text-primary); outline: none; font-family: monospace; font-size: 0.9rem;">
                </div>

                <div style="width: 100%;">
                    <label for="npsn" style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-align: left;">NPSN</label>
                    <input type="text" id="npsn" name="npsn" value="{{ $lastConfig['npsn'] }}" placeholder="Masukkan NPSN" required style="width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid rgba(0,0,0,0.1); background: white; color: var(--text-primary); outline: none; font-family: monospace; font-size: 0.9rem;">
                </div>

                <div style="width: 100%;">
                    <label for="registration_code" style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-align: left;">Kode Registrasi</label>
                    <input type="text" id="registration_code" name="registration_code" value="{{ $lastConfig['registration_code'] ?? '' }}" placeholder="Masukkan Kode Registrasi Dapodik" required style="width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid rgba(0,0,0,0.1); background: white; color: var(--text-primary); outline: none; font-family: monospace; font-size: 0.9rem;">
                </div>

                <div style="width: 100%;">
                    <label for="semester" style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-align: left;">Semester</label>
                    <select id="semester" name="semester" required style="width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid rgba(0,0,0,0.1); background: white; color: var(--text-primary); outline: none; font-size: 0.9rem; cursor: pointer;">
                        <option value="">Pilih Semester</option>
                        <option value="20251" {{ $lastConfig['semester'] == '20251' ? 'selected' : '' }}>2025/2026 Ganjil (20251)</option>
                        <option value="20252" {{ $lastConfig['semester'] == '20252' ? 'selected' : '' }}>2025/2026 Genap (20252)</option>
                        <option value="20241" {{ $lastConfig['semester'] == '20241' ? 'selected' : '' }}>2024/2025 Ganjil (20241)</option>
                        <option value="20242" {{ $lastConfig['semester'] == '20242' ? 'selected' : '' }}>2024/2025 Genap (20242)</option>
                    </select>
                </div>

                <button type="submit" class="btn-sync" id="btnSubmitSync" style="width: 100%; justify-content: center; margin-top: 1rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M2.13 15.57a10 10 0 1 0 14.3-11.4l-3.2 3.1"></path></svg>
                    Mulai Sinkronisasi
                </button>
            </form>

            <script>
                document.getElementById('syncForm').addEventListener('submit', function(e) {
                    const btn = document.getElementById('btnSubmitSync');
                    btn.innerHTML = '<svg class="animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:10px"><path d="M21 12a9 9 0 1 1-6.219-8.56"></path></svg> Sedang Tarik Data...';
                    btn.style.opacity = '0.8';
                    btn.style.pointerEvents = 'none';
                });
            </script>

            <style>
                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
                .animate-spin {
                    animation: spin 1s linear infinite;
                }
            </style>
        </div>
    </div>
    
    <style>
        .btn-sync {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
            background: var(--accent-gradient);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .btn-sync:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }
        .btn-sync:active {
            transform: scale(0.98);
        }
    </style>

    <div class="stat-card animate-slide-up delay-1" style="padding: 2rem;">
        <h3 style="margin-bottom: 1rem; font-weight: 600;">Apa yang disinkronisasi?</h3>
        <ul style="color: var(--text-secondary); line-height: 1.8; margin-left: 1.5rem;">
            <li><strong style="color: var(--text-primary); font-weight: 700;">Profil Sekolah:</strong> Menarik data identitas sekolah, NPSN, dan alamat terbaru.</li>
            <li><strong style="color: var(--text-primary); font-weight: 700;">Data PTK & Operator:</strong> Menarik daftar pengguna aktif beserta akunnya.</li>
            <li><strong style="color: var(--text-primary); font-weight: 700;">Rombongan Belajar:</strong> Menarik seluruh data kelas aktif dan wali kelas yang terdaftar di semester ini.</li>
        </ul>
        <p style="margin-top: 1.5rem; color: #fbbf24; font-size: 0.9rem;">
            ⚠️ Pastikan layanan <strong>Dapodik Lokal (Port 5774)</strong> dalam keadaan berjalan sebelum Anda melakukan sinkronisasi ini.
        </p>
    </div>
@endsection
