<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the appropriate role (e.g., recipient)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'recipient') {
    header("Location: ../auth/login.php");
    exit;
}

// Initialize variables
$request_id = $recipient_id = $blood_type = $volume = "";
$request_id_err = $recipient_id_err = $blood_type_err = $volume_err = "";
$success_message = "";

// Get the blood request details for updating
if (isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];
    $sql = "SELECT * FROM blood_requests WHERE request_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $param_request_id);
        $param_request_id = $request_id;
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $recipient_id = $row['recipient_id'];
                $blood_type = $row['blood_type'];
                $volume = $row['volume'];
            } else {
                echo "Error: Blood request not found.";
                exit;
            }
        } else {
            echo "Something went wrong. Please try again later.";
            exit;
        }
        $stmt->close();
    }
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_request'])) {
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

    // Check input errors before updating in database
    if (empty($blood_type_err) && empty($volume_err)) {
        $sql = "UPDATE blood_requests SET blood_type = ?, volume = ? WHERE request_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssi", $param_blood_type, $param_volume, $param_request_id);
            $param_blood_type = $blood_type;
            $param_volume = $volume;
            $param_request_id = $request_id;
            if ($stmt->execute()) {
                $success_message = "Blood request updated successfully.";
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
    <title>Update Blood Request</title>
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
        <h2 class="mb-4">Update Blood Request</h2>
        <?php 
        if (!empty($success_message)) {
            echo '<div class="alert alert-success success-message">' . $success_message . '</div>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
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
            <div class="btn-group">
                <input type="submit" name="update_request" class="btn btn-primary" value="Update Request">
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
