<?php
session_start();
require_once("class/pet.php");
require_once("class/barangay.php");
require_once("class/db_connect.php");
require_once("class/resident.php");
require_once("class/cases.php");
$active_tab = $_GET['active-tab'];
$pet = new Pet();
$barangay = new Barangay();
$resident = new Resident();
$case = new Cases();

$user = $_SESSION['user'];
// $brgyID = isset($_SESSION['user']['brgyID']) ? $_SESSION['user']['brgyID'] : '';
$residentID = isset($_SESSION['user']['residentID']) ? $_SESSION['user']['residentID'] : '';
// $userType = isset($_SESSION['user']['userType']) ? $_SESSION['user']['userType'] : '';
$name = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : '';
$brgyID = isset($_SESSION['user']['brgyID']) ? $_SESSION['user']['brgyID'] : '';

$cases = null;
$officers = null;
$pets = null;

$sex = array(
    0 => "Male",
    1 => "F"
);

$neutering = array(
    0 => "Not Not Neutered",
    1 => "Not Neutered"
);

$vacStatus = array(
    0 => "Vaccinated",
    1 => "Not Vaccinated"
);

$petType = array(
    0 => "Dog",
    1 => "Cat"
);

$brgyID = isset($_POST["brgyID"]) ? $_POST["brgyID"] : null;

$pets = $pet->getRegistries($brgyID);
$bites = $case->getAllValidBiteCaseByBrgy($brgyID);
// $death = $case->getDeathByBrgy($brgyID);
$rabid = $case->getRabidByBrgy($brgyID);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title style="font-size: 2px; font-weight: bold;">National Rabies Prevention and Control Program<br>Rabies Free Visayas Project<br>Dog Registry and Vaccination Record</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="petstaticon.png">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        h1 {
            color: black;
            font-weight: bold;
        }

        .container {
            margin-top: 50px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);

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

        @media (min-width: 576px) {
            .h-sm-100 {
                height: 100%;
            }


        }
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
                    <ul class="nav nav-underline flex-sm-column flex-row flex-nowrap flex-shrink-1 flex-sm-grow-0 flex-grow-1 mb-sm-auto justify-content-center align-items-center align-items-sm-start" id="menu">
                        <li class="nav-item">
                            <a href="dashboardBAO.php" class="flex-sm-fill text-sm-center nav-link text-dark" aria-current="page">
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
                                <button type="submit" data-bs-toggle="collapse" class="nav-link active text-dark px-sm-0 px-2"> <svg xmlns="http://www.w3.org/2000/svg" height="20" width="15" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
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
                                    </svg><span class="ms-2 d-none d-sm-inline">Create Account</span></button>
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
                        <div class="container mt-2 ">
                            <form method="POST" action="" id="tabform" name="Tabform">
                                <h1>Reported Cases</h1>
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link<?= ($active_tab == 1) ? ' active' : '' ?>" href="./tabularBAO.php?active-tab=1#registered" data-bs-toggle="tab">Registries</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link<?= ($active_tab == 2) ? ' active' : '' ?>" href="./tabularBAO.php?active-tab=2#Bites" data-bs-toggle="tab">Bites</a>
                                    </li>
                                    <!-- <li class="nav-item">
                                        <a class="nav-link<?= ($active_tab == 3) ? ' active' : '' ?>" href="./tabularBAO.php?active-tab=3#Deaths" data-bs-toggle="tab">Deaths</a>
                                    </li> -->
                                    <li class="nav-item">
                                        <a class="nav-link<?= ($active_tab == 4) ? ' active' : '' ?>" href="./tabularBAO.php?active-tab=4#Rabid" data-bs-toggle="tab">Rabid</a>
                                    </li>
                                </ul>
                                <!-- Tab Content -->
                                <div class="tab-content">
                                    <!-- Valid Residents -->
                                    <?php
                                    // Count the number of rows
                                    $query = "SELECT COUNT(*) AS count FROM resident AS r NATURAL JOIN pet AS p LEFT JOIN barangay AS b ON r.brgyID = b.brgyID WHERE p.status = 1 and r.brgyID = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $brgyID);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $count = 0;
                                    if ($row = $result->fetch_assoc()) {
                                        $count = $row['count'];
                                    }
                                    $stmt->close();

                                    $query = "SELECT COUNT(*) AS count FROM resident AS r NATURAL JOIN pet AS p LEFT JOIN barangay AS b ON r.brgyID = b.brgyID WHERE p.status = 1 and p.sex = 1 and r.brgyID = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $brgyID);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $female = 0;
                                    if ($row = $result->fetch_assoc()) {
                                        $female = $row['count'];
                                    }
                                    $stmt->close();

                                    $query = "SELECT COUNT(*) AS count FROM resident AS r NATURAL JOIN pet AS p LEFT JOIN barangay AS b ON r.brgyID = b.brgyID WHERE p.status = 1 and p.sex = 0 and r.brgyID = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $brgyID);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $male = 0;
                                    if ($row = $result->fetch_assoc()) {
                                        $male = $row['count'];
                                    }
                                    $stmt->close();

                                    $query = "SELECT COUNT(*) AS count FROM resident AS r NATURAL JOIN pet AS p LEFT JOIN barangay AS b ON r.brgyID = b.brgyID WHERE p.status = 1 and p.petType = 0 and r.brgyID = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $brgyID);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $dog = 0;
                                    if ($row = $result->fetch_assoc()) {
                                        $dog = $row['count'];
                                    }
                                    $stmt->close();

                                    $query = "SELECT COUNT(*) AS count FROM resident AS r NATURAL JOIN pet AS p LEFT JOIN barangay AS b ON r.brgyID = b.brgyID WHERE p.status = 1 and p.petType = 1 and r.brgyID = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $brgyID);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $cat = 0;
                                    if ($row = $result->fetch_assoc()) {
                                        $cat = $row['count'];
                                    }
                                    $stmt->close();

                                    ?>

                                    <div class="tab-pane <?= ($active_tab == 1) ? ' active show' : '' ?>" id="registered">
                                        <div class="table-responsive">
                                            <tr>
                                                <td colspan="11"><Strong>Total Number of Registries:</Strong> <?php echo $count; ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <th colspan="11"><Strong>Total Number of Male:</Strong> <?php echo $male; ?><br>
                                                <th colspan="11"><Strong>Total Number of Female:</Strong> <?php echo $female; ?></th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <th colspan="11"><Strong>Total Number of Dog:</Strong> <?php echo $dog; ?></th><br>
                                                <th colspan="11"><Strong>Total Number of Cat:</Strong> <?php echo $cat; ?></th><br>
                                            </tr>
                                            <div class="mb-3"></div>
                                            <table id="registries" class="table text-center table-bordered table-hover" style="width:100%">
                                                <div class="row">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th style="width: 15%">Owner's Name</th>
                                                            <th style="width: 15%">Date of Registry</th>
                                                            <th style="width: 5%">Species</th>
                                                            <th style="width: 8%">Name of Pet</th>
                                                            <th style="width: 5%">Sex</th>
                                                            <th style="width: 3%">Age</th>
                                                            <th style="width: 13%">Neutering</th>
                                                            <th style="width: 15%">Color</th>
                                                            <th style="width: 8%">Vaccination Status</th>
                                                            <th style="width: 13%">Current Vaccination</th>
                                                            <th style="width: 13%">Address</th>
                                                        </tr>
                                                    </thead>
                                                </div>
                                                <tbody>
                                                    <?php

                                                    if ($pets) {
                                                        foreach ($pets as $row) {
                                                            echo '<tr class="text-center">';
                                                            echo "<td>" . $row["name"] . "</td>";
                                                            $input_date = $row['regDate'];
                                                            $date_obj = new DateTime($input_date);
                                                            $formatted_date = $date_obj->format("F j Y");
                                                            echo '<td>' . $formatted_date . '</td>';
                                                            echo "<td>" . ($row["petType"] == 0 ? 'Dog' : 'Cat') . "</td>";
                                                            echo "<td>" . $row["pname"] . "</td>";
                                                            echo "<td>" . ($row["sex"] == 0 ? 'Male' : 'Female') . "</td>";
                                                            echo "<td>" . $row["age"] . "</td>";
                                                            echo "<td>" . ($row["Neutering"] == 0 ? 'Not Neutered' : 'Spayed') . "</td>";
                                                            echo "<td>" . $row["color"] . "</td>";
                                                            echo "<td>" . ($row["statusVac"] == 0 ? 'Vaccinated' : 'Susceptible') . "</td>";
                                                            $input_date = $row['currentVac'];
                                                            $date_obj = new DateTime($input_date);
                                                            $formatted_date = $date_obj->format("F j Y");
                                                            echo '<td>' . $formatted_date . '</td>';
                                                            echo "<td>" . $row["barangay"] . "</td>";
                                                            echo "</tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='12'>No pets found for this barangay.</td></tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>

                                    <?php
                                    // Count the number of rows
                                    $query = "SELECT COUNT(*) AS count
                                        FROM `case` AS c
                                        INNER JOIN pet AS p ON c.petID = p.petID
                                        INNER JOIN resident AS r ON p.residentID = r.residentID
                                        INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                        WHERE c.caseStatus = 1 AND c.caseType = 0 AND c.brgyID = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $brgyID);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $bite = 0;
                                    if ($row = $result->fetch_assoc()) {
                                        $bite = $row['count'];
                                    }
                                    $stmt->close();

                                    $query = "SELECT COUNT(*) AS count
                                        FROM `case` AS c
                                        INNER JOIN pet AS p ON c.petID = p.petID
                                        INNER JOIN resident AS r ON p.residentID = r.residentID
                                        INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                        WHERE c.caseStatus = 1 AND c.caseType = 0 AND p.sex = 0 AND c.brgyID = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $brgyID);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $maleb = 0;
                                    if ($row = $result->fetch_assoc()) {
                                        $maleb = $row['count'];
                                    }
                                    $stmt->close();

                                    $query = "SELECT COUNT(*) AS count
                                        FROM `case` AS c
                                        INNER JOIN pet AS p ON c.petID = p.petID
                                        INNER JOIN resident AS r ON p.residentID = r.residentID
                                        INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                        WHERE c.caseStatus = 1 AND c.caseType = 0 AND p.sex = 1 AND c.brgyID = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $brgyID);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $femaleb = 0;
                                    if ($row = $result->fetch_assoc()) {
                                        $femaleb = $row['count'];
                                    }
                                    $stmt->close();

                                    $query = "SELECT COUNT(*) AS count
                                        FROM `case` AS c
                                        INNER JOIN pet AS p ON c.petID = p.petID
                                        INNER JOIN resident AS r ON p.residentID = r.residentID
                                        INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                        WHERE c.caseStatus = 1 AND c.caseType = 0 AND p.petType = 0 AND c.brgyID = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $brgyID);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $dogb = 0;
                                    if ($row = $result->fetch_assoc()) {
                                        $dogb = $row['count'];
                                    }
                                    $stmt->close();

                                    $query = "SELECT COUNT(*) AS count
                                        FROM `case` AS c
                                        INNER JOIN pet AS p ON c.petID = p.petID
                                        INNER JOIN resident AS r ON p.residentID = r.residentID
                                        INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                        WHERE c.caseStatus = 1 AND c.caseType = 0 AND p.petType = 1 AND c.brgyID = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $brgyID);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $catb = 0;
                                    if ($row = $result->fetch_assoc()) {
                                        $catb = $row['count'];
                                    }
                                    $stmt->close();

                                    // $query = "SELECT COUNT(*) AS count
                                    // FROM `case`
                                    // WHERE petID = ?
                                    // AND caseStatus = 1
                                    // AND caseType = 0";
                                    // $stmt = $conn->prepare($query);
                                    // $stmt->bind_param("i", $petID);
                                    // $stmt->execute();
                                    // $result = $stmt->get_result();
                                    // $noCount = 0;
                                    // if ($row = $result->fetch_assoc()) {
                                    //     $noCount = $row['count'];
                                    // }
                                    // $stmt->close();

                                    ?>
                                    <!-- Cases -->

                                    <div class="tab-pane <?= ($active_tab == 2) ? ' active show' : '' ?>" id="Bites">
                                        <div class="table-responsive">
                                            <tr>
                                                <td colspan="11"><Strong>Total Number of Registries: <?php echo $bite; ?></Strong>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <th colspan="11"><Strong>Total Number of Male:</Strong> <?php echo $maleb; ?><br>
                                                <th colspan="11"><Strong>Total Number of Female:</Strong> <?php echo $femaleb; ?></th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <th colspan="11"><Strong>Total Number of Dog:</Strong> <?php echo $dogb; ?></th><br>
                                                <th colspan="11"><Strong>Total Number of Cat:</Strong> <?php echo $catb; ?></th><br>
                                            </tr>
                                            <div class="mb-3"></div>
                                            <table id="bites" class="table text-center  table-bordered table-hover" style="width:100%">
                                                <div class="row">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <!-- <th style='background-color: black; color: white;'>Case No.</th> -->
                                                            <th style="width: 13%">Victim's Name</th>
                                                            <th style="width: 5%">Species</th>
                                                            <th style="width: 5%">Sex</th>
                                                            <th style="width: 8%">Pet's Name</th>
                                                            <th style="width: 15%">Owner's Name</th>
                                                            <th style="width: 15%">Date Occurred</th>
                                                            <th style="width: 7%">Vaccination Status</th>
                                                            <!-- <th style="width: 15%">Body Part Bitten</th> -->
                                                            <th style="width: 15%">Description</th>
                                                            <th style="width: 5%">Rabies</th>
                                                            <th style="width: 13%">Address</th>
                                                            <!-- <th style="width: 13%">Number of Records</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody id="valid-c">
                                                        <?php
                                                        if ($bites) {
                                                            foreach ($bites as $case) {
                                                                echo '<tr class="text-center">';
                                                                // echo "<th style='background-color: black; color: white;'> $start_row </th>";
                                                                // echo '<td>' . $case['victimsName'] . '</td>';
                                                                echo '<td>' . $case['victimsName'] . '</td>';
                                                                echo '<td>' . ($case['petType'] == 0 ? 'Dog' : 'Cat') . '</td>';
                                                                echo '<td>' . ($case['sex'] == 0 ? 'Male' : 'Female') . '</td>';
                                                                echo '<td>' . $case['pname'] . '</td>';
                                                                echo '<td>' . $case['name'] . '</td>';
                                                                $input_date = $case['date'];
                                                                $date_obj = new DateTime($input_date);
                                                                $formatted_date = $date_obj->format("F j, Y");
                                                                echo '<td>' . $formatted_date . '</td>';
                                                                echo '<td>' . ($case['statusVac'] == 0 ? 'Susceptible' : 'Vaccinated') . '</td>';
                                                                // echo '<td>' . (
                                                                //     $case['bpartBitten'] == 0 ? 'Head and Neck Area' : ($case['bpartBitten'] == 1 ? 'Thorax Area' : ($case['bpartBitten'] == 2 ? 'Abdomen Area' : ($case['bpartBitten'] == 3 ? 'Upper Extremities' : ($case['bpartBitten'] == 4 ? 'Lower Extremities' : 'Unknown')))))
                                                                //     . '</td>';
                                                                echo '<td>' . $case['description'] . '</td>';
                                                                echo '<td>' . ($case['confirmedRabies'] == 0 ? 'No' : 'Yes') . '</td>';
                                                                echo '<td>' . $case['barangay'] . '</td>';
                                                                // echo '<td>' . $noCount . '</td>';
                                                                echo '</tr>';
                                                                // $start_row++;
                                                            }
                                                        } else {
                                                            echo "<tr><td colspan='10'>No pet cases found for this barangay.</td></tr>";
                                                        }


                                                        ?>
                                                    </tbody>

                                                </div>
                                            </table>
                                        </div>
                                    </div>

                                    <?php
                                    // Count the number of rows
                                    $query = "SELECT COUNT(*) AS count
                                        FROM pet AS p
                                        INNER JOIN resident AS r ON p.residentID = r.residentID
                                        INNER JOIN `case` AS c ON c.petID = p.petID
                                        INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                        WHERE c.caseStatus = 1 AND c.caseType = 2 AND c.brgyID = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $brgyID);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $sus = 0;
                                    if ($row = $result->fetch_assoc()) {
                                        $sus = $row['count'];
                                    }
                                    $stmt->close();

                                    $query = "SELECT COUNT(*) AS count
                                        FROM pet AS p
                                        INNER JOIN resident AS r ON p.residentID = r.residentID
                                        INNER JOIN `case` AS c ON c.petID = p.petID
                                        INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                        WHERE c.caseStatus = 1 AND c.caseType = 2 AND p.sex = 0 AND c.brgyID = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $brgyID);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $males = 0;
                                    if ($row = $result->fetch_assoc()) {
                                        $male = $row['count'];
                                    }
                                    $stmt->close();

                                    $query = "SELECT COUNT(*) AS count
                                        FROM pet AS p
                                        INNER JOIN resident AS r ON p.residentID = r.residentID
                                        INNER JOIN `case` AS c ON c.petID = p.petID
                                        INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                        WHERE c.caseStatus = 1 AND c.caseType = 2 AND p.sex = 1 AND c.brgyID = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $brgyID);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $females = 0;
                                    if ($row = $result->fetch_assoc()) {
                                        $females = $row['count'];
                                    }
                                    $stmt->close();

                                    $query = "SELECT COUNT(*) AS count
                                        FROM pet AS p
                                        INNER JOIN resident AS r ON p.residentID = r.residentID
                                        INNER JOIN `case` AS c ON c.petID = p.petID
                                        INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                        WHERE c.caseStatus = 1 AND c.caseType = 2 AND p.petType = 0 AND c.brgyID = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $brgyID);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $dogs = 0;
                                    if ($row = $result->fetch_assoc()) {
                                        $dogs = $row['count'];
                                    }
                                    $stmt->close();

                                    $query = "SELECT COUNT(*) AS count
                                        FROM pet AS p
                                        INNER JOIN resident AS r ON p.residentID = r.residentID
                                        INNER JOIN `case` AS c ON c.petID = p.petID
                                        INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                        WHERE c.caseStatus = 1 AND c.caseType = 2 AND p.petType = 1 AND c.brgyID = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $brgyID);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $cats = 0;
                                    if ($row = $result->fetch_assoc()) {
                                        $cats = $row['count'];
                                    }
                                    $stmt->close();

                                    ?>
                                    <div class="tab-pane <?= ($active_tab == 4) ? ' active show' : '' ?>" id="Rabid">
                                        <div class="table-responsive">
                                            <tr>
                                                <td colspan="11"><Strong>Total Number of Registries: <?php echo $sus; ?></Strong></td>
                                                </td>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <th colspan="11"><Strong>Total Number of Male:</Strong> <?php echo $males; ?><br>
                                                <th colspan="11"><Strong>Total Number of Female:</Strong> <?php echo $females; ?></th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <th colspan="11"><Strong>Total Number of Dog:</Strong> <?php echo $dogs; ?></th><br>
                                                <th colspan="11"><Strong>Total Number of Cat:</Strong> <?php echo $cats; ?></th><br>
                                            </tr>
                                            <div class="mb-3"></div>
                                            <table id="suspected" class="table text-center table-bordered table-hover" style="width:100%">
                                                <thead>
                                                    <tr class="text-center">
                                                        <!-- <th style='background-color: black; color: white;'>Case No.</th> -->
                                                        <th>Owner's Name</th>
                                                        <th>Pet's Name</th>
                                                        <th>Species</th>
                                                        <th>Sex</th>
                                                        <th>Date Discovered</th>
                                                        <th>Description</th>
                                                        <th>Address</th>
                                                        <th>Rabies</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="valid-s">
                                                    <?php
                                                    if ($rabid) {
                                                        foreach ($rabid as $rabid) {
                                                            echo '<tr class="text-center">';
                                                            // echo "<th style='background-color: black; color: white;'> $start_row </th>";
                                                            echo '<td>' . $rabid['name'] . '</td>';
                                                            echo '<td>' . $rabid['pname'] . '</td>';
                                                            echo '<td>' . ($case['petType'] == 0 ? 'Dog' : 'Cat') . '</td>';
                                                            echo '<td>' . ($case['sex'] == 0 ? 'Male' : 'Female') . '</td>';

                                                            $input_date = $rabid['date'];

                                                            // Convert the input date to a DateTime object
                                                            $date_obj = new DateTime($input_date);

                                                            // Format the date as "Month Day, Year"
                                                            $formatted_date = $date_obj->format("F j, Y");

                                                            // Print the formatted date
                                                            echo '<td>' . $formatted_date . '</td>';
                                                            echo '<td>' . $rabid['description'] . '</td>';
                                                            echo '<td>' . $rabid['barangay'] . '</td>';
                                                            echo '<td>' . ($rabid['confirmedRabies'] == 0 ? 'No' : 'Yes') . '</td>';
                                                            echo '</tr>';
                                                            // $start_row++;
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='6'>No rabid cases found for this barangay.</td></tr>";
                                                    }

                                                    ?>
                                                </tbody>
                                            </table>
                                            <!-- Pagination -->
                                        </div>
                                    </div>


                                    <script>
                                        jQuery(document).ready(function($) {
                                            // Initialize DataTable
                                            $('#registries').DataTable({
                                                // Apply default sorting on the 4th column (index 3) in descending order
                                                "order": [
                                                    [3, 'desc']
                                                ],
                                                // Configure layout for DataTables buttons
                                                "dom": 'Bfrtip',
                                                "buttons": [
                                                    'copy',
                                                    'csv',
                                                    'excel',
                                                    {
                                                        extend: 'pdfHtml5',
                                                        orientation: 'landscape', // Set PDF orientation to landscape
                                                        customize: function(doc) {
                                                            // Set page size to long size bond paper (8.5" x 13") and adjust column widths
                                                            doc.pageSize = {
                                                                width: 330.2,
                                                                height: 215.9
                                                            }; // 13 * 25.4, 8.5 * 25.4
                                                            doc.pageMargins = [5, 5, 5, 5]; // Adjust margins [left, top, right, bottom]

                                                            // Adjust table column widths
                                                            var tableColumnWidths = ['15%', '13%', '3%', '8%', '5%', '3%', '10%', '15%', '8%', '13%', '13%'];
                                                            doc.content[1].table.widths = tableColumnWidths;

                                                            // Adjust font size and line height
                                                            var fontSize = 3; // Adjust font size to fit your requirements
                                                            var lineHeight = .2; // Adjust line height to fit your requirements
                                                            doc.content[1].table.body.forEach(function(row) {
                                                                row.forEach(function(cell) {
                                                                    cell.fontSize = fontSize;
                                                                    cell.lineHeight = lineHeight;
                                                                });
                                                            });

                                                            // Adjust the title font size and include line breaks
                                                            doc.content[0].text = 'National Rabies Prevention and Control Program\nRabies Free Visayas Project\nDog Registry and Vaccination Records'; // Change the title text
                                                            doc.content[0].fontSize = 4; // Change the title font size to fit your requirements
                                                            doc.content[0].alignment = 'center'; // Center align the title

                                                            // Add the total number of entries to the footer
                                                            var entryCount = <?php echo $count; ?>; // Fetch the count from PHP
                                                            var entrymale = <?php echo $male; ?>;
                                                            var entryfemale = <?php echo $female; ?>;
                                                            var entryDog = <?php echo $dog; ?>;
                                                            var entryCat = <?php echo $cat; ?>;
                                                            var footerText = 'Total number of registries: ' + entryCount + '\n' +
                                                                'Total number of male: ' + entrymale + '\n' +
                                                                'Total number of female: ' + entryfemale + '\n' +
                                                                'Total number of dog: ' + entryDog + '\n' +
                                                                'Total number of cat: ' + entryCat;
                                                            doc.content.push({
                                                                text: footerText,
                                                                fontSize: 4,
                                                                margin: [0, 0, 0, 15]
                                                            }); // Set font size to 4 points and add a margin
                                                        }
                                                    },
                                                    'print'
                                                ]
                                            });



                                            $('#bites').DataTable({
                                                // Apply default sorting on the 4th column (index 3) in descending order
                                                "order": [
                                                    [3, 'desc']
                                                ],
                                                // Configure layout for DataTables buttons
                                                "dom": 'Bfrtip',
                                                "buttons": [
                                                    'copy',
                                                    'csv',
                                                    'excel',
                                                    {
                                                        extend: 'pdfHtml5',
                                                        orientation: 'landscape', // Set PDF orientation to landscape
                                                        customize: function(doc) {
                                                            // Set page size to long size bond paper (8.5" x 13") and adjust column widths
                                                            doc.pageSize = {
                                                                width: 330.2,
                                                                height: 215.9
                                                            }; // 13 * 25.4, 8.5 * 25.4
                                                            doc.pageMargins = [5, 5, 5, 5]; // Adjust margins [left, top, right, bottom]

                                                            // Adjust table column widths
                                                            var tableColumnWidths = ['12%', '8%', '5%', '10%', '15%', '15%', '8%', '15%', '5%', '13%'];
                                                            doc.content[1].table.widths = tableColumnWidths;

                                                            // Adjust font size and line height
                                                            var fontSize = 3; // Adjust font size to fit your requirements
                                                            var lineHeight = .2; // Adjust line height to fit your requirements
                                                            doc.content[1].table.body.forEach(function(row) {
                                                                row.forEach(function(cell) {
                                                                    cell.fontSize = fontSize;
                                                                    cell.lineHeight = lineHeight;
                                                                });
                                                            });

                                                            // Adjust the title font size and include line breaks
                                                            doc.content[0].text = 'National Rabies Prevention and Control Program\nRabies Free Visayas Project\nRecords of Bite Cases'; // Change the title text
                                                            doc.content[0].fontSize = 4; // Change the title font size to fit your requirements
                                                            doc.content[0].alignment = 'center'; // Center align the title

                                                            // Add the total number of entries to the footer
                                                            var entryCount = <?php echo $bite; ?>; // Fetch the count from PHP
                                                            var entrymale = <?php echo $maleb; ?>;
                                                            var entryfemale = <?php echo $femaleb; ?>;
                                                            var entryDog = <?php echo $dogb; ?>;
                                                            var entryCat = <?php echo $catb; ?>;
                                                            var footerText = 'Total number of registries: ' + entryCount + '\n' +
                                                                'Total number of male: ' + entrymale + '\n' +
                                                                'Total number of female: ' + entryfemale + '\n' +
                                                                'Total number of dog: ' + entryDog + '\n' +
                                                                'Total number of cat: ' + entryCat;
                                                            doc.content.push({
                                                                text: footerText,
                                                                fontSize: 4,
                                                                margin: [0, 0, 0, 15]
                                                            }); // Set font size to 4 points and add a margin
                                                        }
                                                    },
                                                    'print'
                                                ]
                                            });

                                            // $('#death').DataTable({
                                            //     // Apply default sorting on the 4th column (index 3) in descending order
                                            //     "order": [
                                            //         [3, 'desc']
                                            //     ],
                                            //     // Configure layout for DataTables buttons
                                            //     "dom": 'Bfrtip',
                                            //     "buttons": [
                                            //         'copy',
                                            //         'csv',
                                            //         'excel',
                                            //         {
                                            //             extend: 'pdfHtml5',
                                            //             orientation: 'landscape', // Set PDF orientation to landscape
                                            //             customize: function(doc) {
                                            //                 // Set page size to long size bond paper (8.5" x 13") and adjust column widths
                                            //                 doc.pageSize = {
                                            //                     width: 330.2,
                                            //                     height: 215.9
                                            //                 }; // 13 * 25.4, 8.5 * 25.4
                                            //                 doc.pageMargins = [5, 5, 5, 5]; // Adjust margins [left, top, right, bottom]

                                            //                 // Adjust table column widths
                                            //                 var tableColumnWidths = ['15%', '15%', '20%', '20%', '15%', '15%'];
                                            //                 doc.content[1].table.widths = tableColumnWidths;

                                            //                 // Adjust font size and line height
                                            //                 var fontSize = 3; // Adjust font size to fit your requirements
                                            //                 var lineHeight = .2; // Adjust line height to fit your requirements
                                            //                 doc.content[1].table.body.forEach(function(row) {
                                            //                     row.forEach(function(cell) {
                                            //                         cell.fontSize = fontSize;
                                            //                         cell.lineHeight = lineHeight;
                                            //                     });
                                            //                 });

                                            //                 // Adjust the title font size and include line breaks
                                            //                 doc.content[0].text = 'National Rabies Prevention and Control Program\nRabies Free Visayas Project\nRecords of Death Cases'; // Change the title text
                                            //                 doc.content[0].fontSize = 4; // Change the title font size to fit your requirements
                                            //                 doc.content[0].alignment = 'center'; // Center align the title
                                            //                 // Set font size to 8 points

                                            //             }
                                            //         },
                                            //         'print'
                                            //     ]
                                            // });


                                            $('#suspected').DataTable({
                                                // Apply default sorting on the 4th column (index 3) in descending order
                                                "order": [
                                                    [3, 'desc']
                                                ],
                                                // Configure layout for DataTables buttons
                                                "dom": 'Bfrtip',
                                                "buttons": [
                                                    'copy',
                                                    'csv',
                                                    'excel',
                                                    {
                                                        extend: 'pdfHtml5',
                                                        orientation: 'landscape', // Set PDF orientation to landscape
                                                        customize: function(doc) {
                                                            // Set page size to long size bond paper (8.5" x 13") and adjust column widths
                                                            doc.pageSize = {
                                                                width: 330.2,
                                                                height: 215.9
                                                            }; // 13 * 25.4, 8.5 * 25.4
                                                            doc.pageMargins = [5, 5, 5, 5]; // Adjust margins [left, top, right, bottom]

                                                            // Adjust table column widths
                                                            var tableColumnWidths = ['15%', '12%', '8%', '5%', '15%', '20%', '15%', '15%'];
                                                            doc.content[1].table.widths = tableColumnWidths;

                                                            // Adjust font size and line height
                                                            var fontSize = 3; // Adjust font size to fit your requirements
                                                            var lineHeight = .2; // Adjust line height to fit your requirements
                                                            doc.content[1].table.body.forEach(function(row) {
                                                                row.forEach(function(cell) {
                                                                    cell.fontSize = fontSize;
                                                                    cell.lineHeight = lineHeight;
                                                                });
                                                            });

                                                            // Adjust the title font size and include line breaks
                                                            doc.content[0].text = 'National Rabies Prevention and Control Program\nRabies Free Visayas Project\nRecords of Suspected Rabid Cases'; // Change the title text
                                                            doc.content[0].fontSize = 4; // Change the title font size to fit your requirements
                                                            doc.content[0].alignment = 'center'; // Center align the title

                                                            var entryCount = <?php echo $sus; ?>; // Fetch the count from PHP
                                                            var entrymale = <?php echo $males; ?>;
                                                            var entryfemale = <?php echo $females; ?>;
                                                            var entryDog = <?php echo $dogs; ?>;
                                                            var entryCat = <?php echo $cats; ?>;
                                                            var footerText = 'Total number of Suspected Cases: ' + entryCount + '\n' +
                                                                'Total number of male: ' + entrymale + '\n' +
                                                                'Total number of female: ' + entryfemale + '\n' +
                                                                'Total number of dog: ' + entryDog + '\n' +
                                                                'Total number of cat: ' + entryCat;
                                                            doc.content.push({
                                                                text: footerText,
                                                                fontSize: 4,
                                                                margin: [0, 0, 0, 15]
                                                            });
                                                        }


                                                    },
                                                    'print'
                                                ]
                                            });
                                        });
                                    </script>


                            </form>
                </main>
            </div>
        </div>
    </div>




    <!-- Add Bootstrap JavaScript -->
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

</body>

</html>