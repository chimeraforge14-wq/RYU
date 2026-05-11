@extends('layouts.app')
@section('title', $title . ' - e-Rapor SD')
@section('header_title', $title)
@section('header_subtitle', 'Data tersinkronisasi dari Dapodik Lokal')

@section('content')
<!-- Search & Filter Bar -->
<div class="stat-card animate-slide-up" style="margin-bottom: 2rem; padding: 1.25rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
    <div style="position: relative; flex: 1; max-width: 400px; min-width: 250px;">
        <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <input type="text" id="searchInput" placeholder="Cari data di sini..." style="width: 100%; padding: 0.75rem 0.75rem 0.75rem 40px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; outline: none;">
    </div>
    <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
        @if(strtolower($type) == 'guru' && (session('role') == 'admin' || session('role') == 'superadmin'))
            <button onclick="document.getElementById('modalGuru').style.display='flex'" class="btn-sync" style="background: var(--accent-gradient); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Guru Manual
            </button>
        @endif
        @if(strtolower($type) == 'pembelajaran' && (session('role') == 'admin' || session('role') == 'superadmin'))
            <button onclick="document.getElementById('modalPembelajaran').style.display='flex'" class="btn-sync" style="background: var(--accent-gradient); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Atur Kelas Guru
            </button>
        @endif
        <div style="font-size: 0.875rem; color: var(--text-secondary);">
            Total: <span style="color: white; font-weight: 600;">{{ count($data) }}</span> data ditemukan
        </div>
    </div>
</div>

@if(strtolower($type) == 'guru' && (session('role') == 'admin' || session('role') == 'superadmin'))
<!-- Modal Guru Manual -->
<div id="modalGuru" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(8px); z-index: 1000; align-items: center; justify-content: center; padding: 1rem;">
    <div class="stat-card" style="width: 100%; max-width: 500px; padding: 2rem; position: relative; animation: slideUp 0.3s ease;">
        <button onclick="document.getElementById('modalGuru').style.display='none'" style="position: absolute; top: 1.5rem; right: 1.5rem; background: none; border: none; color: var(--text-secondary); cursor: pointer;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
        
        <h3 style="margin-bottom: 0.5rem; font-size: 1.25rem;">Tambah Guru Manual</h3>
        <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 2rem;">Gunakan ini untuk menambahkan guru yang belum terdaftar di Dapodik.</p>

        <form action="{{ route('guru.tambah') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; font-weight: 600;">Nama Lengkap (Wajib)</label>
                <input type="text" name="nama" required placeholder="Masukkan nama lengkap..." style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; outline: none;">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; font-weight: 600;">NUPTK / NIP</label>
                    <input type="text" name="nuptk" placeholder="Opsional" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; outline: none;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; font-weight: 600;">NIK</label>
                    <input type="text" name="nik" placeholder="Opsional" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; outline: none;">
                </div>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; font-weight: 600;">Email</label>
                <input type="email" name="email" placeholder="Masukkan email untuk login..." style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; outline: none;">
            </div>
            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; font-weight: 600;">Jenis Guru</label>
                <select name="jenis_ptk" style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; outline: none;">
                    <option value="Guru Kelas">Guru Kelas</option>
                    <option value="Guru Mapel">Guru Mapel</option>
                    <option value="Guru Agama">Guru Agama</option>
                    <option value="Guru PJOK">Guru PJOK</option>
                </select>
            </div>
            
            <button type="submit" class="btn-sync" style="width: 100%; padding: 1rem; border: none; border-radius: 8px; background: var(--accent-gradient); color: white; font-weight: 700; cursor: pointer;">
                Simpan Data Guru
            </button>
        </form>
    </div>
</div>
@endif

@if(strtolower($type) == 'pembelajaran' && (session('role') == 'admin' || session('role') == 'superadmin'))
<!-- Modal Pembelajaran Manual -->
<div id="modalPembelajaran" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(8px); z-index: 1000; align-items: center; justify-content: center; padding: 1rem;">
    <div class="stat-card" style="width: 100%; max-width: 500px; padding: 2rem; position: relative; animation: slideUp 0.3s ease;">
        <button onclick="document.getElementById('modalPembelajaran').style.display='none'" style="position: absolute; top: 1.5rem; right: 1.5rem; background: none; border: none; color: var(--text-secondary); cursor: pointer;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
        
        <h3 style="margin-bottom: 0.5rem; font-size: 1.25rem;">Atur Pembelajaran Guru</h3>
        <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 2rem;">Hubungkan guru (manual/dapodik) dengan kelas dan mata pelajaran.</p>

        <form action="{{ route('pembelajaran.tambah') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; font-weight: 600;">Pilih Guru</label>
                <select name="ptk_id" required style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; outline: none;">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($ptks as $p)
                        <option value="{{ $p['ptk_id'] }}">{{ $p['nama'] }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; font-weight: 600;">Pilih Rombel / Kelas</label>
                <select name="rombongan_belajar_id" required style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; outline: none;">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($rombels as $r)
                        <option value="{{ $r['rombongan_belajar_id'] ?? $r['id'] }}">{{ $r['nama'] }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600;">Mata Pelajaran</label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; cursor: pointer; color: var(--accent);">
                        <input type="checkbox" id="isGuruKelas" onchange="toggleGuruKelas(this)"> Guru Kelas (Mapel Utama)
                    </label>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <input type="text" name="mata_pelajaran_id" id="mapelId" placeholder="ID Mapel" style="width: 30%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; outline: none;">
                    <input type="text" name="nama_mata_pelajaran" id="mapelNama" required placeholder="Nama Mata Pelajaran..." style="flex: 1; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; outline: none;">
                </div>
            </div>

            <script>
                function toggleGuruKelas(cb) {
                    const idInput = document.getElementById('mapelId');
                    const namaInput = document.getElementById('mapelNama');
                    
                    if (cb.checked) {
                        idInput.value = '100000000';
                        namaInput.value = 'Guru Kelas';
                        idInput.readOnly = true;
                        namaInput.readOnly = true;
                        idInput.style.opacity = '0.5';
                        namaInput.style.opacity = '0.5';
                    } else {
                        idInput.value = '';
                        namaInput.value = '';
                        idInput.readOnly = false;
                        namaInput.readOnly = false;
                        idInput.style.opacity = '1';
                        namaInput.style.opacity = '1';
                    }
                }
            </script>
            
            <button type="submit" class="btn-sync" style="width: 100%; padding: 1rem; border: none; border-radius: 8px; background: var(--accent-gradient); color: white; font-weight: 700; cursor: pointer; margin-top: 1rem;">
                Hubungkan Guru & Kelas
            </button>
        </form>
    </div>
</div>
@endif

<div class="table-container animate-slide-up delay-1">
    <div style="overflow-x: auto;">
        <table id="dataTable">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    @if(strtolower($type) == 'sekolah')
                        <th>Field</th>
                        <th>Keterangan</th>
                    @elseif(strtolower($type) == 'guru')
                        <th>Nama Lengkap</th>
                        <th>NUPTK / NIP</th>
                        <th>L/P</th>
                        <th>Tugas</th>
                    @elseif(strtolower($type) == 'siswa')
                        <th>Nama Siswa</th>
                        <th>NISN / NIPD</th>
                        <th>L/P</th>
                        <th>Tempat, Tgl Lahir</th>
                    @elseif(strtolower($type) == 'kelas')
                        <th>Nama Rombel</th>
                        <th>Tingkat</th>
                        <th>Kurikulum</th>
                        <th>Wali Kelas</th>
                        <th style="text-align: center;">Aksi</th>
                    @elseif(strtolower($type) == 'mapel')
                        <th>ID Mapel</th>
                        <th>Nama Mata Pelajaran</th>
                    @elseif(strtolower($type) == 'pembelajaran')
                        <th>Rombel</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru Pengajar</th>
                        <th style="text-align: center;">Jam/Mgg</th>
                    @elseif(strtolower($type) == 'ekstrakurikuler')
                        <th>Nama Ekstrakurikuler</th>
                        <th>Pembina</th>
                        <th>Tingkat</th>
                        <th style="text-align: center;">Aksi</th>
                    @else
                        <th>Informasi</th>
                        <th>Detail</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $row)
                    @php $rowArr = (array) $row; @endphp
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        @if(strtolower($type) == 'sekolah')
                            <td colspan="2" style="padding:0;">
                                <table style="width: 100%; border:none;">
                                    @foreach($rowArr as $k => $v)
                                        @if(!is_array($v))
                                        <tr>
                                            <td style="width: 30%; font-weight: 600; border:none; color: var(--text-secondary);">{{ ucwords(str_replace('_', ' ', $k)) }}</td>
                                            <td style="border:none;">{{ $v }}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </table>
                            </td>
                        @elseif(strtolower($type) == 'guru')
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--accent-gradient); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.8rem; flex-shrink: 0;">
                                        {{ substr($rowArr['nama'] ?? $rowArr['nama_guru'] ?? 'G', 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: var(--text-primary);">
                                            {{ $rowArr['nama'] ?? $rowArr['nama_guru'] ?? '-' }}
                                            @if(isset($rowArr['is_manual']))
                                                <span style="font-size: 0.6rem; background: rgba(59, 130, 246, 0.15); color: #60a5fa; padding: 2px 6px; border-radius: 4px; margin-left: 6px; font-weight: 600; letter-spacing: 0.5px;">MANUAL</span>
                                            @endif
                                        </div>
                                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 2px;">{{ $rowArr['email'] ?? 'Tidak ada email' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-family: monospace; background: rgba(255,255,255,0.05); padding: 2px 6px; border-radius: 4px; display: inline-block;">
                                    NUPTK: {{ $rowArr['nuptk'] ?? '-' }}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 2px;">
                                    NIP: {{ $rowArr['nip'] ?? '-' }}
                                </div>
                            </td>
                            <td>
                                @if(($rowArr['jenis_kelamin'] ?? '') == 'L')
                                    <span style="background: rgba(59, 130, 246, 0.1); color: #60a5fa; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem;">Laki-laki</span>
                                @elseif(($rowArr['jenis_kelamin'] ?? '') == 'P')
                                    <span style="background: rgba(236, 72, 153, 0.1); color: #f472b6; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem;">Perempuan</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 500;">
                                    {{ $rowArr['jenis_ptk_id_str'] ?? $rowArr['tugas_tambahan'] ?? 'Guru Mapel/Kelas' }}
                                </span>
                            </td>
                        @elseif(strtolower($type) == 'siswa')
                            <td>
                                <div style="font-weight: 600; color: var(--text-primary);">{{ $rowArr['nama'] ?? '-' }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 2px;">{{ $rowArr['agama_id_str'] ?? '' }}</div>
                            </td>
                            <td>
                                <div style="font-family: monospace; background: rgba(255,255,255,0.05); padding: 2px 6px; border-radius: 4px; display: inline-block;">
                                    NISN: {{ $rowArr['nisn'] ?? '-' }}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 2px;">
                                    NIPD: {{ $rowArr['nipd'] ?? '-' }}
                                </div>
                            </td>
                            <td>
                                @if(($rowArr['jenis_kelamin'] ?? '') == 'L')
                                    <span style="background: rgba(59, 130, 246, 0.1); color: #60a5fa; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem;">Laki-laki</span>
                                @elseif(($rowArr['jenis_kelamin'] ?? '') == 'P')
                                    <span style="background: rgba(236, 72, 153, 0.1); color: #f472b6; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem;">Perempuan</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $rowArr['tempat_lahir'] ?? '-' }}, {{ $rowArr['tanggal_lahir'] ?? '-' }}</td>
                        @elseif(strtolower($type) == 'kelas')
                            <td>
                                <div style="font-weight: 600; font-size: 1.05rem; color: var(--accent);">{{ $rowArr['nama'] ?? '-' }}</div>
                            </td>
                            <td>
                                <span style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; padding: 2px 8px; border-radius: 4px; font-weight: 600; font-size: 0.8rem;">
                                    Kelas {{ $rowArr['tingkat_pendidikan_id'] ?? '-' }}
                                </span>
                            </td>
                            <td style="font-size: 0.85rem; color: var(--text-secondary);">{{ $rowArr['kurikulum_id_str'] ?? '-' }}</td>
                            <td style="font-weight: 500;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 24px; height: 24px; background: rgba(99, 102, 241, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    </div>
                                    {{ $rowArr['nama_wali_kelas'] ?? '-' }}
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ url('/referensi/kelas/anggota/' . ($rowArr['rombongan_belajar_id'] ?? $rowArr['id'])) }}" class="btn-sync" style="padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.75rem; text-decoration:none; background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2);">Lihat Anggota</a>
                            </td>
                        @elseif(strtolower($type) == 'mapel')
                            <td style="font-family: monospace; color: var(--text-secondary);">{{ $rowArr['mata_pelajaran_id'] ?? '-' }}</td>
                            <td style="font-weight: 600; color: var(--text-primary);">{{ $rowArr['nama_mata_pelajaran'] ?? '-' }}</td>
                        @elseif(strtolower($type) == 'pembelajaran')
                            <td>
                                <span style="background: rgba(99, 102, 241, 0.1); color: #818cf8; padding: 2px 8px; border-radius: 4px; font-weight: 600; font-size: 0.8rem;">
                                    {{ $rowArr['nama_rombel'] ?? '-' }}
                                </span>
                            </td>
                            <td style="font-weight: 500;">
                                {{ $rowArr['nama_mata_pelajaran'] ?? $rowArr['mata_pelajaran_id_str'] ?? '-' }}
                                @if(isset($rowArr['is_manual']))
                                    <span style="font-size: 0.65rem; background: rgba(59, 130, 246, 0.1); color: #60a5fa; padding: 2px 6px; border-radius: 4px; margin-left: 5px; font-weight: normal;">MANUAL</span>
                                @endif
                            </td>
                            <td style="color: var(--text-secondary);">{{ $rowArr['ptk_id_str'] ?? '-' }}</td>
                            <td style="text-align: center;">
                                <span style="background: rgba(255, 255, 255, 0.05); padding: 2px 8px; border-radius: 12px; font-size: 0.8rem;">
                                    {{ $rowArr['jam_mengajar_per_minggu'] ?? '0' }} Jam
                                </span>
                            </td>
                        @elseif(strtolower($type) == 'ekstrakurikuler')
                            <td style="font-weight: 600; color: var(--accent);">{{ $rowArr['nama'] ?? '-' }}</td>
                            <td>{{ $rowArr['ptk_id_str'] ?? '-' }}</td>
                            <td>
                                <span style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem;">
                                    {{ $rowArr['tingkat_pendidikan_id_str'] ?? '-' }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ url('/referensi/kelas/anggota/' . ($rowArr['rombongan_belajar_id'] ?? $rowArr['id'])) }}" class="btn-sync" style="padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.75rem; text-decoration:none; background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2);">Lihat Anggota</a>
                            </td>
                        @else
                            <td colspan="2">
                                <pre style="font-size: 0.75rem; color: var(--text-secondary);">{{ json_encode($rowArr, JSON_PRETTY_PRINT) }}</pre>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 4rem; color: var(--text-secondary);">
                            <div style="margin-bottom: 1rem;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="opacity: 0.5;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            </div>
                            Data {{ strtolower($title) }} belum tersedia.<br>
                            Silakan klik menu <strong>Ambil Data Dapodik</strong> untuk sinkronisasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#dataTable tbody tr');
        
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
@endsection
