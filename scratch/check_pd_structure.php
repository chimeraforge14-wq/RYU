<?php
$content = file_get_contents('storage/app/private/dapodik_data.json');
$data = json_decode($content, true);
if (isset($data['pesertaDidik'][0])) {
    echo json_encode($data['pesertaDidik'][0], JSON_PRETTY_PRINT);
} else {
    echo "No pesertaDidik found";
}
