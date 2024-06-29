<?php
session_start();

// Unset specific session variables
unset($_SESSION['login']);

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: login.php");
exit;
?>
