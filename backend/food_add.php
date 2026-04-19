<?php
session_start();
include "db.php";

$user_id = $_SESSION['user_id'];

$name = $_POST['item_name'];
$category = $_POST['category'];
$quantity = $_POST['quantity'];
$purchase = $_POST['date_added'];
$expire = $_POST['expiration_date'];

$sql = "INSERT INTO food_items
(user_id, item_name, category, quantity, date_added, expiration_date, status)
VALUES (?, ?, ?, ?, ?, ?, 'active')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ississ", $user_id, $name, $category, $quantity, $purchase, $expire);

echo $stmt->execute() ? "added" : "error";
?>