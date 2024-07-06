<?php
session_start();
require_once '../../config/database.php';

// Check if the user is logged in and has the donor role
/*if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'donor') {
    header("Location: ../sections/auth/login.php");
    exit;
}
    

<?php */
// helloo
$showalert = false;
$showsuccess = false;
$showalertusername = false;
$showalertemail = false;
$showalertgender = false;
$showalertaddress = false;
$showalertnumber = false;

if (isset($_POST['submit'])) {
    echo "<script>alert('Are you sure you want to add a new DONOR?')</script>";

    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $username = strtoupper($username);
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $blood_group = $_POST['blood_group'];
    $address = $_POST['address'];
    $number = $_POST['number'];

    if (empty($username)) {
        $showalertusername = true;
    } elseif (empty($email)) {
        $showalertemail = true;
    } elseif (empty($gender)) {
        $showalertgender = true;
    } elseif (empty($blood_group)) {
        $showalertblood_group = true;
    } elseif (empty($address)) {
        $showalertaddress = true;
    } elseif (empty($number)) {
        $showalertnumber = true;
    } else {
        $existsql = "SELECT * FROM donors WHERE donor_name='$username' OR donor_email='$email' OR donor_number='$number'";
        $existresult = mysqli_query($conn, $existsql);
        $existuser = mysqli_num_rows($existresult);
        if ($existuser) {
            $showalert = true;
        } else {
            $sql = "INSERT INTO donors (donor_name, donor_email, donor_number, donor_address, donor_gender, donor_blood_group) 
                    VALUES ('$username', '$email', '$number', '$address', '$gender', '$blood_group')";
            mysqli_query($conn, $sql);
            $showsuccess = true;
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
        echo "<div class='alert alert-success'><strong>Success!</strong> Donor information successfully inserted</div>";
      }
      if ($showalert) {
        echo "<div class='alert alert-danger'><strong>Error!</strong> Username, email, or number already exists</div>";
      }
      if ($showalertusername) {
        echo "<div class='alert alert-danger'><strong>Error!</strong> Enter username</div>";
      }
      if ($showalertemail) {
        echo "<div class='alert alert-danger'><strong>Error!</strong> Enter email</div>";
      }
      if ($showalertgender) {
        echo "<div class='alert alert-danger'><strong>Error!</strong> Enter gender</div>";
      }
      if ($showalertaddress) {
        echo "<div class='alert alert-danger'><strong>Error!</strong> Enter address</div>";
      }
      if ($showalertnumber) {
        echo "<div class='alert alert-danger'><strong>Error!</strong> Enter number</div>";
      }
    ?>

    <div class="container">
      <h1 class="text-center text-info mt-3">Add Donors</h1>
      <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <div class="form-group">
          <label for="username">Name:</label>
          <input type="text" name="username" class="form-control" placeholder="Name">
          <label for="email">Email:</label>
          <input type="email" name="email" class="form-control" placeholder="Email">
          
          <div class="form-group">
            <label for="gender">Gender:</label>
            <select name="gender" class="form-control" id="Gender">
            <option>Select one</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="blood_group">Blood Group:</label>
            <select name="blood_group" class="form-control" id="BloodGroup">
              <option>Select one</option>
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
          
          <label for="address">Address:</label>
          <input type="text" name="address" class="form-control" placeholder="Address">
          <label for="number">Number:</label>
          <input type="text" name="number" class="form-control" placeholder="Number">
          <br>
          <input type="submit" name="submit" value="Submit" class="form-control btn btn-outline-info">
        </div>
      </form>
      <a href="donorlist.php" class="form-control btn btn-outline-info">See Donors</a>
      <br><br>
            <form class="logout-btn" method="post" action="/sections/auth/logout.php">
                <button style="text-align: center;"  value="Submit" class="form-control btn btn-outline-info" class="btn btn-danger" type="submit">Logout</button>
            </form>
        </h1>
      <br><br><br>
    </div>
    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>