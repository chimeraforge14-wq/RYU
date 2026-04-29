@extends('layouts.app')

@section('title', 'Identitas Sekolah - e-Rapor SD')
@section('header_title', 'Identitas & Tanda Tangan')
@section('header_subtitle', 'Kelola logo sekolah dan tanda tangan digital untuk dokumen rapor')

@section('content')
<div class="animate-slide-up" style="max-width: 800px;">
    @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid rgba(16, 185, 129, 0.2);">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="table-container" style="padding: 2rem; margin-bottom: 2rem;">
            <h3 style="margin-bottom: 2rem; font-weight: 600; color: var(--accent); display: flex; align-items: center; gap: 0.75rem;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                Identitas Sekolah & Cetak
            </h3>
            
            <!-- Logo Sekolah -->
            <div style="margin-bottom: 2rem; display: flex; gap: 2rem; align-items: flex-start; flex-wrap: wrap;">
                <div style="width: 120px; height: 120px; background: rgba(0,0,0,0.2); border-radius: 12px; border: 1px dashed rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                    @if(isset($settings['school_logo']))
                        <img src="{{ Storage::url($settings['school_logo']) }}" style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="opacity: 0.3;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    @endif
                </div>
                <div style="flex: 1; min-width: 250px;">
                    <label class="form-label" style="font-weight: 600;">Logo Sekolah</label>
                    <p style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 1rem;">Format: PNG/JPG, Maks: 2MB. Disarankan latar belakang transparan.</p>
                    <input type="file" name="school_logo" class="form-control" style="padding: 0.5rem;">
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.05); margin-bottom: 2rem;">

            <!-- Info Kepala Sekolah -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <label class="form-label">Nama Kepala Sekolah</label>
                    <input type="text" name="headmaster_name" value="{{ $settings['headmaster_name'] ?? '' }}" class="form-control" placeholder="Nama Lengkap & Gelar">
                </div>
                <div>
                    <label class="form-label">NIP Kepala Sekolah</label>
                    <input type="text" name="headmaster_nip" value="{{ $settings['headmaster_nip'] ?? '' }}" class="form-control" placeholder="Nomor Induk Pegawai">
                </div>
            </div>

            <!-- Tanda Tangan -->
            <div style="margin-bottom: 1rem; display: flex; gap: 2rem; align-items: flex-start; flex-wrap: wrap;">
                <div style="width: 120px; height: 80px; background: rgba(0,0,0,0.2); border-radius: 12px; border: 1px dashed rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                    @if(isset($settings['headmaster_signature']))
                        <img src="{{ Storage::url($settings['headmaster_signature']) }}" style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="opacity: 0.3;"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                    @endif
                </div>
                <div style="flex: 1; min-width: 250px;">
                    <label class="form-label" style="font-weight: 600;">Tanda Tangan Kepala Sekolah</label>
                    <p style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 1rem;">Unggah hasil scan tanda tangan (PNG Transparan sangat disarankan).</p>
                    <input type="file" name="headmaster_signature" class="form-control" style="padding: 0.5rem;">
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 2rem;">
            <button type="submit" class="btn-sync" style="background: var(--accent-gradient); border: none; padding: 1rem 2.5rem; border-radius: 12px; color: white; font-weight: 700; cursor: pointer; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
