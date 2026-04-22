<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
	echo json_encode([
		"success" => false,
		"message" => "Not logged in."
	]);
	exit();
}

require_once("../backend/db.php");
require_once("../config/spoonacular.php");

$user_id = $_SESSION['user_id'];
$ingredients = [];

if (isset($_GET['ingredients']) && trim($_GET['ingredients']) !== "") {
	$ingredients = array_map('trim', explode(",", $_GET['ingredients']));
} else {
	try {
		$sql = "SELECT item_name FROM food_items WHERE user_id = :user_id AND status != 'used'";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(['user_id' => $user_id]);
		$ingredients = $stmt->fetchAll(PDO::FETCH_COLUMN);
	} catch (PDOException $e) {
		echo json_encode([
			"success" => false,
			"message" => "Database error.",
			"error" => $e->getMessage()
		]);
		exit();
	}
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
	. "&number=6"
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
