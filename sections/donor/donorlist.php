<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the donor role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
    header("Location: ../../auth/donor.php");
    exit;
}

$showalert = false;
$showsuccess = false;
$showalertgender = false;
$showalertblood_group = false;

if (isset($_POST['submit'])) {
    // Confirmation dialog (Note: This alert will not stop the form submission; you might want to handle confirmation via JavaScript on the client-side)
    echo "<script>alert('Are you sure you want to add a new DONOR?')</script>";

    $gender = filter_input(INPUT_POST, "gender", FILTER_SANITIZE_SPECIAL_CHARS);
    $gender = strtoupper($gender);
    $blood_group = filter_input(INPUT_POST, "blood_group", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($gender)) {
        $showalertgender = true;
    } elseif (empty($blood_group)) {
        $showalertblood_group = true;
    } else {
        // Check if the donor already exists
        $existsql = "SELECT * FROM donors WHERE donor_name=? OR donor_email=? OR donor_number=?";
        $stmt = $conn->prepare($existsql);
        $stmt->bind_param("sss", $username, $email, $number);
        $stmt->execute();
        $stmt->store_result();
        $existuser = $stmt->num_rows;

        if ($existuser > 0) {
            $showalert = true;
        } else {
            // Insert new donor
            $sql = "INSERT INTO donors (donor_gender, donor_blood_group) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $gender, $blood_group);
            if ($stmt->execute()) {
                $showsuccess = true;
            } else {
                $showalert = true;
            }
            $stmt->close();
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>Donor Management</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="container-fluid">

    <?php
    if ($showsuccess) {
        echo "<div class='alert alert-success'><strong>Success!</strong> Donor information successfully inserted.</div>";
    }
    if ($showalert) {
        echo "<div class='alert alert-danger'><strong>Error!</strong> Username, email, or number already exists.</div>";
    }
    if ($showalertgender) {
        echo "<div class='alert alert-danger'><strong>Error!</strong> Please select a gender.</div>";
    }
    if ($showalertblood_group) {
        echo "<div class='alert alert-danger'><strong>Error!</strong> Please select a blood group.</div>";
    }
    ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="gender">Gender:</label>
            <select name="gender" class="form-control" id="Gender">
                <option value="">Select one</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <div class="form-group">
            <label for="blood_group">Blood Group:</label>
            <select name="blood_group" class="form-control" id="BloodGroup">
                <option value="">Select one</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>
        </div>

        <br>
        <input type="submit" name="submit" value="Submit" class="form-control btn btn-outline-info">
    </form>
    <br><br>
    <form class="logout-btn" method="post" action="/sections/auth/logout.php">
        <button style="text-align: center;" value="Submit" class="form-control btn btn-outline-info" class="btn btn-danger" type="submit">Logout</button>
    </form>

    <br><br><br>
    
    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>