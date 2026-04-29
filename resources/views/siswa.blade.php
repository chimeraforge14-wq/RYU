@extends('layouts.app')

@section('title', 'Data Siswa - e-Rapor SD')
@section('header_title', 'Data Siswa & Rombel')
@section('header_subtitle', 'Manajemen Rombongan Belajar dan Siswa dari Dapodik')

@section('content')
    <div class="section-header animate-slide-up">
        <div>Daftar Rombongan Belajar (Kelas)</div>
        <div class="badge-dapodik">SINKRONISASI AKTIF</div>
    </div>
    
    <div class="table-container animate-slide-up delay-1">
        <table>
            <thead>
                <tr>
                    <th>Nama Kelas</th>
                    <th>Wali Kelas</th>
                    <th>Kurikulum</th>
                    <th>Tingkat Pendidikan</th>
                    <th>Ruang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rombonganBelajar as $rombel)
                    <tr>
                        <td style="font-weight: 600; font-size: 1.1rem; color: var(--accent);">{{ $rombel['nama'] }}</td>
                        <td>{{ $rombel['ptk_id_str'] }}</td>
                        <td style="color: var(--text-secondary);">{{ $rombel['kurikulum_id_str'] }}</td>
                        <td>Tingkat {{ $rombel['tingkat_pendidikan_id'] ?? '-' }}</td>
                        <td>{{ $rombel['id_ruang_str'] }}</td>
                        <td>
                            @php $rombelId = $rombel['rombongan_belajar_id'] ?? $rombel['id'] ?? ''; @endphp
                            <a href="{{ url('/referensi/kelas/anggota/' . $rombelId) }}" style="padding: 0.4rem 0.8rem; background: var(--accent-gradient); color: white; border-radius: 6px; text-decoration: none; font-size: 0.85rem; font-weight: 500; display: inline-block;">Daftar Siswa</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-secondary); padding: 2rem;">Tidak ada data Rombongan Belajar yang ditemukan di Dapodik Lokal.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
