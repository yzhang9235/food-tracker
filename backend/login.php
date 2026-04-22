<!--
Food Tracker - Log In
-->

<?php
session_start();
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get input and validate it
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    if (empty($email) || empty($password)) {
        die("Email and password are required.");
    }

    // prepare the query and execute it
    $sql = "SELECT user_id, username, password_hash FROM users 
      WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // get the result from the database and compare
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];

        // header("Location: dashboard.php");
        /*** -------------------------- ***/
        /*** temporary test ***/
        header("Location: food_add_test.php");
        /*** -------------------------- ***/
        exit();
    } else {
        echo "Invalid email or password.";
    }

    $stmt->close();
    $conn->close();
}
?>
