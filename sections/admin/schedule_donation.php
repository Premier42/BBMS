<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Initialize variables
$donor_id = $unit_id = $donation_date = $location_id = "";
$donor_id_err = $unit_id_err = $donation_date_err = $location_id_err = "";
$success_message = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate donor_id
    if (empty(trim($_POST["donor_id"]))) {
        $donor_id_err = "Please enter a donor ID.";
    } else {
        $donor_id = trim($_POST["donor_id"]);
    }

    // Validate unit_id
    if (empty(trim($_POST["unit_id"]))) {
        $unit_id_err = "Please enter a unit ID.";
    } else {
        $unit_id = trim($_POST["unit_id"]);
    }

    // Validate donation_date
    if (empty(trim($_POST["donation_date"]))) {
        $donation_date_err = "Please enter a donation date.";
    } else {
        $donation_date = trim($_POST["donation_date"]);
    }

    // Validate location_id
    if (empty(trim($_POST["location_id"]))) {
        $location_id_err = "Please enter a location ID.";
    } else {
        $location_id = trim($_POST["location_id"]);
    }

    // Check input errors before inserting in database
    if (empty($donor_id_err) && empty($unit_id_err) && empty($donation_date_err) && empty($location_id_err)) {
        $sql = "INSERT INTO donations (donor_id, unit_id, donation_date, location_id) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iisi", $param_donor_id, $param_unit_id, $param_donation_date, $param_location_id);
            $param_donor_id = $donor_id;
            $param_unit_id = $unit_id;
            $param_donation_date = $donation_date;
            $param_location_id = $location_id;
            if ($stmt->execute()) {
                $success_message = "Donation scheduled successfully.";
                $donor_id = $unit_id = $donation_date = $location_id = "";
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
    <title>Schedule Donation</title>
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
        <h2 class="mb-4">Schedule Donation</h2>
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
            <div class="form-group mb-3 <?php echo (!empty($unit_id_err)) ? 'has-error' : ''; ?>">
                <label>Unit ID</label>
                <input type="text" name="unit_id" class="form-control" value="<?php echo $unit_id; ?>">
                <span class="help-block"><?php echo $unit_id_err; ?></span>
            </div>
            <div class="form-group mb-3 <?php echo (!empty($donation_date_err)) ? 'has-error' : ''; ?>">
                <label>Donation Date</label>
                <input type="date" name="donation_date" class="form-control" value="<?php echo $donation_date; ?>">
                <span class="help-block"><?php echo $donation_date_err; ?></span>
            </div>
            <div class="form-group mb-3 <?php echo (!empty($location_id_err)) ? 'has-error' : ''; ?>">
                <label>Location ID</label>
                <input type="text" name="location_id" class="form-control" value="<?php echo $location_id; ?>">
                <span class="help-block"><?php echo $location_id_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a href="dashboard.php#donations" class="btn btn-secondary ml-2">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
