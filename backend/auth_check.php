<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "not logged in";
    exit();
}
?>