<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Leger_{{ $rombelData['nama'] ?? 'Kelas' }}</title>
    <style>
        @page { margin: 1cm; size: A4 landscape; }
        body { font-family: 'Arial', sans-serif; font-size: 9pt; }
        .text-center { text-align: center; }
        .header { font-size: 12pt; font-weight: bold; margin-bottom: 15px; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { border: 1px solid black; padding: 4px; }
        .data-table th { background-color: #eee; }
        .signature-table { width: 100%; margin-top: 20px; }
        .signature-table td { width: 50%; text-align: center; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header text-center">
        LEGER NILAI RAPOR - {{ $rombelData['nama'] ?? '-' }}<br>
        {{ $sekolah['nama'] ?? 'SEKOLAH' }}
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2" width="3%">No</th>
                <th rowspan="2" width="20%">Nama Siswa</th>
                <th colspan="{{ count($mapels) }}">Mata Pelajaran</th>
                <th rowspan="2" width="5%">Rata2</th>
            </tr>
            <tr>
                @foreach($mapels as $id => $name)
                    <th style="font-size: 7pt;">{{ $name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($siswaDalamRombel as $index => $siswa)
                @php
                    $pdId = $siswa['peserta_didik_id'] ?? '';
                    $total = 0; $count = 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $siswa['nama'] ?? 'ID: '.substr($pdId, 0, 8) }}</td>
                    @foreach($mapels as $id => $name)
                        @php
                            $n = $grades[$pdId][$id]->nilai_akhir ?? 0;
                            if($n > 0) { $total += $n; $count++; }
                        @endphp
                        <td class="text-center">{{ $n ?: '-' }}</td>
                    @endforeach
                    <td class="text-center"><b>{{ $count > 0 ? round($total/$count, 1) : '-' }}</b></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td>Kepala Sekolah<br><br><br><br>________________</td>
            <td>Wali Kelas<br><br><br><br>________________</td>
        </tr>
    </table>
</body>
</html>
