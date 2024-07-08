<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'hospital_rep') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $request_id = $_GET['id'];

    // Fetch the existing request details
    $sql = "SELECT request_id, blood_type, volume FROM blood_requests WHERE request_id = ? AND recipient_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $request_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $request = $result->fetch_assoc();
    $stmt->close();

    if (!$request) {
        // Redirect if request not found or doesn't belong to the user
        header("Location: dashboard.php");
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];
    $blood_type = $_POST['blood_type'];
    $volume = $_POST['volume'];

    // Update the blood request
    $sql_update = "UPDATE blood_requests SET blood_type = ?, volume = ? WHERE request_id = ? AND recipient_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("siii", $blood_type, $volume, $request_id, $user_id);

    if ($stmt_update->execute()) {
        $stmt_update->close();
        $conn->close();
        header("Location: dashboard.php"); // Redirect to dashboard after update
        exit;
    } else {
        echo "Error updating request: " . $stmt_update->error;
    }
} else {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blood Request</title>
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
            margin-top: 50px;
        }
        .form-section {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .form-section h2 {
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 10px;
        }
        .btn-back {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="h4 mb-3 text-center">Edit Blood Request</h2>
        <div class="form-section">
            <form method="post">
                <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($request['request_id']); ?>">
                <div class="form-floating mb-3">
                    <select class="form-select" id="blood_type" name="blood_type" required>
                        <option value="">Select Blood Type</option>
                        <option value="A+" <?php if ($request['blood_type'] === 'A+') echo 'selected'; ?>>A+</option>
                        <option value="A-" <?php if ($request['blood_type'] === 'A-') echo 'selected'; ?>>A-</option>
                        <option value="B+" <?php if ($request['blood_type'] === 'B+') echo 'selected'; ?>>B+</option>
                        <option value="B-" <?php if ($request['blood_type'] === 'B-') echo 'selected'; ?>>B-</option>
                        <option value="AB+" <?php if ($request['blood_type'] === 'AB+') echo 'selected'; ?>>AB+</option>
                        <option value="AB-" <?php if ($request['blood_type'] === 'AB-') echo 'selected'; ?>>AB-</option>
                        <option value="O+" <?php if ($request['blood_type'] === 'O+') echo 'selected'; ?>>O+</option>
                        <option value="O-" <?php if ($request['blood_type'] === 'O-') echo 'selected'; ?>>O-</option>
                    </select>
                    <label for="blood_type">Blood Type</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="volume" name="volume" placeholder="Volume (in mL)" required value="<?php echo htmlspecialchars($request['volume']); ?>">
                    <label for="volume">Volume (in mL)</label>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit">Update Request</button>
                <a href="dashboard.php" class="btn btn-lg btn-secondary btn-back w-100 mt-2">Go Back to Dashboard</a>
            </form>
        </div>
    </div>
</body>
</html>
