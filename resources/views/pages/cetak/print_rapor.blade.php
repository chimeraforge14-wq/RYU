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

        /* Watermark */
        .watermark { position: fixed; top: 50%; left: 50%; transform: translate(-50%,-50%) rotate(-45deg); opacity: 0.06; z-index: -1000; width: 380px; }

        /* COP Sekolah */
        .cop-table { width: 100%; border-bottom: 3px solid black; margin-bottom: 12px; padding-bottom: 5px; }
        .cop-logo { width: 80px; }
        .cop-text { text-align: center; }
        .cop-school-name { font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .cop-address { font-size: 8.5pt; }

        .header-title { font-size: 12pt; margin-bottom: 12px; font-weight: bold; text-decoration: underline; }

        /* Layout Info */
        .info-table { width: 100%; margin-bottom: 8px; border-collapse: collapse; font-size: 9pt; }
        .info-table td { vertical-align: top; padding: 1.5px; }

        /* Data Nilai */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 4px; font-size: 9pt; }
        .data-table th, .data-table td { border: 1px solid black; padding: 3px 4px; }
        .data-table th { background-color: #e8e8e8; font-weight: bold; }

        /* Section title */
        .section-title { font-size: 9.5pt; font-weight: bold; margin-top: 12px; margin-bottom: 4px; border-left: 3px solid #333; padding-left: 5px; }

        /* Catatan & Absensi */
        .box { border: 1px solid black; padding: 6px; min-height: 45px; font-size: 8.5pt; }

        /* Tanda Tangan */
        .signature-table { width: 100%; margin-top: 15px; font-size: 9pt; page-break-inside: avoid; }
        .signature-table td { text-align: center; vertical-align: top; }
    </style>
</head>
<body>
    @if($identity['school_logo'])
        <img src="{{ $identity['school_logo'] }}" class="watermark">
    @endif

    {{-- COP SEKOLAH --}}
    <table class="cop-table">
        <tr>
            <td class="cop-logo">
                @if($identity['school_logo'] && function_exists('imagecreatefrompng'))
                    <img src="{{ $identity['school_logo'] }}" style="width: 70px;">
                @endif
            </td>
            <td class="cop-text">
                <div class="cop-school-name">{{ $sekolah['nama'] ?? 'NAMA SEKOLAH' }}</div>
                <div class="cop-address">
                    {{ $sekolah['alamat_jalan'] ?? '' }} {{ $sekolah['desa_kelurahan'] ?? '' }}<br>
                    {{ $sekolah['kecamatan'] ?? '' }}, {{ $sekolah['kabupaten_kota'] ?? '' }}<br>
                    NPSN: {{ !empty($sekolah['npsn']) ? $sekolah['npsn'] : '-' }}
                </div>
            </td>
        </tr>
    </table>

    <div class="header-title text-center">LAPORAN HASIL BELAJAR (RAPOR)</div>

    <table class="info-table">
        <tr>
            <td width="18%">Nama Siswa</td><td width="2%">:</td><td width="45%" class="text-bold">{{ $siswaData['nama'] ?? 'Siswa Tidak Ditemukan' }}</td>
            <td width="15%">Kelas</td><td width="2%">:</td><td width="18%">{{ $rombelData['nama'] ?? '-' }}</td>
        </tr>
        <tr>
            <td>NISN</td><td>:</td><td>{{ !empty($siswaData['nisn']) ? $siswaData['nisn'] : '-' }}</td>
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

    {{-- A. NILAI AKADEMIK --}}
    <div class="section-title">A. Muatan Pelajaran</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Mata Pelajaran</th>
                <th width="10%">Nilai</th>
                <th width="50%">Capaian Kompetensi</th>
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
                    <td style="font-size: 8pt;">{!! $nilai ? strip_tags($nilai->deskripsi_capaian, '<b><strong>') : 'Belum ada data capaian.' !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- B. PROYEK P5 --}}
    @if(isset($p5Proyeks) && $p5Proyeks->count() > 0)
    <div class="section-title">B. Projek Penguatan Profil Pelajar Pancasila (P5)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="40%">Nama Projek</th>
                <th width="15%">Nilai</th>
                <th width="40%">Catatan Proses</th>
            </tr>
        </thead>
        <tbody>
            @php $noP5 = 1; $adaP5 = false; @endphp
            @foreach($p5Proyeks as $proyekId => $proyek)
                @php $penilaian = $p5Penilaians[$proyekId] ?? null; @endphp
                @if($penilaian)
                    @php $adaP5 = true; @endphp
                    <tr>
                        <td class="text-center">{{ $noP5++ }}</td>
                        <td>{{ $proyek->nama_proyek ?? '-' }}</td>
                        <td class="text-center text-bold">
                            @php
                                $nilaiP5 = strtoupper($penilaian->nilai ?? '');
                                $labelMap = ['BB' => 'BB', 'MB' => 'MB', 'BSH' => 'BSH', 'SB' => 'SB'];
                                echo $labelMap[$nilaiP5] ?? $nilaiP5 ?: '-';
                            @endphp
                        </td>
                        <td style="font-size: 8pt;">{{ $penilaian->catatan_proses ?? '-' }}</td>
                    </tr>
                @endif
            @endforeach
            @if(!$adaP5)
                <tr><td colspan="4" class="text-center" style="color: #666; font-style: italic;">Belum ada penilaian P5</td></tr>
            @endif
        </tbody>
    </table>
    <div style="font-size: 7.5pt; margin-top: 3px; color: #555;">
        Keterangan: BB = Belum Berkembang &nbsp;|&nbsp; MB = Mulai Berkembang &nbsp;|&nbsp; BSH = Berkembang Sesuai Harapan &nbsp;|&nbsp; SB = Sangat Berkembang
    </div>
    @endif

    {{-- C. ABSENSI & CATATAN --}}
    <div class="section-title">C. Catatan & Kehadiran</div>
    <table style="width: 100%; font-size: 9pt; border-collapse: collapse;">
        <tr>
            <td width="38%" style="vertical-align: top;">
                <table class="data-table" style="margin-top: 0;">
                    <tr style="background: #e8e8e8;"><td colspan="2" class="text-center"><b>Ketidakhadiran</b></td></tr>
                    <tr><td width="65%">Sakit</td><td class="text-center">{{ $pelengkap->sakit ?? 0 }} hari</td></tr>
                    <tr><td>Izin</td><td class="text-center">{{ $pelengkap->izin ?? 0 }} hari</td></tr>
                    <tr><td>Alpa</td><td class="text-center">{{ $pelengkap->tanpa_keterangan ?? 0 }} hari</td></tr>
                </table>
            </td>
            <td width="4%"></td>
            <td width="58%" style="vertical-align: top;">
                <b>Catatan Wali Kelas:</b>
                <div class="box" style="margin-top: 3px;">{{ $pelengkap->catatan_wali_kelas ?? '-' }}</div>
            </td>
        </tr>
    </table>

    {{-- D. KOKURIKULER --}}
    @if(isset($kokurikulerGroups) && $kokurikulerGroups->count() > 0)
    @php
        $adaKokurikuler = false;
        foreach($kokurikulerGroups as $kg) {
            foreach($kg->activities as $ka) {
                if(isset($kokurikulerNilai[$ka->id])) { $adaKokurikuler = true; break 2; }
            }
        }
    @endphp
    @if($adaKokurikuler)
    <div class="section-title">D. Kegiatan Kokurikuler</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Kegiatan</th>
                <th width="20%">Tema / Dimensi</th>
                <th width="10%">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @php $noK = 1; @endphp
            @foreach($kokurikulerGroups as $kg)
                @foreach($kg->activities as $ka)
                    @if(isset($kokurikulerNilai[$ka->id]))
                    <tr>
                        <td class="text-center">{{ $noK++ }}</td>
                        <td>{{ $kg->name }} — {{ $ka->activity_name }}</td>
                        <td style="font-size: 7.5pt;">{{ $ka->theme }}</td>
                        <td class="text-center text-bold">{{ $kokurikulerNilai[$ka->id]->nilai ?? '-' }}</td>
                    </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
    <div style="font-size: 7.5pt; margin-top: 3px; color: #555;">
        Keterangan: BB = Belum Berkembang &nbsp;|&nbsp; MB = Mulai Berkembang &nbsp;|&nbsp; BSH = Berkembang Sesuai Harapan &nbsp;|&nbsp; SB = Sangat Berkembang
    </div>
    @endif
    @endif

    {{-- TANDA TANGAN --}}
    <table class="signature-table">
        <tr>
            <td width="33%">
                Mengetahui,<br>Orang Tua/Wali<br><br><br><br><br>
                ..........................................
            </td>
            <td width="34%"></td>
            <td width="33%">
                {{ $sekolah['kabupaten_kota'] ?? '..........' }}, {{ \Carbon\Carbon::parse($identity['titimangsa_rapor'] ?? date('Y-m-d'))->locale('id')->translatedFormat('d F Y') }}<br>
                Wali Kelas,<br>
                <div style="height: 60px; margin: 5px 0; display: flex; align-items: center; justify-content: center;">
                    @if(isset($waliKelasSignature) && $waliKelasSignature && function_exists('imagecreatefrompng'))
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
            <td colspan="3" style="padding-top: 15px;">
                Mengetahui,<br>Kepala Sekolah,<br>
                <div style="height: 60px; margin: 5px 0; display: flex; align-items: center; justify-content: center;">
                    @if(isset($identity['headmaster_signature']) && $identity['headmaster_signature'] && function_exists('imagecreatefrompng'))
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
