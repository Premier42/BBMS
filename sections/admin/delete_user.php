<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Check if user_id is provided via POST
if (!isset($_POST['user_id']) || empty(trim($_POST['user_id']))) {
    echo "User ID not specified.";
    exit;
}

$user_id = trim($_POST['user_id']);

// Prepare a delete statement
$sql = "DELETE FROM users WHERE user_id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id_param);
    $user_id_param = $user_id;

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Redirect to dashboard with success message
        $_SESSION['success_message'] = "User deleted successfully.";
        header("Location: dashboard.php#users");
        exit;
    } else {
        echo "Something went wrong. Please try again later.";
    }
    $stmt->close();
}

$conn->close();
?>
