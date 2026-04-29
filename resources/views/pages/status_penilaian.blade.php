@extends('layouts.app')

@section('title', $title . ' - e-Rapor SD')
@section('header_title', $title)
@section('header_subtitle', 'Pantau progres pengisian nilai akademik per kelas')

@section('content')
<div class="stat-card animate-slide-up" style="margin-bottom: 2rem; padding: 1.25rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
    <div style="position: relative; flex: 1; max-width: 400px;">
        <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <input type="text" id="statusSearch" placeholder="Cari kelas atau mata pelajaran..." style="width: 100%; padding: 0.75rem 0.75rem 0.75rem 40px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; outline: none;">
    </div>
    <div style="font-size: 0.875rem; color: var(--text-secondary);">
        Total: <span style="color: white; font-weight: 600;">{{ count($statusData) }}</span> Mapel terdaftar
    </div>
</div>

<div class="table-container animate-slide-up delay-1">
    <div style="overflow-x: auto;">
        <table id="statusTable">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Rombongan Belajar</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru Pengajar</th>
                    <th style="text-align: center;">Progres</th>
                    <th style="text-align: center; width: 150px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($statusData as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="font-weight: 600;">{{ $item['rombel'] }}</td>
                        <td>{{ $item['mapel'] }}</td>
                        <td style="font-size: 0.85rem; color: var(--text-secondary);">{{ $item['guru'] }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="flex: 1; height: 8px; background: rgba(255,255,255,0.05); border-radius: 4px; overflow: hidden;">
                                    <div style="width: {{ $item['persen'] }}%; height: 100%; background: {{ $item['persen'] == 100 ? '#10b981' : 'var(--accent)' }}; transition: width 0.5s ease;"></div>
                                </div>
                                <span style="font-size: 0.75rem; width: 35px; text-align: right;">{{ $item['persen'] }}%</span>
                            </div>
                            <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 4px;">
                                {{ $item['terisi'] }} dari {{ $item['jml_siswa'] }} Siswa terinput
                            </div>
                        </td>
                        <td style="text-align: center;">
                            @if($item['persen'] == 100)
                                <span style="padding: 0.25rem 0.75rem; background: rgba(16, 185, 129, 0.1); color: #10b981; border-radius: 20px; font-size: 0.75rem; font-weight: 600; border: 1px solid rgba(16, 185, 129, 0.2);">SELESAI</span>
                            @elseif($item['persen'] > 0)
                                <span style="padding: 0.25rem 0.75rem; background: rgba(245, 158, 11, 0.1); color: #f59e0b; border-radius: 20px; font-size: 0.75rem; font-weight: 600; border: 1px solid rgba(245, 158, 11, 0.2);">PROSES</span>
                            @else
                                <span style="padding: 0.25rem 0.75rem; background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 20px; font-size: 0.75rem; font-weight: 600; border: 1px solid rgba(239, 68, 68, 0.2);">BELUM ISI</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 4rem; color: var(--text-secondary);">
                            Data rombel atau mata pelajaran tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('statusSearch').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#statusTable tbody tr');
        
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
@endsection
