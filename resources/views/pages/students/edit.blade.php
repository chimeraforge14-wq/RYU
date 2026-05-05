@extends('layouts.app')

@section('title', 'Edit Siswa - e-Rapor SD')
@section('header_title', 'Edit Identitas Peserta Didik')

@section('content')
    <div class="animate-slide-up" style="max-width: 640px;">
        <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 1rem;">
            <form action="{{ route('students.update', $student['peserta_didik_id']) }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ $student['nama'] ?? '' }}" required
                               style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none;"
                               onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">NISN</label>
                            <input type="text" name="nisn" value="{{ $student['nisn'] ?? '' }}"
                                   style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">NIK</label>
                            <input type="text" name="nik" value="{{ $student['nik'] ?? '' }}"
                                   style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none;">
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ $student['tempat_lahir'] ?? '' }}"
                                   style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ isset($student['tanggal_lahir']) ? date('Y-m-d', strtotime($student['tanggal_lahir'])) : '' }}"
                                   style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none;">
                        </div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">Jenis Kelamin</label>
                        <select name="jenis_kelamin" style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none;">
                            <option value="L" {{ ($student['jenis_kelamin'] ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ ($student['jenis_kelamin'] ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">Alamat</label>
                        <textarea name="alamat_jalan" rows="2"
                                  style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; resize: vertical;">{{ $student['alamat_jalan'] ?? '' }}</textarea>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; margin-top: 1.5rem; padding-top: 1.5rem; border-top: var(--glass-border);">
                    <a href="{{ route('students.index') }}" style="color: var(--text-secondary); text-decoration: none; padding: 0.6rem 1.5rem; border-radius: var(--radius-md); background: var(--bg-tertiary); font-size: 0.85rem;">Batal</a>
                    <button type="submit" style="background: var(--accent-gradient); color: white; border: none; padding: 0.6rem 2rem; border-radius: var(--radius-md); font-weight: 600; font-size: 0.85rem; cursor: pointer; box-shadow: 0 4px 12px rgba(99,102,241,0.3);">Simpan Perubahan</button>
                </div>
            </form>
        </div>
        <div style="background: rgba(99,102,241,0.08); border: 1px solid rgba(99,102,241,0.15); border-radius: var(--radius-md); padding: 0.85rem 1.25rem; font-size: 0.8rem; color: #a5b4fc;">
            ℹ️ Perubahan ini hanya disimpan di database lokal dan tidak mengubah data Dapodik pusat.
        </div>
    </div>
@endsection
