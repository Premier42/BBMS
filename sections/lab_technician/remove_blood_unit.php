<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the appropriate role (e.g., lab_technician)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'lab_technician') {
    header("Location: ../auth/login.php");
    exit;
}

// Check if the unit_id is provided
if (isset($_POST['unit_id'])) {
    $unit_id = $_POST['unit_id'];

    // Prepare a delete statement
    $sql = "DELETE FROM blood_units WHERE unit_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $param_unit_id);
        $param_unit_id = $unit_id;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Blood unit deleted successfully, redirect to the dashboard
            header("Location: dashboard.php?msg=deleted");
            exit;
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close statement
        $stmt->close();
    }
} else {
    // Redirect to the dashboard if unit_id is not set
    header("Location: dashboard.php");
    exit;
}

// Close connection
$conn->close();
?>
