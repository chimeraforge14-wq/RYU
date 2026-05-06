@extends('layouts.app')

@section('title', 'Tujuan Pembelajaran - e-Rapor SD')
@section('header_title', 'Input Tujuan Pembelajaran (TP) / Capaian Pembelajaran (CP)')
@section('header_subtitle', 'Kelola daftar Tujuan Pembelajaran untuk setiap mata pelajaran')

@section('content')
    <!-- Filter -->
    <div class="animate-slide-up" style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.25rem 1.5rem; margin-bottom: 1.5rem;">
        <form action="{{ route('tp.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 0.7rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.3rem; text-transform: uppercase; letter-spacing: 0.5px;">Rombongan Belajar</label>
                <select name="rombongan_belajar_id" onchange="this.form.submit()"
                        style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.6rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                    <option value="">-- Pilih Rombel --</option>
                    @foreach($rombels as $r)
                        <option value="{{ $r['rombongan_belajar_id'] ?? $r['id'] }}" {{ $rombelId == ($r['rombongan_belajar_id'] ?? $r['id']) ? 'selected' : '' }}>{{ $r['nama'] }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 0.7rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.3rem; text-transform: uppercase; letter-spacing: 0.5px;">Mata Pelajaran</label>
                <select name="mata_pelajaran_id" onchange="this.form.submit()"
                        style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.6rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                    <option value="">-- Pilih Mapel --</option>
                    @php $selectedRombel = collect($rombels)->firstWhere(fn($r) => ($r['rombongan_belajar_id'] ?? $r['id']) == $rombelId); @endphp
                    @if($selectedRombel && isset($selectedRombel['pembelajaran']))
                        @foreach($selectedRombel['pembelajaran'] as $p)
                            <option value="{{ $p['mata_pelajaran_id'] }}" {{ $mapelId == $p['mata_pelajaran_id'] ? 'selected' : '' }}>{{ $p['nama_mata_pelajaran'] }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.1); color: #34d399; padding: 0.85rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; border: 1px solid rgba(16, 185, 129, 0.15); font-size: 0.85rem;">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error') || $errors->any())
        <div style="background: rgba(239, 68, 68, 0.1); color: #f87171; padding: 0.85rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; border: 1px solid rgba(239, 68, 68, 0.15); font-size: 0.85rem;">
            ⚠ {{ session('error') ?? $errors->first() }}
        </div>
    @endif

    @if($rombelId && $mapelId)
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem;" class="animate-slide-up delay-1">
        <!-- Form Tambah/Import -->
        <div>
            <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 1.25rem;">Tambah Manual</h3>
                <form action="{{ route('tp.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="rombongan_belajar_id" value="{{ $rombelId }}">
                    <input type="hidden" name="mata_pelajaran_id" value="{{ $mapelId }}">
                    
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.4rem;">Tipe</label>
                        <select name="type" required style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.65rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                            <option value="tp">Tujuan Pembelajaran (TP)</option>
                            <option value="cp">Capaian Pembelajaran (CP)</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.4rem;">Kode (Opsional)</label>
                        <input type="text" name="kode" placeholder="Contoh: TP1, TP2" style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.65rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                    </div>

                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.4rem;">Konten / Deskripsi</label>
                        <textarea name="content" required rows="3" placeholder="Masukkan deskripsi capaian atau tujuan pembelajaran..." style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.65rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none; resize: vertical;"></textarea>
                    </div>

                    <button type="submit" style="width: 100%; background: var(--accent-gradient); color: white; border: none; padding: 0.65rem 1.5rem; border-radius: var(--radius-md); font-weight: 600; font-size: 0.85rem; cursor: pointer; box-shadow: 0 4px 12px rgba(99,102,241,0.3);">
                        Simpan Data
                    </button>
                </form>
            </div>

            <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.5rem;">
                <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem;">Import CSV / TXT</h3>
                <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 1.25rem;">Punya banyak data? Import menggunakan format CSV.</p>
                
                <form action="{{ route('tp.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="rombongan_belajar_id" value="{{ $rombelId }}">
                    <input type="hidden" name="mata_pelajaran_id" value="{{ $mapelId }}">
                    
                    <input type="file" name="file" accept=".csv,.txt" required style="width: 100%; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 1rem;">
                    
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="submit" style="flex: 1; background: #10b981; color: white; border: none; padding: 0.6rem; border-radius: var(--radius-md); font-weight: 600; font-size: 0.8rem; cursor: pointer;">
                            Mulai Import
                        </button>
                        <a href="{{ route('tp.export') }}" style="display: inline-flex; justify-content: center; align-items: center; background: rgba(255,255,255,0.05); color: var(--text-primary); border: 1px solid var(--border-color); padding: 0.6rem; border-radius: var(--radius-md); font-size: 0.8rem; text-decoration: none;">
                            Unduh Template
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Daftar Data -->
        <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); overflow: hidden; display: flex; flex-direction: column;">
            <div style="padding: 1.25rem 1.5rem; border-bottom: var(--glass-border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-weight: 600;">Daftar TP / CP</h3>
                <span style="background: var(--accent-light); color: #818cf8; padding: 0.2rem 0.7rem; border-radius: 99px; font-size: 0.7rem; font-weight: 600;">Total: {{ count($tpData) }}</span>
            </div>
            
            <div style="padding: 1.5rem; overflow-y: auto; max-height: 600px;">
                @if(count($tpData) > 0)
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($tpData as $item)
                            <div style="background: var(--bg-tertiary); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1rem; display: flex; gap: 1rem; align-items: flex-start;">
                                <div style="background: {{ $item->type == 'tp' ? 'rgba(99,102,241,0.1)' : 'rgba(16,185,129,0.1)' }}; 
                                            color: {{ $item->type == 'tp' ? '#818cf8' : '#34d399' }}; 
                                            padding: 0.4rem 0.6rem; border-radius: 6px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase;">
                                    {{ $item->type }}
                                </div>
                                <div style="flex: 1;">
                                    @if($item->kode)
                                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem;">Kode: {{ $item->kode }}</div>
                                    @endif
                                    <div style="font-size: 0.9rem; line-height: 1.5;">{{ $item->content }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="opacity: 0.2; margin-bottom: 1rem;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <p>Belum ada Tujuan Pembelajaran yang diinput untuk mata pelajaran ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="animate-slide-up delay-1" style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 3rem; text-align: center; color: var(--text-secondary);">
        Pilih Rombongan Belajar dan Mata Pelajaran terlebih dahulu untuk mengelola TP / CP.
    </div>
    @endif
@endsection
