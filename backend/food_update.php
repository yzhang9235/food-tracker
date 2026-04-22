<!--
Food Tracker - Update an Item in Inventory
-->

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "db_connect.php";

// temporary test only
$user_id = 1;

// Part 1: form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $item_name = trim($_POST['item_name']);
    $category = trim($_POST['category']);
    $quantity = trim($_POST['quantity']);
    $unit = trim($_POST['unit']);
    $pack_date = !empty($_POST['pack_date']) ? $_POST['pack_date'] : null;
    $purchase_date = !empty($_POST['purchase_date']) ? $_POST['purchase_date'] : null;
    $expiration_date = !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null;
    $status = trim($_POST['status']);

    // validate input
    if (empty($item_name) || empty($quantity) || empty($expiration_date) || empty($status)) {
        die("Please fill in all required fields.");
    }
    if (!empty($purchase_date) && !empty($expiration_date) && $expiration_date < $purchase_date) {
        die("Expiration date cannot be earlier than purchase date.");
    }

    // prepare SQL query
    $sql = "UPDATE food_items
            SET item_name = ?, category = ?, quantity = ?, unit = ?, pack_date = ?, purchase_date = ?, expiration_date = ?, status = ?
            WHERE item_id = ? AND user_id = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "ssdsssssii",
        $item_name,
        $category,
        $quantity,
        $unit,
        $pack_date,
        $purchase_date,
        $expiration_date,
        $status,
        $item_id,
        $user_id
    );

    // execute the update
    if ($stmt->execute()) {
        // header("Location: inventory.php");
        echo "<h1>Update Successful</h1>";
        echo "<p>The food item was updated successfully.</p>";
        echo '<p><a href="inventory.php">Back to Inventory</a></p>';
        exit();
    } else {
        die("Update failed: " . $stmt->error);
    }
}

// Part 2: load current item

// if (!isset($_GET['id'])) {
//     die("No item ID provided.");
// }

// $item_id = $_GET['id'];
$item_id =1;

// get the current item for this user
$sql = "SELECT * FROM food_items WHERE item_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ii", $item_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    die("Food item not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Food Item</title>
</head>
<body>
    <h1>Edit Food Item</h1>

    <!-- form is pre-filled with the current food item information -->
    <form action="food_update.php" method="POST">
        <!-- hidden field keeps track of which item is being updated -->
        <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">

        <div>
            <label for="item_name">Item Name:</label>
            <input type="text" name="item_name" id="item_name"
                   value="<?php echo htmlspecialchars($item['item_name']); ?>" required>
        </div>

        <br>

        <div>
            <label for="category">Category:</label>
            <input type="text" name="category" id="category"
                   value="<?php echo htmlspecialchars($item['category']); ?>">
        </div>

        <br>

        <div>
            <label for="quantity">Quantity:</label>
            <input type="number" step="0.01" name="quantity" id="quantity"
                   value="<?php echo htmlspecialchars($item['quantity']); ?>" required>
        </div>

        <br>

        <div>
            <label for="unit">Unit:</label>
            <input type="text" name="unit" id="unit"
                   value="<?php echo htmlspecialchars($item['unit']); ?>">
        </div>

        <br>

        <div>
            <label for="pack_date">Pack Date:</label>
            <input type="date" name="pack_date" id="pack_date"
                   value="<?php echo htmlspecialchars($item['pack_date']); ?>">
        </div>

        <br>

        <div>
            <label for="purchase_date">Purchase Date:</label>
            <input type="date" name="purchase_date" id="purchase_date"
                   value="<?php echo htmlspecialchars($item['purchase_date']); ?>">
        </div>

        <br>

        <div>
            <label for="expiration_date">Expiration Date:</label>
            <input type="date" name="expiration_date" id="expiration_date"
                   value="<?php echo htmlspecialchars($item['expiration_date']); ?>" required>
        </div>

        <br>

        <div>
            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="active" <?php if ($item['status'] == 'active') echo 'selected'; ?>>Active</option>
                <option value="used" <?php if ($item['status'] == 'used') echo 'selected'; ?>>Used</option>
                <option value="expired" <?php if ($item['status'] == 'expired') echo 'selected'; ?>>Expired</option>
            </select>
        </div>

        <br>

        <button type="submit">Update Food Item</button>
    </form>

    <br>
    <p><a href="inventory.php">Back to Inventory</a></p>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
