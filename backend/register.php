<!--
Food Tracker - Create a New User
-->

<?php
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get input and validate it
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    if (empty($username) || empty($email) || empty($password)) {
        die("All fields are required.");
    }

    // hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // SQL matches your users table schema
    $sql = "INSERT INTO users (username, email, password_hash)
            VALUES (?, ?, ?)";

    // add a new user to the database `users`
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sss", $username, $email, $password_hash);

    if ($stmt->execute()) {
        // if register successfully, jump to login.php
        header("Location: login.php");
        exit();
    } else {
        echo "Registration failed: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
