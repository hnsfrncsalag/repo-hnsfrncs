<?php
session_start();
require_once "class/db_connect.php";
require_once("class/barangay.php");
require_once("class/cases.php");
require_once("class/resident.php");
require_once("class/notification.php");

// Check if the user is logged in and has admin privileges (userType = 1)
$user = $_SESSION['user'];
if (!isset($_SESSION['user']) || $_SESSION['user']['userType'] != 1) {
    header("Location: login.php");
    exit();
}
$barangay = isset($_SESSION['user']['barangay']) ? $_SESSION['user']['barangay'] : '';
$userType = isset($_SESSION['user']['userType']) ? $_SESSION['user']['userType'] : '';
$name = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : '';
$user = $_SESSION['user'];
$brgyID = isset($_SESSION['user']['brgyID']) ? $_SESSION['user']['brgyID'] : '';
$residentID = isset($_SESSION['user']['residentID']) ? $_SESSION['user']['residentID'] : '';
$resident = new Resident();
$notif = new Notification();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
if (isset($_SESSION['user']['brgyID'])) {
    // Retrieve the brgyID
    $brgyID = isset($_SESSION['user']['brgyID']) ? $_SESSION['user']['brgyID'] : '';

    $barangay = new Barangay();
    // $brgyID = isset($user['brgyID']) ? $user['brgyID'] : '';
    $result = $barangay->getBrgyName($brgyID);
    $barangay = new Barangay();
    $case = new Cases();
    $count = new Cases();
    $count1 = new Cases();
    $count2 = new Cases();
    $count3 = new Cases();
    $lat = [];
    $lng = [];
    $lat1 = [];
    $lng1 = [];
    $barangayName = ""; // Initialize $barangayName

    // $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

    if (!$user || $user['userType'] != '1') { // note the change in comparison
        header("Location: login.php?error=Unauthorized");
        exit();
    }
    // $user = $_SESSION['user'];

    // if (!isset($_SESSION['user']) || $_SESSION['user']['userType'] != 1) {
    //     header("Location: login.php");
    //     exit();
    // }

    $brgyID = $user['brgyID'];

    $users = $case->getAllValidCaseByBarangay($brgyID);

    if ($users && $users->num_rows > 0) {
        $heatmapData = [];

        while ($row = $users->fetch_assoc()) {
            // Assuming latitude and longitude are always present, handle null or empty values if necessary
            $lat = floatval($row['latitude']); // Convert to float
            $lng = floatval($row['longitude']); // Convert to float
            $heatmapData[] = [$lat, $lng];
        }
    }
    $brgyLocation = $barangay->getBrgyLocation($brgyID);

    if (!empty($brgyLocation) && isset($brgyLocation[0])) {
        $barangayName = $brgyLocation[0]["barangay"];
        $lat1 = $brgyLocation[0]["latitude"];
        $lng1 = $brgyLocation[0]["longitude"];
    } else {
        echo "No location information available for the given barangay ID.\n";
    }
    $counts = $count->countCase($brgyID);
    $counts1 = $count1->countAllCase($brgyID);
    $counts2 = $count2->countAllCasePerYear($brgyID);
    $counts3 = $count3->countAllValidCasePerYear($brgyID);
    $brgyLocation = $barangay->getBrgyLocation($brgyID);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard BAO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.maptiler.com/maptiler-sdk-js/v1.1.1/maptiler-sdk.umd.js"></script>
    <link href="https://cdn.maptiler.com/maptiler-sdk-js/v1.1.1/maptiler-sdk.css" rel="stylesheet" />
    <script src="https://cdn.maptiler.com/leaflet-maptilersdk/v1.0.0/leaflet-maptilersdk.js"></script>
    <script src="https://unpkg.com/heatmap.js"></script>
    <script src="https://unpkg.com/leaflet.heat"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- <link rel="manifest" href="/manifest.json"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="petstaticon.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }

        /* 
        .container {
            margin-top: 50px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            height: 100%;
            width: 200%;
            margin-left: -200px;
            margin-right: auto;
            align-items: start;
        } */

        .container {
            /* margin-top: 50px; */
            background-color: #ffffff;
            /* padding: 20px; */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* .container .content {
            flex: 1;
            overflow-y: auto;
        } */

        h1 {
            color: black;
            font-weight: bold;
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

        #main-content {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
        }

        /* #map {
            width: 100%;
            Adjust width to match container
            height: 50vh;
            border: none;
            Remove border
        } */
        #map {
            /* max-width: 80%; */
            height: 50vh;
            /* margin: 10px auto; */
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;

        }

        #myChart,
        #pieChart {
            width: 300%;
            /* Adjust width to match container */
            border: black;
            /* Remove border */
        }




        @media (min-width: 576px) {
            .h-sm-100 {
                height: 100%;
            }
        }



        .chart-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: start;
        }

        @media (min-width: 768px) {
            .chart-container {
                flex-wrap: nowrap;
            }
        }

        canvas {
            max-width: 200%;
            height: auto;
        }

        /* .col-12,
        .col-xl-2 {
            width: 20%;
        }

        .px-sm-2,
        .px-0 {
            padding-right: 20px;
            padding-left: 20px;
        }

        .pt-2 {
            padding-top: 20px;
        }

        .bg-gray {
            background-color: #c5c6d0;
        } */
    </style>
</head>
<?php
// Count the number of new notifications
$sql_count = "SELECT COUNT(*) AS new_notif_count FROM notification WHERE notifType IN (0, 1, 2, 3, 4, 5, 6, 7, 8) AND (MAOID = ? OR brgyID = ?) AND BAOread = 0";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("ii", $residentID, $brgyID);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$new_notif_count = 0;
if ($row_count = $result_count->fetch_assoc()) {
    $new_notif_count = $row_count['new_notif_count'];
}
$stmt_count->close();
?>

<body>
    <div class="container-fluid overflow-hidden">
        <div class="row overflow-auto">
            <div class="col-12 col-sm-3 shadow-sm bg-white col-xl-2 px-sm-2 px-0 d-flex sticky-top">
                <div class="d-flex flex-sm-column flex-row flex-grow-1 align-items-center align-items-sm-start px-3 pt-2 text-white">
                    <!-- <div class="row " style="width: 100%;">
                        <div class="container-fluid">
                            <div class="col-md-3 shadow-sm p-3 mb-5 bg-white rounded .col-sm-1 .col-lg-2 d-flex flex-column flex-shrink-0 p-3 bg-light " style="width: 280px;">
                                <div class="left-sidebar--sticky-container js-sticky-leftnav">
                                    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none"> -->
                    <div class="navbar-brand fs-4 d-flex align-items-center mb-3 mb-md-0 me-md-auto">
                        <a href="#" class="d-flex align-items-center text-black text-decoration-none">
                            <span class="">
                                <img class="bi me-2" width="55" height="55" role="img" src="petstaticon.svg"></img>
                                <span class="d-none d-sm-inline pt-3" style="vertical-align: -10px;"><strong>PETSTAT</strong></span>
                            </span>
                        </a>
                        <div class="d-flex align-items-center text-black text-decoration-none" style="position: relative;">
                            <a href="#" class="btn btn-link me-2" data-bs-toggle="modal" data-bs-target="#notificationModal" style="padding-top: 7px; position: relative;">
                                <i class="bi bi-bell fs-5 text-black" style="vertical-align: -20px;"></i>
                                <?php if ($new_notif_count > 0) { ?>
                                    <div class="notification-indicator" style="background-color: red; color: white; width: 20px; height: 20px; border-radius: 50%; position: absolute; top: 15px; left: 25px; display: flex; justify-content: center; align-items: center; font-size: 12px;"><?php echo $new_notif_count; ?></div>
                                <?php } ?>
                            </a>
                        </div>
                    </div>
                    <!-- </div> -->


                    <ul class="nav nav-underline flex-sm-column flex-row flex-nowrap flex-shrink-1 flex-sm-grow-0 flex-grow-1 mb-sm-auto justify-content-center align-items-center align-items-sm-start" id="menu">
                        <li class="nav-item">
                            <a href="#" class="flex-sm-fill text-sm-center nav-link active" aria-current="page">
                                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                    <path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z" />
                                </svg><span class="ms-2 d-none d-sm-inline">Home</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="post" action="./BAOpetdashboard.php?active-tab=1">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                                <input type="hidden" name="active-tab" value="1">
                                <button type="submit" data-bs-toggle="collapse" class="nav-link text-dark px-sm-0 px-2"> <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                        <path d="M226.5 92.9c14.3 42.9-.3 86.2-32.6 96.8s-70.1-15.6-84.4-58.5s.3-86.2 32.6-96.8s70.1 15.6 84.4 58.5zM100.4 198.6c18.9 32.4 14.3 70.1-10.2 84.1s-59.7-.9-78.5-33.3S-2.7 179.3 21.8 165.3s59.7 .9 78.5 33.3zM69.2 401.2C121.6 259.9 214.7 224 256 224s134.4 35.9 186.8 177.2c3.6 9.7 5.2 20.1 5.2 30.5v1.6c0 25.8-20.9 46.7-46.7 46.7c-11.5 0-22.9-1.4-34-4.2l-88-22c-15.3-3.8-31.3-3.8-46.6 0l-88 22c-11.1 2.8-22.5 4.2-34 4.2C84.9 480 64 459.1 64 433.3v-1.6c0-10.4 1.6-20.8 5.2-30.5zM421.8 282.7c-24.5-14-29.1-51.7-10.2-84.1s54-47.3 78.5-33.3s29.1 51.7 10.2 84.1s-54 47.3-78.5 33.3zM310.1 189.7c-32.3-10.6-46.9-53.9-32.6-96.8s52.1-69.1 84.4-58.5s46.9 53.9 32.6 96.8s-52.1 69.1-84.4 58.5z" />
                                    </svg> <span class="ms-2 d-none d-sm-inline">Pet Dashboard</span></button>
                            </form>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="nav-link dropdown-toggle text-decoration-none text-dark px-sm-0 px-1" id="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                    <path d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V299.6l-94.7 94.7c-8.2 8.2-14 18.5-16.8 29.7l-15 60.1c-2.3 9.4-1.8 19 1.4 27.8H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zM549.8 235.7l14.4 14.4c15.6 15.6 15.6 40.9 0 56.6l-29.4 29.4-71-71 29.4-29.4c15.6-15.6 40.9-15.6 56.6 0zM311.9 417L441.1 287.8l71 71L382.9 487.9c-4.1 4.1-9.2 7-14.9 8.4l-60.1 15c-5.5 1.4-11.2-.2-15.2-4.2s-5.6-9.7-4.2-15.2l15-60.1c1.4-5.6 4.3-10.8 8.4-14.9z" />
                                </svg><span class="ms-2 d-none d-sm-inline">Report Case</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark text-lg" aria-labelledby="dropdown">
                                <li><a class="dropdown-item" href="addBiteCase.php">Report Bite Case</a></li>
                                <!-- <li><a class="dropdown-item" href="addDeathCase.php">Report Death Case</a></li> -->
                                <li><a class="dropdown-item" href="reportRabidBao.php">Report Rabid Case</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="nav-link dropdown-toggle text-decoration-none text-dark px-sm-0 px-1" id="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                    <path d="M448 160H320V128H448v32zM48 64C21.5 64 0 85.5 0 112v64c0 26.5 21.5 48 48 48H464c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48H48zM448 352v32H192V352H448zM48 288c-26.5 0-48 21.5-48 48v64c0 26.5 21.5 48 48 48H464c26.5 0 48-21.5 48-48V336c0-26.5-21.5-48-48-48H48z" />
                                </svg><span class="ms-2 d-none d-sm-inline">Manage</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark text-lg " aria-labelledby="dropdown">
                                <li><a class="dropdown-item" href="./dashboard1.php?active-tab=1">Manage Resident</a></li>
                                <li><a class="dropdown-item" href="./dashboard1pet.php?active-tab=1">Manage Pet</a></li>
                                <li><a class="dropdown-item" href="./dashboardBiteCases.php?active-tab=1">Manage Bite Cases</a></li>
                                <li><a class="dropdown-item" href="./dashboardRabidCases.php?active-tab=1">Manage Suspected Cases</a></li>
                                <!-- <li><a class="dropdown-item" href="./dashboardDeathCases.php?active-tab=1">Manage Death Cases</a></li> -->
                            </ul>
                        </li>
                        <!-- <li class="nav-item">
                            <form method="POST" action="reportCase.php" id="reportBiteCaseForm" style="display: inline;">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <button class="nav-link text-dark" type="submit" class="btn">Report Case</button>
                            </form>
                        </li> -->
                        <li class="nav-item">
                            <form method="post" action="./tabularBAO.php?active-tab=1" style="display: inline;">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <button type="submit" data-bs-toggle="collapse" class="nav-link text-dark px-sm-0 px-2"> <svg xmlns="http://www.w3.org/2000/svg" height="20" width="15" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                        <path d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128z" />
                                    </svg><span class="ms-2 d-none d-sm-inline"> Reports</span></button>
                            </form>
                        </li>

                        <!-- <li class="nav-item"><a href="viewHeatmaps.php" class="nav-link text-dark">View Heatmaps</a></li> -->
                        <!-- <li class="nav-item">
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
                                <button type="submit" class="nav-link text-dark">Manage Pet</button>
                            </form>
                        </li>
                        <li class="nav-item">
                            <form method="post" action="./dashboardBiteCases.php?active-tab=1" style="display: inline;">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <button type="submit" class="nav-link text-dark">Manage Bite Cases</button>
                            </form>
                        </li>
                        <li>
                            <form method="post" action="./dashboardRabidCases.php?active-tab=1" style="display: inline;">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <button type="submit" class="nav-link text-dark">Manage Suspected Cases</button>
                            </form>
                        </li>
                        <li>
                            <form method="post" action="./dashboardDeathCases.php?active-tab=1" style="display: inline;">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <button type="submit" class="nav-link text-dark">Manage Death Cases</button>
                            </form>
                        </li> -->

                        <li class="nav-item">
                            <form method="post" action="createAccForResident.php" style="display: inline;">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <button type="submit" data-bs-toggle="collapse" class="nav-link text-dark px-sm-0 px-2"> <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                        <path d="M96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3zM504 312V248H440c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V136c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H552v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z" />
                                    </svg><span class="ms-2 d-none d-sm-inline">Create Account </span></button>
                                </a>
                            </form>
                        </li>
                        <div class="dropdown py-sm-4 mt-sm-auto ms-auto ms-sm-0 flex-shrink-1">
                            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" class="rounded-circle me-2 outline" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                    <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z" />
                                </svg>
                                <strong><span class="d-none d-sm-inline mx-1"> <?php echo isset($user['name']) ? $user['name'] : ''; ?> </span>
                                </strong>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </div>
                    </ul>
                </div>
            </div>

            <div class="col d-flex flex-column h-sm-200">
                <main class="row overflow-auto">
                    <div class="col-md-10 p-1 mt-2 my-auto mx-auto">
                        <div class="container mt-2 p-3">
                            <strong>Filter: </strong>
                            <select class="form-select mb-2" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                                <option value="">Select...</option>
                                <option value="brgyFilterCase.php">Cases</option>
                                <option value="brgyFilterPet.php">Pets</option>
                            </select>
                        </div>
                        <div class="container mt-2 p-3">
                            <h1 class="text-center">Pet Statistic for Barangay <?php echo $barangayName ?></h1>
                            <div id="map"></div>
                            <script>
                                const key = 'A8yOIIILOal2yE0Rvb63';

                                // Use PHP to echo the latitude and longitude values
                                const lat1 = <?php echo $lat1; ?>;
                                const lng1 = <?php echo $lng1; ?>;

                                const map = L.map('map').setView([lat1, lng1], 15);

                                const mtLayer = L.maptilerLayer({
                                    apiKey: key,
                                    style: "8a85054c-5879-4e0b-b2f8-7f9564b6e3f8", //optional
                                }).addTo(map);

                                var heatmapData = [];

                                // Create heatmap layer using Leaflet Heatmap Overlay plugin
                                var heat = L.heatLayer(heatmapData, {
                                    radius: 20,
                                    blur: 30,
                                    maxZoom: 18,
                                    max: .3, // Remove concentrationFactor from here
                                    gradient: {
                                        0.5: 'green'
                                    }
                                }).addTo(map);

                                $(document).ready(function() {
                                    // Listen for changes in the select element
                                    $("#barangay").change(function() {
                                        // Get the selected value
                                        var selectedBarangay = $(this).val();

                                        // Create a form dynamically
                                        var form = $('<form action="Vcase_heatmaps.php" method="POST">' +
                                            '<input type="hidden" name="selectedBarangay" value="' + selectedBarangay + '">' +
                                            '</form>');

                                        // Append the form to the body and submit it
                                        $('body').append(form);
                                        form.submit();
                                    });
                                });

                                // Update heatmap data and redraw the layer when needed
                                function updateHeatmapData(newData) {
                                    heatmapData = newData;
                                    heat.setLatLngs(newData).redraw();
                                }

                                <?php
                                // JavaScript block with PHP values
                                if (isset($heatmapData)) {
                                    echo "var initialData = " . json_encode($heatmapData) . ";";
                                    echo "updateHeatmapData(initialData);"; // Update the heatmap initially with fetched data
                                }
                                ?>
                            </script>
                        </div>
                        <div class="container-fluid">
                            <div class="row overflow-auto">
                                <div class="col-md-8 mx-auto">
                                    <div class="chart-container mt-2 justify-content">
                                        <!-- <div class="chart-container d-flex  mt-2 justify-content-center mx-auto"> -->
                                        <!-- <canvas class="container col-12 m-1" id="myChart"></canvas> -->
                                        <!-- <script>
                                            const options = {
                                                responsive: true,
                                                maintainAspectRatio: true,
                                                // Other chart options...
                                            };
                                            document.addEventListener('DOMContentLoaded', function() {
                                                // Data for the chart
                                                <?php
                                                // JavaScript block with PHP values for the chart
                                                if (isset($counts, $counts1)) {
                                                    // Create an array with all month names
                                                    $allMonths = [
                                                        'January', 'February', 'March', 'April', 'May', 'June',
                                                        'July', 'August', 'September', 'October', 'November', 'December'
                                                    ];

                                                    // Initialize labels and data arrays
                                                    $labels = [];
                                                    $data = [];
                                                    $data1 = [];


                                                    // Loop through all months
                                                    foreach ($allMonths as $monthName) {
                                                        // Check if there is data for the current month in $counts
                                                        $found = false;
                                                        foreach ($counts as $count) {
                                                            $countMonthName = date('F', mktime(0, 0, 0, $count['month'], 1, $count['year']));
                                                            if ($countMonthName === $monthName) {
                                                                $found = true;
                                                                // Extract 'count_per_month' value as data
                                                                $data[] = $count['count_per_month'];
                                                                break;
                                                            }
                                                        }

                                                        // If no data for the current month in $counts, set count_per_month to 0
                                                        if (!$found) {
                                                            $data[] = 0;
                                                        }

                                                        // Check if there is data for the current month in $counts1
                                                        $found1 = false;
                                                        foreach ($counts1 as $count1) {
                                                            $countMonthName = date('F', mktime(0, 0, 0, $count1['month'], 1, $count1['year']));
                                                            if ($countMonthName === $monthName) {
                                                                $found1 = true;
                                                                // Extract 'count_per_month' value as data1
                                                                $data1[] = $count1['count_per_month'];
                                                                break;
                                                            }
                                                        }

                                                        // If no data for the current month in $counts1, set count_per_month to 0
                                                        if (!$found1) {
                                                            $data1[] = 0;
                                                        }


                                                        // Add the month name to labels only once
                                                        $labels[] = $monthName;
                                                    }

                                                    echo "var labels = " . json_encode($labels) . ";";
                                                    echo "var data = " . json_encode($data) . ";";
                                                    echo "var data1 = " . json_encode($data1) . ";";
                                                }
                                                ?>

                                                // Create the chart
                                                var ctx = document.getElementById('myChart').getContext('2d');
                                                var myChart = new Chart(ctx, {
                                                    type: 'bar',
                                                    data: {
                                                        labels: labels,
                                                        datasets: [{
                                                                label: 'Reported Bite Case',
                                                                data: data1,
                                                                backgroundColor: 'rgba(0, 123, 255, 0.5)',
                                                                borderColor: 'rgba(0, 123, 255, 1)',
                                                                borderWidth: 1,
                                                            },
                                                            {
                                                                label: 'Confirm Bite Case',
                                                                data: data,
                                                                backgroundColor: 'rgba(255, 0, 0, 0.5)',
                                                                borderColor: 'rgba(255, 0, 0, 1)',
                                                                borderWidth: 1,
                                                            }
                                                        ]
                                                    },
                                                    options: {
                                                        scales: {
                                                            y: {
                                                                beginAtZero: true
                                                            }
                                                        }
                                                    }
                                                });
                                            }); -->
                                        </script>
                                        <!-- <canvas class="container col-12 p-1 m-1" id="pieChart" style="max-width: 300px; max-height:300px"></canvas> -->
                                        <!-- <script>
                                            // ... (existing scripts)
                                            // Data for the pie chart
                                            <?php
                                            // Initialize arrays for data2 and data3
                                            $data2 = [];
                                            $data3 = [];

                                            // Check if there is data for the current year in $counts2
                                            $found2 = false;
                                            foreach ($counts2 as $count2) {
                                                // Extract 'count_per_year' value as data2
                                                $data2[Date('Y')] = $count2['count_per_year'];
                                                $found2 = true;
                                            }

                                            // If no data for the current year in $counts2, set count_per_year to 0
                                            if (!$found2) {
                                                $data2[Date('Y')] = 0;
                                            }

                                            // Check if there is data for the current year in $counts3
                                            $found3 = false;
                                            foreach ($counts3 as $count3) {
                                                // Extract 'count_per_year' value as data3
                                                $data3[Date('Y')] = $count3['count_per_year'];
                                                $found3 = true;
                                            }

                                            // If no data for the current year in $counts3, set count_per_year to 0
                                            if (!$found3) {
                                                $data3[Date('Y')] = 0;
                                            }

                                            // Calculate the percentage of $data3 with respect to $data2
                                            $slicePercentage = ($data2[Date('Y')] > 0) ? ($data3[Date('Y')] / $data2[Date('Y')]) * 100 : 0;

                                            // Calculate the percentage of the remaining data (whole pizza)
                                            $remainingPercentage = 100 - $slicePercentage;

                                            // Use $slicePercentage and $remainingPercentage in the JavaScript block
                                            echo "var slicePercentage = " . json_encode($slicePercentage) . ";";
                                            echo "var remainingPercentage = " . json_encode($remainingPercentage) . ";";
                                            ?>




                                            // Create the pie chart
                                            // Create the pie chart
                                            // Create the pie chart
                                            var ctxPie = document.getElementById('pieChart').getContext('2d');
                                            var myPieChart = new Chart(ctxPie, {
                                                type: 'pie', // Use 'doughnut' type for a pie chart with a hole
                                                data: {
                                                    labels: ['Reported Bite Case', 'Confirmed Bite Cases'],
                                                    datasets: [{
                                                        data: [remainingPercentage, slicePercentage],
                                                        backgroundColor: ['rgba(255, 0, 0, 0.5)', 'rgba(0, 123, 255, 0.5)'],
                                                        borderColor: ['rgba(255, 0, 0, 1)', 'rgba(0, 123, 255, 1)'],
                                                        borderWidth: 1,
                                                    }],
                                                },
                                                options: {
                                                    responsive: true,
                                                    maintainAspectRatio: false,
                                                    legend: {
                                                        display: true,
                                                        position: 'bottom',
                                                        labels: {
                                                            fontColor: 'black',
                                                        },
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'Bite Case Distribution', // Set your desired title here
                                                        fontSize: 18,
                                                    },
                                                },
                                            });
                                        </script> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>


        <?php
        // Replace these with your actual database credentials
        // Check connection
        require_once("class/db_connect.php"); // Assuming this file contains database connection logic
        require_once("class/barangay.php");
        require_once("class/notification.php");

        // Check if the user is logged in
        if (!isset($_SESSION['user'])) {
            header("Location: login.php");
            exit();
        }

        // Get the brgyID and residentID from the session
        $brgyID = isset($_SESSION['user']['brgyID']) ? $_SESSION['user']['brgyID'] : null;
        $residentID = isset($_SESSION['user']['residentID']) ? $_SESSION['user']['residentID'] : null;

        // Check if both brgyID and residentID are available
        if ($brgyID !== null && $residentID !== null) {
            // Prepare the SQL query with proper grouping of conditions
            $sql = "SELECT n.* FROM notification as n WHERE (n.brgyID = ? OR MAOID = ?) AND notifType IN (0, 1, 2, 3, 4, 5, 6, 7, 8) 
        ORDER BY n.notifDate DESC";

            // Prepare the statement
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Error in preparing statement: " . $conn->error);
            }

            // Bind parameters
            $stmt->bind_param("ii", $brgyID, $residentID);

            // Execute the query
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
            }

            // Get the result
            $result = $stmt->get_result();

            // Fetch notifications as an associative array
            $allNotifs = $result->fetch_all(MYSQLI_ASSOC);

            // Close the statement
            $stmt->close();
        } else {
            // If brgyID or residentID is not valid, set an empty array for notifications
            $allNotifs = [];
        }
        ?>

        <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="notificationModalLabel">Notifications</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php if (!empty($allNotifs)) { ?>
                            <table class="table text-start table-bordered table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Message</th>
                                        <th>Date </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allNotifs as $notif) { ?>
                                        <tr>
                                            <?php
                                            // $redirectUrl = '';

                                            // switch ($notif['notifType']) {
                                            //     case 0:
                                            //         $redirectUrl = './dashboard1.php?active-tab=1';
                                            //         break;
                                            //     case 1:
                                            //         $redirectUrl = './dashboard1pet.php?active-tab=1';
                                            //         break;
                                            //     case 2:
                                            //         $redirectUrl = './dashboardBiteCases.php?active-tab=1';
                                            //         break;
                                            //     case 3:
                                            //         $redirectUrl = './dashboardRabidCases.php?active-tab=1';
                                            //         break;
                                            //     case 4:
                                            //         $redirectUrl = './dashboard1pet.php?active-tab=2';
                                            //         break;
                                            //     case 6:
                                            //         $redirectUrl = './BAOpetdashboard.php?active-tab=2';
                                            //         break;
                                            //     case 7:
                                            //         $redirectUrl = './BAOpetdashboard.php?active-tab=1';
                                            //         break;
                                            //     case 8:
                                            //         $redirectUrl = './BAOpetdashboard.php?active-tab=3';
                                            //         break;
                                            //     default:
                                            //         // Default case if notifType doesn't match any of the above
                                            //         $redirectUrl = '#';
                                            // }

                                            ?>

                                            <td style="background-color: <?php echo ($notif['BAOread'] == 0) ? '#d3d3d3' : ''; ?>;">
                                                <form method="get" action="mark_all_read.php" style="margin: 0;">
                                                    <!-- <input type="hidden" name="isRead" value="1"> -->
                                                    <form method="get" action="mark_all_read.php" style="margin: 0;">
                                                        <input type="hidden" name="BAOread" value="1">
                                                        <input type="hidden" name="notifID" value="<?php echo $notif['notifID'] ?>">
                                                        <input type="hidden" name="notifType" value="<?php echo $notif['notifType'] ?>">
                                                        <button type="submit" name="BAO_read" class="btn btn-link" style="border: none; text-decoration: none; text-align: justify; color: black; padding: 0;">
                                                            <?php echo $notif['notifMessage'] . ' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ' ?>
                                                        </button>
                                            </td>
                                            <td style="background-color: <?php echo ($notif['BAOread'] == 0) ? '#d3d3d3' : ''; ?>;">
                                                <input type="hidden" name="BAOread" value="1">
                                                <input type="hidden" name="notifID" value="<?php echo $notif['notifID'] ?>">
                                                <input type="hidden" name="notifType" value="<?php echo $notif['notifType'] ?>">
                                                <button type="submit" name="BAO_read" class="btn btn-link" style="border: none; text-decoration: none; text-align: justify; color: black; padding: 0;">
                                                    <?php echo date('F j, Y, g:i A', strtotime($notif['notifDate'])); ?>
                                                </button>
                                            </td>
                                            </form>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } else { ?>
                            <p>No notifications available.</p>
                        <?php } ?>
                    </div>
                    <div class="modal-footer">
                        <form method="post" action="mark_all_read.php"> <!-- Create mark_all_read.php for handling the form submission -->
                            <input type="hidden" name="brgyID" id="brgyID" value="<?php echo $brgyID ?>">
                            <input type="hidden" name="residentID" id="residentID" value="<?php echo $residentID ?>">
                            <button type="submit" name="mark_all_read_BAO" class="btn btn-primary">Read All</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        </div>

</body>


</html>