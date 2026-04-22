<!--
Food Tracker - Display Inventory
This is NOT THE FINAL DISPLAY PAGE
TESTING PURPOSE ONLY
-->

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "db_connect.php";

// temporary test without login
$user_id = 1;

$sql = "SELECT item_id, item_name, category, quantity, unit, pack_date, purchase_date, expiration_date, status
        FROM food_items
        WHERE user_id = ?
        ORDER BY expiration_date ASC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .empty-message {
            margin-top: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>My Food Inventory</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Item Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Pack Date</th>
                <th>Purchase Date</th>
                <th>Expiration Date</th>
                <th>Status</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($row['unit']); ?></td>
                    <td><?php echo htmlspecialchars($row['pack_date'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['purchase_date'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['expiration_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <a class="edit-link" href="food_update.php?id=<?php echo $row['item_id']; ?>">
                            Edit
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="empty-message">No food items found.</p>
    <?php endif; ?>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
