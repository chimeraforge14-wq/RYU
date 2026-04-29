<?php
$content = file_get_contents('storage/app/private/dapodik_data.json');
$data = json_decode($content, true);

$targetRombel = "2.B";
$rombelId = "";
$studentsInRombel = [];

foreach ($data['rombonganBelajar'] as $r) {
    if ($r['nama'] == $targetRombel) {
        $rombelId = $r['rombongan_belajar_id'];
        $studentsInRombel = $r['anggota_rombel'];
        break;
    }
}

if (!$rombelId) {
    echo "Rombel $targetRombel tidak ditemukan.\n";
    exit;
}

$pdMap = [];
foreach ($data['pesertaDidik'] as $pd) {
    $pdMap[$pd['peserta_didik_id']] = $pd['nama'];
}

echo "DAFTAR SISWA KELAS $targetRombel DI DATABASE:\n";
foreach ($studentsInRombel as $idx => $s) {
    $id = $s['peserta_didik_id'];
    $nama = $pdMap[$id] ?? "UNDEFINED (ID: $id)";
    echo ($idx + 1) . ". $nama\n";
}
