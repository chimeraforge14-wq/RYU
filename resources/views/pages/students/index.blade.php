@extends('layouts.app')

@section('title', 'Kelola Peserta Didik - e-Rapor SD')
@section('header_title', 'Kelola Peserta Didik')
@section('header_subtitle', 'Edit identitas dan kelola rombongan belajar siswa')

@section('content')
    <div class="animate-slide-up" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <input type="text" id="studentSearch" placeholder="Cari nama siswa..."
                   style="background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.6rem 1.25rem; border-radius: 99px; font-size: 0.85rem; width: 280px; outline: none; transition: border-color 0.3s;"
                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">
        </div>
        <a href="{{ route('students.create') }}" style="background: var(--accent-gradient); color: white; padding: 0.6rem 1.5rem; border-radius: 99px; text-decoration: none; font-weight: 600; font-size: 0.85rem; box-shadow: 0 4px 12px rgba(99,102,241,0.3); transition: all 0.3s;"
           onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
            + Tambah Siswa Baru
        </a>
    </div>

    <div class="table-container animate-slide-up delay-1">
        <table id="studentTable">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>NISN</th>
                    <th>Jenis Kelamin</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $siswa)
                <tr>
                    <td>
                        <div style="font-weight: 500;">{{ $siswa['nama'] }}</div>
                        <div style="color: var(--text-muted); font-size: 0.75rem;">NIK: {{ $siswa['nik'] ?? '-' }}</div>
                    </td>
                    <td>{{ $siswa['nisn'] ?? '-' }}</td>
                    <td>
                        @if(($siswa['jenis_kelamin'] ?? '') == 'L')
                            <span style="background: rgba(99,102,241,0.12); color: #818cf8; padding: 0.2rem 0.7rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600;">Laki-laki</span>
                        @else
                            <span style="background: rgba(236,72,153,0.12); color: #f472b6; padding: 0.2rem 0.7rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600;">Perempuan</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('students.edit', $siswa['peserta_didik_id']) }}" style="color: #818cf8; text-decoration: none; font-size: 0.8rem; margin-right: 0.75rem; font-weight: 500;">Edit</a>
                        <a href="{{ route('students.rombel', $siswa['peserta_didik_id']) }}" style="color: #34d399; text-decoration: none; font-size: 0.8rem; font-weight: 500;">Rombel</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
    document.getElementById('studentSearch').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let tr = document.getElementById('studentTable').getElementsByTagName('tr');
        for (let i = 1; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName('td')[0];
            if (td) {
                let textValue = td.textContent || td.innerText;
                tr[i].style.display = textValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
            }
        }
    });
    </script>
@endsection
