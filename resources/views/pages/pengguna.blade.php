@extends('layouts.app')
@section('title', 'Manajemen Pengguna - e-Rapor SD')
@section('header_title', 'Manajemen Pengguna')
@section('header_subtitle', 'Data Pengguna Aplikasi dari Sinkronisasi Dapodik')

@section('content')
<div class="table-container animate-slide-up delay-1">
    <table>
        <thead>
            <tr>
                <th>Nama Lengkap</th>
                <th>Username / Email</th>
                <th>Peran</th>
                <th>No HP</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @if(empty($pengguna))
                <tr>
                    <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                        Tidak ada data pengguna.<br>Silakan lakukan Sinkronisasi Dapodik terlebih dahulu.
                    </td>
                </tr>
            @else
                @foreach($pengguna as $user)
                    <tr>
                        <td style="font-weight: 500;">{{ $user['nama'] ?? '-' }}</td>
                        <td style="color: var(--text-secondary);">{{ $user['username'] ?? '-' }}</td>
                        <td>
                            <span class="role-badge {{ str_contains(strtolower($user['peran_id_str'] ?? ''), 'operator') ? 'role-op' : 'role-guru' }}">
                                {{ $user['peran_id_str'] ?? 'Guru' }}
                            </span>
                        </td>
                        <td>{{ $user['no_hp'] ?? '-' }}</td>
                        <td>
                            <span style="display:inline-block; width:8px; height:8px; background:#10b981; border-radius:50%; margin-right:0.25rem; box-shadow: 0 0 8px #10b981;"></span> Aktif
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
@endsection
