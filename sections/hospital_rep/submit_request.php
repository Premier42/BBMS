<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the hospital_rep role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hospital_rep') {
    header("Location: ../../auth/login.php");
    exit;
}

// Function to sanitize user input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $blood_type = sanitize_input($_POST['blood_type']);
    $volume = sanitize_input($_POST['volume']);

    // Validate required fields
    if (empty($blood_type) || empty($volume)) {
        echo "<div class='alert alert-danger'>Please fill in all required fields.</div>";
        exit;
    }

    // Ensure volume is a positive number
    if (!is_numeric($volume) || $volume <= 0) {
        echo "<div class='alert alert-danger'>Volume must be a positive number.</div>";
        exit;
    }

    $recipient_id = $_SESSION['user_id'];
    $request_date = date('Y-m-d');

    // Prepare SQL query to insert blood request
    $sql = "INSERT INTO blood_requests (recipient_id, blood_type, volume, request_date, status)
            VALUES (?, ?, ?, ?, 'pending')";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isis", $recipient_id, $blood_type, $volume, $request_date);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Blood request submitted successfully.</div>";
            header("refresh:2;url=dashboard.php");
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }

    $conn->close();
}
?>
