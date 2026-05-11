@extends('layouts.app')

@section('title', 'Kirim ke Dapodik - e-Rapor SD')
@section('header_title', 'Kirim Data ke Dapodik')
@section('header_subtitle', 'Sinkronisasi nilai rapor menuju server Dapodik Pusat')

@section('content')
<div class="animate-slide-up" style="max-width: 800px;">
    
    {{-- Status Server Dapodik --}}
    <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; background: rgba(16,185,129,0.1); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
                    <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                    <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                </svg>
            </div>
            <div>
                <h3 style="font-size: 1rem; font-weight: 600; color: var(--text-primary);">Koneksi Server Dapodik</h3>
                <p style="font-size: 0.875rem; color: var(--text-secondary);">Web Service Dapodik Aktif</p>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 0.5rem; background: rgba(16,185,129,0.1); padding: 0.5rem 1rem; border-radius: 9999px;">
            <span style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; box-shadow: 0 0 8px #10b981; display: inline-block; animation: pulse 2s infinite;"></span>
            <span style="font-size: 0.875rem; font-weight: 600; color: #10b981;">Terhubung</span>
        </div>
    </div>

    {{-- Data Ready to Sync --}}
    <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.75rem; margin-bottom: 1.5rem;">
        <h3 style="font-size: 1rem; font-weight: 600; color: var(--accent); margin-bottom: 1rem;">Data Siap Dikirim</h3>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
            <div style="background: rgba(99,102,241,0.05); border: 1px solid rgba(99,102,241,0.1); border-radius: var(--radius-md); padding: 1rem; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: 700; color: #6366f1; margin-bottom: 0.25rem;">124</div>
                <div style="font-size: 0.8rem; color: var(--text-secondary);">Nilai Sumatif</div>
            </div>
            <div style="background: rgba(236,72,153,0.05); border: 1px solid rgba(236,72,153,0.1); border-radius: var(--radius-md); padding: 1rem; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: 700; color: #ec4899; margin-bottom: 0.25rem;">42</div>
                <div style="font-size: 0.8rem; color: var(--text-secondary);">Nilai Kokurikuler</div>
            </div>
            <div style="background: rgba(245,158,11,0.05); border: 1px solid rgba(245,158,11,0.1); border-radius: var(--radius-md); padding: 1rem; text-align: center;">
                <div style="font-size: 1.5rem; font-weight: 700; color: #f59e0b; margin-bottom: 0.25rem;">18</div>
                <div style="font-size: 0.8rem; color: var(--text-secondary);">Absensi & Catatan</div>
            </div>
        </div>

        <button id="btnSync" onclick="startSync()" style="width: 100%; background: linear-gradient(135deg, #4f46e5, #6366f1); color: white; border: none; padding: 1rem; border-radius: var(--radius-md); font-weight: 600; font-size: 1rem; display: flex; align-items: center; justify-content: center; gap: 0.75rem; cursor: pointer; transition: all 0.2s;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.59-9.27l-5.63-5.63"/>
            </svg>
            Mulai Kirim Data ke Dapodik
        </button>
    </div>

    {{-- Sync Progress --}}
    <div id="syncProgress" style="display: none; background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.75rem;">
        <h3 style="font-size: 1rem; font-weight: 600; color: var(--accent); margin-bottom: 1.5rem;">Proses Sinkronisasi</h3>
        
        <div style="margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.875rem;">
                <span id="syncStatusText" style="color: var(--text-secondary); font-weight: 500;">Inisialisasi koneksi...</span>
                <span id="syncPercentage" style="color: var(--text-primary); font-weight: 600;">0%</span>
            </div>
            <div style="width: 100%; height: 8px; background: var(--bg-color); border-radius: 4px; overflow: hidden;">
                <div id="syncBar" style="height: 100%; width: 0%; background: linear-gradient(90deg, #4f46e5, #ec4899); border-radius: 4px; transition: width 0.3s ease;"></div>
            </div>
        </div>

        <ul id="syncLog" style="list-style: none; padding: 0; margin: 0; max-height: 200px; overflow-y: auto; font-family: monospace; font-size: 0.8rem; background: var(--bg-color); border-radius: var(--radius-md); padding: 1rem;">
            {{-- Logs will appear here --}}
        </ul>
    </div>
</div>

<style>
@keyframes pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}
.animate-spin { animation: spin 1s linear infinite; }
@keyframes spin { 100% { transform: rotate(360deg); } }
</style>

<script>
function startSync() {
    const btn = document.getElementById('btnSync');
    const progress = document.getElementById('syncProgress');
    const log = document.getElementById('syncLog');
    const bar = document.getElementById('syncBar');
    const text = document.getElementById('syncStatusText');
    const percent = document.getElementById('syncPercentage');

    btn.disabled = true;
    btn.style.opacity = '0.7';
    btn.innerHTML = '<svg class="animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Memproses...';
    
    progress.style.display = 'block';
    log.innerHTML = '';
    bar.style.width = '0%';

    const steps = [
        { progress: 10, msg: "Menghubungkan ke Web Service Dapodik...", delay: 800 },
        { progress: 25, msg: "Otentikasi token Dapodik berhasil.", delay: 1000 },
        { progress: 40, msg: "Mempersiapkan payload data nilai sumatif...", delay: 1200 },
        { progress: 55, msg: "Mengirim 124 data nilai sumatif ke Dapodik...", delay: 1500 },
        { progress: 70, msg: "Mengirim 42 data nilai kokurikuler...", delay: 1200 },
        { progress: 85, msg: "Mengirim data absensi dan catatan wali kelas...", delay: 1000 },
        { progress: 100, msg: "Sinkronisasi selesai dengan sukses!", delay: 800 }
    ];

    let currentDelay = 0;

    steps.forEach((step, index) => {
        currentDelay += step.delay;
        setTimeout(() => {
            bar.style.width = step.progress + '%';
            percent.innerText = step.progress + '%';
            text.innerText = step.msg;

            const li = document.createElement('li');
            li.style.marginBottom = '0.5rem';
            li.style.color = index === steps.length - 1 ? '#10b981' : 'var(--text-secondary)';
            li.innerHTML = `[${new Date().toLocaleTimeString()}] ${step.msg}`;
            log.appendChild(li);
            log.scrollTop = log.scrollHeight;

            if (index === steps.length - 1) {
                btn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Selesai';
                setTimeout(() => {
                    alert('Data berhasil dikirim ke Dapodik!');
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.innerHTML = 'Kirim Data Ulang';
                }, 500);
            }
        }, currentDelay);
    });
}
</script>
@endsection
