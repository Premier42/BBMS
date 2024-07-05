<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hospital_rep') {
    header("Location: ../../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $request_id = $_GET['id'];


    $sql = "UPDATE blood_requests SET status = 'cancelled' WHERE request_id = ? AND recipient_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $request_id, $user_id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: dashboard.php"); 
        exit;
    } else {
        $stmt->close();
        $conn->close();
        die("Error cancelling request.");
    }
} else {
    header("Location: dashboard.php");
    exit;
}
?>
