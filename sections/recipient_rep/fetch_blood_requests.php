<?php
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

// Fetch blood requests for the logged-in recipient
$user_id = $_SESSION['user_id'];
$sql = "SELECT br.request_id, br.recipient_id, br.blood_type, br.volume, br.request_date, br.status, u.username
        FROM blood_requests br
        INNER JOIN users u ON br.recipient_id = u.user_id
        WHERE br.recipient_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if query execution was successful
if ($result) {
    // Check if there are results
    if (mysqli_num_rows($result) > 0) {
        // Output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<th scope='row'>" . htmlspecialchars($row['request_id']) . "</th>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['blood_type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['volume']) . "</td>";
            echo "<td>" . htmlspecialchars($row['request_date']) . "</td>";
            echo "<td>" . ucfirst(htmlspecialchars($row['status'])) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No blood requests found for this recipient</td></tr>";
    }
} else {
    echo "<tr><td colspan='6'>Error executing query: " . mysqli_error($conn) . "</td></tr>";
}

// Close statement and database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
