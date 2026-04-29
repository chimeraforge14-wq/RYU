<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor_{{ $siswaData['nama'] ?? 'Siswa' }}</title>
    <style>
        @page { margin: 1cm; size: A4 portrait; }
        body { font-family: 'Times New Roman', serif; font-size: 11pt; line-height: 1.3; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .header { font-size: 14pt; margin-bottom: 20px; font-weight: bold; }
        
        /* Layout Info menggunakan Tabel (Pengganti Flex) */
        .info-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .info-table td { vertical-align: top; padding: 2px; }
        
        /* Data Nilai */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid black; padding: 5px; }
        .data-table th { background-color: #f0f0f0; }

        /* Catatan & Absensi menggunakan tabel agar sejajar */
        .box-table { width: 100%; margin-top: 20px; }
        .box { border: 1px solid black; padding: 10px; height: 80px; }

        /* Tanda Tangan */
        .signature-table { width: 100%; margin-top: 30px; }
        .signature-table td { width: 33%; text-align: center; vertical-align: top; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header text-center">
        LAPORAN HASIL BELAJAR (RAPOR)
    </div>

    <table class="info-table">
        <tr>
            <td width="18%">Nama Siswa</td><td width="2%">:</td><td width="45%" class="text-bold">{{ $siswaData['nama'] ?? 'Siswa Tidak Ditemukan' }}</td>
            <td width="15%">Kelas</td><td width="2%">:</td><td width="18%">{{ $rombelData['nama'] ?? '-' }}</td>
        </tr>
        <tr>
            <td>NISN</td><td>:</td><td>{{ $siswaData['nisn'] ?? '-' }}</td>
            <td>Fase</td><td>:</td><td>B</td>
        </tr>
        <tr>
            <td>Sekolah</td><td>:</td><td>{{ $sekolah['nama'] ?? '-' }}</td>
            <td>Semester</td><td>:</td><td>Ganjil</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Mata Pelajaran</th>
                <th width="10%">Nilai</th>
                <th width="55%">Capaian Kompetensi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($mapels as $id => $name)
                @php $nilai = $nilaiDb[$id] ?? null; @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $name }}</td>
                    <td class="text-center text-bold">{{ $nilai ? $nilai->nilai_akhir : '-' }}</td>
                    <td style="font-size: 9pt;">
                        {!! $nilai ? strip_tags($nilai->deskripsi_capaian, '<b><strong>') : 'Belum ada data capaian.' !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="box-table">
        <tr>
            <td width="40%" style="vertical-align: top;">
                <table class="data-table" style="margin-top: 0;">
                    <tr style="background: #eee;"><td colspan="2" class="text-center"><b>Ketidakhadiran</b></td></tr>
                    <tr><td width="60%">Sakit</td><td class="text-center">{{ $pelengkap->sakit ?? 0 }} hari</td></tr>
                    <tr><td>Izin</td><td class="text-center">{{ $pelengkap->izin ?? 0 }} hari</td></tr>
                    <tr><td>Alpa</td><td class="text-center">{{ $pelengkap->tanpa_keterangan ?? 0 }} hari</td></tr>
                </table>
            </td>
            <td width="5%"></td>
            <td width="55%" style="vertical-align: top;">
                <b>Catatan Wali Kelas:</b>
                <div class="box" style="font-size: 9pt;">
                    {{ $pelengkap->catatan_wali_kelas ?? '-' }}
                </div>
            </td>
        </tr>
    </table>

    <table class="signature-table">
        <tr>
            <td width="33%">
                Mengetahui,<br>Orang Tua/Wali<br><br><br><br><br><br>
                ..........................................
            </td>
            <td width="33%" style="position: relative; vertical-align: top;">
                Kepala Sekolah,<br>
                <div style="height: 80px; margin-top: 5px; margin-bottom: 5px; display: flex; align-items: center; justify-content: center;">
                    @if($identity['headmaster_signature'])
                        <img src="{{ $identity['headmaster_signature'] }}" style="height: 70px; max-width: 150px;">
                    @else
                        <br><br><br>
                    @endif
                </div>
                <span class="text-bold" style="text-decoration: underline;">{{ $identity['headmaster_name'] }}</span><br>
                NIP. {{ $identity['headmaster_nip'] }}
            </td>
            <td width="33%" style="vertical-align: top;">
                .........., ........... 2026<br>
                Wali Kelas,<br><br><br><br><br><br>
                <span class="text-bold" style="text-decoration: underline;">{{ $rombelData['ptk_id_str'] ?? '..........................................' }}</span><br>
                NIP. {{ $rombelData['ptk_nip'] ?? '-' }}
            </td>
        </tr>
    </table>
</body>
</html>
 </table>

    <table class="signature-table">
        <tr>
            <td>Orang Tua/Wali<br><br><br><br>________________</td>
            <td>Kepala Sekolah<br><br><br><br>________________</td>
            <td>Wali Kelas<br><br><br><br>________________</td>
        </tr>
    </table>
</body>
</html>
