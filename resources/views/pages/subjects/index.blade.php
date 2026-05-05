@extends('layouts.app')

@section('title', 'Mata Pelajaran Manual - e-Rapor SD')
@section('header_title', 'Manajemen Mata Pelajaran')
@section('header_subtitle', 'Tambah mapel manual dan sub-mapel di luar data Dapodik')

@section('content')
    <div style="display: grid; grid-template-columns: 340px 1fr; gap: 1.5rem; align-items: start;">
        <!-- Form Add -->
        <div class="animate-slide-up" style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.5rem;">
            <h3 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 1.25rem;">Tambah Mata Pelajaran</h3>
            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.3rem; text-transform: uppercase; letter-spacing: 0.5px;">ID Mapel (Unik)</label>
                        <input type="text" name="mata_pelajaran_id" required placeholder="Contoh: MP-001"
                               style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.6rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.3rem; text-transform: uppercase; letter-spacing: 0.5px;">Nama Mata Pelajaran</label>
                        <input type="text" name="nama_mata_pelajaran" required
                               style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.6rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.3rem; text-transform: uppercase; letter-spacing: 0.5px;">Kelompok Rapor</label>
                        <select name="kelompok" style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.6rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                            <option value="A">Kelompok A (Wajib)</option>
                            <option value="B">Kelompok B (Umum)</option>
                            <option value="C">Kelompok C (Peminatan)</option>
                            <option value="Ekskul">Ekstrakurikuler</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.3rem; text-transform: uppercase; letter-spacing: 0.5px;">Sub-Mapel dari (Opsional)</label>
                        <select name="parent_id" style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.6rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                            <option value="">-- Bukan Sub-Mapel --</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->mata_pelajaran_id }}">{{ $s->nama_mata_pelajaran }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" style="background: var(--accent-gradient); color: white; border: none; padding: 0.65rem; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.85rem; cursor: pointer; box-shadow: 0 4px 12px rgba(99,102,241,0.3);">Simpan Mapel</button>
                </div>
            </form>
        </div>

        <!-- List -->
        <div class="animate-slide-up delay-1">
            <div class="table-container" style="margin-bottom: 0;">
                <table>
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th>Kelompok</th>
                            <th>Tipe</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $s)
                        <tr>
                            <td>
                                <div style="font-weight: 500;">{{ $s->nama_mata_pelajaran }}</div>
                                <div style="color: var(--text-muted); font-size: 0.7rem;">ID: {{ $s->mata_pelajaran_id }}</div>
                            </td>
                            <td>{{ $s->kelompok }}</td>
                            <td><span style="background: rgba(99,102,241,0.12); color: #818cf8; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.7rem; font-weight: 600;">Induk</span></td>
                            <td style="text-align: center;">
                                <form action="{{ route('subjects.destroy', $s->id) }}" method="POST" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus?')" style="background: none; border: none; color: #f87171; cursor: pointer; font-size: 0.8rem;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                            @foreach($s->children as $child)
                            <tr>
                                <td style="padding-left: 2.5rem;">
                                    <div style="font-weight: 500; font-size: 0.85rem;">└─ {{ $child->nama_mata_pelajaran }}</div>
                                    <div style="color: var(--text-muted); font-size: 0.65rem;">ID: {{ $child->mata_pelajaran_id }}</div>
                                </td>
                                <td style="color: var(--text-secondary);">{{ $child->kelompok }}</td>
                                <td><span style="background: rgba(100,116,139,0.15); color: #94a3b8; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.7rem; font-weight: 600;">Sub</span></td>
                                <td style="text-align: center;">
                                    <form action="{{ route('subjects.destroy', $child->id) }}" method="POST" style="display: inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #f87171; cursor: pointer; font-size: 0.8rem;">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        @empty
                        <tr><td colspan="4" style="text-align: center; color: var(--text-secondary); padding: 2rem;">Belum ada mata pelajaran manual.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
