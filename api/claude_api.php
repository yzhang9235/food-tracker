<?php
// make sure everything is in json format
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');


//get image from frontend
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);
if (!$data || !isset($data["image"])) {
    echo json_encode([
        "success" => false,
        "error" => "No image received"
    ]);
    exit;
}
$image = $data["image"];
// delete base64 header
$image = str_replace("data:image/png;base64,", "", $image);

// change to the format that claude can understand
$payload = [
  "model" => "claude-sonnet-4-5",
  "max_tokens" => 300,
  "messages" => [
    [
      "role" => "user",
      "content" => [
        [
          "type" => "image",
          "source" => [
            "type" => "base64",
            "media_type" => "image/png",
            "data" => $image
          ]
        ],
        [
          "type" => "text",
          "text" => "Identify the food in the image.
Return ONLY valid JSON in this format:
{
  \"name\": string,
  \"category\": string,
  \"estimated_expiration_days\": number
}
Assume typical storage at fridge.
Do not include any extra text."
        ]
      ]
    ]
  ]
];


// get api key from .env
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

$apiKey = $_ENV['ANTHROPIC_API_KEY'] ?? null;

error_reporting(E_ALL);

$ch = curl_init("https://api.anthropic.com/v1/messages");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "x-api-key: " . $apiKey,
    "anthropic-version: 2023-06-01"
]);

//sent request to claude
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

// get json return from claude
$response = curl_exec($ch);

$purchaseDate = date('Y-m-d'); 
$expirationDate = date('Y-m-d', strtotime('+' . $foodData['estimated_expiration_days'] . ' days'));
echo json_encode([
    "success" => true,
    "name" => $foodData['name'],
    "category" => $foodData['category'],
    "expiration_date" => $expirationDate,
    "purchase_date" => $purchaseDate
]);


//curl_close($ch);

echo $response;

