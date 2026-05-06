<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ds = app(\App\Services\DapodikService::class);
$rombonganBelajar = $ds->getRombonganBelajar();
if (empty($rombonganBelajar)) {
    echo "No rombongan_belajar data found.\n";
    exit;
}

$rombel = $rombonganBelajar[0];
echo "Checking Mapel for Rombel: " . $rombel['nama'] . "\n";
if (isset($rombel['pembelajaran'])) {
    foreach ($rombel['pembelajaran'] as $p) {
        $namaMapel = $p['nama_mata_pelajaran'] ?? $p['mata_pelajaran_id_str'] ?? '';
        echo "- " . $namaMapel . "\n";
    }
} else {
    echo "No 'pembelajaran' key in this rombel.\n";
}

