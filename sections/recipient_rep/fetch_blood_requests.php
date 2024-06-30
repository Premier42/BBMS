<?php
session_start();

// Include database connection
include '../../config/database.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

// Fetch blood requests for the logged-in recipient
$user_id = $_SESSION['user_id'];
$sql = "SELECT br.request_id, br.recipient_id, br.blood_type, br.volume, br.request_date, br.status, u.username
        FROM blood_requests br
        INNER JOIN users u ON br.recipient_id = u.user_id
        WHERE br.recipient_id = ?";
$stmt = mysqli_prepare($CONN, $sql);
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
            echo "<th scope='row'>" . $row['request_id'] . "</th>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['blood_type'] . "</td>";
            echo "<td>" . $row['volume'] . "</td>";
            echo "<td>" . $row['request_date'] . "</td>";
            echo "<td>" . ucfirst($row['status']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No blood requests found for this recipient</td></tr>";
    }
} else {
    echo "Error executing query: " . mysqli_error($CONN);
}

// Close statement and database connection
mysqli_stmt_close($stmt);
mysqli_close($CONN);
?>
