<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');

// Read raw POST body
$raw = file_get_contents("php://input");
// Decode JSON input into PHP associative array
$data = json_decode($raw, true);

// Ensure image exists
if (!$data || !isset($data["image"])) {
    echo json_encode([
        "success" => false,
        "error" => "No image received"
    ]);
    exit;
}

$image = $data["image"];

// Remove base64 header prefix
$image = preg_replace('#^data:image/\w+;base64,#i', '', $image);

// Load environment variables (API key stored in .env file)
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Get API key from environment variables
if (isset($_ENV['ANTHROPIC_API_KEY'])) {
    $apiKey = $_ENV['ANTHROPIC_API_KEY'];
} else {
    $apiKey = null;
}

// Stop if API key is missing
if (!$apiKey) {
    echo json_encode([
        "success" => false,
        "error" => "Missing API key"
    ]);
    exit;
}

// Build request payload for Claude API
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
                        "media_type" => "image/jpeg",
                        "data" => $image
                    ]
                ],
                [
                    "type" => "text",
                    "text" => 'Identify the food in the image. Return ONLY valid JSON in this exact format:
{
  "name": string,
  "category": string,
  "estimated_expiration_days": number
}
Use one category from this list when possible: produce, dairy, meat, pantry, frozen, other.
Do not include markdown fences or extra text.'
                ]
            ]
        ]
    ]
];

// Initialize cURL request to Claude API
$ch = curl_init("https://api.anthropic.com/v1/messages");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

// Set HTTP headers including API key
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "x-api-key: " . $apiKey,
    "anthropic-version: 2023-06-01"
]);

// Attach JSON payload
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
if ($response === false) {
    echo json_encode([
        "success" => false,
        "error" => "API request failed: " . curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Check if request was successful
if ($httpCode < 200 || $httpCode >= 300) {
    echo json_encode([
        "success" => false,
        "error" => "Claude API returned HTTP " . $httpCode,
        "raw" => $response
    ]);
    exit;
}

// Decode API response to JSON
$decoded = json_decode($response, true);
// Extract text response from Claude output
$text = $decoded["content"][0]["text"] ?? "";

// Validate response content
if (!$text) {
    echo json_encode([
        "success" => false,
        "error" => "No usable response from API",
        "raw" => $response
    ]);
    exit;
}

// parse the JSON text returned by Claude
$text = trim($text);

// remove markdown code fences if Claude returns ```json ... ``` 
$text = preg_replace('/^```json\s*/i', '', $text);
$text = preg_replace('/^```\s*/', '', $text);
$text = preg_replace('/\s*```$/', '', $text);

// parse cleand JSON string
$foodData = json_decode(trim($text), true);

if (!$foodData || !isset($foodData["name"])) {
    echo json_encode([
        "success" => false,
        "error" => "Could not parse Claude response",
        "raw_text" => $text
    ]);
    exit;
}

// Process expiration estimation
if (isset($foodData["estimated_expiration_days"])) {
    $days = (int)$foodData["estimated_expiration_days"];
} else {
    $days = 7;
}

// Ensure expiration days is not negative
if ($days < 0) $days = 0;

// generate expiration date
$purchaseDate = date('Y-m-d');
$expirationDate = date('Y-m-d', strtotime('+' . $days . ' days'));

// Return structured JSON response
echo json_encode([
    "success" => true,
    "name" => $foodData["name"] ?? "",
    "category" => $foodData["category"] ?? "other",
    "estimated_expiration_days" => $days,
    "purchase_date" => $purchaseDate,
    "expiration_date" => $expirationDate
]);
?>
