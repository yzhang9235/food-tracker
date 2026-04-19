<?php
include "db.php";

// receive data from front end
$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, email, password)
        VALUES (?, ?, ?)";

// connect to database
$stmt = $conn->prepare($sql);
//process three variables as string type
$stmt->bind_param("sss", $username, $email, $password);

if ($stmt->execute()) {
    echo "success";
    // if register successfully, jump to login.php
    header("Location: login.php");
    exit();
} else {
    echo "error";
}
?>