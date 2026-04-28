<?php
session_start();// Start session to access login information
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

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request."
    ]);
    exit();
}

// Check if item_id is provided in POST data
if (empty($_POST['item_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "No item ID provided."
    ]);
    exit();
}

$user_id = $_SESSION['user_id']; // Get current user ID from session
$item_id = (int) $_POST['item_id']; // Convert item_id to integer for safety

// SQL query to delete item only if it belongs to the logged-in user
$sql = "DELETE FROM food_items WHERE item_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);

// Check if prepare failed
if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit();
}

// Bind parameters to SQL statement (both integers)
$stmt->bind_param("ii", $item_id, $user_id);

// Execute delete query
if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Item deleted successfully."
    ]);
} else {
    // Return error message if execution fails
    echo json_encode([
        "success" => false,
        "message" => "Delete failed: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
