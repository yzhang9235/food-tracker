<!--
Food Tracker - add an item to inventory
-->

<?php
// session_start();
require_once "db_connect.php";

// // Make sure user is logged in
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $user_id = $_SESSION['user_id'];
    $user_id = 1;

    // get and clean input
    $item_name = trim($_POST['item_name']);
    $category = trim($_POST['category']);
    $quantity = trim($_POST['quantity']);
    $unit = trim($_POST['unit']);
    $pack_date = !empty($_POST['pack_date']) ? $_POST['pack_date'] : null;
    $expiration_date = !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null;

    // if the user selects purchase date is today, set purchase_date here
    $purchase_date = !empty($_POST['purchase_date']) ? $_POST['purchase_date'] : null;
    if (isset($_POST['purchase_today']) && $_POST['purchase_today'] == "1") {
        $purchase_date = date("Y-m-d");
    }

    // validate input
    if (empty($item_name) || empty($quantity) || empty($expiration_date)) {
        die("Please fill in all required fields.");
    }
    if (!empty($purchase_date) && !empty($expiration_date) && $expiration_date < $purchase_date) {
        die("Expiration date cannot be earlier than purchase date.");
    }

    // write query and insert the item into the database 
    $sql = "INSERT INTO food_items
            (user_id, item_name, category, quantity, unit, pack_date, 
            purchase_date, expiration_date, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active')";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "issdssss",
        $user_id,
        $item_name,
        $category,
        $quantity,
        $unit,
        $pack_date,
        $purchase_date,
        $expiration_date
    );

    // if ($stmt->execute()) {
    //     echo "food added.";
    //     header("Location: inventory.php");
    //     exit();
    // } else {
    //     echo "Error adding food item: " . $stmt->error;
    // }
    /*** -------------------------- ***/
    /*** temporary test ***/
    echo "<pre>";
    echo "Trying insert...\n";
    echo "user_id: $user_id\n";
    echo "item_name: $item_name\n";
    echo "category: $category\n";
    echo "quantity: $quantity\n";
    echo "unit: $unit\n";
    echo "pack_date: " . ($pack_date ?? "NULL") . "\n";
    echo "purchase_date: " . ($purchase_date ?? "NULL") . "\n";
    echo "expiration_date: " . ($expiration_date ?? "NULL") . "\n";
    /*** -------------------------- ***/

    if ($stmt->execute()) {
        echo "INSERT SUCCESS\n";
        echo "Inserted item_id: " . $stmt->insert_id . "\n";
    } else {
        echo "INSERT FAILED\n";
        echo "Error: " . $stmt->error . "\n";
    }
    echo "</pre>";


    $stmt->close();
    $conn->close();
}
?>
