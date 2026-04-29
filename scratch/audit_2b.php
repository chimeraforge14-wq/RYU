<?php
$content = file_get_contents('storage/app/private/dapodik_data.json');
$data = json_decode($content, true);

$physicalList = [
    "ABRAHAM MOSES", "ADAM ABDILLAH SANTOSO", "AHMAD PRAYITNO", "Amoureyza Ashera Panjaitan",
    "ANDRA PUTRA PRATAMA", "ARFAN FADILAH MAULANA", "BILAL AUSHAF", "DWI RAHAYU",
    "FAJAR ARYA WIBOWO", "FIKRI GUNA SETIAWAN", "KHANZA SAATHYA BAGGIS", "KHAYLA NADHIFAH",
    "LARAS SEPTIANI", "MARLINA PUTRI", "MUHAMMAD ELVAN FAHREZA", "Muhammad Latif Abdullah",
    "MUHAMMAD LUTVI SANJAYA", "MUHAMMAD RAFFA EDITA PRAT", "MUHAMMAD RIZKY FADILAH",
    "NAURA IZZATUNNISA PRATAMA", "RADITYA RIFQIE NUGRAHA", "RASYIQUL AKMAL ADHA",
    "REZA FATHUR RAHMAN", "RIANI SHABRINA ANJANI", "SALSABILA APRILIANI", "VIVIAN OLIVIA ALMIRA"
];

$semuaSiswa = $data['pesertaDidik'] ?? [];
$foundNames = [];
foreach ($semuaSiswa as $s) {
    $foundNames[] = strtoupper(trim($s['nama']));
}

echo "HASIL AUDIT DATA SISWA KELAS 2B:\n";
echo "================================\n";

$missing = [];
foreach ($physicalList as $name) {
    if (!in_array(strtoupper(trim($name)), $foundNames)) {
        $missing[] = $name;
    }
}

if (empty($missing)) {
    echo "SEMUA 26 SISWA DITEMUKAN DI DATABASE.\n";
} else {
    echo count($missing) . " SISWA TIDAK DITEMUKAN DI DATABASE:\n";
    foreach ($missing as $m) {
        echo "- $m\n";
    }
}

echo "\nCONTOH 10 NAMA SISWA YANG ADA DI DATABASE:\n";
foreach (array_slice($foundNames, 0, 10) as $n) {
    echo "- $n\n";
}
