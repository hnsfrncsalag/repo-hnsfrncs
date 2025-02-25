<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
if (isset($_POST['brgyID'])) {
    // Retrieve the brgyID
    $brgyID = $_POST['brgyID'];

    // Now, you can use $brgyID as needed
} else {
    // Handle the case when brgyID is not set
    // echo "BrgyID is not set in the POST data.";
}
if (isset($_POST['residentID'])) {
    // Retrieve the brgyID
    $residentID = $_POST['residentID'];

    // Now, you can use $brgyID as needed
} else {
    // Handle the case when brgyID is not set
    // echo "residentID is not set in the POST data.";
}
if (isset($_POST['userType'])) {
    // Retrieve the brgyID
    $userType = $_POST['userType'];

    // Now, you can use $brgyID as needed
} else {
    // Handle the case when brgyID is not set
    // echo "residentID is not set in the POST data.";
}
if (isset($_POST['name'])) {
    // Retrieve the brgyID
    $name = $_POST['name'];

    // Now, you can use $brgyID as needed
}
// G
// Get the user's information from the session
$user = $_SESSION['user'];
$residentID = isset($_SESSION['user']['residentID']) ? $_SESSION['user']['residentID'] : '';
// Display the dashboard content
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Case</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="petstaticon.png">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #007bff;
        }

        .left-sidebar--sticky-container {
            width: 100%;
        }

        .nav-tabs {
            margin-bottom: 1rem;
        }

        .modal-dialog {
            margin: 0 auto;
        }
    </style>
</head>


<body>
    <div class="row vh-100" style="width: 100%;">
        <div class="col-md-3  shadow-sm p-3 mb-5 bg-white rounded .col-sm-1 .col-lg-2 d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;">
            <div class="left-sidebar--sticky-container js-sticky-leftnav">
                <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                    <a href="dashboard1.php" class="navbar-brand fs-4 d-flex align-items-center ">
                        <img class="bi me-2" width="55" height="55" role="img" aria-label="Bootstrap" src="petstaticon.svg">
                        </img>
                        PETSTAT
                    </a>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="dashboardBAO.php" class="nav-link text-dark">
                                Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="post" action="./BAOpetdashboard.php?active-tab=1">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                                <input type="hidden" name="active-tab" value="1">
                                <button class="nav-link text-dark" aria-current="page" type="submit">My Pet Dashboard</button>
                            </form>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="reportCase.php" id="reportBiteCaseForm" style="display: inline;">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <button class="nav-link active w-100 text-start" type="submit" class="btn">Report Case</button>
                            </form>
                        </li>
                        <li class="nav-item">
                            <form method="post" action="tabularBAO.php" style="display: inline;">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <button type="submit" class="nav-link link-dark">View Reports</button>
                            </form>
                        <!-- <li class="nav-item"><a href="viewHeatmaps.php" class="nav-link link-dark">View Heatmaps</a></li> -->
                        </li>
                        <li class="nav-item">
                            <form method="post" action="./dashboard1.php?active-tab=1" style="display: inline;">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <button type="submit" class="nav-link text-dark">Manage Resident</button>
                            </form>
                        </li>
                        <li class="nav-item">
                            <form method="post" action="./dashboard1pet.php?active-tab=1" style="display: inline;">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <button type="submit" class="nav-link link-dark">Manage Pet</button>
                            </form>
                        </li>
                        <li class="nav-item">
                            <form method="post" action="./dashboardBiteCases.php?active-tab=1" style="display: inline;">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <button type="submit" class="nav-link link-dark">Manage Bite Cases</button>
                            </form>
                        </li>
                        <li>
                            <form method="post" action="./dashboardRabidCases.php?active-tab=1" style="display: inline;">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <button type="submit" class="nav-link link-dark">Manage Suspected Cases</button>
                            </form>
                        </li>
                        <li>
                            <form method="post" action="./dashboardDeathCases.php?active-tab=1" style="display: inline;">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <button type="submit" class="nav-link link-dark">Manage Death Cases</button>
                            </form>
                        </li>
                        <li>
                            <form method="post" action="createAccForResident.php" style="display: inline;">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <button type="submit" class="nav-link link-dark">Create Account for Resident</button>
                            </form>
                        </li>

                        <hr>
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src=" " alt="" width="32" height="32" class="rounded-circle me-2">
                                <strong><?php echo isset($user['name']) ? $user['name'] : ''; ?></strong>
                            </a>
                            <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
                                <li><a class="dropdown-item" href="logout.php" class="btn btn-primary btn-manage">Logout</a></li>
                            </ul>
                        </div>

                    </ul>
            </div>
        </div>

        <div class="col-md-8 p-1 mt-2 my-auto mx-auto">
            <div class="row">

                <!-- <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <form method="post" action="BAOpetdashboard.php?active-tab=1">
                        <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                        <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                        <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                        <button class="navbar-brand" class="btn btn-lg">My Pet Dashboard</button>
                        <button class="navbar-brand" class="btn btn-lg"><?php echo isset($user['name']) ? $user['name'] : ''; ?></button>
                    </form>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <form method="post" action="#">
                                <h4><?php echo isset($user['name']) ? $user['name'] : ''; ?></h4>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav> -->
                <!-- <div class="col-8 d-flex flex-column p-3  justify-content-center align-items-center" style="list-style: none;"> -->
                <div class="container mt-4 gap-2 col-8 mx-auto">
                    <div class="col text-center display:inline">
                        <h1 class="mb-3">Report a Case</h1>
                        <ul class="nav nav-pills d-grid gap-2 col-8 mx-auto">
                            <li class="nav-item mb-2">
                                <form method="POST" action="addBiteCase.php" id="reportBiteCaseForm">
                                    <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                    <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                    <input type="hidden" name="userType" id="userType" value="<?php echo $userType; ?>">
                                    <button type="submit" class="btn btn-primary  btn-lg col-6">Report Bite Case</button>
                                </form>
                            </li>
                            <li class="nav-item mb-2">
                                <form method="POST" action="addDeathCase.php" id="reportBiteCaseForm">
                                    <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                    <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                    <input type="hidden" name="userType" id="userType" value="<?php echo $userType; ?>">
                                    <button type="submit" class="btn btn-primary  btn-lg col-6">Report Death Case</button>
                                </form>
                            </li>

                            <li class="nav-item mb-2">
                                <form method="POST" action="reportRabidBao.php" id="reportBiteCaseForm">
                                    <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                    <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                    <input type="hidden" name="userType" id="userType" value="<?php echo $userType; ?>">
                                    <button type="submit" class="btn btn-primary  btn-lg col-6">Report Rabid Case</button>
                                </form>
                            </li>
                            <!-- <li class="nav-item mb-2">
                        <form method="POST" action="./dashboard1.php?active-tab=1" id="reportDeathCaseForm">
                            <input type="hidden" name="residentID" value="<?php echo $brgyID; ?>">
                            <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                            <input type="hidden" name="userType" id="userType" value="<?php echo $userType; ?>">
                            <button type="submit" class="btn btn-primary btn-lg">Back</button>
                        </form>
                    </li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>