<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the appropriate role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'inventory_manager') {
    header("Location: ../../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch blood inventory
$sql_inventory = "
    SELECT bu.unit_id, bu.blood_type, bu.volume, bu.expiration_date, i.status
    FROM blood_units bu
    JOIN inventory i ON bu.unit_id = i.unit_id
    WHERE i.inventory_manager_id = ?";
$stmt_inventory = $conn->prepare($sql_inventory);
$stmt_inventory->bind_param("i", $user_id);
$stmt_inventory->execute();
$result_inventory = $stmt_inventory->get_result();
$inventory = $result_inventory->fetch_all(MYSQLI_ASSOC);
$stmt_inventory->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management Dashboard</title>
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
        .btn-delete {
            background-color: #d9534f;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c9302c;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="h3 mb-3 fw-normal text-center">
            Inventory Management Dashboard
            <form class="logout-btn" method="post" action="/sections/auth/logout.php">
                <button class="btn btn-danger" type="submit">Logout</button>
            </form>
        </h1>

        <!-- Add Blood Unit -->
        <div class="form-section">
            <h2 class="h4 mb-3">Add Blood Unit</h2>
            <form method="post" action="add_blood_unit.php">
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
                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="expiration_date" name="expiration_date" placeholder="Expiration Date" required>
                    <label for="expiration_date">Expiration Date</label>
                </div>
                <button class="w-100 btn btn-lg btn-danger" type="submit">Save</button>
            </form>
        </div>

        <!-- Manage Blood Inventory -->
        <div class="form-section">
            <h2 class="h4 mb-3">Manage Blood Inventory</h2>
            <?php if (count($inventory) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Unit ID</th>
                            <th scope="col">Blood Type</th>
                            <th scope="col">Volume (mL)</th>
                            <th scope="col">Expiration Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventory as $unit): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($unit['unit_id']); ?></td>
                                <td><?php echo htmlspecialchars($unit['blood_type']); ?></td>
                                <td><?php echo htmlspecialchars($unit['volume']); ?></td>
                                <td><?php echo htmlspecialchars($unit['expiration_date']); ?></td>
                                <td><?php echo htmlspecialchars($unit['status']); ?></td>
                                <td>
                                    <a href="edit_blood_unit.php?id=<?php echo $unit['unit_id']; ?>" class="btn btn-edit btn-sm">Edit</a>
                                    <a href="delete_blood_unit.php?id=<?php echo $unit['unit_id']; ?>" class="btn btn-delete btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No blood units found in the inventory.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
