<?php
session_start();
// Include your database configuration file
require_once '../../config/database.php';

// Include role privileges
require_once '../../config/role_privileges.php';

// Function to sanitize user input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);
    $email = sanitize_input($_POST['email']);
    $phone_number = sanitize_input($_POST['phone_number']);
    $address = sanitize_input($_POST['address']);
    $role = sanitize_input($_POST['role']);

    // Validate required fields (server-side validation)
    if (empty($username) || empty($password) || empty($email) || empty($role)) {
        echo "<div class='alert alert-danger'>Please fill in all required fields.</div>";
        exit;
    }

    // Check if the username already exists
    $sql_check_username = "SELECT COUNT(*) as count FROM users WHERE username = ?";
    $stmt_check_username = $conn->prepare($sql_check_username);
    $stmt_check_username->bind_param("s", $username);
    $stmt_check_username->execute();
    $result_check_username = $stmt_check_username->get_result();
    $row = $result_check_username->fetch_assoc();
    $stmt_check_username->close();

    if ($row['count'] > 0) {
        echo "<div class='alert alert-danger'>Username already taken. Please choose a different username.</div>";
    } else {
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Modify your existing registration logic to set 'approved' based on role
        if ($role == 'lab_technician' || $role == 'inventory_manager' || $role == 'hospital_rep' || $role == 'admin') {
            $approved = 0; // Initial approval status set to false (0 in MySQL)
        } else {
            $approved = 1; // Roles like 'donor', 'recipient' are approved immediately (1 in MySQL)
        }

        // Prepare SQL query to insert user data
        $sql = "INSERT INTO users (username, password_hash, role, email, phone_number, address, approved)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssssi", $username, $password_hash, $role, $email, $phone_number, $address, $approved);

            // Execute the statement
            if ($stmt->execute()) {
                // Registration successful message
                echo "<div class='alert alert-success'>Registration successful. ";

                // Retrieve the last inserted ID
                $last_inserted_id = $stmt->insert_id;

                // Check if approval is needed
                if ($approved == 0) {
                    echo "Waiting for admin approval.</div>";
                } else {
                    // Store user role and privileges in session
                    $_SESSION['user_id'] = $last_inserted_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role;
                    $_SESSION['privileges'] = $rolePrivileges[$role];

                    echo "Redirecting to login page...</div>";
                    header("refresh:3;url=login.php");
                }
            } else {
                // Error message if execution fails
                echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
            }

            $stmt->close();
        } else {
            // Error message if SQL preparation fails
            echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
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
    <title>Register</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-signup {
            max-width: 500px;
            padding: 30px;
            margin: auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .form-signup .form-floating:focus-within {
            z-index: 2;
        }
        .form-signup input[type="text"],
        .form-signup input[type="password"],
        .form-signup input[type="email"],
        .form-signup input[type="tel"],
        .form-signup input[type="address"],
        .form-signup select {
            margin-bottom: 10px;
        }
        .form-signup button {
            margin-top: 20px;
        }
        .form-signup .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-signup">
            <h1 class="h3 mb-3 fw-normal text-center">Register</h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    <label for="username">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    <label for="email">Email</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="tel" class="form-control" id="phone_number" name="phone_number" placeholder="Phone Number" required>
                    <label for="phone_number">Phone Number</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
                    <label for="address">Address</label>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select" id="role" name="role" required>
                        <option value="donor">Donor</option>
                        <option value="recipient">Recipient</option>
                        <option value="lab_technician">Lab Technician</option>
                        <option value="inventory_manager">Inventory Manager</option>
                        <option value="hospital_rep">Hospital Representative</option>
                        <option value="admin">Admin</option>
                    </select>
                    <label for="role">Role</label>
                </div>
                <button class="w-100 btn btn-lg btn-danger" type="submit">Register</button>
                <button type="button" class="w-100 btn btn-lg btn-primary mt-3" onclick="location.href='login.php'">Login Instead</button>
            </form>
        </div>
    </div>
</body>
</html>
