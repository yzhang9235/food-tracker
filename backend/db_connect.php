<!--
Food Tracker - Connect to MySQL Database
-->

<?php
$host = "localhost";
$db = "dbcw2d0sauq3nd";
$userid = "ue2gx4bwe7mcp";
$password = "rjz040817";

// create and check connection
$conn = new mysqli($host, $userid, $password, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
