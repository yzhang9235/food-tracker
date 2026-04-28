<?php
session_start();
header("Content-Type: application/json");
require_once "db_connect.php";

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

$user_id = $_SESSION['user_id'];

$item_name = trim($_POST['item_name'] ?? '');
$category = trim($_POST['category'] ?? '');
$quantity = trim($_POST['quantity'] ?? '');
$expiration_date = !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null;

if ($item_name === '') {
    echo json_encode([
        "success" => false,
        "message" => "Item name is required."
    ]);
    exit();
}

$sql = "INSERT INTO food_items (user_id, item_name, category, quantity, expiration_date, status)
        VALUES (?, ?, ?, ?, ?, 'active')";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit();
}

$stmt->bind_param("issss", $user_id, $item_name, $category, $quantity, $expiration_date);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Item added successfully."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Insert failed: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
