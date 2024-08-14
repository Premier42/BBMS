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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- CSS starting from here -->

    <style>
/* Global Styles */
body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
}

/* Sidebar Styles */
.sidebar {
    height: 100%;
    width: 250px;
    position: fixed;
    top: 0;
    left: 0;
    background-color: indianred;
    padding-top: 20px;
    transition: 0.3s;
}
.sidebar a {
    padding: 10px 15px;
    text-decoration: none;
    font-size: 18px;
    color: #f8f9fa;
    display: block;
    transition: 0.3s;
}
.sidebar a i {
    margin-right: 10px;
}
.sidebar a:hover {
    background-color: black;
}

/* Content Area Styles */
.content {
    margin-left: 250px;
    padding: 20px;
    overflow: hidden; /* Ensure content doesn't overflow */
}

/* Form Section Styles */
.form-section {
    display: none;
    margin-bottom: 30px;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.form-section.active {
    display: block;
}
.form-section h2 {
    border-bottom: 2px solid #e74c3c;
    padding-bottom: 10px;
}

/* Table Styles */
.table {
    width: 100%;
    min-width: 800px;
    border-collapse: collapse;
    table-layout: fixed;
}
.table th, .table td {
    padding: 10px;
    text-align: left;
    vertical-align: middle;
    word-wrap: break-word;
    overflow: hidden;
    text-overflow: ellipsis;
    border: 1px solid #dee2e6;
}
.table th {
    background-color: #e74c3c;
    color: #fff;
    font-weight: bold;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(231, 76, 60, 0.1);
}
.table-striped tbody tr:nth-of-type(even) {
    background-color: rgba(231, 76, 60, 0.05);
}

/* Logout Button Styles */
.logout-btn {
    position: fixed;
    top: 20px;
    right: 20px;
    text-align: center;
}
.logout-btn button {
    width: 100%; /* Adjusted to fit button */
    padding: 10px;
    background-color: #dc3545;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}
.logout-btn button:hover {
    background-color: #c82333;
}

/* Button Styles */
.custom-btn {
    background-color: lightcoral;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    transition: background-color 0.3s, transform 0.3s;
}
.custom-btn:hover {
    background-color: #dc3545;
    transform: translateY(-2px);
}
.custom-btn:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(38, 143, 255, 0.5);
}
.custom-btn:active {
    background-color: #dc3545;
    transform: translateY(0);
}

/* Sidebar Header Styles */
.sidebar-header {
    text-align: center;
    padding: 20px;
    color: white;
    border-bottom: 1px solid #495057;
}
.system-name {
    font-size: 24px;
    font-weight: bold;
    margin: 0;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        margin-bottom: 20px; /* Space below the sidebar */
    }
    .sidebar a {
        float: left;
    }
    .content {
        margin-left: 0;
    }
}

@media (max-width: 480px) {
    .sidebar a {
        text-align: center;
        float: none;
    }
    .table {
        width: 100%;
        min-width: 100%;
    }
    .table th, .table td {
        display: block;
        width: 100%;
        text-align: right;
    }
    .table th {
        position: absolute;
        top: -9999px;
        left: -9999px;
    }
    .table td:before {
        content: attr(data-label);
        float: left;
        font-weight: bold;
        text-transform: uppercase;
    }
}

</style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
    <div class="sidebar-header">
            <h2 class="system-name"><i class="fas fa-hospital"></i> CRUDCare</h2>
      </div>
        <a href="#" class="nav-link" data-section="submit-request">Submit Blood Request</a>
        <a href="#" class="nav-link" data-section="track-requests">Track Blood Requests</a>
        <a href="#" class="nav-link" data-section="blood-availability">Blood Availability</a>
    </div>

    <!-- Content -->
    <div class="content">
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

        <!-- Submit Blood Request Section -->
        <div id="submit-request" class="form-section active">
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

        <!-- Track Blood Requests Section -->
        <div id="track-requests" class="form-section">
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

        <!-- Blood Availability Section -->
        <div id="blood-availability" class="form-section">
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
                <p>No blood units available.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Sidebar navigation click event
            $('.nav-link').on('click', function (e) {
                e.preventDefault();
                var targetSection = $(this).data('section');
                
                // Hide all sections
                $('.form-section').removeClass('active');
                
                // Show the clicked section
                $('#' + targetSection).addClass('active');
            });

            // Initially show the first section
            $('.form-section').first().addClass('active');
        });
    </script>
</body>
</html>
