<?php
session_start(); //start the session to access user login info
header("Content-Type: application/json");
require_once "db_connect.php"; //include database connection

//check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Not logged in."
    ]);
    exit();
}

//Only allow POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];

// remove white space
$item_name = trim($_POST['item_name'] ?? '');
$category = trim($_POST['category'] ?? '');
$quantity = isset($_POST['quantity']) && $_POST['quantity'] !== '' ? (int) $_POST['quantity'] : null;
$unit = trim($_POST['unit'] ?? '');
$expiration_date = !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null;

if ($item_name === '') {
    echo json_encode([
        "success" => false,
        "message" => "Item name is required."
    ]);
    exit();
}

//prepare SQL insert statement
$sql = "INSERT INTO food_items (user_id, item_name, category, quantity, expiration_date, status)
        VALUES (?, ?, ?, ?, ?, 'active')";

$stmt = $conn->prepare($sql);

//check if prepare failed
if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit();
}

// bine parameters to the SQL statement
$stmt->bind_param("ississ", $user_id, $item_name, $category, $quantity, $unit, $expiration_date);

// execute the statement
if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Item added successfully."
    ]);
} else {
    // return error if execution fails
    echo json_encode([
        "success" => false,
        "message" => "Insert failed: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
