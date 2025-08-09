<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$apiKey = 'YOUR_API_KEY';
$customerNumber = 'YOUR_CUSTOMER_NO';

$payload = [
  "fromPostalCode" => $input['fromPostalCode'],
  "toPostalCode" => $input['toPostalCode'],
  "countryCode" => $input['countryCode'],
  "packages" => [
    [
      "weightInKg" => $input['weight'],
      "lengthInCm" => $input['length'],
      "widthInCm" => $input['width'],
      "heightInCm" => $input['height']
    ]
  ],
  "product" => "SERVICEPAKKE",
  "customerNumber" => $customerNumber
];

$ch = curl_init();
curl_setopt_array($ch, [
  CURLOPT_URL => 'https://api.bring.com/shippingguide/api/shippingguide',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => [
    'Content-Type: application/json',
    "X-MyBring-API-Uid: $apiKey",
    "X-MyBring-API-Key: $apiKey"
  ],
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => json_encode($payload)
]);

$response = curl_exec($ch);
if (curl_errno($ch)) {
  echo json_encode(["error" => curl_error($ch)]);
  curl_close($ch);
  exit;
}
curl_close($ch);

echo $response;