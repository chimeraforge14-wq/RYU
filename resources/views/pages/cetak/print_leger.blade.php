<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Leger_{{ $rombelData['nama'] ?? 'Kelas' }}</title>
    <style>
        @page { margin: 1cm; size: A4 landscape; }
        body { font-family: 'Arial', sans-serif; font-size: 8.5pt; }
        .text-center { text-align: center; }
        .cop-table { width: 100%; border-bottom: 3px solid black; margin-bottom: 10px; padding-bottom: 5px; }
        .cop-school-name { font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .cop-address { font-size: 8pt; }
        .header { font-size: 11pt; font-weight: bold; margin-bottom: 10px; }
        .info-row { font-size: 8.5pt; margin-bottom: 8px; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { border: 1px solid black; padding: 3px 4px; }
        .data-table th { background-color: #ddd; font-size: 8pt; }
        .signature-table { width: 100%; margin-top: 15px; font-size: 8.5pt; }
        .signature-table td { text-align: center; vertical-align: top; padding-top: 10px; }
    </style>
</head>
<body>
    {{-- COP SEKOLAH --}}
    <table class="cop-table">
        <tr>
            <td style="width: 70px;">
                @if(isset($identity['school_logo']) && $identity['school_logo'])
                    <img src="{{ $identity['school_logo'] }}" style="width: 60px; height: 60px; object-fit: contain;">
                @endif
            </td>
            <td style="text-align: center;">
                <div class="cop-school-name">{{ $sekolah['nama'] ?? 'NAMA SEKOLAH' }}</div>
                <div class="cop-address">
                    {{ $sekolah['alamat_jalan'] ?? '' }} {{ $sekolah['desa_kelurahan'] ?? '' }},
                    {{ $sekolah['kecamatan'] ?? '' }}, {{ $sekolah['kabupaten_kota'] ?? '' }}<br>
                    NPSN: {{ $sekolah['npsn'] ?? '-' }} | Kode Registrasi: {{ $identity['koreg_unik'] ?? '-' }}
                </div>
            </td>
        </tr>
    </table>

    <div class="header text-center">
        LEGER NILAI RAPOR — {{ $rombelData['nama'] ?? '-' }}<br>
        <span style="font-size: 9pt; font-weight: normal;">
            Semester: {{ $identity['semester'] ?? 'Ganjil' }} &nbsp;|&nbsp; Tahun Pelajaran: {{ $identity['tahun_pelajaran'] ?? '-' }}
        </span>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2" width="3%">No</th>
                <th rowspan="2" width="18%">Nama Siswa</th>
                <th rowspan="2" width="8%">NISN</th>
                <th colspan="{{ count($mapels) }}">Nilai Mata Pelajaran</th>
                <th rowspan="2" width="5%">Rata2</th>
            </tr>
            <tr>
                @foreach($mapels as $id => $name)
                    <th style="font-size: 6.5pt; writing-mode: vertical-lr; transform: rotate(180deg); height: 70px; padding: 3px 1px;">{{ $name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($siswaDalamRombel as $index => $siswa)
                @php
                    $pdId  = $siswa['peserta_didik_id'] ?? '';
                    $total = 0; $count = 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $siswa['nama'] ?? 'ID: '.substr($pdId, 0, 8) }}</td>
                    <td class="text-center" style="font-size: 7.5pt;">{{ $siswa['nisn'] ?? '-' }}</td>
                    @foreach($mapels as $id => $name)
                        @php
                            $n = $grades[$pdId][$id]->nilai_akhir ?? 0;
                            if ($n > 0) { $total += $n; $count++; }
                        @endphp
                        <td class="text-center">{{ $n ?: '-' }}</td>
                    @endforeach
                    <td class="text-center"><b>{{ $count > 0 ? round($total / $count, 1) : '-' }}</b></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <table class="signature-table">
        <tr>
            <td width="33%">
                Mengetahui,<br>Kepala Sekolah,<br>
                <div style="height: 50px; margin: 5px 0; display: flex; align-items: center; justify-content: center;">
                    @if(isset($identity['headmaster_signature']) && $identity['headmaster_signature'])
                        <img src="{{ $identity['headmaster_signature'] }}" style="height: 50px; max-width: 130px;">
                    @endif
                </div>
                <span style="text-decoration: underline; font-weight: bold;">{{ $identity['headmaster_name'] ?? '............................' }}</span><br>
                NIP. {{ $identity['headmaster_nip'] ?? '-' }}
            </td>
            <td width="34%"></td>
            <td width="33%">
                {{ $sekolah['kabupaten_kota'] ?? '..........' }}, {{ \Carbon\Carbon::parse($identity['titimangsa_rapor'] ?? date('Y-m-d'))->locale('id')->translatedFormat('d F Y') }}<br>
                Wali Kelas,<br><br><br><br><br>
                <span style="text-decoration: underline; font-weight: bold;">{{ $rombelData['ptk_id_str'] ?? '............................' }}</span><br>
                NIP. {{ !empty($rombelData['ptk_nip']) ? $rombelData['ptk_nip'] : '-' }}
            </td>
        </tr>
    </table>
</body>
</html>
