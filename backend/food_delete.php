<?php
include "db.php";

$item_id = $_POST['item_id'];

$sql = "DELETE FROM food_items WHERE item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $item_id);

echo $stmt->execute() ? "deleted" : "error";
?>