<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 0);

if (!isset($_SESSION['user_id'])) {
	echo json_encode([
		"success" => false,
		"message" => "Not logged in."
	]);
	exit();
}

require_once("../backend/db_connect.php");
require_once("../config/spoonacular.php");

$user_id = $_SESSION['user_id'];
$ingredients = [];

if (isset($_GET['ingredients']) && trim($_GET['ingredients']) !== "") {
	$ingredients = array_map('trim', explode(",", $_GET['ingredients']));
} else {
	$sql = "SELECT item_name FROM food_items WHERE user_id = ? AND status != 'used'";
	$stmt = $conn->prepare($sql);

	if (!$stmt) {
		echo json_encode([
			"success" => false,
			"message" => "Database prepare failed.",
			"error" => $conn->error
		]);
		exit();
	}

	$stmt->bind_param("i", $user_id);
	$stmt->execute();

	$result = $stmt->get_result();

	while ($row = $result->fetch_assoc()) {
		$ingredients[] = $row['item_name'];
	}

	$stmt->close();
}

if (empty($ingredients)) {
	echo json_encode([
		"success" => false,
		"message" => "No ingredients available."
	]);
	exit();
}

$ingredientString = implode(",", $ingredients);

$url = "https://api.spoonacular.com/recipes/findByIngredients"
	. "?ingredients=" . urlencode($ingredientString)
	. "&number=10"
	. "&ranking=1"
	. "&ignorePantry=true"
	. "&apiKey=" . urlencode($spoonacular_api_key);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
	echo json_encode([
		"success" => false,
		"message" => "API request failed.",
		"error" => curl_error($ch)
	]);
	curl_close($ch);
	exit();
}

curl_close($ch);

$data = json_decode($response, true);

if ($httpCode !== 200) {
	echo json_encode([
		"success" => false,
		"message" => "Recipe API returned an error.",
		"status_code" => $httpCode,
		"api_response" => $data
	]);
	exit();
}

echo json_encode([
	"success" => true,
	"ingredients_used" => $ingredients,
	"recipes" => $data
]);
