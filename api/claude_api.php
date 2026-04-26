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

$ch = curl_init("https://api.anthropic.com/v1/messages");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "x-api-key: secrete",
    "anthropic-version: 2023-06-01"
]);

//sent request to claude
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

// get json return from claude
$response = curl_exec($ch);
if (!$response) {
    echo json_encode([
        "success" => false,
        "error" => "Claude API failed"
    ]);
    exit;
}

//curl_close($ch);

echo $response;

