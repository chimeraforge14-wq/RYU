@extends('layouts.app')
@section('title', 'Perencanaan P5 - e-Rapor SD')
@section('header_title', 'Perencanaan P5 (Projek Penguatan Profil Pelajar Pancasila)')

@section('content')
<div class="animate-slide-up">
    @if(session('success'))
        <div style="background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid rgba(34, 197, 94, 0.2);">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem;">
        
        <!-- Form Tambah Proyek -->
        <div style="background: var(--card-bg); border: var(--glass-border); border-radius: 16px; padding: 1.5rem; backdrop-filter: blur(10px);">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; color: var(--text-primary); border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 0.75rem;">
                Buat Proyek P5 Baru
            </h3>
            
            <form action="{{ route('kokurikuler.perencanaan.store') }}" method="POST">
                @csrf
                
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Semester</label>
                    <input type="text" name="semester" value="20251" required style="width: 100%; padding: 0.75rem; background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: var(--text-primary);">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Tema P5</label>
                    <select name="tema_id" required style="width: 100%; padding: 0.75rem; background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: var(--text-primary);">
                        <option value="">Pilih Tema</option>
                        @foreach($temas as $tema)
                            <option value="{{ $tema->id }}">{{ $tema->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Nama Proyek</label>
                    <input type="text" name="nama_proyek" required placeholder="Contoh: Membuat Kompos dari Daun" style="width: 100%; padding: 0.75rem; background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: var(--text-primary);">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" style="width: 100%; padding: 0.75rem; background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: var(--text-primary);"></textarea>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.75rem; font-weight: 600;">ASSIGN KE KELAS (ROMBEL)</label>
                    <div style="max-height: 200px; overflow-y: auto; background: rgba(0,0,0,0.2); padding: 0.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                        @foreach($rombels as $rombel)
                            <label style="display: flex; align-items: center; padding: 0.5rem; background: rgba(255,255,255,0.03); border-radius: 8px; cursor: pointer; transition: background 0.2s;">
                                <input type="checkbox" name="rombongan_belajar_id[]" value="{{ $rombel['rombongan_belajar_id'] ?? $rombel['id'] }}" style="width: 16px; height: 16px; margin-right: 0.75rem; accent-color: var(--accent);">
                                <span style="font-size: 0.85rem; font-weight: 500;">{{ $rombel['nama'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" style="width: 100%; background: var(--accent-gradient); color: white; border: none; padding: 1rem; border-radius: 12px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    SIMPAN PROYEK
                </button>
            </form>
        </div>

        <!-- Daftar Proyek -->
        <div style="background: var(--card-bg); border: var(--glass-border); border-radius: 16px; padding: 1.5rem; backdrop-filter: blur(10px);">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; color: var(--text-primary); border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 0.75rem;">
                Daftar Proyek P5 Semester Ini
            </h3>

            @if($proyeks->count() > 0)
                <div style="display: grid; gap: 1.25rem;">
                    @foreach($proyeks as $proyek)
                        @php
                            $colors = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4'];
                            $themeColor = $colors[$proyek->tema_id % count($colors)];
                        @endphp
                        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 1.25rem; border-left: 5px solid {{ $themeColor }};">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                                <div>
                                    <span style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase; color: {{ $themeColor }}; letter-spacing: 0.05em;">TEMA: {{ $proyek->tema->nama ?? '-' }}</span>
                                    <h4 style="font-weight: 700; font-size: 1.15rem; color: white; margin-top: 0.25rem;">{{ $proyek->nama_proyek }}</h4>
                                </div>
                                <span style="font-size: 0.7rem; background: rgba(255,255,255,0.05); color: var(--text-secondary); padding: 0.35rem 0.6rem; border-radius: 20px; font-weight: 600;">SMT {{ $proyek->semester }}</span>
                            </div>
                            
                            <p style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 1.25rem;">{{ $proyek->deskripsi ?: 'Tidak ada deskripsi proyek.' }}</p>

                            <div style="background: rgba(0,0,0,0.2); padding: 0.75rem; border-radius: 12px;">
                                <div style="display: flex; flex-wrap: wrap; gap: 0.4rem;">
                                    @foreach($proyek->rombel as $pr)
                                        @php
                                            $namaRombel = 'Unknown';
                                            foreach($rombels as $r) {
                                                if(($r['rombongan_belajar_id'] ?? $r['id'] ?? '') === $pr->rombongan_belajar_id) {
                                                    $namaRombel = $r['nama'];
                                                    break;
                                                }
                                            }
                                        @endphp
                                        <span style="font-size: 0.7rem; background: {{ $themeColor }}22; color: {{ $themeColor }}; padding: 0.25rem 0.6rem; border-radius: 6px; font-weight: 600; border: 1px solid {{ $themeColor }}44;">{{ $namaRombel }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 3rem 1rem; color: var(--text-secondary);">
                    <div style="width: 64px; height: 64px; background: rgba(255,255,255,0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                    </div>
                    <p>Belum ada Proyek P5 yang dibuat untuk semester ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
