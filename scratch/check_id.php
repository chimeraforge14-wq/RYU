<?php
$content = file_get_contents('storage/app/private/dapodik_data.json');
$data = json_decode($content, true);
$targetId = '6f93d3d2-dfb2-4cfe-a729-0d1ec7f77ccb';

$foundMaster = false;
foreach ($data['pesertaDidik'] ?? [] as $pd) {
    if ($pd['peserta_didik_id'] === $targetId) {
        $foundMaster = true;
        echo "FOUND in pesertaDidik: " . $pd['nama'] . "\n";
        break;
    }
}

if (!$foundMaster) {
    echo "NOT FOUND in pesertaDidik\n";
}

foreach ($data['rombonganBelajar'] ?? [] as $rb) {
    foreach ($rb['anggota_rombel'] ?? [] as $ar) {
        if ($ar['peserta_didik_id'] === $targetId) {
            echo "FOUND in rombel " . $rb['nama'] . "\n";
        }
    }
}
