<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Initialize variables
$user_id = $username = $email = $phone_number = $address = $role = $approved = "";
$username_err = $email_err = $role_err = "";
$success_message = "";

// Retrieve user details for editing if user_id is provided
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['user_id']) && !empty(trim($_GET['user_id']))) {
    $user_id = trim($_GET['user_id']);

    // Prepare a select statement
    $sql = "SELECT * FROM users WHERE user_id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $param_user_id);
        $param_user_id = $user_id;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                // Assign fetched values only if they exist
                if (isset($row['username'])) {
                    $username = $row['username'];
                }
                if (isset($row['email'])) {
                    $email = $row['email'];
                }
                if (isset($row['phone_number'])) {
                    $phone_number = $row['phone_number'];
                }
                if (isset($row['address'])) {
                    $address = $row['address'];
                }
                if (isset($row['role'])) {
                    $role = $row['role'];
                }
                if (isset($row['approved'])) {
                    $approved = $row['approved'];
                }
            } else {
                echo "User not found.";
            }
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close statement
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
    color:forestgreen;
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
    background-color:crimson;
    border: none;
    color: white;
}

.btn-secondary {
    background-color:crimson;
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
        <h2>Edit User</h2>
        <?php
        if (!empty($success_message)) {
            echo '<div class="alert alert-success">' . $success_message . '</div>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
            <div class="form-group mb-3 <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group mb-3 <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group mb-3">
                <label>Phone Number</label>
                <input type="text" name="phone_number" class="form-control" value="<?php echo htmlspecialchars($phone_number); ?>">
            </div>
            <div class="form-group mb-3">
                <label>Address</label>
                <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($address); ?>">
            </div>
            <div class="form-group mb-3">
                <label for="approved">Approved:</label>
                <input type="checkbox" name="approved" id="approved" <?php if ($approved == 1) echo "checked"; ?>>
            </div>
            <div class="form-group mb-3 <?php echo (!empty($role_err)) ? 'has-error' : ''; ?>">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="">Select role</option>
                    <option value="admin" <?php if ($role == 'admin') echo "selected"; ?>>Admin</option>
                    <option value="donor" <?php if ($role == 'donor') echo "selected"; ?>>Donor</option>
                    <option value="recipient" <?php if ($role == 'recipient') echo "selected"; ?>>Recipient</option>
                    <option value="lab_technician" <?php if ($role == 'lab_technician') echo "selected"; ?>>Lab Technician</option>
                    <option value="inventory_manager" <?php if ($role == 'inventory_manager') echo "selected"; ?>>Inventory Manager</option>
                    <option value="hospital_rep" <?php if ($role == 'hospital_rep') echo "selected"; ?>>Hospital Rep</option>
                </select>
                <span class="help-block"><?php echo $role_err; ?></span>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="dashboard.php#users" class="btn btn-secondary ml-2">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
