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
        "message" => "Invalid request."
    ]);
    exit();
}

if (empty($_POST['item_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "No item ID provided."
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];
$item_id = (int) $_POST['item_id'];

$sql = "DELETE FROM food_items WHERE item_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit();
}

$stmt->bind_param("ii", $item_id, $user_id);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Item deleted successfully."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Delete failed: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
