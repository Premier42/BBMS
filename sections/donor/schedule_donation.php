<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the donor role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
    header("Location: ../../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Sanitize and validate input
$donation_date = $_POST['donation_date'];
$time_slot = $_POST['time_slot'];
$location = htmlspecialchars($_POST['location'], ENT_QUOTES, 'UTF-8');

// Insert the donation schedule into the database
$sql_schedule = "
    INSERT INTO donation_schedule (donor_id, donation_date, time_slot, location) 
    VALUES (?, ?, ?, ?)";
$stmt_schedule = $conn->prepare($sql_schedule);
$stmt_schedule->bind_param("isss", $user_id, $donation_date, $time_slot, $location);

if ($stmt_schedule->execute()) {
    $_SESSION['success_message'] = "Your donation has been scheduled successfully!";
} else {
    $_SESSION['error_message'] = "There was an error scheduling your donation. Please try again.";
}

$stmt_schedule->close();
$conn->close();

header("Location: dashboard.php");
exit;
?>
