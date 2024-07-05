<?php
session_start();

// Include database connection and check connection
include '../../config/database.php';
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if user is logged in and has the recipient role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'recipient') {
    header("Location: ../../auth/login.php");
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if required fields are set
    if (isset($_POST['bloodType']) && isset($_POST['volume'])) {
        // Retrieve and sanitize form data
        $blood_type = mysqli_real_escape_string($conn, $_POST['bloodType']);
        $volume = mysqli_real_escape_string($conn, $_POST['volume']);
        $request_date = date('Y-m-d'); // Current date
        $status = 'pending'; // Default status

        // Get recipient_id from session
        $recipient_id = $_SESSION['user_id'];

        // Prepare and execute SQL query
        $sql = "INSERT INTO blood_requests (recipient_id, blood_type, volume, request_date, status) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "isiss", $recipient_id, $blood_type, $volume, $request_date, $status);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                echo "Request submitted successfully"; // Response back to JavaScript
                exit;
            } else {
                echo "Error: " . mysqli_stmt_error($stmt);
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Failed to prepare statement: " . mysqli_error($conn);
        }
    } else {
        echo "Please fill in all required fields.";
    }
}

// Close connection
mysqli_close($conn);
?>
