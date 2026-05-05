@extends('layouts.app')

@section('title', 'Kelompok Kokurikuler - e-Rapor SD')
@section('header_title', 'Kelompok Kokurikuler (P5)')
@section('header_subtitle', 'Kelola kelompok, koordinator, dan kegiatan P5')

@section('content')
    <div style="display: grid; grid-template-columns: 340px 1fr; gap: 1.5rem; align-items: start;">
        <!-- Forms -->
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <!-- Add Group -->
            <div class="animate-slide-up" style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.5rem;">
                <h3 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 1rem;">Tambah Kelompok</h3>
                <form action="{{ route('kokurikuler.groups.store') }}" method="POST">
                    @csrf
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <input type="text" name="name" required placeholder="Nama Kelompok (cth: P5 Kelas 4)"
                               style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.6rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                        <select name="fase" style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.6rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                            <option value="A">Fase A (Kelas 1-2)</option>
                            <option value="B">Fase B (Kelas 3-4)</option>
                            <option value="C">Fase C (Kelas 5-6)</option>
                        </select>
                        <select name="coordinator_id" style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.6rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                            <option value="">-- Koordinator --</option>
                            @foreach($ptks as $p)
                                <option value="{{ $p['ptk_id'] }}">{{ $p['nama'] }}</option>
                            @endforeach
                        </select>
                        <button type="submit" style="background: var(--accent-gradient); color: white; border: none; padding: 0.6rem; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.85rem; cursor: pointer;">Simpan Kelompok</button>
                    </div>
                </form>
            </div>

            <!-- Add Activity -->
            <div class="animate-slide-up delay-1" style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 1.5rem;">
                <h3 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 1rem;">Tambah Kegiatan P5</h3>
                <form action="{{ route('kokurikuler.activities.store') }}" method="POST">
                    @csrf
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <select name="group_id" required style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.6rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                            <option value="">-- Pilih Kelompok --</option>
                            @foreach($groups as $g)
                                <option value="{{ $g->id }}">{{ $g->name }}</option>
                            @endforeach
                        </select>
                        <select name="theme" style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.6rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                            <option value="Gaya Hidup Berkelanjutan">Gaya Hidup Berkelanjutan</option>
                            <option value="Kearifan Lokal">Kearifan Lokal</option>
                            <option value="Bhinneka Tunggal Ika">Bhinneka Tunggal Ika</option>
                            <option value="Bangunlah Jiwa dan Raganya">Bangunlah Jiwa dan Raganya</option>
                            <option value="Suara Demokrasi">Suara Demokrasi</option>
                            <option value="Rekayasa dan Teknologi">Rekayasa dan Teknologi</option>
                            <option value="Kewirausahaan">Kewirausahaan</option>
                        </select>
                        <input type="text" name="activity_name" required placeholder="Nama Kegiatan"
                               style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.6rem 0.85rem; border-radius: var(--radius-sm); font-size: 0.85rem; outline: none;">
                        <button type="submit" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; padding: 0.6rem; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.85rem; cursor: pointer;">Simpan Kegiatan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- List -->
        <div class="animate-slide-up delay-2">
            @forelse($groups as $g)
            <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); margin-bottom: 1rem; overflow: hidden;">
                <div style="padding: 1rem 1.25rem; display: flex; justify-content: space-between; align-items: center; border-bottom: var(--glass-border);">
                    <div>
                        <span style="font-weight: 600; color: var(--accent);">{{ $g->name }}</span>
                        <span style="color: var(--text-muted); font-size: 0.8rem; margin-left: 0.5rem;">Fase {{ $g->fase }}</span>
                    </div>
                    <span style="background: var(--accent-light); color: #818cf8; padding: 0.15rem 0.6rem; border-radius: 99px; font-size: 0.75rem; font-weight: 600;">{{ $g->activities->count() }} kegiatan</span>
                </div>
                @foreach($g->activities as $a)
                <div style="padding: 0.7rem 1.25rem 0.7rem 2rem; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="background: rgba(6,182,212,0.12); color: #22d3ee; padding: 0.1rem 0.5rem; border-radius: 4px; font-size: 0.65rem; font-weight: 600;">{{ $a->theme }}</span>
                        <span style="margin-left: 0.5rem; font-size: 0.85rem;">{{ $a->activity_name }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @empty
            <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 3rem; text-align: center; color: var(--text-secondary);">
                Belum ada kelompok kokurikuler. Silakan buat di panel sebelah kiri.
            </div>
            @endforelse
        </div>
    </div>
@endsection
