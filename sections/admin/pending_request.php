<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Check if request_id is provided via POST
if (!isset($_POST['request_id']) || empty(trim($_POST['request_id']))) {
    echo json_encode(['success' => false, 'message' => 'Request ID not specified']);
    exit;
}

$request_id = trim($_POST['request_id']);

// Prepare an update statement
$sql = "UPDATE blood_requests SET status = 'pending' WHERE request_id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $request_id);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Request status set to pending']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
}

$conn->close();
?>
