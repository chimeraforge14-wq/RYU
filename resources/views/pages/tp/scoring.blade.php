@extends('layouts.app')
@section('title', 'Input Nilai TP - e-Rapor SD')
@section('header_title', 'Input Nilai Tujuan Pembelajaran')
@section('header_subtitle', 'Masukkan nilai per TP, nilai akhir dihitung otomatis')

@section('content')
    {{-- Filter Rombel & Mapel --}}
    <div class="animate-slide-up" style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.25rem 1.5rem; margin-bottom: 1.5rem;">
        <form action="{{ route('tp.scoring') }}" method="GET" style="display: flex; gap: 1rem; align-items: end; flex-wrap: wrap;">
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
    <div style="background: rgba(16,185,129,0.1); color: #34d399; padding: 0.85rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; border: 1px solid rgba(16,185,129,0.15); font-size: 0.85rem;">
        ✓ {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div style="background: rgba(239,68,68,0.1); color: #f87171; padding: 0.85rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.25rem; border: 1px solid rgba(239,68,68,0.15); font-size: 0.85rem;">
        ⚠ {{ session('error') }}
    </div>
    @endif

    @if($rombelId && $mapelId)

    {{-- Panel: Tambah TP Baru (selalu tampil jika rombel+mapel dipilih) --}}
    <div class="animate-slide-up delay-1" style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.25rem 1.5rem; margin-bottom: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
            <div style="font-weight: 600; font-size: 0.9rem; color: var(--accent);">
                📋 Daftar Tujuan Pembelajaran (TP)
                <span style="background: rgba(99,102,241,0.1); color: #818cf8; padding: 0.15rem 0.65rem; border-radius: 99px; font-size: 0.75rem; margin-left: 0.5rem;">{{ count($tpData) }} TP</span>
            </div>
            <button type="button" onclick="toggleTpForm()" id="btnTambahTP"
                    style="background: rgba(99,102,241,0.1); color: #818cf8; border: 1px solid rgba(99,102,241,0.2); padding: 0.4rem 1rem; border-radius: var(--radius-sm); font-size: 0.8rem; cursor: pointer; font-weight: 600;">
                ➕ Tambah TP
            </button>
        </div>

        {{-- Form Tambah TP (tersembunyi default) --}}
        <div id="tpFormPanel" style="display: none; background: rgba(99,102,241,0.05); border: 1px solid rgba(99,102,241,0.15); border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 1rem;">
            <form action="{{ route('tp.store') }}" method="POST" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
                @csrf
                <input type="hidden" name="rombongan_belajar_id" value="{{ $rombelId }}">
                <input type="hidden" name="mata_pelajaran_id" value="{{ $mapelId }}">
                <input type="hidden" name="type" value="tp">
                <div style="flex: 0 0 90px;">
                    <label style="display:block; font-size:0.72rem; color:var(--text-secondary); margin-bottom:0.25rem; font-weight:600; text-transform:uppercase;">Kode</label>
                    <input type="text" name="kode" placeholder="TP1, TP2..." maxlength="10"
                           style="width:100%; background:var(--bg-tertiary); border:1px solid var(--border-color); color:var(--text-primary); padding:0.55rem 0.75rem; border-radius:var(--radius-sm); font-size:0.85rem; outline:none;">
                </div>
                <div style="flex: 1; min-width: 250px;">
                    <label style="display:block; font-size:0.72rem; color:var(--text-secondary); margin-bottom:0.25rem; font-weight:600; text-transform:uppercase;">Deskripsi Tujuan Pembelajaran</label>
                    <input type="text" name="content" required placeholder="Contoh: Siswa dapat memahami unsur-unsur seni rupa..."
                           style="width:100%; background:var(--bg-tertiary); border:1px solid var(--border-color); color:var(--text-primary); padding:0.55rem 0.75rem; border-radius:var(--radius-sm); font-size:0.85rem; outline:none;">
                </div>
                <div style="display:flex; gap:0.5rem;">
                    <button type="submit" style="background:var(--accent-gradient); color:white; border:none; padding:0.55rem 1.25rem; border-radius:var(--radius-sm); font-weight:600; font-size:0.85rem; cursor:pointer; white-space:nowrap;">
                        Simpan TP
                    </button>
                    <button type="button" onclick="toggleTpForm()" style="background:transparent; color:var(--text-secondary); border:1px solid var(--border-color); padding:0.55rem 0.85rem; border-radius:var(--radius-sm); font-size:0.8rem; cursor:pointer;">
                        Batal
                    </button>
                </div>
            </form>
        </div>

        {{-- Daftar TP yang sudah ada --}}
        @if(count($tpData) > 0)
        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
            @foreach($tpData as $tp)
            <div style="background: rgba(99,102,241,0.07); border: 1px solid rgba(99,102,241,0.15); border-radius: var(--radius-sm); padding: 0.4rem 0.85rem; font-size: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                <span style="color: #818cf8; font-weight: 700;">{{ $tp->kode ?? 'TP'.$loop->iteration }}</span>
                <span style="color: var(--text-secondary);" title="{{ $tp->content }}">{{ Str::limit($tp->content, 50) }}</span>
                <form action="{{ route('tp.destroy', $tp->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Hapus TP ini? Semua nilai terkait akan ikut terhapus.')">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:none; border:none; color:rgba(239,68,68,0.5); cursor:pointer; font-size:0.75rem; padding:0; line-height:1;" title="Hapus">✕</button>
                </form>
            </div>
            @endforeach
        </div>
        @else
        <div style="color: var(--text-muted); font-size: 0.85rem; font-style: italic; padding: 0.5rem 0;">
            Belum ada TP. Klik <strong style="color: #818cf8;">Tambah TP</strong> di atas untuk memulai.
        </div>
        @endif
    </div>

    {{-- Tabel Scoring --}}
    @if(count($tpData) > 0 && count($students) > 0)
    <div class="animate-slide-up delay-2" style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); overflow: hidden;">
        <div style="padding: 1rem 1.5rem; border-bottom: var(--glass-border); display: flex; justify-content: space-between; align-items: center;">
            <div style="font-weight: 600; font-size: 0.9rem;">Input Nilai per Tujuan Pembelajaran</div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">Rentang nilai: 0 – 100</div>
        </div>
        <form action="{{ route('tp.scores.store') }}" method="POST">
            @csrf
            <input type="hidden" name="rombongan_belajar_id" value="{{ $rombelId }}">
            <input type="hidden" name="mata_pelajaran_id" value="{{ $mapelId }}">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                    <thead>
                        <tr style="background: rgba(255,255,255,0.03);">
                            <th style="padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid var(--border-color); color: var(--text-secondary); font-weight: 600; white-space: nowrap;">Nama Siswa</th>
                            @foreach($tpData as $tp)
                            <th style="padding: 0.75rem 0.5rem; text-align: center; border-bottom: 1px solid var(--border-color); color: #818cf8; font-weight: 700; min-width: 70px;"
                                title="{{ $tp->content }}">
                                {{ $tp->kode ?? 'TP'.$loop->iteration }}
                                <div style="font-size: 0.6rem; font-weight: 400; color: var(--text-muted); white-space: nowrap; max-width: 80px; overflow: hidden; text-overflow: ellipsis;">{{ Str::limit($tp->content, 20) }}</div>
                            </th>
                            @endforeach
                            <th style="padding: 0.75rem 0.75rem; text-align: center; border-bottom: 1px solid var(--border-color); color: #34d399; font-weight: 700; min-width: 75px;">Nilai Akhir<br><span style="font-size: 0.65rem; font-weight: 400;">(Tertinggi)</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $siswa)
                        @php
                            $pdId     = $siswa['peserta_didik_id'];
                            $maxScore = 0;
                        @endphp
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);" class="tp-row">
                            <td style="padding: 0.6rem 1rem;">
                                <div style="font-weight: 500; color: var(--text-primary);">{{ $siswa['nama'] }}</div>
                                <div style="color: var(--text-muted); font-size: 0.7rem;">{{ $siswa['nisn'] ?? '-' }}</div>
                            </td>
                            @foreach($tpData as $tp)
                            @php
                                $scoreObj = $scores->has($pdId) ? $scores->get($pdId)->firstWhere('tp_id', $tp->id) : null;
                                $val      = $scoreObj ? $scoreObj->score : null;
                                if ($val > $maxScore) $maxScore = $val;
                            @endphp
                            <td style="padding: 0.4rem 0.25rem; text-align: center;">
                                <input type="number"
                                       name="scores[{{ $pdId }}][{{ $tp->id }}]"
                                       value="{{ $val }}"
                                       min="0" max="100"
                                       class="tp-input"
                                       data-row="{{ $pdId }}"
                                       oninput="recalcMax('{{ $pdId }}')"
                                       style="width: 62px; text-align: center; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.4rem; border-radius: 6px; font-size: 0.88rem; outline: none; transition: border-color 0.2s;"
                                       onfocus="this.style.borderColor='#6366f1'"
                                       onblur="this.style.borderColor='var(--border-color)'">
                            </td>
                            @endforeach
                            <td style="text-align: center; padding: 0.6rem 0.75rem;">
                                <span id="max_{{ $pdId }}" style="font-weight: 700; color: {{ $maxScore > 0 ? '#34d399' : 'var(--text-muted)' }}; font-size: 1.05rem;">
                                    {{ $maxScore ?: '-' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding: 1rem 1.5rem; text-align: right; border-top: var(--glass-border); display: flex; justify-content: space-between; align-items: center;">
                <div style="font-size: 0.8rem; color: var(--text-muted);">
                    💡 Nilai akhir diambil dari nilai TP tertinggi setiap siswa
                </div>
                <button type="submit" style="background: var(--accent-gradient); color: white; border: none; padding: 0.7rem 2.25rem; border-radius: var(--radius-md); font-weight: 700; font-size: 0.9rem; cursor: pointer; box-shadow: 0 4px 14px rgba(99,102,241,0.35);">
                    💾 Simpan & Kalkulasi Nilai
                </button>
            </div>
        </form>
    </div>
    @elseif(count($tpData) === 0)
    {{-- sudah ditangani di panel atas --}}
    @else
    <div class="animate-slide-up delay-2" style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 2rem; text-align: center; color: var(--text-secondary);">
        Tidak ada siswa di kelas ini.
    </div>
    @endif

    @else
    <div class="animate-slide-up delay-1" style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 3rem; text-align: center; color: var(--text-secondary);">
        Pilih Rombongan Belajar dan Mata Pelajaran terlebih dahulu.
    </div>
    @endif

<script>
    function toggleTpForm() {
        const panel = document.getElementById('tpFormPanel');
        const btn   = document.getElementById('btnTambahTP');
        if (panel.style.display === 'none') {
            panel.style.display = 'block';
            btn.textContent = '✕ Tutup';
            panel.querySelector('input[name="content"]').focus();
        } else {
            panel.style.display = 'none';
            btn.textContent = '➕ Tambah TP';
        }
    }

    // Kalkulasi nilai tertinggi secara live
    function recalcMax(pdId) {
        const inputs = document.querySelectorAll(`.tp-input[data-row="${pdId}"]`);
        let max = 0;
        inputs.forEach(inp => {
            const v = parseInt(inp.value) || 0;
            if (v > max) max = v;
        });
        const span = document.getElementById('max_' + pdId);
        if (span) {
            span.textContent = max > 0 ? max : '-';
            span.style.color = max > 0 ? '#34d399' : 'var(--text-muted)';
        }
    }
</script>
@endsection
