<?php
$content = file_get_contents('storage/app/private/dapodik_data.json');
$data = json_decode($content, true);

$targetPdId = "6f93d3d2-dfb2-4cfe-a729-0d1ec7f77ccb";
$foundInPD = null;
$foundInRombel = [];

foreach ($data['pesertaDidik'] as $pd) {
    if ($pd['peserta_didik_id'] === $targetPdId) {
        $foundInPD = $pd;
        break;
    }
}

foreach ($data['rombonganBelajar'] as $r) {
    foreach ($r['anggota_rombel'] as $ar) {
        if ($ar['peserta_didik_id'] === $targetPdId) {
            $foundInRombel[] = [
                'rombel_nama' => $r['nama'],
                'rombel_jenis' => $r['jenis_rombel_str'] ?? 'Unknown',
                'data' => $ar
            ];
        }
    }
}

echo "HASIL PELACAKAN ID $targetPdId:\n";
echo "========================================\n";
if ($foundInPD) {
    echo "DATA MASTER (pesertaDidik):\n";
    print_r($foundInPD);
} else {
    echo "DATA MASTER TIDAK DITEMUKAN.\n";
}

echo "\nDATA DI ROMBEL:\n";
if (!empty($foundInRombel)) {
    print_r($foundInRombel);
} else {
    echo "TIDAK DITEMUKAN DI ROMBEL MANAPUN.\n";
}
