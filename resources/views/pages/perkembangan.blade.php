@extends('layouts.app')

@section('title', $title . ' - e-Rapor SD')
@section('header_title', $title)
@section('header_subtitle', 'Analisis capaian nilai siswa per kelas')

@section('content')
@if(count($rombels) > 1 || session('role') === 'admin')
<div class="stat-card animate-slide-up" style="margin-bottom: 2rem; padding: 1.25rem; display: flex; align-items: flex-end; gap: 1rem;">
    <div style="flex: 1;">
        <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Pilih Rombongan Belajar</label>
        <select id="rombelSelect" class="form-control" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 0.75rem; border-radius: 8px; width: 100%;">
            <option value="">-- Pilih Kelas --</option>
            @foreach($rombels as $r)
                <option value="{{ $r['rombongan_belajar_id'] }}" {{ $rombelId == $r['rombongan_belajar_id'] ? 'selected' : '' }}>{{ $r['nama'] }}</option>
            @endforeach
        </select>
    </div>
    <button onclick="changeRombel()" class="btn-sync" style="padding: 0.75rem 1.5rem; border-radius: 8px; background: var(--accent); color: white; border: none; cursor: pointer;">
        Tampilkan Data
    </button>
</div>
@else
<div style="display: none;">
    <select id="rombelSelect">
        @foreach($rombels as $r)
            <option value="{{ $r['rombongan_belajar_id'] }}" selected>{{ $r['nama'] }}</option>
        @endforeach
    </select>
</div>
<div class="animate-slide-up" style="margin-bottom: 1.5rem; padding: 0.75rem 1.25rem; background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 12px; display: inline-flex; align-items: center; gap: 0.75rem;">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
    <span style="color: #60a5fa; font-weight: 600;">Analisis Nilai Kelas {{ $rombels[0]['nama'] ?? '-' }}</span>
</div>
@endif

@if($rombelId)
    @if($type == 'tabel')
        <div class="table-container animate-slide-up delay-1" id="perkembanganTableContainer">
            <table draggable="false" style="width: 100%; border-collapse: separate; border-spacing: 0; min-width: 1200px; user-select: none;">
                <thead>
                    <tr>
                        <th class="sticky-col" style="width: 50px; text-align: center; border-right: 1px solid rgba(255,255,255,0.05);">No</th>
                        <th class="sticky-col-2" style="width: 250px; white-space: nowrap; border-right: 1px solid rgba(255,255,255,0.1);">Nama Siswa</th>
                        @foreach($data['subjects'] as $id => $name)
                            <th style="text-align: center; font-size: 0.75rem; white-space: nowrap; min-width: 120px; border-right: 1px solid rgba(255,255,255,0.05);">{{ $name }}</th>
                        @endforeach
                        <th style="text-align: center; background: rgba(59, 130, 246, 0.15); color: #60a5fa; white-space: nowrap; border-left: 1px solid rgba(59, 130, 246, 0.3);">RATA-RATA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['students'] as $index => $student)
                        @php 
                            $pdId = $student['peserta_didik_id'];
                            $studentGrades = $data['grades']->get($pdId) ? $data['grades']->get($pdId)->keyBy('mata_pelajaran_id') : collect();
                            $total = 0;
                            $count = 0;
                        @endphp
                        <tr>
                            <td class="sticky-col" style="text-align: center; border-right: 1px solid rgba(255,255,255,0.05);">{{ $index + 1 }}</td>
                            <td class="sticky-col-2" style="font-weight: 500; border-right: 1px solid rgba(255,255,255,0.1);">{{ $student['nama'] }}</td>
                            @foreach($data['subjects'] as $subId => $subName)
                                @php 
                                    $score = $studentGrades->get($subId)->nilai_akhir ?? null;
                                    if($score) { $total += $score; $count++; }
                                @endphp
                                <td style="text-align: center; border-right: 1px solid rgba(255,255,255,0.05); {{ $score && $score < 70 ? 'color: #ef4444;' : '' }}">
                                    {{ $score ?: '-' }}
                                </td>
                            @endforeach
                            <td style="text-align: center; font-weight: 700; background: rgba(59, 130, 246, 0.05); color: #60a5fa; border-left: 1px solid rgba(59, 130, 246, 0.1);">
                                {{ $count > 0 ? round($total / $count, 1) : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <!-- Graphic View -->
        <div class="stats-grid animate-slide-up delay-1">
            @foreach($data['students'] as $student)
                @php 
                    $pdId = $student['peserta_didik_id'];
                    $studentGrades = $data['grades']->get($pdId) ? $data['grades']->get($pdId)->keyBy('mata_pelajaran_id') : collect();
                    $avg = 0; $t = 0; $c = 0;
                    foreach($data['subjects'] as $sid => $sn) {
                        $s = $studentGrades->get($sid)->nilai_akhir ?? 0;
                        if($s) { $t += $s; $c++; }
                    }
                    $avg = $c > 0 ? round($t / $c) : 0;
                @endphp
                <div class="stat-card" style="display: block; padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div>
                            <div style="font-weight: 600; font-size: 1rem;">{{ $student['nama'] }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary);">NISN: {{ $student['nisn'] }}</div>
                        </div>
                        <div style="font-size: 1.5rem; font-weight: 800; color: var(--accent);">{{ $avg }}</div>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @foreach($data['subjects'] as $subId => $subName)
                            @php $s = $studentGrades->get($subId)->nilai_akhir ?? 0; @endphp
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="font-size: 0.65rem; width: 80px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--text-secondary);" title="{{ $subName }}">{{ $subName }}</div>
                                <div style="flex: 1; height: 6px; background: rgba(255,255,255,0.05); border-radius: 3px; overflow: hidden;">
                                    <div style="width: {{ $s }}%; height: 100%; background: {{ $s >= 70 ? 'var(--accent)' : '#ef4444' }};"></div>
                                </div>
                                <div style="font-size: 0.65rem; width: 20px; text-align: right;">{{ $s ?: '0' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@else
    <div style="text-align: center; padding: 5rem; color: var(--text-secondary);" class="animate-slide-up">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 1rem; opacity: 0.3;"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
        <p>Silakan pilih Rombongan Belajar (Kelas) untuk melihat perkembangan nilai.</p>
    </div>
@endif

<script>
    function changeRombel() {
        const id = document.getElementById('rombelSelect').value;
        if(!id) return;
        window.location.href = `?rombongan_belajar_id=${id}`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('perkembanganTableContainer');
        if (container) {
            // Scroll shadow listener
            container.addEventListener('scroll', function() {
                if (this.scrollLeft > 0) {
                    this.classList.add('scrolled-left');
                } else {
                    this.classList.remove('scrolled-left');
                }
            });

            // Improved Grab to scroll logic
            let isDown = false;
            let startX;
            let scrollLeft;

            container.addEventListener('mousedown', (e) => {
                isDown = true;
                container.style.cursor = 'grabbing';
                startX = e.pageX - container.offsetLeft;
                scrollLeft = container.scrollLeft;
            });

            window.addEventListener('mouseup', () => {
                isDown = false;
                if(container) container.style.cursor = 'grab';
            });

            window.addEventListener('mousemove', (e) => {
                if (!isDown || !container) return;
                e.preventDefault();
                const x = e.pageX - container.offsetLeft;
                const walk = (x - startX) * 2.5; // Increased speed factor
                container.scrollLeft = scrollLeft - walk;
            });
        }
    });
</script>
@endsection
