<?php
require_once '../../../utils.php';
require_once '../../../route.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Mybring-API-Uid, X-Mybring-API-Key, X-Bring-Client-URL");
header("Content-Type: application/json; charset=utf-8");

$postalCode = $_GET['postalCode'] ?? '';
$country = $_GET['country'] ?? 'NO'; // опціонально

if (!$postalCode) {
  http_response_code(400);
  echo json_encode(['error' => 'Postal code is required']);
  exit;
}

// 🔐 Заміни ці дані на свої
$mybringEmail = 'market@vippersenter.no';
$apiKey = 'b95286c9-e8ee-4010-9ce6-fbf23089bad0';
$clientUrl = 'PARCELS_NORWAY-00000000005';

// 📡 URL Bring API
$url = "https://api.bring.com/pickuppoint/api/pickuppoint?postalCode=$postalCode&country=$country";

// 🔄 Виконання запиту
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "X-Mybring-API-Uid: $mybringEmail",
  "X-Mybring-API-Key: $apiKey",
  "X-Bring-Client-URL: $clientUrl"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 🔁 Повертаємо результат
http_response_code($httpCode);
echo $response;
