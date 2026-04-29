<?php
$content = file_get_contents('storage/app/private/dapodik_data.json');
$data = json_decode($content, true);

echo "CONTOH 50 NAMA SISWA DI DATABASE:\n";
foreach (array_slice($data['pesertaDidik'], 0, 50) as $s) {
    echo "- " . $s['nama'] . " (NISN: " . ($s['nisn'] ?? '-') . ")\n";
}
