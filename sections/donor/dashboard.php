<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the donor role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
    header("Location: ../../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch the donor's donations
$sql_donations = "
    SELECT 
        d.donation_id, 
        bu.blood_type, 
        bu.volume, 
        d.donation_date, 
        bu.status AS blood_status
    FROM 
        donations d
    JOIN 
        blood_units bu ON d.unit_id = bu.unit_id
    WHERE 
        d.donor_id = ?";
$stmt_donations = $conn->prepare($sql_donations);
$stmt_donations->bind_param("i", $user_id);
$stmt_donations->execute();
$result_donations = $stmt_donations->get_result();
$donations = $result_donations->fetch_all(MYSQLI_ASSOC);
$stmt_donations->close();

// Fetch donor profile information
$sql_profile = "
    SELECT 
        username, 
        email, 
        phone_number, 
        address
    FROM 
        users
    WHERE 
        user_id = ?";
$stmt_profile = $conn->prepare($sql_profile);
$stmt_profile->bind_param("i", $user_id);
$stmt_profile->execute();
$result_profile = $stmt_profile->get_result();
$profile = $result_profile->fetch_assoc();
$stmt_profile->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
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
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .form-section h2 {
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 10px;
        }
        .logout-btn {
            float: right;
            margin-top: -10px;
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
    <div class="container">
        <h1 class="h3 mb-3 fw-normal text-center">
            Donor Dashboard
            <form class="logout-btn" method="post" action="/sections/auth/logout.php">
                <button class="btn btn-danger" type="submit">Logout</button>
            </form>
        </h1>

        <!-- Donor Profile -->
        <div class="form-section">
            <h2 class="h4 mb-3">Profile Information</h2>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($profile['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($profile['email']); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($profile['phone_number']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($profile['address']); ?></p>
        </div>

        <!-- View Past Donations -->
        <div class="form-section">
            <h2 class="h4 mb-3">Past Donations</h2>
            <?php if (count($donations) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Donation ID</th>
                            <th scope="col">Blood Type</th>
                            <th scope="col">Volume (mL)</th>
                            <th scope="col">Donation Date</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($donations as $donation): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($donation['donation_id']); ?></td>
                                <td><?php echo htmlspecialchars($donation['blood_type']); ?></td>
                                <td><?php echo htmlspecialchars($donation['volume']); ?></td>
                                <td><?php echo htmlspecialchars($donation['donation_date']); ?></td>
                                <td><?php echo htmlspecialchars($donation['blood_status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No donations found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
