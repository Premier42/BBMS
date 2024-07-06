<?php
// Check if session is not already active, then start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include '../../config/database.php'; 

// Check if user is logged in and has the recipient_rep role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'recipient') {
    header("Location: ../../auth/login.php");
    exit;
}

// User is logged in, retrieve their user_id from session
$user_id = $_SESSION['user_id'];

// Initialize variables for counts
$totalRequestsCount = 0;
$pendingRequestsCount = 0;

// Query to fetch total number of requests for the logged-in user
$totalRequestsQuery = "SELECT COUNT(*) AS totalRequests FROM blood_requests WHERE recipient_id = ?";
if ($totalRequestsStmt = $conn->prepare($totalRequestsQuery)) {
    $totalRequestsStmt->bind_param("i", $user_id);
    if ($totalRequestsStmt->execute()) {
        $totalRequestsResult = $totalRequestsStmt->get_result();
        if ($totalRequestsResult->num_rows > 0) {
            $totalRequestsCount = $totalRequestsResult->fetch_assoc()['totalRequests'];
        } else {
            echo "No total requests found.";
        }
    } else {
        echo "Error executing total requests query: " . $totalRequestsStmt->error;
    }
    $totalRequestsStmt->close();
} else {
    echo "Failed to prepare total requests statement: " . $conn->error;
}

// Query to fetch number of pending requests for the logged-in user
$pendingRequestsQuery = "SELECT COUNT(*) AS pendingRequests FROM blood_requests WHERE recipient_id = ? AND status = 'pending'";
if ($pendingRequestsStmt = $conn->prepare($pendingRequestsQuery)) {
    $pendingRequestsStmt->bind_param("i", $user_id);
    if ($pendingRequestsStmt->execute()) {
        $pendingRequestsResult = $pendingRequestsStmt->get_result();
        if ($pendingRequestsResult->num_rows > 0) {
            $pendingRequestsCount = $pendingRequestsResult->fetch_assoc()['pendingRequests'];
        } else {
            echo "No pending requests found.";
        }
    } else {
        echo "Error executing pending requests query: " . $pendingRequestsStmt->error;
    }
    $pendingRequestsStmt->close();
} else {
    echo "Failed to prepare pending requests statement: " . $conn->error;
}

// Close database connection

$conn->close();
?>

<div class="container">
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="card border-0 bg-custom-red">
                    <div class="card-body py-4">
                        <h5 class="mb-2 fw-bold text-center">Total Requests</h5>
                        <p class="mb-0 fw-bold text-center">
                            <?php echo $totalRequestsCount; ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 bg-custom-green">
                    <div class="card-body py-4">
                        <h5 class="mb-2 fw-bold text-center">Pending Requests</h5>
                        <p class="mb-0 fw-bold text-center">
                            <?php echo $pendingRequestsCount; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>