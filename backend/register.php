<?php
header("Content-Type: application/json");
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
    exit();
}

// Get username safely
if (isset($_POST['username'])) {
    $username = trim($_POST['username']);
} else {
    $username = '';
}

// Get email safely
if (isset($_POST['email'])) {
    $email = trim($_POST['email']);
} else {
    $email = '';
}

// Get password safely
if (isset($_POST['password'])) {
    $password = $_POST['password'];
} else {
    $password = '';
}

// Validate input fields
if ($username === '' || $email === '' || $password === '') {
    echo json_encode([
        "success" => false,
        "message" => "All fields are required."
    ]);
    exit();
}

// Hash password for security
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// SQL query to insert new user
$sql = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

// Check prepare success
if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit();
}

// Bind parameters
$stmt->bind_param("sss", $username, $email, $password_hash);

// Execute insert query
if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Account created successfully."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Registration failed: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>