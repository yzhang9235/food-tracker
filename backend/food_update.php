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

// Get item_id safely, default to 0 if not provided
if (isset($_POST['item_id'])) {
    $item_id = (int) $_POST['item_id'];
} else {
    $item_id = 0;
}
// Get item name
if (isset($_POST['item_name'])) {
    $item_name = trim($_POST['item_name']);
} else {
    $item_name = '';
}
// Get category
if (isset($_POST['category'])) {
    $category = trim($_POST['category']);
} else {
    $category = '';
}
// Get quantity
if (isset($_POST['quantity'])) {
    $quantity = trim($_POST['quantity']);
} else {
    $quantity = '';
}

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
        SET item_name = ?, category = ?, quantity = ?
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
$stmt->bind_param("sssii", $item_name, $category, $quantity, $item_id, $user_id);

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
