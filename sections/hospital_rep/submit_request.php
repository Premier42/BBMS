<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hospital_rep') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blood_type = $_POST['blood_type'];
    $volume = $_POST['volume'];

    // Insert the new blood request into the database
    $sql = "INSERT INTO blood_requests (recipient_id, blood_type, volume, request_date, status) VALUES (?, ?, ?, NOW(), 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $user_id, $blood_type, $volume);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: dashboard.php"); // Redirect to dashboard after submission
        exit;
    } else {
        $stmt->close();
        $conn->close();
        die("Error submitting request.");
    }
} else {
    header("Location: dashboard.php");
    exit;
}
?>
