<?php
$host = "localhost";
$db = "dbcw2d0sauq3nd";
$userid = "ue2gx4bwe7mcp";
$password = "rjz040817";

$conn = new mysqli($host, $userid, $password, $db);

if ($conn->connect_error) {
    header("Content-Type: application/json");
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed: " . $conn->connect_error
    ]);
    exit();
}
?>