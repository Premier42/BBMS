<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the appropriate role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'inventory_manager') {
    header("Location: ../../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch the blood unit details
if (isset($_GET['id'])) {
    $unit_id = $_GET['id'];

    $sql_unit = "
        SELECT bu.unit_id, bu.blood_type, bu.volume, bu.expiration_date, i.status AS inventory_status
        FROM blood_units bu
        JOIN inventory i ON bu.unit_id = i.unit_id
        WHERE bu.unit_id = ? AND i.inventory_manager_id = ?";
    $stmt_unit = $conn->prepare($sql_unit);
    $stmt_unit->bind_param("ii", $unit_id, $user_id);
    $stmt_unit->execute();
    $result_unit = $stmt_unit->get_result();
    $blood_unit = $result_unit->fetch_assoc();
    $stmt_unit->close();

    if (!$blood_unit) {
        echo "Blood unit not found or you don't have permission to edit this unit.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}

// Update blood unit details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blood_type = $_POST['blood_type'];
    $volume = $_POST['volume'];
    $expiration_date = $_POST['expiration_date'];
    $status = $_POST['status'];

    // Update the blood_units table
    $sql_update_blood_unit = "
        UPDATE blood_units 
        SET blood_type = ?, volume = ?, expiration_date = ?
        WHERE unit_id = ?";
    $stmt_update_blood_unit = $conn->prepare($sql_update_blood_unit);
    $stmt_update_blood_unit->bind_param("sisi", $blood_type, $volume, $expiration_date, $unit_id);
    $stmt_update_blood_unit->execute();
    $stmt_update_blood_unit->close();

    // Update the inventory table
    $sql_update_inventory = "
        UPDATE inventory 
        SET status = ?
        WHERE unit_id = ? AND inventory_manager_id = ?";
    $stmt_update_inventory = $conn->prepare($sql_update_inventory);
    $stmt_update_inventory->bind_param("sii", $status, $unit_id, $user_id);
    $stmt_update_inventory->execute();
    $stmt_update_inventory->close();

    header("Location: dashboard.php");
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blood Unit</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 600px;
            padding: 15px;
            margin: auto;
        }
        .form-section {
            margin-top: 50px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .form-section h2 {
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-section">
            <h2 class="h4 mb-3">Edit Blood Unit</h2>
            <form method="post" action="">
                <div class="form-floating mb-3">
                    <select class="form-select" id="blood_type" name="blood_type" required>
                        <option value="">Select Blood Type</option>
                        <option value="A+" <?php echo ($blood_unit['blood_type'] == 'A+') ? 'selected' : ''; ?>>A+</option>
                        <option value="A-" <?php echo ($blood_unit['blood_type'] == 'A-') ? 'selected' : ''; ?>>A-</option>
                        <option value="B+" <?php echo ($blood_unit['blood_type'] == 'B+') ? 'selected' : ''; ?>>B+</option>
                        <option value="B-" <?php echo ($blood_unit['blood_type'] == 'B-') ? 'selected' : ''; ?>>B-</option>
                        <option value="AB+" <?php echo ($blood_unit['blood_type'] == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                        <option value="AB-" <?php echo ($blood_unit['blood_type'] == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                        <option value="O+" <?php echo ($blood_unit['blood_type'] == 'O+') ? 'selected' : ''; ?>>O+</option>
                        <option value="O-" <?php echo ($blood_unit['blood_type'] == 'O-') ? 'selected' : ''; ?>>O-</option>
                    </select>
                    <label for="blood_type">Blood Type</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="volume" name="volume" placeholder="Volume (in mL)" value="<?php echo htmlspecialchars($blood_unit['volume']); ?>" required>
                    <label for="volume">Volume (in mL)</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="expiration_date" name="expiration_date" placeholder="Expiration Date" value="<?php echo htmlspecialchars($blood_unit['expiration_date']); ?>" required>
                    <label for="expiration_date">Expiration Date</label>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select" id="status" name="status" required>
                        <option value="">Select Status</option>
                        <option value="available" <?php echo ($blood_unit['inventory_status'] == 'available') ? 'selected' : ''; ?>>Available</option>
                        <option value="expired" <?php echo ($blood_unit['inventory_status'] == 'expired') ? 'selected' : ''; ?>>Expired</option>
                    </select>
                    <label for="status">Status</label>
                </div>
                <button class="w-100 btn btn-lg btn-danger" type="submit">Update</button>
            </form>
        </div>
    </div>
</body>
</html>
