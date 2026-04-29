@extends('layouts.app')
@section('title', 'Daftar Siswa Kelas ' . ($rombel['nama'] ?? '') . ' - e-Rapor SD')
@section('header_title', 'Kelas: ' . ($rombel['nama'] ?? 'Tidak Diketahui'))
@section('header_subtitle', 'Wali Kelas: ' . ($rombel['ptk_id_str'] ?? 'Tidak Diketahui'))

@section('content')
<div style="margin-bottom: 1rem;">
    <a href="{{ route('referensi', ['type' => 'kelas']) }}" style="color: var(--accent); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Kembali ke Daftar Kelas
    </a>
</div>

<div class="table-container animate-slide-up delay-1">
    <div style="padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
        <h3 style="font-weight: 600; font-size: 1.1rem;">Daftar Anggota Rombel</h3>
        <span style="background: rgba(59, 130, 246, 0.1); color: var(--accent); padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.85rem; font-weight: 600;">
            Total: {{ count($anggota) }} Siswa
        </span>
    </div>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Peserta Didik</th>
                    <th>NISN / NIS</th>
                    <th>Jenis Kelamin</th>
                    <th>Agama</th>
                </tr>
            </thead>
            <tbody>
                @if(empty($anggota))
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                            Tidak ada data siswa untuk kelas ini.
                        </td>
                    </tr>
                @else
                    @foreach($anggota as $index => $siswa)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td style="font-weight: 500;">{{ $siswa['nama'] ?? $siswa['peserta_didik_id_str'] ?? 'Tidak diketahui' }}</td>
                            <td style="color: var(--text-secondary); font-family: monospace;">{{ $siswa['nisn'] ?? $siswa['nipd'] ?? '-' }}</td>
                            <td>{{ $siswa['jenis_kelamin'] ?? '-' }}</td>
                            <td>{{ $siswa['agama_id_str'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
