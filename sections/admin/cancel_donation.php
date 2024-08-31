<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate donation_id
    if (empty(trim($_POST["donation_id"]))) {
        echo "Donation ID is required.";
        exit;
    } else {
        $donation_id = trim($_POST["donation_id"]);
    }

    // Prepare a delete statement
    $sql = "DELETE FROM donations WHERE donation_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $param_donation_id);
        $param_donation_id = $donation_id;
        
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to dashboard with success message
            $_SESSION['success_message'] = "Donation cancelled successfully.";

            header("Location: dashboard.php");
            exit;

            
        } else {
            echo "Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
}
$conn->close();
?>
