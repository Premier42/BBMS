<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the recipient role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'recipient') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipient Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

        .table th,
        .table td {
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
            width: 100%;
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

            .table th,
            .table td {
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

        <!-- Sidebar Links -->
        <!-- Sidebar Links -->
        <a href="#" onclick="showSection('countRequestPending')" class="active">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="#" onclick="showSection('requestForBlood')">
            <i class="fa-solid fa-droplet"></i> Request for Blood
        </a>
        <a href="#" onclick="showSection('bloodRequests')">
            <i class="fa-solid fa-notes-medical"></i>Blood Requests History
        </a>
        <a href="#" onclick="showSection('inventory-section')">
            <i class="fas fa-vials"></i> Available Blood
        </a>

    </div>

    <!-- Content Area -->
    <div class="content">
        <!-- Count Pending Requests Section -->
        <div id="countRequestPending" class="form-section active">
            <?php include 'count_pending.php'; ?>
        </div>

        <!-- Available Manage Blood Section -->
        <div id="inventory-section" class="form-section">
            <h2 class="h4 mb-3">Available blood </h2>
            <?php include 'fetch_available_inventory.php'; ?>
            <?php if (count($inventory) > 0) : ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Inventory ID</th>
                            <th scope="col">Unit Id</th>
                            <th scope="col">Inventory Manager Id</th>
                            <th scope="col">Received Date</th>
                            <th scope="col">Expiration Date</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventory as $item) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['inventory_id']); ?></td>
                                <td><?php echo htmlspecialchars($item['unit_id']); ?></td>
                                <td><?php echo htmlspecialchars($item['inventory_manager_id']); ?></td>
                                <td><?php echo htmlspecialchars($item['received_date']); ?></td>
                                <td><?php echo htmlspecialchars($item['expiration_date']); ?></td>
                                <td><?php echo htmlspecialchars($item['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No inventory items found.</p>
            <?php endif; ?>
        </div>

        <!-- Request for Blood Section -->
        <div id="requestForBlood" class="form-section">
            <h2 class="h4 mb-3">Request for Blood</h2>
            <form id="bloodRequestForm" method="POST" action="request_for_blood.php">
                <div class="mb-3">
                    <label for="bloodType" class="form-label">Blood Type</label>
                    <select class="form-select" id="bloodType" name="bloodType" required>
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
                </div>
                <div class="mb-3">
                    <label for="volume" class="form-label">Volume (ml)</label>
                    <input type="number" class="form-control" id="volume" name="volume" required>
                </div>
                <button type="submit" class="custom-btn btn-primary">Submit</button>
            </form>
        </div>

        <!-- Blood Requests Section -->
        <div id="bloodRequests" class="form-section">
            <h2 class="h4 mb-3">Blood Requests History</h2>
            <div class="row">
                <div class="col-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Recipient ID</th>
                                <th scope="col">Blood Type</th>
                                <th scope="col">Volume (ml)</th>
                                <th scope="col">Request Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include 'fetch_blood_requests.php'; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Button -->
    <div class="logout-btn">
        <form action="../auth/logout.php" method="POST">
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>

    <!-- Script to Toggle Form Sections -->
    <!-- Script to Toggle Form Sections -->
    <script>
        function showSection(sectionId) {
            var sections = document.getElementsByClassName("form-section");
            for (var i = 0; i < sections.length; i++) {
                sections[i].classList.remove('active');
            }
            document.getElementById(sectionId).classList.add('active');

            // Show inventory section if dashboard is clicked
            if (sectionId === 'countRequestPending') {
                document.getElementById('inventory-section').classList.add('active');
            }
        }

        // Initially show the default section
        showSection('countRequestPending');
    </script>

</body>

</html>