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

$user_id = $_SESSION['user_id'];

// SQL query to get all food items belonging to this user
$sql = "SELECT item_id, item_name, category, quantity, unit, expiration_date
        FROM food_items
        WHERE user_id = ?
        ORDER BY expiration_date IS NULL, expiration_date ASC, item_name ASC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit();
}

// Bind user_id parameter to query
$stmt->bind_param("i", $user_id);

// Execute the query
if (!$stmt->execute()) {
    echo json_encode([
        "success" => false,
        "message" => "Execute failed: " . $stmt->error
    ]);
    exit();
}

// Get result set from executed query
$result = $stmt->get_result();
$items = []; // Array to store all food items

// Fetch each row and store into array
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

// Return data as JSON array
echo json_encode($items);

$stmt->close();
$conn->close();
?>
