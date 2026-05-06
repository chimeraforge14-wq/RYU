<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor_{{ $siswaData['nama'] ?? 'Siswa' }}</title>
    <style>
        @page { margin: 1cm; size: A4 portrait; }
        body { font-family: 'Times New Roman', serif; font-size: 10pt; line-height: 1.3; margin: 0; padding: 0; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .text-uppercase { text-transform: uppercase; }
        
        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.1;
            z-index: -1000;
            width: 400px;
        }

        /* COP Sekolah */
        .cop-table { width: 100%; border-bottom: 3px solid black; margin-bottom: 15px; padding-bottom: 5px; }
        .cop-logo { width: 80px; }
        .cop-text { text-align: center; }
        .cop-school-name { font-size: 16pt; font-weight: bold; }
        .cop-address { font-size: 9pt; }

        .header-title { font-size: 12pt; margin-bottom: 15px; font-weight: bold; text-decoration: underline; }
        
        /* Layout Info */
        .info-table { width: 100%; margin-bottom: 10px; border-collapse: collapse; font-size: 9pt; }
        .info-table td { vertical-align: top; padding: 1px; }
        
        /* Data Nilai */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 5px; font-size: 9pt; }
        .data-table th, .data-table td { border: 1px solid black; padding: 4px; }
        .data-table th { background-color: #f0f0f0; }

        /* Catatan & Absensi */
        .box-table { width: 100%; margin-top: 15px; font-size: 9pt; }
        .box { border: 1px solid black; padding: 8px; height: 60px; }

        /* Tanda Tangan */
        .signature-table { width: 100%; margin-top: 20px; font-size: 9pt; page-break-inside: avoid; }
        .signature-table td { text-align: center; vertical-align: top; }
    </style>
</head>
<body>
    @if($identity['school_logo'])
        <img src="{{ $identity['school_logo'] }}" class="watermark">
    @endif

    <!-- COP SEKOLAH -->
    <table class="cop-table">
        <tr>
            <td class="cop-logo">
                @if($identity['school_logo'])
                    <img src="{{ $identity['school_logo'] }}" style="width: 70px;">
                @endif
            </td>
            <td class="cop-text">
                <div class="cop-school-name">{{ $sekolah['nama'] ?? 'NAMA SEKOLAH' }}</div>
                <div class="cop-address">
                    {{ $sekolah['alamat_jalan'] ?? '' }} {{ $sekolah['desa_kelurahan'] ?? '' }}<br>
                    {{ $sekolah['kecamatan'] ?? '' }}, {{ $sekolah['kabupaten_kota'] ?? '' }}<br>
                    NPSN: {{ !empty($sekolah['npsn']) ? $sekolah['npsn'] : '-' }} | Kode Registrasi: {{ !empty($identity['koreg_unik']) ? $identity['koreg_unik'] : '-' }}
                </div>
            </td>
        </tr>
    </table>

    <div class="header-title text-center">
        LAPORAN HASIL BELAJAR (RAPOR)
    </div>

    <table class="info-table">
        <tr>
            <td width="18%">Nama Siswa</td><td width="2%">:</td><td width="45%" class="text-bold">{{ $siswaData['nama'] ?? 'Siswa Tidak Ditemukan' }}</td>
            <td width="15%">Kelas</td><td width="2%">:</td><td width="18%">{{ $rombelData['nama'] ?? '-' }}</td>
        </tr>
        <tr>
            <td>NIS / NISN</td><td>:</td><td>{{ !empty($siswaData['nipd']) ? $siswaData['nipd'] : '-' }} / {{ !empty($siswaData['nisn']) ? $siswaData['nisn'] : '-' }}</td>
            <td>Fase</td><td>:</td><td>{{ !empty($rombelData['fase']) ? $rombelData['fase'] : '-' }}</td>
        </tr>
        <tr>
            <td>Sekolah</td><td>:</td><td>{{ $sekolah['nama'] ?? '-' }}</td>
            <td>Semester</td><td>:</td><td>{{ $identity['semester'] ?? 'Ganjil' }}</td>
        </tr>
        <tr>
            <td>Alamat Sekolah</td><td>:</td><td>{{ $sekolah['alamat_jalan'] ?? '-' }}</td>
            <td>Tahun Pelajaran</td><td>:</td><td>{{ $identity['tahun_pelajaran'] ?? '-' }}</td>
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
                    <td style="font-size: 8pt;">
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
                <div class="box" style="font-size: 8pt;">
                    {{ $pelengkap->catatan_wali_kelas ?? '-' }}
                </div>
            </td>
        </tr>
    </table>

    <table class="signature-table">
        <tr>
            <td width="33%">
                Mengetahui,<br>Orang Tua/Wali<br><br><br><br><br>
                ..........................................
            </td>
            <td width="33%"></td>
            <td width="33%">
                {{ $sekolah['kabupaten_kota'] ?? '..........' }}, {{ \Carbon\Carbon::parse($identity['titimangsa_rapor'] ?? date('Y-m-d'))->locale('id')->translatedFormat('d F Y') }}<br>
                Wali Kelas,<br>
                <div style="height: 60px; margin-top: 5px; margin-bottom: 5px; display: flex; align-items: center; justify-content: center;">
                    @if(isset($waliKelasSignature) && $waliKelasSignature)
                        <img src="{{ $waliKelasSignature }}" style="height: 60px; max-width: 150px;">
                    @else
                        <br><br><br>
                    @endif
                </div>
                <span class="text-bold" style="text-decoration: underline;">{{ $rombelData['ptk_id_str'] ?? '..........................................' }}</span><br>
                NIP. {{ !empty($rombelData['ptk_nip']) ? $rombelData['ptk_nip'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 20px;">
                Mengetahui,<br>Kepala Sekolah,<br>
                <div style="height: 60px; margin-top: 5px; margin-bottom: 5px; display: flex; align-items: center; justify-content: center;">
                    @if(isset($identity['headmaster_signature']) && $identity['headmaster_signature'])
                        <img src="{{ $identity['headmaster_signature'] }}" style="height: 60px; max-width: 150px;">
                    @else
                        <br><br><br>
                    @endif
                </div>
                <span class="text-bold" style="text-decoration: underline;">{{ $identity['headmaster_name'] ?? '..........................................' }}</span><br>
                NIP. {{ !empty($identity['headmaster_nip']) ? $identity['headmaster_nip'] : '-' }}
            </td>
        </tr>
    </table>
</body>
</html>
