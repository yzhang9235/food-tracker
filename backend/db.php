<?php
$host = "localhost";
$userid = "ucu8b8zx9ylzp";
$pw = "#9J&U3rn/)t(m7S";
$db = "dbx5sn9gom5yqj";

$conn = new mysqli($host, $userid, $pw, $db);

if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}
?>