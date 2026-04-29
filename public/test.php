<?php
$token = 'CWecbcR0fSMzMi3';
$npsn = '20236167';
$semester = '20241'; // we will try 20241, 20231, 20232

$urls = [
    'getRombonganBelajar' => "http://localhost:5774/WebService/getRombonganBelajar?npsn=$npsn&semester_id=$semester"
];

foreach ($urls as $name => $url) {
    echo "--- $name ---\n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token"
    ]);
    
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    echo "HTTP CODE: $httpcode\n";
    if ($error) {
        echo "CURL ERROR: $error\n";
    }
    
    $data = json_decode($response, true);
    if ($data !== null) {
        if (isset($data['rows'])) {
            echo "ROWS COUNT: " . count($data['rows']) . "\n";
            if (count($data['rows']) > 0) {
                echo "FIRST ROW KEYS: " . implode(', ', array_keys($data['rows'][0])) . "\n";
            }
        } else {
            echo "JSON HAS NO ROWS. RAW: " . substr($response, 0, 200) . "\n";
        }
    } else {
        echo "NOT JSON. RAW: " . substr($response, 0, 200) . "\n";
    }
    echo "\n";
}
