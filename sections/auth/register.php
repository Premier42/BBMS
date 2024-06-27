<?php
session_start();

// Include database connection
require 'config/database.php';

// Function to sanitize input data
function sanitize($data, $conn) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize($_POST['email'], $conn);
    $password = sanitize($_POST['password'], $conn);
    $role = sanitize($_POST['role'], $conn);

    // Check if role is valid
    $valid_roles = ['admin', 'donor', 'recipient', 'lab_technician', 'inventory_manager', 'hospital_rep'];
    if (!in_array($role, $valid_roles)) {
        $error = "Invalid role selected.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Set approval status
        $approved = ($role == 'donor' || $role == 'recipient') ? 1 : 0;

        // Insert user into the database
        $sql = "INSERT INTO users (email, password, role, approved) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $email, $hashed_password, $role, $approved);

        if ($stmt->execute()) {
            if ($approved) {
                $_SESSION['user_id'] = $stmt->insert_id;
                header("Location: welcome.php");
            } else {
                $message = "Registration successful. Your account needs admin approval.";
            }
        } else {
            $error = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mt-5">Register</h2>
                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php } elseif (isset($message)) { ?>
                    <div class="alert alert-success"><?php echo $message; ?></div>
                <?php } ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="donor">Donor</option>
                            <option value="recipient">Recipient</option>
                            <option value="lab_technician">Lab Technician</option>
                            <option value="inventory_manager">Inventory Manager</option>
                            <option value="hospital_rep">Hospital Representative</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
