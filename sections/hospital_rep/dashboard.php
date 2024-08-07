<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the hospital_rep role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hospital_rep') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch hospital and representative information
$sql_info = "SELECT locations.name AS hospital_name, locations.address AS hospital_address, locations.phone_number AS hospital_phone,
                    users.username AS rep_name, users.phone_number AS rep_phone
             FROM hospital_representative_info
             INNER JOIN locations ON hospital_representative_info.hospital_id = locations.location_id
             INNER JOIN users ON hospital_representative_info.user_id = users.user_id
             WHERE hospital_representative_info.user_id = ?";
$stmt_info = $conn->prepare($sql_info);
$stmt_info->bind_param("i", $user_id);
$stmt_info->execute();
$result_info = $stmt_info->get_result();
$info = $result_info->fetch_assoc();
$stmt_info->close();

// Fetch previous blood requests
$sql_requests = "SELECT request_id, blood_type, volume, request_date, status FROM blood_requests WHERE recipient_id = ?";
$stmt_requests = $conn->prepare($sql_requests);
$stmt_requests->bind_param("i", $user_id);
$stmt_requests->execute();
$result_requests = $stmt_requests->get_result();
$requests = $result_requests->fetch_all(MYSQLI_ASSOC);
$stmt_requests->close();

// Fetch blood availability from inventory
$sql_inventory = "SELECT blood_units.blood_type, COUNT(blood_units.unit_id) AS available_units
                  FROM blood_units
                  JOIN inventory ON blood_units.unit_id = inventory.unit_id
                  WHERE inventory.status = 'available'
                  GROUP BY blood_units.blood_type";
$result_inventory = $conn->query($sql_inventory);
$inventory = $result_inventory->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Representative Dashboard</title>
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
        .btn-edit {
            background-color: #f0ad4e;
            color: white;
        }
        .btn-edit:hover {
            background-color: #ec971f;
            color: white;
        }
        .btn-cancel {
            background-color: #d9534f;
            color: white;
        }
        .btn-cancel:hover {
            background-color: #c9302c;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="h3 mb-3 fw-normal text-center">
            Hospital Representative Dashboard
            <form class="logout-btn" method="post" action="/sections/auth/logout.php">
                <button class="btn btn-danger" type="submit">Logout</button>
            </form>
        </h1>

        <div class="alert alert-info">
            <strong>Hospital:</strong> <?php echo htmlspecialchars($info['hospital_name']); ?><br>
            <strong>Location:</strong> <?php echo htmlspecialchars($info['hospital_address']); ?><br>
            <strong>Phone:</strong> <?php echo htmlspecialchars($info['hospital_phone']); ?><br>
            <strong>Representative:</strong> <?php echo htmlspecialchars($info['rep_name']); ?><br>
            <strong>Representative Phone:</strong> <?php echo htmlspecialchars($info['rep_phone']); ?><br>
        </div>

        <!-- Submit and Track Blood Requests -->
        <div class="form-section">
            <h2 class="h4 mb-3">Submit Blood Request</h2>
            <form method="post" action="submit_request.php">
                <div class="form-floating mb-3">
                    <select class="form-select" id="blood_type" name="blood_type" required>
                        <option value="">Select Blood Type</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                    <label for="blood_type">Blood Type</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="volume" name="volume" placeholder="Volume (in mL)" required>
                    <label for="volume">Volume (in mL)</label>
                </div>
                <button class="w-100 btn btn-lg btn-danger" type="submit">Submit Request</button>
            </form>
        </div>

        <div class="form-section">
            <h2 class="h4 mb-3">Track Blood Requests</h2>
            <?php if (count($requests) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Request ID</th>
                            <th scope="col">Blood Type</th>
                            <th scope="col">Volume (mL)</th>
                            <th scope="col">Request Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($request['request_id']); ?></td>
                                <td><?php echo htmlspecialchars($request['blood_type']); ?></td>
                                <td><?php echo htmlspecialchars($request['volume']); ?></td>
                                <td><?php echo htmlspecialchars($request['request_date']); ?></td>
                                <td><?php echo htmlspecialchars($request['status']); ?></td>
                                <td>
                                    <a href="edit_request.php?id=<?php echo $request['request_id']; ?>" class="btn btn-edit btn-sm">Edit</a>
                                    <a href="cancel_request.php?id=<?php echo $request['request_id']; ?>" class="btn btn-cancel btn-sm">Cancel</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No blood requests found.</p>
            <?php endif; ?>
        </div>

        <!-- Communicate with Inventory Managers -->
        <div class="form-section">
            <h2 class="h4 mb-3">Blood Availability</h2>
            <?php if (count($inventory) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Blood Type</th>
                            <th scope="col">Available Units</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventory as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['blood_type']); ?></td>
                                <td><?php echo htmlspecialchars($item['available_units']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No available blood units found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
