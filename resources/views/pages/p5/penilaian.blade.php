@extends('layouts.app')
@section('title', 'Penilaian P5 - e-Rapor SD')
@section('header_title', 'Penilaian P5')

@section('content')
<div class="animate-slide-up">
    @if(session('success'))
        <div style="background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid rgba(34, 197, 94, 0.2);">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: var(--card-bg); border: var(--glass-border); border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; backdrop-filter: blur(10px);">
        <form action="{{ route('kokurikuler.penilaian') }}" method="GET" style="display: flex; gap: 1rem; align-items: flex-end;">
            @if(count($rombels) > 1 || session('role') === 'admin')
            <div style="flex: 1;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Pilih Kelas (Rombel)</label>
                <select name="rombongan_belajar_id" required style="width: 100%; padding: 0.75rem; background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: var(--text-primary);">
                    <option value="">Pilih Kelas</option>
                    @foreach($rombels as $r)
                        <option value="{{ $r['rombongan_belajar_id'] ?? $r['id'] ?? '' }}" {{ $rombelId == ($r['rombongan_belajar_id'] ?? $r['id'] ?? '') ? 'selected' : '' }}>
                            {{ $r['nama'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            @else
            <input type="hidden" name="rombongan_belajar_id" value="{{ $rombels[0]['rombongan_belajar_id'] ?? $rombels[0]['id'] ?? '' }}">
            <div style="flex: 1;">
                 <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Kelas (Rombel)</label>
                 <div style="padding: 0.75rem; background: rgba(59, 130, 246, 0.1); color: #60a5fa; border-radius: 8px; font-weight: 700; border: 1px solid rgba(59, 130, 246, 0.2);">
                    {{ $rombels[0]['nama'] ?? '-' }}
                 </div>
            </div>
            @endif
            
            <div style="flex: 2;">
                <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Pilih Proyek P5</label>
                <select name="proyek_id" required style="width: 100%; padding: 0.75rem; background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: var(--text-primary);">
                    <option value="">Pilih Proyek</option>
                    @foreach($proyeks as $p)
                        <option value="{{ $p->id }}" {{ $proyekId == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_proyek }} ({{ $p->tema->nama ?? 'Tanpa Tema' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" style="background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid rgba(255, 255, 255, 0.1); padding: 0.75rem 1.5rem; border-radius: 8px; cursor: pointer; transition: background 0.2s;">
                    Tampilkan Siswa
                </button>
            </div>
        </form>
    </div>

    @if($proyekId && $rombelId)
        <div style="background: var(--card-bg); border: var(--glass-border); border-radius: 16px; overflow: hidden; backdrop-filter: blur(10px);">
            <div style="padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 style="font-weight: 600; font-size: 1.25rem;">Input Nilai: {{ $selectedProyek->nama_proyek ?? '-' }}</h3>
                    <p style="color: var(--text-secondary); font-size: 0.875rem;">Kelas: {{ $selectedRombelName }} | Total: {{ count($siswaData) }} Siswa</p>
                </div>
                <div style="font-size: 0.75rem; display: flex; gap: 1rem; background: rgba(0,0,0,0.2); padding: 0.5rem 1rem; border-radius: 8px;">
                    <span><strong style="color: var(--accent);">BB:</strong> Belum Berkembang</span>
                    <span><strong style="color: var(--accent);">MB:</strong> Mulai Berkembang</span>
                    <span><strong style="color: var(--accent);">BSH:</strong> Berkembang Sesuai Harapan</span>
                    <span><strong style="color: var(--accent);">SB:</strong> Sangat Berkembang</span>
                </div>
            </div>

            @if(count($siswaData) > 0)
                <form action="{{ route('kokurikuler.penilaian.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="proyek_id" value="{{ $proyekId }}">
                    <input type="hidden" name="rombongan_belajar_id" value="{{ $rombelId }}">

                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left;">
                            <thead>
                                <tr style="background: rgba(255, 255, 255, 0.02); border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                                    <th style="padding: 1rem 1.5rem; font-weight: 500; color: var(--text-secondary); width: 50px;">No</th>
                                    <th style="padding: 1rem 1.5rem; font-weight: 500; color: var(--text-secondary);">Nama Siswa</th>
                                    <th style="padding: 1rem 1.5rem; font-weight: 500; color: var(--text-secondary); width: 150px;">Predikat</th>
                                    <th style="padding: 1rem 1.5rem; font-weight: 500; color: var(--text-secondary);">Catatan Proses</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswaData as $index => $siswa)
                                    @php
                                        $idSiswa = $siswa['peserta_didik_id'] ?? '';
                                        $nilaiSaatIni = $penilaianMap[$idSiswa]['nilai'] ?? '';
                                        $catatanSaatIni = $penilaianMap[$idSiswa]['catatan_proses'] ?? '';
                                    @endphp
                                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                                        <td style="padding: 1rem 1.5rem;">{{ $index + 1 }}</td>
                                        <td style="padding: 1rem 1.5rem;">
                                            <div style="font-weight: 600; font-size: 1.05rem;">{{ $siswa['nama'] ?? 'Unknown' }}</div>
                                            <div style="font-size: 0.75rem; color: var(--text-secondary);">NISN: {{ $siswa['nisn'] ?? '-' }}</div>
                                        </td>
                                        <td style="padding: 1rem 1.5rem;">
                                            <div class="p5-grade-group">
                                                <input type="radio" name="penilaian[{{ $idSiswa }}][nilai]" id="bb_{{ $idSiswa }}" value="BB" {{ $nilaiSaatIni == 'BB' ? 'checked' : '' }}>
                                                <label for="bb_{{ $idSiswa }}" class="grade-bb">BB</label>

                                                <input type="radio" name="penilaian[{{ $idSiswa }}][nilai]" id="mb_{{ $idSiswa }}" value="MB" {{ $nilaiSaatIni == 'MB' ? 'checked' : '' }}>
                                                <label for="mb_{{ $idSiswa }}" class="grade-mb">MB</label>

                                                <input type="radio" name="penilaian[{{ $idSiswa }}][nilai]" id="bsh_{{ $idSiswa }}" value="BSH" {{ $nilaiSaatIni == 'BSH' ? 'checked' : '' }}>
                                                <label for="bsh_{{ $idSiswa }}" class="grade-bsh">BSH</label>

                                                <input type="radio" name="penilaian[{{ $idSiswa }}][nilai]" id="sb_{{ $idSiswa }}" value="SB" {{ $nilaiSaatIni == 'SB' ? 'checked' : '' }}>
                                                <label for="sb_{{ $idSiswa }}" class="grade-sb">SB</label>
                                            </div>
                                        </td>
                                        <td style="padding: 1rem 1.5rem;">
                                            <textarea name="penilaian[{{ $idSiswa }}][catatan_proses]" placeholder="Tulis catatan perkembangan di sini..." rows="1" style="width: 100%; padding: 0.75rem; background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: var(--text-primary); font-size: 0.85rem; resize: vertical; min-height: 40px;">{{ $catatanSaatIni }}</textarea>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <style>
                        .p5-grade-group {
                            display: flex;
                            gap: 6px;
                            background: rgba(0,0,0,0.2);
                            padding: 4px;
                            border-radius: 10px;
                            width: fit-content;
                        }
                        .p5-grade-group input[type="radio"] {
                            display: none;
                        }
                        .p5-grade-group label {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            width: 45px;
                            height: 35px;
                            border-radius: 6px;
                            font-size: 0.75rem;
                            font-weight: 700;
                            cursor: pointer;
                            transition: all 0.2s;
                            color: var(--text-secondary);
                            border: 1px solid transparent;
                        }
                        
                        /* BB: Belum Berkembang (Gray/Red) */
                        .p5-grade-group input[type="radio"][value="BB"]:checked + label {
                            background: #64748b; color: white; box-shadow: 0 4px 10px rgba(0,0,0,0.3);
                        }
                        /* MB: Mulai Berkembang (Yellow) */
                        .p5-grade-group input[type="radio"][value="MB"]:checked + label {
                            background: #eab308; color: #000; box-shadow: 0 4px 10px rgba(234, 179, 8, 0.3);
                        }
                        /* BSH: Berkembang Sesuai Harapan (Green) */
                        .p5-grade-group input[type="radio"][value="BSH"]:checked + label {
                            background: #10b981; color: white; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
                        }
                        /* SB: Sangat Berkembang (Blue) */
                        .p5-grade-group input[type="radio"][value="SB"]:checked + label {
                            background: #3b82f6; color: white; box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
                        }
                        
                        .p5-grade-group label:hover {
                            background: rgba(255,255,255,0.05);
                        }
                    </style>
                    
                    <div style="padding: 1.5rem; border-top: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: flex-end;">
                        <button type="submit" style="background: var(--accent-gradient); color: white; border: none; padding: 0.75rem 2rem; border-radius: 8px; font-weight: 500; cursor: pointer;">
                            Simpan Nilai
                        </button>
                    </div>
                </form>
            @else
                <div style="padding: 3rem; text-align: center; color: var(--text-secondary);">
                    <p>Siswa tidak ditemukan untuk Rombongan Belajar ini.</p>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
