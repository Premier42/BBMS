<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or handle unauthorized access
    header("Location:../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipient Dashboard</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/css/recipients.css">
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-grid-alt"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#">BBMS</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" data-section-id="requestForBlood">
                        <i class="lni lni-help"></i> <span>Request for blood</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" data-section-id="bloodRequests">
                        <i class="lni lni-popup"></i> <span>Blood Request history</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" data-section-id="pendingRequests">
                        <i class="lni lni-agenda"></i> <span>Pending Request</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="../auth/logout.php" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <div class="main">
            <nav class="navbar navbar-expand px-4 py-3">
                <form action="#" class="d-none d-sm-inline-block"></form>
                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <img src="../../assets/Images/account.png" class="avatar img-fluid" alt="">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end rounded">
                                <?php include 'navbar.php'; ?>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content px-3 py-4">
                <div class="container-fluid">
                    <div id="countRequestPending" class="content-section">
                        <?php include 'count_request_pending.php'; ?>
                    </div>

                    <div id="requestForBlood" class="content-section">
                        <h3 class="fw-bold fs-4 mb-3">Request for Blood</h3>
                        <div id="requestForBloodForm">
                            <form id="bloodRequestForm" method="POST" action="request_for_blood.php">
                                <div class="mb-3">
                                    <label for="bloodType" class="form-label">Blood Type</label>
                                    <select class="form-select" id="bloodType" name="bloodType" required>
                                        <option value="">Select Blood Type</option>
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
                                <div class="mb-3">
                                    <label for="volume" class="form-label">Volume (ml)</label>
                                    <input type="number" class="form-control" id="volume" name="volume" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>

                    <div id="bloodRequests" class="content-section">
                        <h3 class="fw-bold fs-4 mb-3">Blood Requests</h3>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Recipient ID</th>
                                            <th scope="col">Blood Type</th>
                                            <th scope="col">Volume (ml)</th>
                                            <th scope="col">Request Date</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php include 'fetch_blood_requests.php'; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="pendingRequests" class="content-section" style="display: none;">
                        <h3 class="fw-bold fs-4 mb-3">Pending Requests</h3>
                        <!-- Add your Pending Requests content here -->
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="../../assets/js/recipient_scripts.js"></script>
</body>

</html>
