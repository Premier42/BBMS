<?php
session_start();

// Include database connection
include '../../config/database.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $bloodType = mysqli_real_escape_string($conn, $_POST['bloodType']);
    $volume = mysqli_real_escape_string($conn, $_POST['volume']);
    $requestDate = date('Y-m-d'); // Current date
    $status = 'pending'; // Default status

    // Get recipient_id from session
    $recipientId = $_SESSION['user_id'];

    // Prepare and execute SQL query
    $sql = "INSERT INTO blood_requests (recipient_id, blood_type, volume, request_date, status) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isiss", $recipientId, $bloodType, $volume, $requestDate, $status);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Close connection (if not already closed)
mysqli_close($conn);
?>
