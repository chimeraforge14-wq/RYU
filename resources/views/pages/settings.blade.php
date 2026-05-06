@extends('layouts.app')

@section('title', 'Identitas Sekolah - e-Rapor SD')
@section('header_title', 'Identitas & Tanda Tangan')
@section('header_subtitle', 'Kelola logo sekolah, tanda tangan digital, dan identitas rapor')

@section('content')
<div class="animate-slide-up" style="max-width: 800px;">
    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.1); color: #34d399; padding: 0.85rem 1.25rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; border: 1px solid rgba(16, 185, 129, 0.15); font-size: 0.85rem;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Logo & Tanda Tangan -->
        <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.75rem; margin-bottom: 1.25rem;">
            <h3 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--accent);">Identitas Sekolah & Cetak</h3>

            <div style="display: flex; gap: 1.5rem; align-items: flex-start; flex-wrap: wrap; margin-bottom: 1.75rem;">
                <div style="width: 100px; height: 100px; background: var(--bg-tertiary); border-radius: var(--radius-md); border: 1px dashed rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                    @if(isset($settings['school_logo']) && $settings['school_logo'])
                        <img src="{{ $settings['school_logo'] }}" style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="opacity: 0.2;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
                    @endif
                </div>
                <div style="flex: 1; min-width: 220px;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.3rem; text-transform: uppercase; letter-spacing: 0.5px;">Logo Sekolah</label>
                    <p style="font-size: 0.7rem; color: var(--text-muted); margin-bottom: 0.75rem;">Format PNG, Maks 2MB. Latar transparan sangat disarankan.</p>
                    <input type="file" name="school_logo" accept="image/png" style="font-size: 0.8rem; color: var(--text-secondary);">
                </div>
            </div>

            <div style="border-top: var(--glass-border); padding-top: 1.5rem; margin-bottom: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">Nama Kepala Sekolah</label>
                    <input type="text" name="headmaster_name" value="{{ $settings['headmaster_name'] ?? '' }}" placeholder="Nama & Gelar"
                           style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.65rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">NIP Kepala Sekolah</label>
                    <input type="text" name="headmaster_nip" value="{{ $settings['headmaster_nip'] ?? '' }}" placeholder="NIP"
                           style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.65rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                </div>
            </div>

            <div style="display: flex; gap: 1.5rem; align-items: flex-start; flex-wrap: wrap;">
                <div style="width: 100px; height: 70px; background: var(--bg-tertiary); border-radius: var(--radius-md); border: 1px dashed rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                    @if(isset($settings['headmaster_signature']) && $settings['headmaster_signature'])
                        <img src="{{ $settings['headmaster_signature'] }}" style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="opacity: 0.2;"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                    @endif
                </div>
                <div style="flex: 1; min-width: 220px;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.3rem; text-transform: uppercase; letter-spacing: 0.5px;">Tanda Tangan Kepala Sekolah</label>
                    <p style="font-size: 0.7rem; color: var(--text-muted); margin-bottom: 0.75rem;">Format PNG, latar transparan disarankan.</p>
                    <input type="file" name="headmaster_signature" accept="image/png" style="font-size: 0.8rem; color: var(--text-secondary);">
                </div>
            </div>
        </div>

        <!-- Identitas Rapor -->
        <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.75rem; margin-bottom: 1.25rem;">
            <h3 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--accent);">Identitas Rapor</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">Kode Registrasi Unik (Koreg)</label>
                    <input type="text" name="koreg_unik" value="{{ $settings['koreg_unik'] ?? '' }}" placeholder="Kode registrasi sekolah"
                           style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.65rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">Titimangsa Rapor</label>
                    <input type="text" name="titimangsa_rapor" value="{{ $settings['titimangsa_rapor'] ?? '' }}" placeholder="cth: 20 Juni 2026"
                           style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.65rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">Semester Aktif</label>
                    <select name="semester_aktif" style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.65rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                        <option value="">-- Pilih Semester --</option>
                        <option value="Ganjil" {{ ($settings['semester_aktif'] ?? '') === 'Ganjil' ? 'selected' : '' }}>Ganjil (1)</option>
                        <option value="Genap" {{ ($settings['semester_aktif'] ?? '') === 'Genap' ? 'selected' : '' }}>Genap (2)</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">Tahun Pelajaran</label>
                    <input type="text" name="tahun_pelajaran" value="{{ $settings['tahun_pelajaran'] ?? '' }}" placeholder="cth: 2025/2026"
                           style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.65rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <button type="submit" style="background: var(--accent-gradient); color: white; border: none; padding: 0.7rem 2.5rem; border-radius: var(--radius-md); font-weight: 600; font-size: 0.9rem; cursor: pointer; box-shadow: 0 4px 16px rgba(99,102,241,0.3);">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
