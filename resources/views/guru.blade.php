@extends('layouts.app')

@section('title', 'Data Guru - e-Rapor SD')
@section('header_title', 'Data Guru')
@section('header_subtitle', 'Kelola informasi PTK dan Operator Sekolah dari Dapodik')

@section('content')
    <div class="stat-card animate-slide-up" style="margin-bottom: 2rem; padding: 1.25rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
        <div class="section-header" style="margin-bottom: 0;">
            <div style="font-size: 1.25rem; font-weight: 700;">Daftar Guru & Tenaga Kependidikan</div>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem;">
            @if(session('role') == 'admin')
                <button onclick="document.getElementById('modalGuru').style.display='flex'" class="btn-sync" style="background: var(--accent-gradient); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Guru Manual
                </button>
            @endif
            <div class="badge-dapodik">SINKRONISASI AKTIF</div>
        </div>
    </div>

    @if(session('role') == 'admin')
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
                    <label style="display: block; font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; font-weight: 600;">Email / Username</label>
                    <input type="text" name="email" placeholder="Masukkan email untuk login..." style="width: 100%; padding: 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; outline: none;">
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
    
    <div class="table-container animate-slide-up delay-1">
        <table>
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>NIP / NUPTK</th>
                    <th>Email / Username</th>
                    <th>Status / Tugas</th>
                    <th style="text-align: center;">Tipe</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ptk as $user)
                    <tr>
                        <td style="font-weight: 600;">{{ $user['nama'] }}</td>
                        <td style="color: var(--text-secondary);">{{ $user['nuptk'] ?? $user['nip'] ?? '-' }}</td>
                        <td>{{ $user['email'] ?? $user['username'] ?? '-' }}</td>
                        <td>
                            <span class="role-badge role-guru">
                                {{ $user['jenis_ptk_id_str'] ?? 'Guru' }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            @if(isset($user['is_manual']))
                                <span style="font-size: 0.65rem; background: rgba(59, 130, 246, 0.1); color: #60a5fa; padding: 2px 6px; border-radius: 4px; font-weight: normal;">MANUAL</span>
                            @else
                                <span style="font-size: 0.65rem; background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 2px 6px; border-radius: 4px; font-weight: normal;">DAPODIK</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-secondary); padding: 4rem;">
                            Tidak ada data Guru yang ditemukan.<br>
                            Silakan sinkronisasi Dapodik atau tambah secara manual.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
