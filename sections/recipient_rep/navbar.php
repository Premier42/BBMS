<?php
session_start();
include '../../config/database.php'; 

// Check if user is logged in and has the recipient_rep role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'recipient_rep') {
    header("Location: ../auth/login.php");
    exit;
}

// Fetch user email based on session user_id
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    
    $sql = "SELECT email FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $email = htmlspecialchars($row['email']);

            // Display user email in the navbar
            echo '<nav class="navbar navbar-expand px-4 py-3">
                    <form action="#" class="d-none d-sm-inline-block"></form>
                    <div class="navbar-collapse collapse">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                    <img src="../../assets/Images/account.png" class="avatar img-fluid" alt="">
                                    <span class="ms-2">' . $email . '</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end rounded">
                                    <a class="dropdown-item" href="#">Profile</a>
                                    <a class="dropdown-item" href="#">Settings</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="../auth/logout.php">Logout</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>';
        } else {
            echo "User not found.";
        }

        mysqli_stmt_close($stmt); // Close statement
    } else {
        echo "Failed to prepare statement: " . mysqli_error($conn);
    }
} else {
    echo "Session not set. User not logged in.";
}

mysqli_close($conn); // Close database connection
?>
