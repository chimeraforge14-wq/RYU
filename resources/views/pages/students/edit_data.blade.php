@extends('layouts.app')

@section('title', 'Detail Data Siswa — ' . ($student['nama'] ?? '') . ' - e-Rapor SD')
@section('header_title', 'Lengkapi Data Peserta Didik')
@section('header_subtitle', 'Lengkapi informasi detail siswa untuk keperluan Identitas Rapor')

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
        <a href="{{ route('students.edit', $student['peserta_didik_id']) }}" style="color: var(--text-secondary); text-decoration: none; transition: color 0.2s;"
           onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-secondary)'">{{ $student['nama'] }}</a>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        <span style="color: var(--text-primary); font-weight: 500;">Detail Data</span>
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

                <div style="margin-top: 1.25rem; display: flex; flex-direction: column; gap: 0.6rem; text-align: left;">
                    <div style="background: var(--bg-tertiary); border-radius: var(--radius-md); padding: 0.65rem 0.9rem;">
                        <div style="font-size: 0.68rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.2rem;">NISN</div>
                        <div style="font-weight: 600; font-size: 0.85rem; font-family: monospace;">{{ $student['nisn'] ?? '-' }}</div>
                    </div>
                    <div style="background: var(--bg-tertiary); border-radius: var(--radius-md); padding: 0.65rem 0.9rem;">
                        <div style="font-size: 0.68rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.2rem;">NIK</div>
                        <div style="font-weight: 600; font-size: 0.85rem; font-family: monospace;">{{ $student['nik'] ?? '-' }}</div>
                    </div>
                </div>
            </div>

            {{-- Tombol Navigasi Cepat --}}
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <a href="{{ route('students.edit', $student['peserta_didik_id']) }}"
                style="display: flex; align-items: center; gap: 0.6rem;
                        background: var(--bg-tertiary); border: var(--glass-border); color: var(--text-secondary);
                        padding: 0.75rem 1rem; border-radius: var(--radius-md);
                        text-decoration: none; font-weight: 600; font-size: 0.82rem;
                        transition: all 0.2s;"
                onmouseover="this.style.color='var(--text-primary)'; this.style.background='rgba(255,255,255,0.05)'"
                onmouseout="this.style.color='var(--text-secondary)'; this.style.background='var(--bg-tertiary)'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Edit Identitas Utama
                </a>
                <a href="{{ route('students.rombel', $student['peserta_didik_id']) }}"
                style="display: flex; align-items: center; gap: 0.6rem;
                        background: var(--bg-tertiary); border: var(--glass-border); color: var(--text-secondary);
                        padding: 0.75rem 1rem; border-radius: var(--radius-md);
                        text-decoration: none; font-weight: 600; font-size: 0.82rem;
                        transition: all 0.2s;"
                onmouseover="this.style.color='var(--text-primary)'; this.style.background='rgba(255,255,255,0.05)'"
                onmouseout="this.style.color='var(--text-secondary)'; this.style.background='var(--bg-tertiary)'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Pindah Rombel
                </a>
            </div>

            {{-- Info Notice --}}
            <div style="background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.15); border-radius: var(--radius-md); padding: 0.85rem 1rem; margin-top: 1rem; font-size: 0.78rem; color: #6ee7b7; line-height: 1.5;">
                <div style="font-weight: 600; margin-bottom: 0.3rem; display:flex; align-items:center; gap:0.4rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    Penggunaan Data
                </div>
                Data ini akan digunakan untuk mencetak lembar <strong>Identitas Peserta Didik</strong> pada buku Rapor.
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

            <form action="{{ route('students.update_data', $student['peserta_didik_id']) }}" method="POST">
                @csrf

                {{-- Seksi: Keterangan Diri --}}
                <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.75rem; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: var(--glass-border);">
                        <div style="width: 32px; height: 32px; background: rgba(99,102,241,0.12); border-radius: 8px; display:flex; align-items:center; justify-content:center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div>
                            <div style="font-weight: 700; font-size: 0.9rem;">Keterangan Diri</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">Informasi tambahan mengenai siswa</div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem;">
                        {{-- NIS --}}
                        <div>
                            <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.6px;">NIS / NISS</label>
                            <input type="text" name="nipd" value="{{ $student['nipd'] ?? '' }}" placeholder="Nomor Induk Siswa..."
                                   style="width: 100%; box-sizing: border-box; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
                        </div>

                        {{-- Agama --}}
                        <div>
                            <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.6px;">Agama</label>
                            <select name="agama_id_str"
                                    style="width: 100%; box-sizing: border-box; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; transition: border-color 0.2s;">
                                <option value="">- Pilih Agama -</option>
                                @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Khonghucu'] as $agm)
                                    <option value="{{ $agm }}" {{ ($student['agama_id_str'] ?? '') == $agm ? 'selected' : '' }}>{{ $agm }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status Keluarga --}}
                        <div>
                            <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.6px;">Status dalam Keluarga</label>
                            <input type="text" name="status_dalam_keluarga" value="{{ $student['status_dalam_keluarga'] ?? '' }}" placeholder="Contoh: Anak Kandung"
                                   style="width: 100%; box-sizing: border-box; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
                        </div>

                        {{-- Anak Ke --}}
                        <div>
                            <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.6px;">Anak Ke-</label>
                            <input type="number" name="anak_ke" value="{{ $student['anak_ke'] ?? '' }}" placeholder="1"
                                   style="width: 100%; box-sizing: border-box; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
                        </div>
                    </div>
                </div>

                {{-- Seksi: Keterangan Orang Tua --}}
                <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.75rem; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: var(--glass-border);">
                        <div style="width: 32px; height: 32px; background: rgba(16,185,129,0.12); border-radius: 8px; display:flex; align-items:center; justify-content:center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div>
                            <div style="font-weight: 700; font-size: 0.9rem;">Data Orang Tua</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">Nama dan Pekerjaan Orang Tua</div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem;">
                        {{-- Ayah --}}
                        <div>
                            <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.6px;">Nama Ayah</label>
                            <input type="text" name="nama_ayah" value="{{ $student['nama_ayah'] ?? '' }}" placeholder="Nama Lengkap Ayah..."
                                   style="width: 100%; box-sizing: border-box; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.6px;">Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah_id_str" value="{{ $student['pekerjaan_ayah_id_str'] ?? '' }}" placeholder="Pekerjaan Ayah..."
                                   style="width: 100%; box-sizing: border-box; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
                        </div>

                        {{-- Ibu --}}
                        <div>
                            <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.6px;">Nama Ibu</label>
                            <input type="text" name="nama_ibu" value="{{ $student['nama_ibu'] ?? '' }}" placeholder="Nama Lengkap Ibu..."
                                   style="width: 100%; box-sizing: border-box; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.6px;">Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu_id_str" value="{{ $student['pekerjaan_ibu_id_str'] ?? '' }}" placeholder="Pekerjaan Ibu..."
                                   style="width: 100%; box-sizing: border-box; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
                        </div>
                    </div>

                    <div style="margin-top: 1.1rem;">
                        <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.6px;">Alamat Orang Tua</label>
                        <textarea name="alamat_orang_tua" rows="2" placeholder="Jika berbeda dengan alamat siswa..."
                                  style="width: 100%; box-sizing: border-box; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; resize: vertical; transition: border-color 0.2s; font-family: inherit;"
                                  onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">{{ $student['alamat_orang_tua'] ?? '' }}</textarea>
                    </div>
                </div>

                {{-- Seksi: Keterangan Wali --}}
                <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.75rem; margin-bottom: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: var(--glass-border);">
                        <div style="width: 32px; height: 32px; background: rgba(251,146,60,0.12); border-radius: 8px; display:flex; align-items:center; justify-content:center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fb923c" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
                        </div>
                        <div>
                            <div style="font-weight: 700; font-size: 0.9rem;">Data Wali</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">Diisi jika siswa tinggal bersama wali</div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem;">
                        <div>
                            <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.6px;">Nama Wali</label>
                            <input type="text" name="nama_wali" value="{{ $student['nama_wali'] ?? '' }}" placeholder="Nama Lengkap Wali..."
                                   style="width: 100%; box-sizing: border-box; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.6px;">Pekerjaan Wali</label>
                            <input type="text" name="pekerjaan_wali_id_str" value="{{ $student['pekerjaan_wali_id_str'] ?? '' }}" placeholder="Pekerjaan Wali..."
                                   style="width: 100%; box-sizing: border-box; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
                        </div>
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
                        Simpan Detail Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
