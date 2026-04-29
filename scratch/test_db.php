<?php

$host = '202.10.42.212';
$port = '5432';
$dbname = 'erapor_dateng';
$user = 'erapor_dateng';
$pass = '1234';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    echo "Mencoba koneksi ke $host...\n";
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "KONEKSI BERHASIL!\n";
    
    // Cek tabel
    $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    $tables = $stmt->fetchAll();
    
    echo "Daftar Tabel:\n";
    foreach ($tables as $t) {
        echo "- " . $t['table_name'] . "\n";
    }

} catch (\PDOException $e) {
    echo "KONEKSI GAGAL: " . $e->getMessage() . "\n";
}
