<?php
session_start();
header("Content-Type: application/json");
require_once "db_connect.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Not logged in."
    ]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
    exit();
}

$user_id = $_SESSION['user_id']; // Get logged-in user id from session

// Get info about the item
$item_id = (int) ($_POST['item_id'] ?? 0);
$item_name = trim($_POST['item_name'] ?? '');
$category = trim($_POST['category'] ?? '');
$quantity = isset($_POST['quantity']) && $_POST['quantity'] !== '' ? (int) $_POST['quantity'] : null;
$unit = trim($_POST['unit'] ?? '');
$expiration_date = !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null;

// Validate required fields
if ($item_id <= 0 || $item_name === '') {
    echo json_encode([
        "success" => false,
        "message" => "Missing required fields."
    ]);
    exit();
}

// SQL query to update item 
$sql = "UPDATE food_items
        SET item_name = ?, category = ?, quantity = ?, unit = ?, expiration_date = ?
        WHERE item_id = ? AND user_id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit();
}

// Bind parameters to SQL statement
$stmt->bind_param("ssissii", $item_name, $category, $quantity, $unit, $expiration_date, $item_id, $user_id);

// Execute update query
if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Item updated successfully."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Update failed: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
