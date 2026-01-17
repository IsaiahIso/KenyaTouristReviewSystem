<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

if (!isset($_GET['q']) || empty($_GET['q'])) {
    echo json_encode([]);
    exit;
}

$query = urlencode($_GET['q'] . ', Kenya');

$url = "https://nominatim.openstreetmap.org/search?format=json&q=$query";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// REQUIRED by OpenStreetMap
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "User-Agent: TouristReviewSystem/1.0 (contact@localhost)"
]);

$response = curl_exec($ch);

if ($response === false) {
    echo json_encode([
        "error" => curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}

curl_close($ch);

echo $response;
