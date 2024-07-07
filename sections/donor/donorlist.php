<?php
session_start();
require_once '../../config/database.php';

// Check if user is logged in and has the role 'donor'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
    header("Location: ../sections/auth/login.php");
    exit;
}

// Fetch donor requests from the database
$requests = getDonorRequests();

?>

<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <title>See Donor</title>
    <link rel="stylesheet" href="../../path/to/bootstrap.min.css"> <!-- Adjust the path as necessary -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 900px;
            padding: 15px;
            margin: auto;
        }
        .form-section {
            margin-bottom: 30px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 30px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        .form-section h2 {
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 10px;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(231, 76, 60, 0.1);
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: rgba(231, 76, 60, 0.05);
        }
    </style>
</head>
<body>
    <div class="form-section">
        <h2 style="text-align:left;" style="font-family: 'Courier New', Courier, monospace;" class="h4 mb-3">See Donor</h2>
        <?php if (!empty($requests)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Donor Name</th>
                        <th scope="col">Donor Email</th>
                        <th scope="col">Donor Gender</th>
                        <th scope="col">Blood Group</th>
                        <th scope="col">Donor Address</th>
                        <th scope="col">Donor Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $request): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($request['donor_name']); ?></td>
                            <td><?php echo htmlspecialchars($request['donor_email']); ?></td>
                            <td><?php echo htmlspecialchars($request['donor_gender']); ?></td>
                            <td><?php echo htmlspecialchars($request['donor_blood_group']); ?></td>
                            <td><?php echo htmlspecialchars($request['donor_address']); ?></td>
                            <td><?php echo htmlspecialchars($request['donor_number']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No donors available.</p>
        <?php endif; ?>
    </div>
    <script src="../../path/to/bootstrap.bundle.min.js"></script> <!-- Adjust the path as necessary -->
</body>
</html>

<?php
// Function to fetch donor requests from the database
function getDonorRequests() {
    global $conn;

    // Create an empty array to hold requests
    $requests = [];

    // Query to fetch donor data
    $query = "SELECT donor_name, donor_email, donor_gender, donor_blood_group, donor_address, donor_number FROM donors";
    $result = mysqli_query($conn, $query);

    // Fetch and add each row to the $requests array
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $requests[] = $row;
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    return $requests;
}
?>
