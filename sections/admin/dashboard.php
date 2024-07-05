
<?php
session_start();
require_once '../../config/database.php';


// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Fetch users
$sql_users = "SELECT * FROM users";
$result_users = $conn->query($sql_users);

if ($result_users->num_rows > 0) {
    $users = $result_users->fetch_all(MYSQLI_ASSOC);
} else {
    $users = [];
}

// Fetch donations
$sql_donations = "SELECT * FROM donations";
$result_donations = $conn->query($sql_donations);

if ($result_donations->num_rows > 0) {
    $donations = $result_donations->fetch_all(MYSQLI_ASSOC);
} else {
    $donations = [];
}

// Fetch requests
$sql_requests = "SELECT * FROM blood_requests";
$result_requests = $conn->query($sql_requests);

if ($result_requests->num_rows > 0) {
    $requests = $result_requests->fetch_all(MYSQLI_ASSOC);
} else {
    $requests = [];
}

// Fetch inventory
$sql_inventory = "SELECT * FROM inventory";
$result_inventory = $conn->query($sql_inventory);

if ($result_inventory->num_rows > 0) {
    $inventory = $result_inventory->fetch_all(MYSQLI_ASSOC);
} else {
    $inventory = [];
}


$conn->close();
?>

<!-- HTML Part -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- CSS starting from here -->

    <style>

    /* Global Styles */
    body {
        background-color: #f8f9fa; 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
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
        margin-left: 260px; 
        padding: 20px; 
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
    right: 80px;
    text-align: center;
    }
    .logout-btn button {
        width: 150%; 
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
    background-color:lightcoral;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    transition: background-color 0.3s, transform 0.3s;
}

.custom-btn:hover {
    background-color:#dc3545;
    transform: translateY(-2px);
}

.custom-btn:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(38, 143, 255, 0.5);
}

.custom-btn:active {
    background-color:#dc3545;
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
<!-- CSS ending here -->

</head>
<body>

   <!-- Navbar link -->

    <div class="sidebar">
        <div class="sidebar-header">
            <h2 class="system-name"><i class="fas fa-hospital"></i> CRUDCare</h2>
      </div>

        <a href="#users" onclick="showSection('users')">
            <i class="fas fa-users"></i>Manage Users
        </a>
        <a href="#donations" onclick="showSection('donations')">
            <i class="fas fa-hand-holding-heart"></i>Manage Donations
        </a>
        <a href="#requests" onclick="showSection('requests')">
            <i class="fas fa-tint"></i>Manage Requests
        </a>
        <a href="#inventory" onclick="showSection('inventory')">
            <i class="fas fa-warehouse"></i>Manage Inventory
        </a>
    </div>

    <div class="content">
        <h1 class="h3 mb-3 fw-normal text-center">
            Admin Dashboard
            <form class="logout-btn" method="post" action="/sections/auth/logout.php">
                <button class="btn btn-danger" type="submit">Logout</button>
            </form>
        </h1>


       <!-- Manage Users Section -->
<div class="form-section" id="users-section">
    <h2 class="h4 mb-3">Manage Users</h2>
    <form method="get" action="add_user.php">
        <button type="submit" class="custom-btn btn-primary mb-3">Add User</button>
    </form>
    <?php if (count($users) > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">User ID</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Approved</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td><?php echo $user['approved'] ? '1' : '0'; ?></td>
                        <td>
                            <form method="post" action="edit_user.php" class="d-inline">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
                                <button class="btn btn-warning btn-sm" type="submit">Edit</button>
                            </form>
                            <form method="post" action="delete_user.php" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
                                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
</div>
<!-- Manage Users section ending here -->


        <!-- Manage Donations Section -->

        <div class="form-section" id="donations-section">
            <h2 class="h4 mb-3">Manage Donations</h2>
            <!--<a href="schedule_donation.php" class="btn btn-primary mb-3">Schedule Donation</a> -->
            <form method="get" action="schedule_donation.php">
                <button type="submit" class="custom-btn btn-primary mb-3">Schedule Donation</button>
            </form>
            <?php if (count($donations) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Donation ID</th>
                            <th scope="col">Donor ID</th>
                            <th scope="col">Donation Date</th>
                            <th scope="col">Location ID</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($donations as $donation): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($donation['donation_id']); ?></td>
                                <td><?php echo htmlspecialchars($donation['donor_id']); ?></td>
                                <td><?php echo htmlspecialchars($donation['donation_date']); ?></td>
                                <td><?php echo htmlspecialchars($donation['location_id']); ?></td>
                                <td>
                                    <form method="post" action="cancel_donation.php" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this donation?');">
                                        <input type="hidden" name="donation_id" value="<?php echo htmlspecialchars($donation['donation_id']); ?>">
                                        <button class="btn btn-danger btn-sm" type="submit">Cancel</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No donations found.</p>
            <?php endif; ?>
        </div>
  <!-- Manage Donation section ending here -->


        <!-- Manage Requests Section -->

<div class="form-section" id="requests-section">
            <h2 class="h4 mb-3">Manage Requests</h2>
            <?php if (count($requests) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Request ID</th>
                            <th scope="col">Recipient ID</th>
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
                                <td><?php echo htmlspecialchars($request['recipient_id']); ?></td>
                                <td><?php echo htmlspecialchars($request['blood_type']); ?></td>
                                <td><?php echo htmlspecialchars($request['volume']); ?></td>
                                <td><?php echo htmlspecialchars($request['request_date']); ?></td>
                                <td><?php echo htmlspecialchars($request['status']); ?></td>
                                <td>
                                    <form method="post" action="approve_request.php" class="d-inline">
                                        <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request['request_id']); ?>">
                                        <button class="btn btn-success btn-sm" type="submit">Approve</button>
                                    </form>
                                    <form method="post" action="reject_request.php" class="d-inline">
                                        <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request['request_id']); ?>">
                                        <button class="btn btn-warning btn-sm" type="submit">Reject</button>
                                    </form>
                                    <form method="post" action="fulfill_request.php" class="d-inline">
                                        <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request['request_id']); ?>">
                                        <button class="btn btn-info btn-sm" type="submit">Fulfill</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No requests found.</p>
            <?php endif; ?>
        </div>
        <!-- Manage Request section ending here -->


        <!-- Manage Inventory Section -->

        <div class="form-section" id="inventory-section">
            <h2 class="h4 mb-3">Manage Inventory</h2>
            <!--<a href="add_inventory.php" class="btn btn-primary mb-3">Add to Inventory</a> -->
            <form method="get" action="add_inventory.php">
        <button type="submit" class="custom-btn btn-primary mb-3">Add to Inventory</button>
    </form>
            <?php if (count($inventory) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Inventory ID</th>
                            <th scope="col">Unit Id</th>
                            <th scope="col">Inventory Manager Id</th>
                            <th scope="col">Received Date</th>
                            <th scope="col">Expiration Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventory as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['inventory_id']); ?></td>
                                <td><?php echo htmlspecialchars($item['unit_id']); ?></td>
                                <td><?php echo htmlspecialchars($item['inventory_manager_id']); ?></td>
                                <td><?php echo htmlspecialchars($item['received_date']); ?></td>
                                <td><?php echo htmlspecialchars($item['expiration_date']); ?></td>
                                <td><?php echo htmlspecialchars($item['status']); ?></td>
                                <td>
                                    <form method="post" action="update_inventory.php" class="d-inline">
                                        <input type="hidden" name="inventory_id" value="<?php echo htmlspecialchars($item['inventory_id']); ?>">
                                        <button class="btn btn-warning btn-sm" type="submit">Update</button>
                                    </form>
                                    <form method="post" action="remove_inventory.php" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this item from inventory?');">
                                        <input type="hidden" name="inventory_id" value="<?php echo htmlspecialchars($item['inventory_id']); ?>">
                                        <button class="btn btn-danger btn-sm" type="submit">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No items found in inventory.</p>
            <?php endif; ?>
        </div>
        <!-- Manage Inventory section ending here -->
    </div>


    <!-- JAVASCRIPT starting from here -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script>
        function showSection(sectionId) {
            const sections = document.querySelectorAll('.form-section');
            sections.forEach(section => {
                if (section.id === sectionId + '-section') {
                    section.classList.add('active');
                } else {
                    section.classList.remove('active');
                }
            });
        }

        // Initialize by showing the first section
        //showSection('users');
    </script>
    <!-- JS ending here -->
</body>
</html>

