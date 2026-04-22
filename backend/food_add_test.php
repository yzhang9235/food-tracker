<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Add Food</title>
</head>
<body>
    <h1>Test Add Food Item</h1>

    <p>Logged in as: <?php echo htmlspecialchars($_SESSION['username']); ?></p>

    <form action="food_add.php" method="POST">
        <div>
            <label for="item_name">Item Name:</label>
            <input type="text" name="item_name" id="item_name" required>
        </div>

        <br>

        <div>
            <label for="category">Category:</label>
            <input type="text" name="category" id="category">
        </div>

        <br>

        <div>
            <label for="quantity">Quantity:</label>
            <input type="number" step="0.01" name="quantity" id="quantity" required>
        </div>

        <br>

        <div>
            <label for="unit">Unit:</label>
            <input type="text" name="unit" id="unit" placeholder="e.g. lbs, pieces, bottles">
        </div>

        <br>

        <div>
            <label for="pack_date">Pack Date:</label>
            <input type="date" name="pack_date" id="pack_date">
        </div>

        <br>

        <div>
            <label for="purchase_date">Purchase Date:</label>
            <input type="date" name="purchase_date" id="purchase_date">
        </div>

        <br>

        <div>
            <label>
                <input type="checkbox" name="purchase_today" value="1" id="purchase_today">
                Purchase date is today
            </label>
        </div>

        <br>

        <div>
            <label for="expiration_date">Expiration Date:</label>
            <input type="date" name="expiration_date" id="expiration_date" required>
        </div>

        <br>

        <button type="submit">Add Food</button>
    </form>

    <br>
    <p><a href="logout.php">Logout</a></p>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const checkbox = document.getElementById("purchase_today");
            const purchaseDateInput = document.getElementById("purchase_date");

            checkbox.addEventListener("change", function () {
                if (this.checked) {
                    const today = new Date().toISOString().split("T")[0];
                    purchaseDateInput.value = today;
                } else {
                    purchaseDateInput.value = "";
                }
            });
        });
    </script>
</body>
</html>