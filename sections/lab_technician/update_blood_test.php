<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the appropriate role (e.g., lab_technician)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'lab_technician') {
    header("Location: ../auth/login.php");
    exit;
}

// Initialize variables
$unit_id = $test_result = "";
$unit_id_err = $test_result_err = "";
$success_message = "";

// Get the blood test result details for updating
if (isset($_POST['unit_id'])) {
    $unit_id = $_POST['unit_id'];
    $sql = "SELECT * FROM blood_test_results WHERE unit_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $param_unit_id);
        $param_unit_id = $unit_id;
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $test_result = $row['test_result'];
            } else {
                echo "Error: Blood test result not found.";
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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_test'])) {
    // Validate test_result
    if (empty(trim($_POST["test_result"]))) {
        $test_result_err = "Please select a test result.";
    } else {
        $test_result = trim($_POST["test_result"]);
    }

    // Check input errors before updating in database
    if (empty($test_result_err)) {
        $sql = "UPDATE blood_test_results SET test_result = ? WHERE unit_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("si", $param_test_result, $param_unit_id);
            $param_test_result = $test_result;
            $param_unit_id = $unit_id;
            if ($stmt->execute()) {
                $success_message = "Blood test result updated successfully.";
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
    <title>Update Blood Test Result</title>
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
        <h2 class="mb-4">Update Blood Test Result</h2>
        <?php 
        if (!empty($success_message)) {
            echo '<div class="alert alert-success success-message">' . $success_message . '</div>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="unit_id" value="<?php echo $unit_id; ?>">
            <div class="form-group mb-3 <?php echo (!empty($test_result_err)) ? 'has-error' : ''; ?>">
                <label>Test Result</label>
                <select name="test_result" class="form-control">
                    <option value="">Select test result</option>
                    <option value="positive" <?php echo ($test_result == 'positive') ? 'selected' : ''; ?>>Positive</option>
                    <option value="negative" <?php echo ($test_result == 'negative') ? 'selected' : ''; ?>>Negative</option>
                </select>
                <span class="help-block"><?php echo $test_result_err; ?></span>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary" name="update_test">Update</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
