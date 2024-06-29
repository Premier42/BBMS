<?php
session_start();
// Database connection
include 'database.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit;
}

// User is logged in, retrieve their user_id from session
$user_id = $_SESSION['user_id'];

// Initialize variables for counts
$totalRequestsCount = 0;
$pendingRequestsCount = 0;

// Query to fetch total number of requests for the logged-in user
$totalRequestsQuery = "SELECT COUNT(*) AS totalRequests FROM blood_requests WHERE recipient_id = ?";
$totalRequestsStmt = $conn->prepare($totalRequestsQuery);
$totalRequestsStmt->bind_param("i", $user_id);
$totalRequestsStmt->execute();
$totalRequestsResult = $totalRequestsStmt->get_result();
if ($totalRequestsResult->num_rows > 0) {
    $totalRequestsCount = $totalRequestsResult->fetch_assoc()['totalRequests'];
}
$totalRequestsStmt->close();

// Query to fetch number of pending requests for the logged-in user
$pendingRequestsQuery = "SELECT COUNT(*) AS pendingRequests FROM blood_requests WHERE recipient_id = ? AND status = 'pending'";
$pendingRequestsStmt = $conn->prepare($pendingRequestsQuery);
$pendingRequestsStmt->bind_param("i", $user_id);
$pendingRequestsStmt->execute();
$pendingRequestsResult = $pendingRequestsStmt->get_result();
if ($pendingRequestsResult->num_rows > 0) {
    $pendingRequestsCount = $pendingRequestsResult->fetch_assoc()['pendingRequests'];
}
$pendingRequestsStmt->close();

// Close database connection
$conn->close();
?>

<div class="row">
    <div class="col-12 col-md-4">
        <div class="card border-0">
            <div class="card-body py-4">
                <h5 class="mb-2 fw-bold">Total Requests</h5>
                <p class="mb-0 fw-bold">
                    <?php echo $totalRequestsCount; ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card border-0">
            <div class="card-body py-4">
                <h5 class="mb-2 fw-bold">Pending Requests</h5>
                <p class="mb-0 fw-bold">
                    <?php echo $pendingRequestsCount; ?>
                </p>
            </div>
        </div>
    </div>
</div>
