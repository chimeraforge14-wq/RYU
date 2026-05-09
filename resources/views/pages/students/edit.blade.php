@extends('layouts.app')

@section('title', 'Edit Peserta Didik — ' . ($student['nama'] ?? '') . ' - e-Rapor SD')
@section('header_title', 'Edit Identitas Peserta Didik')
@section('header_subtitle', 'Perubahan hanya disimpan lokal, tidak mengubah data Dapodik pusat')

@section('content')
<div class="animate-slide-up">

    {{-- Breadcrumb --}}
    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.75rem; font-size: 0.82rem; color: var(--text-muted);">
        <a href="{{ route('students.index') }}" style="color: var(--text-secondary); text-decoration: none; display:flex; align-items:center; gap:0.3rem; transition: color 0.2s;"
           onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-secondary)'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Kelola Peserta Didik
        </a>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        <span style="color: var(--text-primary); font-weight: 500;">Edit Identitas</span>
    </div>

    <div style="display: grid; grid-template-columns: 300px 1fr; gap: 1.5rem; align-items: start;">

        {{-- Kartu Info Siswa (kiri) --}}
        <div>
            <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.75rem; text-align: center; margin-bottom: 1rem;">
                {{-- Avatar --}}
                <div style="width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 1rem;
                            background: {{ ($student['jenis_kelamin'] ?? '') == 'L' ? 'linear-gradient(135deg, #6366f1, #818cf8)' : 'linear-gradient(135deg, #ec4899, #f472b6)' }};
                            display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700; color: white;
                            box-shadow: 0 8px 24px {{ ($student['jenis_kelamin'] ?? '') == 'L' ? 'rgba(99,102,241,0.4)' : 'rgba(236,72,153,0.4)' }};">
                    {{ strtoupper(substr($student['nama'] ?? 'S', 0, 1)) }}
                </div>

                <h2 style="font-size: 1rem; font-weight: 700; margin-bottom: 0.25rem; line-height: 1.3;">{{ $student['nama'] ?? '-' }}</h2>

                @if(($student['jenis_kelamin'] ?? '') == 'L')
                    <span style="background: rgba(99,102,241,0.12); color: #818cf8; padding: 0.2rem 0.75rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600;">Laki-laki</span>
                @else
                    <span style="background: rgba(236,72,153,0.12); color: #f472b6; padding: 0.2rem 0.75rem; border-radius: 99px; font-size: 0.72rem; font-weight: 600;">Perempuan</span>
                @endif

                <div style="margin-top: 1.25rem; display: flex; flex-direction: column; gap: 0.6rem; text-align: left;">
                    <div style="background: var(--bg-tertiary); border-radius: var(--radius-md); padding: 0.65rem 0.9rem;">
                        <div style="font-size: 0.68rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.2rem;">NISN</div>
                        <div style="font-weight: 600; font-size: 0.85rem; font-family: monospace;">{{ $student['nisn'] ?? '-' }}</div>
                    </div>
                    <div style="background: var(--bg-tertiary); border-radius: var(--radius-md); padding: 0.65rem 0.9rem;">
                        <div style="font-size: 0.68rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.2rem;">NIK</div>
                        <div style="font-weight: 600; font-size: 0.85rem; font-family: monospace;">{{ $student['nik'] ?? '-' }}</div>
                    </div>
                    @if(!empty($student['tanggal_lahir']))
                    <div style="background: var(--bg-tertiary); border-radius: var(--radius-md); padding: 0.65rem 0.9rem;">
                        <div style="font-size: 0.68rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.2rem;">Tanggal Lahir</div>
                        <div style="font-weight: 600; font-size: 0.85rem;">
                            {{ \Carbon\Carbon::parse($student['tanggal_lahir'])->translatedFormat('d F Y') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Tombol Pindah Rombel --}}
            <a href="{{ route('students.rombel', $student['peserta_didik_id']) }}"
               style="display: flex; align-items: center; justify-content: center; gap: 0.6rem;
                      background: linear-gradient(135deg, rgba(16,185,129,0.15), rgba(5,150,105,0.15));
                      border: 1px solid rgba(16,185,129,0.3); color: #34d399;
                      padding: 0.75rem 1rem; border-radius: var(--radius-md);
                      text-decoration: none; font-weight: 600; font-size: 0.85rem;
                      transition: all 0.25s; width: 100%; box-sizing: border-box;"
               onmouseover="this.style.background='linear-gradient(135deg,rgba(16,185,129,0.25),rgba(5,150,105,0.25))'"
               onmouseout="this.style.background='linear-gradient(135deg,rgba(16,185,129,0.15),rgba(5,150,105,0.15))'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 1l4 4-4 4"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><path d="M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/>
                </svg>
                Pindah Rombel
            </a>

            {{-- Info Notice --}}
            <div style="background: rgba(99,102,241,0.07); border: 1px solid rgba(99,102,241,0.15); border-radius: var(--radius-md); padding: 0.85rem 1rem; margin-top: 1rem; font-size: 0.78rem; color: #a5b4fc; line-height: 1.5;">
                <div style="font-weight: 600; margin-bottom: 0.3rem; display:flex; align-items:center; gap:0.4rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    Catatan Penting
                </div>
                Data yang diubah di sini hanya tersimpan di database lokal dan <strong>tidak mengubah data Dapodik pusat</strong>.
            </div>
        </div>

        {{-- Form Edit (kanan) --}}
        <div>
            @if(session('success'))
            <div style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.25); color: #34d399;
                        padding: 0.85rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.25rem;
                        display: flex; align-items: center; gap: 0.6rem; font-size: 0.875rem; font-weight: 500;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('students.update', $student['peserta_didik_id']) }}" method="POST">
                @csrf

                {{-- Seksi: Identitas Utama --}}
                <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.75rem; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: var(--glass-border);">
                        <div style="width: 32px; height: 32px; background: rgba(99,102,241,0.12); border-radius: 8px; display:flex; align-items:center; justify-content:center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div>
                            <div style="font-weight: 700; font-size: 0.9rem;">Identitas Utama</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">Nama, NIK, NISN, dan data kelamin</div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr; gap: 1.1rem;">
                        {{-- Nama --}}
                        <div>
                            <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.6px;">Nama Lengkap <span style="color:#ef4444">*</span></label>
                            <input type="text" name="nama" value="{{ $student['nama'] ?? '' }}" required placeholder="Masukkan nama lengkap..."
                                   style="width: 100%; box-sizing: border-box; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            {{-- NISN --}}
                            <div>
                                <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.6px;">NISN</label>
                                <input type="text" name="nisn" value="{{ $student['nisn'] ?? '' }}" placeholder="Nomor Induk Siswa Nasional"
                                       style="width: 100%; box-sizing: border-box; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; transition: border-color 0.2s; font-family: monospace;"
                                       onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
                            </div>
                            {{-- NIK --}}
                            <div>
                                <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.6px;">NIK</label>
                                <input type="text" name="nik" value="{{ $student['nik'] ?? '' }}" placeholder="Nomor Induk Kependudukan"
                                       style="width: 100%; box-sizing: border-box; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; transition: border-color 0.2s; font-family: monospace;"
                                       onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
                            </div>
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.6px;">Jenis Kelamin</label>
                            <div style="display: flex; gap: 0.75rem;">
                                <label id="jk-l-label" style="flex: 1; display: flex; align-items: center; gap: 0.6rem; background: var(--bg-tertiary); border: 1px solid {{ ($student['jenis_kelamin'] ?? '') == 'L' ? 'var(--accent)' : 'var(--border-color)' }}; border-radius: var(--radius-md); padding: 0.7rem 1rem; cursor: pointer; transition: all 0.2s;">
                                    <input type="radio" name="jenis_kelamin" value="L" {{ ($student['jenis_kelamin'] ?? '') == 'L' ? 'checked' : '' }} style="accent-color: var(--accent);" onchange="updateJKStyle()">
                                    <span style="font-size: 0.85rem; font-weight: 500;">Laki-laki</span>
                                </label>
                                <label id="jk-p-label" style="flex: 1; display: flex; align-items: center; gap: 0.6rem; background: var(--bg-tertiary); border: 1px solid {{ ($student['jenis_kelamin'] ?? '') == 'P' ? '#ec4899' : 'var(--border-color)' }}; border-radius: var(--radius-md); padding: 0.7rem 1rem; cursor: pointer; transition: all 0.2s;">
                                    <input type="radio" name="jenis_kelamin" value="P" {{ ($student['jenis_kelamin'] ?? '') == 'P' ? 'checked' : '' }} style="accent-color: #ec4899;" onchange="updateJKStyle()">
                                    <span style="font-size: 0.85rem; font-weight: 500;">Perempuan</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Seksi: Data Kelahiran --}}
                <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.75rem; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: var(--glass-border);">
                        <div style="width: 32px; height: 32px; background: rgba(16,185,129,0.12); border-radius: 8px; display:flex; align-items:center; justify-content:center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </div>
                        <div>
                            <div style="font-weight: 700; font-size: 0.9rem;">Data Kelahiran</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">Tempat dan tanggal lahir</div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.6px;">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ $student['tempat_lahir'] ?? '' }}" placeholder="Nama kota/kabupaten..."
                                   style="width: 100%; box-sizing: border-box; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.6px;">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir"
                                   value="{{ isset($student['tanggal_lahir']) ? date('Y-m-d', strtotime($student['tanggal_lahir'])) : '' }}"
                                   style="width: 100%; box-sizing: border-box; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
                        </div>
                    </div>
                </div>

                {{-- Seksi: Alamat & Kontak --}}
                <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.75rem; margin-bottom: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: var(--glass-border);">
                        <div style="width: 32px; height: 32px; background: rgba(251,146,60,0.12); border-radius: 8px; display:flex; align-items:center; justify-content:center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fb923c" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <div style="font-weight: 700; font-size: 0.9rem;">Alamat</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">Alamat tempat tinggal siswa</div>
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.6px;">Alamat Jalan / Desa</label>
                        <textarea name="alamat_jalan" rows="3" placeholder="Nama jalan, RT/RW, desa/kelurahan..."
                                  style="width: 100%; box-sizing: border-box; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; resize: vertical; transition: border-color 0.2s; font-family: inherit;"
                                  onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">{{ $student['alamat_jalan'] ?? '' }}</textarea>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                    <a href="{{ route('students.index') }}"
                       style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--text-secondary); text-decoration: none; padding: 0.65rem 1.5rem; border-radius: var(--radius-md); background: var(--bg-tertiary); border: var(--glass-border); font-size: 0.85rem; font-weight: 500; transition: all 0.2s;"
                       onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                        Kembali
                    </a>
                    <button type="submit"
                            style="display: inline-flex; align-items: center; gap: 0.6rem; background: var(--accent-gradient); color: white; border: none; padding: 0.7rem 2rem; border-radius: var(--radius-md); font-weight: 700; font-size: 0.9rem; cursor: pointer; box-shadow: 0 4px 16px rgba(99,102,241,0.35); transition: all 0.25s;"
                            onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px rgba(99,102,241,0.45)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(99,102,241,0.35)'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateJKStyle() {
    const lRadio = document.querySelector('input[name="jenis_kelamin"][value="L"]');
    const pRadio = document.querySelector('input[name="jenis_kelamin"][value="P"]');
    const lLabel = document.getElementById('jk-l-label');
    const pLabel = document.getElementById('jk-p-label');
    lLabel.style.borderColor = lRadio.checked ? 'var(--accent)' : 'var(--border-color)';
    pLabel.style.borderColor = pRadio.checked ? '#ec4899' : 'var(--border-color)';
}
</script>
@endsection
