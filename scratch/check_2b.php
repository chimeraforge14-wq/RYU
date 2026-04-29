<?php
require 'vendor/autoload.php';
$content = file_get_contents('storage/app/dapodik_data.json');
$data = json_decode($content, true);

$rombel2B = null;
foreach ($data['rombonganBelajar'] as $r) {
    if ($r['nama'] === '2.B') {
        $rombel2B = $r;
        break;
    }
}

if (!$rombel2B) {
    echo "Rombel 2.B tidak ditemukan.\n";
    exit;
}

$rombelId = $rombel2B['rombongan_belajar_id'];
$anggotaIds = [];
foreach ($rombel2B['anggota_rombel'] as $ar) {
    $anggotaIds[] = $ar['peserta_didik_id'];
}

$siswaMap = [];
foreach ($data['pesertaDidik'] as $s) {
    $siswaMap[$s['peserta_didik_id']] = $s;
}

echo "DAFTAR SISWA KELAS 2.B (DAPODIK):\n";
echo "----------------------------------\n";
$i = 1;
foreach ($anggotaIds as $id) {
    if (isset($siswaMap[$id])) {
        $s = $siswaMap[$id];
        echo $i++ . ". " . $s['nama'] . " (" . $s['jenis_kelamin'] . ") - NISN: " . ($s['nisn'] ?? '-') . "\n";
    } else {
        echo $i++ . ". ID: $id (Data Master Tidak Ditemukan)\n";
    }
}
