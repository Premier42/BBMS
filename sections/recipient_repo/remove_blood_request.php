<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the appropriate role (e.g., recipient)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'recipient') {
    header("Location: ../auth/login.php");
    exit;
}

// Check if request_id is set in the POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];

    // Prepare a delete statement
    $sql = "DELETE FROM blood_requests WHERE request_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $param_request_id);
        $param_request_id = $request_id;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Request removed successfully, redirect to dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Something went wrong. Please try again later.";
        }
        $stmt->close();
    } else {
        echo "Something went wrong. Please try again later.";
    }
    $conn->close();
} else {
    // Redirect to dashboard if request_id is not set
    header("Location: dashboard.php");
    exit;
}
?>
