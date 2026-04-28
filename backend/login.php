<?php
session_start();
header("Content-Type: application/json");

require_once "db_connect.php"; // Database connection file

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
    exit();
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

// Validate input
if ($email === '' || $password === '') {
    echo json_encode([
        "success" => false,
        "message" => "Email and password are required."
    ]);
    exit();
}

// Prepare SQL to find user by email
$sql = "SELECT user_id, username, password_hash FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit();
}

// Bind email parameter
$stmt->bind_param("s", $email);
// Execute query
$stmt->execute();
// Get result
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Verify user and password
if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];

    echo json_encode([
        "success" => true,
        "message" => "Login successful."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid email or password."
    ]);
}

$stmt->close();
$conn->close();
?>
