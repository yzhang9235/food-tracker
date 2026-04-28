<?php
session_start();
header('Content-Type: application/json');

// stop here if the user is not logged in
if (!isset($_SESSION['user_id'])) {
	echo json_encode([
		"success" => false,
		"message" => "Not logged in."
	]);
	exit();
}

// load database connection and Spoonacular API key
require_once("../backend/db_connect.php");
require_once("../config/spoonacular.php");

$user_id = $_SESSION['user_id'];
$ingredients = [];

// if user typed ingredients manually, use those first
if (isset($_GET['ingredients']) && trim($_GET['ingredients']) !== "") {
	$ingredients = array_map('trim', explode(",", $_GET['ingredients']));
} else {
	try {
		// otherwise, pull ingredient names from this user's inventory
		$sql = "SELECT item_name FROM food_items WHERE user_id = :user_id AND status != 'used'";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(['user_id' => $user_id]);

		// fetch just the item_name column into an array
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

// if there are no ingredients, stop before calling the recipe API
if (empty($ingredients)) {
	echo json_encode([
		"success" => false,
		"message" => "No ingredients available."
	]);
	exit();
}

$ingredientString = implode(",", $ingredients);

// build the Spoonacular request URL
$url = "https://api.spoonacular.com/recipes/findByIngredients"
	. "?ingredients=" . urlencode($ingredientString)
	. "&number=6"             // limit results to 6 recipes
	. "&ranking=1"            // let Spoonacular rank the recipes
	. "&ignorePantry=true"    // ignore common pantry items in matching
	. "&apiKey=" . urlencode($spoonacular_api_key);

// initialize cURL request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return response as a string
curl_setopt($ch, CURLOPT_TIMEOUT, 15);          // stop if request takes too long

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// handle network/cURL-level errors
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
?>
