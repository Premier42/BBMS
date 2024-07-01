<?php
session_start();
require_once '../../config/database.php';

// Function to sanitize user input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);

    // Validate required fields
    if (empty($username) || empty($password)) {
        echo "Please fill in all required fields.";
        exit;
    }

    // Prepare SQL query to fetch user data including 'approved' status
    $sql = "SELECT user_id, password_hash, role, approved FROM users WHERE username = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($user_id, $password_hash, $role, $approved);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $password_hash)) {
                // Check if user is approved
                if ($approved == 0) {
                    echo "Your account is not approved yet. Please contact the administrator.";
                    exit;
                }

                // Store user data in session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                // Redirect based on role
                switch ($role) {
                    case 'admin':
                        header("Location: sections/admin/dashboard.php");
                        break;
                    case 'donor':
                        header("Location: sections/donor/dashboard.php");
                        break;
                    case 'recipient':
                        header("Location: ../recipient_rep/dashboard.php");
                        break;
                    case 'lab_technician':
                        header("Location: sections/lab_technician/dashboard.php");
                        break;
                    case 'inventory_manager':
                        header("Location: sections/inventory_manager/dashboard.php");
                        break;
                    case 'hospital_rep':
                        header("Location: ../hospital_rep/dashboard.php");
                        break;
                    default:
                        echo "Invalid role.";
                        exit;
                }
                exit;
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No user found with that username.";
        }

        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-signin {
            max-width: 330px;
            padding: 30px;
            margin: auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .form-signin .form-floating:focus-within {
            z-index: 2;
        }
        .form-signin input[type="text"],
        .form-signin input[type="password"] {
            margin-bottom: 10px;
        }
        .form-signin button {
            margin-top: 20px;
        }
        .form-signin .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-signin">
            <h1 class="h3 mb-3 fw-normal text-center">Please sign in</h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    <label for="username">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>
                <button class="w-100 btn btn-lg btn-danger" type="submit">Sign in</button>
            </form>
            <button type="button" class="w-100 btn btn-lg btn-primary mt-3" onclick="location.href='register.php'">Register Instead</button>
        </div>        
    </div>
</body>
</html>
