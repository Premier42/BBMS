<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the appropriate role (e.g., lab_technician)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'lab_technician') {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['unit_id'])) {
    $unit_id = $_POST['unit_id'];
    $sql = "DELETE FROM blood_test_results WHERE unit_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $param_unit_id);
        $param_unit_id = $unit_id;
        if ($stmt->execute()) {
            echo "Blood test result removed successfully.";
        } else {
            echo "Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
    $conn->close();
    header("Location: dashboard.php");
    exit;
} else {
    echo "Invalid request.";
}
?>
