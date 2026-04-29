@extends('layouts.app')

@section('title', 'Kelola Database - e-Rapor SD')
@section('header_title', 'Kelola Database')
@section('header_subtitle', 'Konfigurasi koneksi cloud dan sinkronisasi data database')

@section('content')
<div class="animate-slide-up">
    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid rgba(16, 185, 129, 0.2); display: flex; align-items: center; gap: 0.75rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid rgba(239, 68, 68, 0.2); display: flex; align-items: center; gap: 0.75rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            {{ session('error') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 400px; gap: 2rem; align-items: start;">
        
        <!-- Left: Connection Config -->
        <div class="stat-card" style="padding: 2.5rem; background: linear-gradient(145deg, rgba(30, 41, 59, 0.4), rgba(15, 23, 42, 0.6)); border: 1px solid rgba(255,255,255,0.05); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -100px; left: -100px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(96, 165, 250, 0.05) 0%, transparent 70%); pointer-events: none;"></div>
            
            <h3 style="margin-bottom: 2.5rem; font-weight: 800; font-size: 1.25rem; letter-spacing: -0.02em; color: #fff; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 40px; height: 40px; background: rgba(96, 165, 250, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #60a5fa; border: 1px solid rgba(96, 165, 250, 0.2);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                </div>
                Konfigurasi Cloud Database
            </h3>

            <form action="{{ route('database.update') }}" method="POST">
                @csrf
                <div style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Host / Server Endpoint</label>
                    <input type="text" name="db_host" value="{{ config('database.connections.pgsql.host') }}" class="form-control" style="background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.1); padding: 1rem; border-radius: 12px; color: #fff;" placeholder="database.server.com">
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label class="form-label" style="color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Nama Database</label>
                        <input type="text" name="db_database" value="{{ config('database.connections.pgsql.database') }}" class="form-control" style="background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.1); padding: 1rem; border-radius: 12px; color: #fff;">
                    </div>
                    <div>
                        <label class="form-label" style="color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Port</label>
                        <input type="text" name="db_port" value="{{ config('database.connections.pgsql.port') }}" class="form-control" style="background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.1); padding: 1rem; border-radius: 12px; color: #fff;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2.5rem;">
                    <div>
                        <label class="form-label" style="color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Username</label>
                        <input type="text" name="db_username" value="{{ config('database.connections.pgsql.username') }}" class="form-control" style="background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.1); padding: 1rem; border-radius: 12px; color: #fff;">
                    </div>
                    <div>
                        <label class="form-label" style="color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Password</label>
                        <input type="password" name="db_password" value="{{ config('database.connections.pgsql.password') }}" class="form-control" style="background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.1); padding: 1rem; border-radius: 12px; color: #fff;" placeholder="••••••••">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn-sync-premium" style="background: linear-gradient(135deg, #3b82f6, #2563eb); border: none; padding: 1rem 2.5rem; border-radius: 12px; color: white; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.75rem; box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Workflow Actions -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            
            <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; padding-left: 0.5rem;">Alur Sinkronisasi Pusat</div>

            <!-- Push Data -->
            <div class="stat-card" style="padding: 2rem; display: block; background: rgba(59, 130, 246, 0.05); border: 1px solid rgba(59, 130, 246, 0.1); position: relative;">
                <div style="position: absolute; top: 1.5rem; right: 1.5rem; font-size: 2rem; font-weight: 900; color: rgba(59, 130, 246, 0.1); pointer-events: none;">01</div>
                <div style="color: #60a5fa; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem; font-weight: 800;">
                    <div style="width: 32px; height: 32px; background: rgba(59, 130, 246, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    </div>
                    Kirim Data ke Cloud
                </div>
                <p style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 2rem; line-height: 1.6;">Gunakan setelah sinkron Dapodik selesai agar data rombel/siswa bisa diakses oleh Guru.</p>
                <form action="{{ route('database.push') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-sync-premium" style="width: 100%; background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2); font-size: 0.8rem;">PUSH DATA SEKARANG</button>
                </form>
            </div>

            <!-- Pull Data -->
            <div class="stat-card" style="padding: 2rem; display: block; background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.1); position: relative;">
                <div style="position: absolute; top: 1.5rem; right: 1.5rem; font-size: 2rem; font-weight: 900; color: rgba(16, 185, 129, 0.1); pointer-events: none;">02</div>
                <div style="color: #10b981; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem; font-weight: 800;">
                    <div style="width: 32px; height: 32px; background: rgba(16, 185, 129, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    </div>
                    Tarik Data Nilai Guru
                </div>
                <p style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 2rem; line-height: 1.6;">Ambil seluruh hasil penilaian yang sudah dikirim oleh Guru dari cloud ke aplikasi pusat ini.</p>
                <form action="{{ route('database.pull') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-sync-premium" style="width: 100%; background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); font-size: 0.8rem;">PULL DATA SEKARANG</button>
                </form>
            </div>

            <!-- Status -->
            <div class="stat-card" style="padding: 1.5rem; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                    <div style="color: #94a3b8; font-size: 0.75rem; font-weight: 600;">Status Server:</div>
                    <div id="connection-status" style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: #10b981; font-weight: 700;">
                        <span class="pulse-dot"></span> Online
                    </div>
                </div>
                <div style="margin-bottom: 1.5rem; font-size: 0.85rem; color: #fff; font-weight: 500;">
                    {{ config('database.connections.pgsql.host') }}
                </div>
                
                <button onclick="testConnection()" id="btn-test" class="btn-sync-premium" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; font-size: 0.75rem; height: 40px;">
                    CEK KONEKSI SEKARANG
                </button>
            </div>

        </div>

    </div>
</div>

<script>
    async function testConnection() {
        const btn = document.getElementById('btn-test');
        const status = document.getElementById('connection-status');
        const originalText = btn.innerText;
        
        btn.innerText = 'MENGECEK...';
        btn.disabled = true;
        btn.style.opacity = '0.5';

        try {
            const response = await fetch('{{ route("database.test") }}');
            const data = await response.json();
            
            if (data.success) {
                btn.style.background = 'rgba(16, 185, 129, 0.2)';
                btn.style.color = '#10b981';
                btn.innerText = 'KONEKSI SUKSES!';
                status.innerHTML = '<span class="pulse-dot"></span> Online';
                status.style.color = '#10b981';
            } else {
                btn.style.background = 'rgba(239, 68, 68, 0.2)';
                btn.style.color = '#ef4444';
                btn.innerText = 'KONEKSI GAGAL';
                status.innerHTML = '<span class="pulse-dot" style="background:#ef4444; box-shadow:none;"></span> Terputus';
                status.style.color = '#ef4444';
                alert(data.message);
            }
        } catch (error) {
            alert('Terjadi kesalahan jaringan.');
        } finally {
            setTimeout(() => {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.style.background = 'rgba(255,255,255,0.05)';
                btn.style.color = '#fff';
                btn.innerText = originalText;
            }, 3000);
        }
    }
</script>

<style>
    .pulse-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    .btn-sync-premium:hover {
        transform: translateY(-2px);
        filter: brightness(1.1);
    }
</style>
@endsection
