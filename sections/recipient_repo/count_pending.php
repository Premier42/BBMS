<?php
// Check if session is not already active, then start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include '../../config/database.php'; 

// Check if user is logged in and has the recipient_rep role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'recipient') {
    header("Location: ../auth/login.php");
    exit;
}
// SQL query to count pending blood requests for the current recipient
$user_id = $_SESSION['user_id']; // Assuming recipient's user ID is stored in session
$sql = "SELECT COUNT(*) AS pending_count FROM blood_requests WHERE recipient_id = ? AND status = 'Pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pending_count = $row['pending_count'];
} else {
    $pending_count = 0;
}

$stmt->close();

// Display the count
?>
<h2 class="h4 mb-3">Pending Blood Requests</h2>
<p>You have <?php echo $pending_count; ?> pending blood requests.</p>




