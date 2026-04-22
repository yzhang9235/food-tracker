<!--
Food Tracker - Delete an item from the inventory
-->

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "db_connect.php";

// temporary test userid
$user_id = 1;


if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Invalid request.");
}
// make sure an item ID was sent
if (!isset($_POST['item_id']) || empty($_POST['item_id'])) {
    die("No item ID provided.");
}

$item_id = $_POST['item_id'];
// delete only the item that matches both the item ID and the current user
$sql = "DELETE FROM food_items WHERE item_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ii", $item_id, $user_id);

if ($stmt->execute()) {
    echo "<h1>Delete Successful</h1>";
    echo "<p>The food item was deleted successfully.</p>";
    echo '<p><a href="inventory.php">Back to Inventory</a></p>';
} else {
    die("Delete failed: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>
