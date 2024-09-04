<?php
session_start();
$active_tab = $_GET['active-tab'];

require_once("class/cases.php");
require_once("class/barangay.php");
require_once("class/notification.php");

// // Check if the user is logged in and has admin privileges (userType = 1)
// if (!isset($_SESSION['user']) || $_SESSION['user']['userType'] != 1) {
//     header("Location: login.php");
//     exit();
// }
// if (isset($_POST['name'])) {
//     // Retrieve the brgyID
//     $name = $_POST['name'];

//     // Now, you can use $brgyID as needed
// }
// if (isset($_POST['residentID'])) {
//     // Retrieve the brgyID
//     $residentID = $_POST['residentID'];

//     // Now, you can use $brgyID as needed
// }
// if (isset($_POST['brgyID'])) {
//     // Retrieve the brgyID
//     $brgyID = $_POST['brgyID'];

//     // Now, you can use $brgyID as needed
// }
// if (isset($_POST['userType'])) {
//     // Retrieve the brgyID
//     $userType = $_POST['userType'];

//     // Now, you can use $brgyID as needed
// }

// $brgyID = isset($_SESSION['user']['brgyID']) ? $_SESSION['user']['brgyID'] : '';
// $user = $_SESSION['user'];
// $name = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : '';

// $barangay = new Barangay();
// $result1 = $barangay->getBrgyName($brgyID);

// if ($_SERVER["REQUEST_METHOD"] === "POST") {
//     if (isset($_POST["accept"]) || isset($_POST["reject"])) {
//         $caseID = $_POST["caseID"];
//         $caseStatus = isset($_POST["accept"]) ? 1 : 2; // 1 for accepted, 2 for rejected

//         $case = new Cases();
//         $result = $case->updateDeathCaseStatus($caseID, $caseStatus);

//         if ($result === true) {
//             // Successfully updated user status
//             $notifType = 7;
//             $notifDate = date('Y-m-d H:i:s');
//             $notifMessage = $_POST["notifMessage"];
//             $residentID = $_POST['residentID'];

//             $notification = new Notification();
//             $notificationResult = $notification->updateStatus($caseID, $brgyID, $residentID, $notifType, $notifDate, $notifMessage);

//             if ($caseStatus == 2 && $notificationResult === true) {
//                 // Redirect back immediately for rejected status
//                 echo '<script>alert("Case Status Updated Successfully.")window.location.href = "./dashboardDeathCases.php?active-tab=1";</script>';
//             } else {
//                 // Redirect as per your existing logic
//                 echo '<script>alert("Case Status Updated Successfully."); window.location.href = "process_deathLocation.php?caseID=' . $caseID . '";</script>';
//             }
//         } else {
//             // Failed to update user status
//             echo "Failed to update user status: " . $result;
//         }
//     }
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Death Cases</title>
    <!-- <link rel="manifest" href="/manifest.json"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="petstaticon.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->
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

<body>
    <div class="container-fluid overflow-hidden">
        <div class="row vh-100 overflow-auto">
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
                        <div class="d-flex align-items-center text-black text-decoration-none">
                        <a href="#" class="btn btn-link me-2" data-bs-toggle="modal" data-bs-target="#notificationModal" style="padding-top: 7px;">
                            <i class="bi bi-bell fs-5 text-black" style="vertical-align: -20px;"></i>
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
                                <li><a class="dropdown-item" href="addDeathCase.php">Report Death Case</a></li>
                                <li><a class="dropdown-item" href="reportRabidBao.php">Report Rabid Case</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="nav-link active dropdown-toggle text-decoration-none text-dark px-sm-0 px-1 " id="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                    <path d="M448 160H320V128H448v32zM48 64C21.5 64 0 85.5 0 112v64c0 26.5 21.5 48 48 48H464c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48H48zM448 352v32H192V352H448zM48 288c-26.5 0-48 21.5-48 48v64c0 26.5 21.5 48 48 48H464c26.5 0 48-21.5 48-48V336c0-26.5-21.5-48-48-48H48z" />
                                </svg><span class="ms-2 d-none d-sm-inline">Manage</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark text-lg " aria-labelledby="dropdown">
                                <li><a class="dropdown-item" href="./dashboard1.php?active-tab=1">Manage Resident</a></li>
                                <li><a class="dropdown-item" href="./dashboard1pet.php?active-tab=1">Manage Pet</a></li>
                                <li><a class="dropdown-item" href="./dashboardBiteCases.php?active-tab=1">Manage Bite Cases</a></li>
                                <li><a class="dropdown-item" href="./dashboardRabidCases.php?active-tab=1">Manage Suspected Cases</a></li>
                                <li><a class="dropdown-item" href="./dashboardDeathCases.php?active-tab=1">Manage Death Cases</a></li>
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
                                    </svg><span class="ms-2 d-none d-sm-inline">Account for Resident</span></button>
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

            <div class="col d-flex flex-column h-sm-100">
                <main class="row overflow-auto">
                    <div class="col-md-8 p-1 mt-2 my-auto mx-auto">
                        <div class="container mt-4">
                            <h1 class="text-center">Manage Death Cases for Barangay: <?php echo $result1 ?></h1>
                            <!-- Navigation Tabs -->
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link<?= ($active_tab == 1) ? ' active' : '' ?>" href="./dashboardDeathCases.php?active-tab=1&page_new=1#newDeathCase" data-bs-toggle="tab">New Death Cases</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link<?= ($active_tab == 2) ? ' active' : '' ?>" href="./dashboardDeathCases.php?active-tab=2&page_valid=1#validDeathCase" data-bs-toggle="tab">Valid Death Cases</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link<?= ($active_tab == 3) ? ' active' : '' ?>" href="./dashboardDeathCases.php?active-tab=3&page_rej=1#rejectedDeathCase" data-bs-toggle="tab">Rejected Death Cases</a>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content">
                                <!-- New Bite Cases Tab -->
                                <div class="tab-pane <?= ($active_tab == 1) ? ' active show' : '' ?>" id="newDeathCase">
                                    <div class="table">
                                        <div class="card-body">
                                            <label for="newPetSrch" class="form-label"></label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" id="newSrch" placeholder="Search...">
                                                <button class="btn btn-primary" id="newBtn" type="button">Search</button>
                                            </div>
                                            <div class=table-responsive>
                                                <table class="table text-center table-striped table-bordered table-hover" style="width:100%">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Pet Name</th>
                                                            <th>Date</th>
                                                            <th>Rabies</th>
                                                            <th>Description</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="valid-n">
                                                        <?php
                                                        $case = new Cases();
                                                        $items_per_page_newDeath = 8; // Number of items per page

                                                        // Get total number of new death cases
                                                        $total_items_newDeath = count($case->getAllNewDeathCase($brgyID));
                                                        $total_pages_newDeath = ceil($total_items_newDeath / $items_per_page_newDeath);

                                                        // Initialize $page_newDeath and set it based on your requirements
                                                        $page_newDeath = isset($_GET['page_new']) ? $_GET['page_new'] : 1;

                                                        // Calculate the starting index for the current page
                                                        $start_index_newDeath = ($page_newDeath - 1) * $items_per_page_newDeath;

                                                        // Get the data for the current page
                                                        $newDeath = array_slice($case->getAllNewDeathCase($brgyID), $start_index_newDeath, $items_per_page_newDeath);
                                                        if (empty($newDeath)) {
                                                            echo '<tr><td colspan="5">No data found.</td></tr>';
                                                        } else {
                                                            foreach ($newDeath as $row) {
                                                                echo '<tr  class="text-center">';
                                                                echo '<td>' . (!empty($row['pname']) ? $row['pname'] : 'Unknown') . '</td>';
                                                                $input_date = $row['date'];
                                                                $date_obj = new DateTime($input_date);
                                                                $formatted_date = $date_obj->format("F j, Y");
                                                                echo '<td>' . $formatted_date . '</td>';
                                                                echo '<td>' . ($row['confirmedRabies'] == 0 ? 'Natural Cause' : 'Due to Rabies') . '</td>';
                                                                echo '<td>' . $row['description'] . '</td>';
                                                                echo '<td>
                                                                    <form method="post" action="./dashboardDeathCases.php?active-tab=1">
                                                                        <input type="hidden" name="caseID" value="' . $row['caseID'] . '">
                                                                        <input type="hidden" name="residentID" value="' . $row['residentID'] . '">
                                                                        <input type="hidden" name="notifType" id="notifType" value="7">
                                                                        <input type="hidden" name="notifDate" id="notifDate" value="' . date('Y-m-d H:i:s') . '">
                                                                        <input type="hidden" name="notifMessage" id="notifMessage" value="">
                                                                        <button type="submit" name="accept" class="btn btn-success my-1" onclick="document.getElementById(\'notifMessage\').value=\'Death Case report has been accepted.\'">Accept</button>
                                                                        <button type="submit" name="reject" class="btn btn-danger my-1" onclick="document.getElementById(\'notifMessage\').value=\'Death Case report has been rejected.\'">Reject</button>                                                                       
                                                                    </form>
                                                                </td>';
                                                                echo '</tr>';
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="d-flex justify-content-center mt-4">
                                                <ul class="pagination">
                                                    <?php for ($n = 1; $n <= $total_pages_newDeath; $n++) { ?>
                                                        <li class="page-item <?= ($n == $page_newDeath) ? 'active' : '' ?>">
                                                            <a class="page-link" href="./dashboardDeathCases.php?active-tab=1&page_new=<?= $n ?>"><?= $n ?></a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Valid Bite Cases Tab -->
                                <div class="tab-pane <?= ($active_tab == 2) ? ' active show' : '' ?>" id="validDeathCase">
                                    <div class="table">
                                        <div class="card-body">
                                            <label for="ValidSrch" class="form-label"></label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" id="validSrch" placeholder="Search...">
                                                <button class="btn btn-primary" id="validBtn" type="button">Search</button>
                                            </div>
                                            <div class=table-responsive>
                                                <table class="table text-center table-striped table-bordered table-hover" style="width:100%">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Pet Name</th>
                                                            <th>Date</th>
                                                            <th>Rabies</th>
                                                            <th>Description</th>
                                                            <th>View Location</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="valid-v">
                                                        <?php
                                                        $case = new Cases();
                                                        $items_per_page_validDeath = 8; // Number of items per page

                                                        // Get total number of valid death cases
                                                        $total_items_validDeath = count($case->getAllValidDeathCase($brgyID));
                                                        $total_pages_validDeath = ceil($total_items_validDeath / $items_per_page_validDeath);

                                                        // Initialize $page_validDeath and set it based on your requirements
                                                        $page_validDeath = isset($_GET['page_valid']) ? $_GET['page_valid'] : 1;

                                                        // Calculate the starting index for the current page
                                                        $start_index_validDeath = ($page_validDeath - 1) * $items_per_page_validDeath;

                                                        // Get the data for the current page
                                                        $validDeath = array_slice($case->getAllValidDeathCase($brgyID), $start_index_validDeath, $items_per_page_validDeath);
                                                        if (empty($validDeath)) {
                                                            echo '<tr class="text-center"><td colspan="6">No data found.</td></tr>';
                                                        } else {
                                                            foreach ($validDeath as $row) {
                                                                echo '<tr class="text-center">';
                                                                echo '<td>' . (!empty($row['pname']) ? $row['pname'] : 'Unknown') . '</td>';
                                                                $input_date = $row['date'];
                                                                $date_obj = new DateTime($input_date);
                                                                $formatted_date = $date_obj->format("F j, Y");
                                                                echo '<td>' . $formatted_date . '</td>';
                                                                echo '<td>' . ($row['confirmedRabies'] == 0 ? 'Natural Cause' : 'Due to Rabies') . '</td>';
                                                                echo '<td>' . $row['description'] . '</td>';
                                                                echo '<td>
                                                                        <form method="post" action="process_deathLocation.php">
                                                                        <input type="hidden" name="caseID" value="' . $row['caseID'] . '">
                                                                        <button type="submit" name="accept" class="btn btn-success">View Location</button>
                                                                    </form>
                                                                            </td>';
                                                                            echo '<td>';
                                                                            if ($row['confirmedRabies'] != 1) {
                                                                                echo '<form method="post" action="process_rabies.php">';
                                                                                echo '<input type="hidden" name="caseID" value="' . $row['caseID'] . '">';
                                                                                echo '<input type="hidden" name="residentID" value="' . $row['residentID'] . '">';
                                                                                echo '<input type="hidden" name="petID" value="' . $row['petID'] . '">';
                                                                                echo '<input type="hidden" name="brgyID" value="' . $row['brgyID'] . '">';
                                                                                echo '<input type="hidden" name="confirmedRabies" value="' . $row['confirmedRabies'] . '">';
                                                                                echo '<input type="hidden" name="notifType" id="notifType" value="9">';
                                                                                echo '<input type="hidden" name="notifDate" id="notifDate" value="' . date('Y-m-d H:i:s') . '">';
                                                                                echo '<input type="hidden" name="page_valid" value="' . $page_validDeath . '">';
                                                                                echo '<input type="hidden" name="notifMessage" id="notifMessage" value="A death report has been determined as a bite case.">';
                                                                                echo '<button type="submit" name="update" class="btn btn-danger">Update</button>';
                                                                                echo '</form>';
                                                                            } else {
                                                                                echo '<button class="btn btn-danger" disabled>Update</button>';
                                                                            }
                                                                            echo '</td>';
                                                                echo '</tr>';
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="d-flex justify-content-center mt-4">
                                                <ul class="pagination">
                                                    <?php for ($v = 1; $v <= $total_pages_validDeath; $v++) { ?>
                                                        <li class="page-item <?= ($v == $page_validDeath) ? 'active' : '' ?>">
                                                            <a class="page-link" href="./dashboardDeathCases.php?active-tab=2&page_valid=<?= $v ?>"><?= $v ?></a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <!-- Rejected Bite Cases Tab -->
                                <div class="tab-pane <?= ($active_tab == 3) ? ' active show' : '' ?>" id="rejectedDeathCase">
                                    <div class="table">
                                        <div class="card-body">
                                            <label for="rejPetSrch" class="form-label"></label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" id="rejSrch" placeholder="Search...">
                                                <button class="btn btn-primary" id="rejBtn" type="button">Search</button>
                                            </div>
                                            <div class=table-responsive>
                                                <table class="table text-center table-striped table-bordered table-hover" style="width:100%">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Pet Name</th>
                                                            <th>Date</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="valid-r">
                                                        <?php
                                                        $case = new Cases();
                                                        $items_per_page_rejectedDeath = 8; // Number of items per page

                                                        // Get total number of rejected death cases
                                                        $total_items_rejectedDeath = count($case->getAllRejectedDeathCase($brgyID));
                                                        $total_pages_rejectedDeath = ceil($total_items_rejectedDeath / $items_per_page_rejectedDeath);

                                                        // Initialize $page_rejectedDeath and set it based on your requirements
                                                        $page_rejectedDeath = isset($_GET['page']) ? $_GET['page'] : 1;

                                                        // Calculate the starting index for the current page
                                                        $start_index_rejectedDeath = ($page_rejectedDeath - 1) * $items_per_page_rejectedDeath;

                                                        // Get the data for the current page
                                                        $rejDeath = array_slice($case->getAllRejectedDeathCase($brgyID), $start_index_rejectedDeath, $items_per_page_rejectedDeath);
                                                        if (empty($rejDeath)) {
                                                            echo '<tr class="text-center"><td colspan="3">No data found.</td></tr>';
                                                        } else {
                                                            foreach ($rejDeath as $row) {
                                                                echo '<tr class="text-center">';
                                                                echo '<td>' . (!empty($row['pname']) ? $row['pname'] : 'Unknown') . '</td>';
                                                                $input_date = $row['date'];
                                                                $date_obj = new DateTime($input_date);
                                                                $formatted_date = $date_obj->format("F j, Y");
                                                                echo '<td>' . $formatted_date . '</td>';
                                                                echo '<td>' . $row['description'] . '</td>';
                                                                echo '</tr>';
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="d-flex justify-content-center mt-4">
                                                <ul class="pagination">
                                                    <?php for ($r = 1; $r <= $total_pages_rejectedDeath; $r++) { ?>
                                                        <li class="page-item <?= ($r == $page_rejectedDeath) ? 'active' : '' ?>">
                                                            <a class="page-link" href="./dashboardDeathCases.php?active-tab=3&page_rej=<?= $r ?>"><?= $r ?></a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- <form method="POST" action="./dashboard1.php?active-tab=1" id="reportDeathCaseForm">
                    <input type="hidden" name="residentID" value="<?php echo $brgyID; ?>">
                    <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                    <input type="hidden" name="userType" id="userType" value="<?php echo $userType; ?>">
                    <button type="submit" class="btn btn-primary">Back</button>
                </form>
                <a href="logout.php" class="btn btn-primary btn-manage">Logout</a> -->
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function($) {
            $(document).ready(function() {
                $("#newBtn").click(function() {
                    var searchValue = $("#newSrch").val().toLowerCase();
                    $("#valid-n tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(searchValue) > -1);
                    });
                });

                $("#validBtn").click(function() {
                    var searchValue = $("#validSrch").val().toLowerCase();
                    $("#valid-v tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(searchValue) > -1);
                    });
                });

                $("#rejBtn").click(function() {
                    var searchValue = $("#rejSrch").val().toLowerCase();
                    $("#valid-r tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(searchValue) > -1);
                    });
                });
            });
        });
    </script>
    <?php
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
       // Prepare the SQL query
       $sql = "SELECT n.notifMessage, n.notifDate, n.notifType, r.name FROM notification AS n 
           INNER JOIN resident AS r ON n.residentID = r.residentID 
           WHERE n.brgyID = ? OR n.residentID = ? AND n.notifType IN (0, 1, 2, 3, 4, 10) 
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
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allNotifs as $notif) { ?>
                                    <tr>
                                        <?php
                                        $redirectUrl = '';

                                        switch ($notif['notifType']) {
                                            case 0:
                                                $redirectUrl = './dashboard1.php?active-tab=1';
                                                break;
                                            case 1:
                                                $redirectUrl = './dashboard1pet.php?active-tab=1';
                                                break;
                                            case 2:
                                                $redirectUrl = './dashboardBiteCases.php?active-tab=1';
                                                break;
                                            case 3:
                                                $redirectUrl = './dashboardDeathCases.php?active-tab=1';
                                                break;
                                            case 4:
                                                $redirectUrl = './dashboardRabidCases.php?active-tab=1';
                                                break;
                                                // Add more cases if needed
                                            default:
                                                // Default case if notifType doesn't match any of the above
                                                $redirectUrl = '#';
                                        }

                                        ?>

                                        <td>
                                            <a href="<?php echo $redirectUrl; ?>" class="btn btn-link" style="color: black; border: none; text-decoration: none; text-align: justify;">
                                                <?php echo $notif['notifMessage'] . "<strong> by: " . $notif['name'] . "</strong>"; ?>
                                            </a>
                                        </td>
                                        <td><?php echo date('F j, Y, g:i A', strtotime($notif['notifDate'])); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <p>No notifications available.</p>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>