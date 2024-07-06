<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the inventory_manager role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'inventory_manager') {
    header("Location: ../auth/login.php");
    exit;
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
    <title>Inventory Manager Dashboard</title>
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

        <a href="#inventory" onclick="showSection('inventory')">
            <i class="fas fa-warehouse"></i> Manage Inventory
        </a>
    </div>

    <div class="content">
        <h1 class="h3 mb-3 fw-normal text-center">
            Inventory Manager Dashboard
            <form class="logout-btn" method="post" action="/sections/auth/logout.php">
                <button class="btn btn-danger" type="submit">Logout</button>
            </form>
        </h1>

        <!-- Manage Inventory Section -->
        <div class="form-section" id="inventory-section">
            <h2 class="h4 mb-3">Manage Inventory</h2>
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
                <p>No inventory items found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            var sections = document.querySelectorAll('.form-section');
            sections.forEach(function(section) {
                section.classList.remove('active');
            });
            document.getElementById(sectionId + '-section').classList.add('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            showSection('inventory');
        });
    </script>
</body>
</html>
