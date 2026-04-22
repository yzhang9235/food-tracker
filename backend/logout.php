<!--
Food Tracker - Log Out
-->

<?php
// Remove all session variables and destroy the session
session_start();
$_SESSION = [];
session_destroy();
header("Location: login.php");
exit();
?>
