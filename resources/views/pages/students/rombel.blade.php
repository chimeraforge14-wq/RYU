@extends('layouts.app')

@section('title', 'Pindah Rombel - e-Rapor SD')
@section('header_title', 'Perpindahan Rombongan Belajar')

@section('content')
    <div class="animate-slide-up" style="max-width: 480px;">
        <div style="background: var(--card-bg); border: var(--glass-border); border-radius: var(--radius-lg); padding: 2rem; text-align: center;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: rgba(16,185,129,0.12); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2"><path d="M12 14c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5z"></path><path d="M2 20.66C2 18.09 6.48 16 12 16s10 2.09 10 4.66V22H2v-1.34z"></path></svg>
            </div>
            <h3 style="font-weight: 700; margin-bottom: 0.25rem;">{{ $student['nama'] }}</h3>
            <p style="color: var(--text-secondary); font-size: 0.85rem;">NISN: {{ $student['nisn'] ?? '-' }}</p>

            <div style="background: var(--bg-tertiary); border-radius: var(--radius-md); padding: 0.85rem; margin: 1.25rem 0; text-align: left;">
                <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Rombel Saat Ini</div>
                <div style="font-weight: 600; margin-top: 0.25rem;">{{ $currentRombel['nama'] ?? 'Belum terdaftar' }}</div>
            </div>

            <form action="{{ route('students.rombel.update', $student['peserta_didik_id']) }}" method="POST" style="text-align: left;">
                @csrf
                <input type="hidden" name="from_rombongan_belajar_id" value="{{ $currentRombel['rombongan_belajar_id'] ?? $currentRombel['id'] ?? '' }}">
                <input type="hidden" name="action" value="{{ $currentRombel ? 'transfer' : 'add' }}">

                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">Pilih Rombel Baru</label>
                <select name="rombongan_belajar_id" required
                        style="width: 100%; background: var(--bg-tertiary); border: 1px solid var(--border-color); color: var(--text-primary); padding: 0.7rem 1rem; border-radius: var(--radius-md); font-size: 0.9rem; outline: none; margin-bottom: 1.5rem;">
                    <option value="">-- Pilih Rombel --</option>
                    @foreach($rombels as $r)
                        @php $rid = $r['rombongan_belajar_id'] ?? $r['id']; @endphp
                        <option value="{{ $rid }}" {{ ($currentRombel['rombongan_belajar_id'] ?? $currentRombel['id'] ?? '') == $rid ? 'disabled' : '' }}>
                            {{ $r['nama'] }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" style="width: 100%; background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; padding: 0.7rem; border-radius: var(--radius-md); font-weight: 600; font-size: 0.9rem; cursor: pointer; box-shadow: 0 4px 12px rgba(16,185,129,0.3); margin-bottom: 0.5rem;">
                    Proses Perpindahan
                </button>
                <a href="{{ route('students.index') }}" style="display: block; text-align: center; color: var(--text-secondary); text-decoration: none; padding: 0.7rem; font-size: 0.85rem;">Batal</a>
            </form>
        </div>
    </div>
@endsection
