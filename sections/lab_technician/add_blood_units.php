<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the appropriate role (e.g., lab_technician)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'lab_technician') {
    header("Location: ../auth/login.php");
    exit;
}

// Initialize variables
$donor_id = $blood_type = $volume = $status = "";
$donor_id_err = $blood_type_err = $volume_err = $status_err = "";
$success_message = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate donor_id
    if (empty(trim($_POST["donor_id"]))) {
        $donor_id_err = "Please enter a donor ID.";
    } else {
        $donor_id = trim($_POST["donor_id"]);
    }

    // Validate blood_type
    if (empty(trim($_POST["blood_type"]))) {
        $blood_type_err = "Please select a blood type.";
    } else {
        $blood_type = trim($_POST["blood_type"]);
    }

    // Validate volume
    if (empty(trim($_POST["volume"]))) {
        $volume_err = "Please enter the volume.";
    } else {
        $volume = trim($_POST["volume"]);
    }

    // Validate status
    if (empty(trim($_POST["status"]))) {
        $status_err = "Please select a status.";
    } else {
        $status = trim($_POST["status"]);
    }

    // Check input errors before inserting in database
    if (empty($donor_id_err) && empty($blood_type_err) && empty($volume_err) && empty($status_err)) {
        $sql = "INSERT INTO blood_units (donor_id, blood_type, volume, expiration_date, status) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("isiss", $param_donor_id, $param_blood_type, $param_volume, $param_expiration_date, $param_status);
            $param_donor_id = $donor_id;
            $param_blood_type = $blood_type;
            $param_volume = $volume;
            $param_expiration_date = date('Y-m-d', strtotime('+42 days')); // Set expiration_date to 42 days after current date
            $param_status = $status;
            if ($stmt->execute()) {
                $success_message = "Blood unit added successfully.";
                $donor_id = $blood_type = $volume = $status = "";
            } else {
                echo "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Blood Unit</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
body {
    background-color: #f8f9fa;
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.container {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 500px;
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-weight: bold;
    color: #555;
}

.form-control {
    border-radius: 5px;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ced4da;
    transition: border-color 0.3s ease-in-out;
    width: 100%;
    box-sizing: border-box;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 5px rgba(128, 189, 255, 0.5);
}

.has-error .form-control {
    border-color: red;
}

.has-error .help-block {
    color: red;
    margin-top: 5px;
}

.help-block {
    font-size: 14px;
}

.success-message {
    color: forestgreen;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
}

.btn-group {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
}

.btn {
    border-radius: 5px;
    padding: 10px 20px;
    font-size: 16px;
    flex: 1;
    margin-right: 10px;
}

.btn:last-child {
    margin-right: 0;
}

.btn-primary {
    background-color: crimson;
    border: none;
    color: white;
}

.btn-secondary {
    background-color: crimson;
    border: none;
    color: white;
}

.btn-primary:hover,
.btn-secondary:hover {
    opacity: 0.9;
}

@media (max-width: 768px) {
    .container {
        padding: 15px;
    }
    .btn-group {
        flex-direction: column;
    }
    .btn {
        width: 100%;
        margin-bottom: 10px;
    }
    .btn:last-child {
        margin-bottom: 0;
    }
}
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Add Blood Unit</h2>
        <?php 
        if (!empty($success_message)) {
            echo '<div class="alert alert-success success-message">' . $success_message . '</div>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group mb-3 <?php echo (!empty($donor_id_err)) ? 'has-error' : ''; ?>">
                <label>Donor ID</label>
                <input type="text" name="donor_id" class="form-control" value="<?php echo $donor_id; ?>">
                <span class="help-block"><?php echo $donor_id_err; ?></span>
            </div>
            <div class="form-group mb-3 <?php echo (!empty($blood_type_err)) ? 'has-error' : ''; ?>">
                <label>Blood Type</label>
                <select name="blood_type" class="form-control">
                    <option value="">Select blood type</option>
                    <option value="A+" <?php echo ($blood_type == 'A+') ? 'selected' : ''; ?>>A+</option>
                    <option value="A-" <?php echo ($blood_type == 'A-') ? 'selected' : ''; ?>>A-</option>
                    <option value="B+" <?php echo ($blood_type == 'B+') ? 'selected' : ''; ?>>B+</option>
                    <option value="B-" <?php echo ($blood_type == 'B-') ? 'selected' : ''; ?>>B-</option>
                    <option value="AB+" <?php echo ($blood_type == 'AB+') ? 'selected' : ''; ?>>AB+</option>
                    <option value="AB-" <?php echo ($blood_type == 'AB-') ? 'selected' : ''; ?>>AB-</option>
                    <option value="O+" <?php echo ($blood_type == 'O+') ? 'selected' : ''; ?>>O+</option>
                    <option value="O-" <?php echo ($blood_type == 'O-') ? 'selected' : ''; ?>>O-</option>
                </select>
                <span class="help-block"><?php echo $blood_type_err; ?></span>
            </div>
            <div class="form-group mb-3 <?php echo (!empty($volume_err)) ? 'has-error' : ''; ?>">
                <label>Volume (ml)</label>
                <input type="text" name="volume" class="form-control" value="<?php echo $volume; ?>">
                <span class="help-block"><?php echo $volume_err; ?></span>
            </div>
            <div class="form-group mb-3 <?php echo (!empty($status_err)) ? 'has-error' : ''; ?>">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">Select status</option>
                    <option value="available" <?php echo ($status == 'available') ? 'selected' : ''; ?>>Available</option>
                    <option value="unavailable" <?php echo ($status == 'unavailable') ? 'selected' : ''; ?>>Unavailable</option>
                    <option value="donated" <?php echo ($status == 'donated') ? 'selected' : ''; ?>>Donated</option>
                </select>
                <span class="help-block"><?php echo $status_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a href="dashboard.php#blood_units" class="btn btn-secondary ml-2">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
