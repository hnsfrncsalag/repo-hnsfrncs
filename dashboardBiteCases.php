<?php
session_start();
$active_tab = $_GET['active-tab'];

require_once("class/cases.php");
require_once("class/barangay.php");
require_once("class/notification.php");

// Check if the user is logged in and has admin privileges (userType = 1)
if (!isset($_SESSION['user']) || $_SESSION['user']['userType'] != 1) {
    header("Location: login.php");
    exit();
}
if (isset($_POST['name'])) {
    // Retrieve the brgyID
    $name = $_POST['name'];

    // Now, you can use $brgyID as needed
}
if (isset($_POST['residentID'])) {
    // Retrieve the brgyID
    $residentID = $_POST['residentID'];

    // Now, you can use $brgyID as needed
}
if (isset($_POST['brgyID'])) {
    // Retrieve the brgyID
    $brgyID = $_POST['brgyID'];

    // Now, you can use $brgyID as needed
}
if (isset($_POST['userType'])) {
    // Retrieve the brgyID
    $userType = $_POST['userType'];

    // Now, you can use $brgyID as needed
}
$brgyID = isset($_SESSION['user']['brgyID']) ? $_SESSION['user']['brgyID'] : '';
$user = $_SESSION['user'];
$name = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : '';
$residentID = isset($_SESSION['user']['residentID']) ? $_SESSION['user']['residentID'] : '';

$barangay = new Barangay();
$result1 = $barangay->getBrgyName($brgyID);

// Handle form submission
// if ($_SERVER["REQUEST_METHOD"] === "POST") {
//     if (isset($_POST["verify"]) || isset($_POST["reject"])) {
//         $caseID = $_POST["caseID"];
//         $caseStatus = isset($_POST["verify"]) ? 1 : 2; // 1 for verified, 2 for not verified

//         $case = new Cases();
//         $result = $case->updateBiteCaseStatus($caseID, $caseStatus);

//         if ($result === true) {
//             // Successfully updated Bite Case status
//             if ($caseStatus == 2) {
//                 // Redirect back immediately for status 2 (not verified)
//                 header("Location: ./dashboardBiteCases.php?active-tab=1");
//                 exit();
//             }

//             // Redirect for status 1 (verified)
//             echo '<script>alert("Bite Case status updated successfully."); window.location.href = "proccess_viewBitesCaseLocation.php?caseID=' . $caseID . '";</script>';
//         } else {
//             // Failed to update Bite Case status
//             echo "Failed to update Bite Case status: " . $result;
//         }
//     }
// }
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["accept"]) || isset($_POST["reject"])) {
        $caseID = $_POST["caseID"];
        $caseStatus = isset($_POST["accept"]) ? 1 : 2; // 1 for accepted, 2 for rejected

        $case = new Cases();
        $result = $case->updateBiteCaseStatus($caseID, $caseStatus);

        if ($result === true) {
            // Successfully updated user status
            $notifType = 12;
            $notifDate = date('Y-m-d H:i:s');
            $notifMessage = $_POST["notifMessage"];
            $residentID = $_POST['residentID'];
            $MAOID = $_POST['residentID'];

            $notification = new Notification();
            $notificationResult = $notification->updateStatus($caseID, $brgyID, $MAOID, $residentID, $notifType, $notifDate, $notifMessage);

            if ($caseStatus == 2 && $notificationResult === true) {
                // Redirect back immediately for rejected status
                echo '<script>alert("Case Status Updated Successfully.")window.location.href = "./dashboardBiteCases.php?active-tab=1";</script>';
            } else {
                // Redirect as per your existing logic
                echo '<script>alert("Case Status Updated Successfully."); window.location.href = "proccess_viewBitesCaseLocation.php?caseID=' . $caseID . '";</script>';
            }
        } else {
            // Failed to update user status
            echo "Failed to update user status: " . $result;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bite Cases</title>
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
                        <div class="container mt-4">
                            <h1 class="text-center">Manage Bite Cases for Barangay: <?php echo $result1 ?></h1>
                            <!-- Navigation Tabs -->
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link<?= ($active_tab == 1) ? ' active' : '' ?>" href="./dashboardBiteCases.php?active-tab=1&page_new=1#newBiteCase" data-bs-toggle="tab">New Cases</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link<?= ($active_tab == 2) ? ' active' : '' ?>" href="./dashboardBiteCases.php?active-tab=2&page_valid=1#validBiteCase" data-bs-toggle="tab">Valid Cases</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link<?= ($active_tab == 3) ? ' active' : '' ?>" href="./dashboardBiteCases.php?active-tab=3&page_rej=1#rejectedBiteCase" data-bs-toggle="tab">Rejected Cases</a>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content">
                                <!-- New Bite Cases Tab -->
                                <?php
                                // Assuming $resident is your data array, $active_tab is set elsewhere
                                $items_per_page_new = 8; // You can adjust this value as needed

                                // Retrieve the search query from the form submission
                                $search_new = isset($_POST['newSearch']) ? $_POST['newSearch'] : '';

                                // Construct the SQL query to filter data based on the search terms
                                // Modify this query according to your database structure and requirements
                                $newBiteQuery = "SELECT * FROM `case` AS c 
                                LEFT JOIN pet AS p ON c.petID = p.petID 
                                LEFT JOIN resident AS r ON c.residentID = r.residentID  
                                WHERE (pname LIKE ? OR victimsName LIKE ? OR name LIKE ?) 
                                    AND c.caseStatus = 0 
                                    AND caseType = 0 
                                    AND c.brgyID = ? 
                                ORDER BY c.date DESC";

                                // Prepare and execute the SQL query
                                $stmt_new = mysqli_prepare($conn, $newBiteQuery);
                                $search_term = "%$search_new%"; // Adding wildcards for LIKE search
                                mysqli_stmt_bind_param($stmt_new, "sssi", $search_term, $search_term, $search_term, $brgyID); // Assuming $residentID is available
                                mysqli_stmt_execute($stmt_new);
                                $newBiteResult = mysqli_stmt_get_result($stmt_new);

                                // Initialize an array to store the filtered data
                                $newBite = [];

                                // Initialize total pages with default value
                                $total_pages_new = 1;

                                // Check if search results are found
                                if ($newBiteResult && mysqli_num_rows($newBiteResult) > 0) {
                                    // Fetch all search results
                                    $allNewBite = mysqli_fetch_all($newBiteResult, MYSQLI_ASSOC);

                                    // Calculate pagination parameters for search results
                                    $total_items_new = count($allNewBite);
                                    $total_pages_new = ceil($total_items_new / $items_per_page_new);
                                    $page_new = isset($_GET['page_new']) ? $_GET['page_new'] : 1;
                                    $start_index_new = ($page_new - 1) * $items_per_page_new;

                                    // Slice the search results to display the current page
                                    $newBite = array_slice($allNewBite, $start_index_new, $items_per_page_new);
                                }
                                ?>
                                <div class="tab-pane <?= ($active_tab == 1) ? ' active show' : '' ?>" id="newBiteCase">
                                    <div class="table">
                                        <div class="card-body">
                                            <label for="newPetSrch" class="form-label"></label>
                                            <form method="POST" action="dashboardBiteCases.php?active-tab=1">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" id="newSearch" name="newSearch" placeholder="Search by Reporter's Name, Pet's Name, Or Victim's Name" value="<?php echo $search_new; ?>">
                                                    <button class="btn btn-primary" id="newSearchBtn" type="submit">Search</button>
                                                </div>
                                            </form>
                                            <div class=table-responsive>
                                                <table class="table text-center table-bordered table-hover" style="width:100%">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Reporter's Name</th>
                                                            <th>Pet Name</th>
                                                            <th>Victim's Name</th>
                                                            <th>Species</th>
                                                            <th>Sex</th>
                                                            <th>Date</th>
                                                            <th>Description</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="valid-n">
                                                        <?php
                                                        // $case = new Cases();
                                                        // $items_per_page_bite = 8; // Number of items per page

                                                        // // Get total number of new bite cases
                                                        // $total_items_bite = count($case->getAllNewBiteCase($brgyID));
                                                        // $total_pages_bite = ceil($total_items_bite / $items_per_page_bite);

                                                        // // Initialize $page_bite and set it based on your requirements
                                                        // $page_bite = isset($_GET['page_new']) ? $_GET['page_new'] : 1;

                                                        // // Calculate the starting index for the current page
                                                        // $start_index_bite = ($page_bite - 1) * $items_per_page_bite;

                                                        // // Get the data for the current page
                                                        // $newBite = array_slice($case->getAllNewBiteCase($brgyID), $start_index_bite, $items_per_page_bite);
                                                        if (empty($newBite)) {
                                                            echo '<tr><td colspan="10">No data found.</td></tr>';
                                                        } else {
                                                            foreach ($newBite as $row) {
                                                                echo '<tr class="text-center">';
                                                                echo '<td>' . $row['name'] . '</td>';
                                                                echo '<td>' . (!empty($row['pname']) ? $row['pname'] : 'Unknown') . '</td>';
                                                                echo '<td>' . $row['victimsName'] . '</td>';
                                                                echo '<td>' . ($row['petType'] == 0 ? 'Dog' : 'Cat') . '</td>';
                                                                echo '<td>' . ($row['sex'] == 0 ? 'M' : 'F') . '</td>';

                                                                // Input date as a string
                                                                $input_date = $row['date'];

                                                                // Convert the input date to a DateTime object
                                                                $date_obj = new DateTime($input_date);

                                                                // Format the date as "Month Day, Year"
                                                                $formatted_date = $date_obj->format("F j, Y");

                                                                // Print the formatted date
                                                                echo '<td>' . $formatted_date . '</td>';
                                                                echo '<td>' . $row['description'] . '</td>';
                                                                echo '<td>
                                                                    <form method="post" action="./dashboardBiteCases.php?active-tab=1">
                                                                        <input type="hidden" name="caseID" value="' . $row['caseID'] . '">
                                                                        <input type="hidden" name="residentID" value="' . $row['residentID'] . '">
                                                                        <input type="hidden" name="MAOID" value="' .  $residentID . '">
                                                                        <input type="hidden" name="notifType" id="notifType" value="12">
                                                                        <input type="hidden" name="notifDate" id="notifDate" value="' . date('Y-m-d H:i:s') . '">
                                                                        <input type="hidden" name="notifMessage" id="notifMessage" value="">
                                                                        <button type="submit" name="accept" class="btn btn-success my-1" onclick="document.getElementById(\'notifMessage\').value=\'Bite Case report has been accepted.\'">Accept</button>
                                                                        <button type="submit" name="reject" class="btn btn-danger my-1" onclick="document.getElementById(\'notifMessage\').value=\'Bite Case report has been rejected.\'">Reject</button>                                                                       
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
                                                    <?php for ($n = 1; $n <= $total_pages_new; $n++) { ?>
                                                        <li class="page-item <?= ($n == $page_new) ? 'active' : '' ?>">
                                                            <a class="page-link" href="./dashboardBiteCases.php?active-tab=1&page_new=<?= $n ?>&newSearch=<?php echo urlencode($search_new); ?>"><?= $n ?></a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <!-- Valid Bite Cases Tab -->
                                <?php
                                // Assuming $resident is your data array, $active_tab is set elsewhere
                                $items_per_page_valid = 8; // You can adjust this value as needed

                                // Retrieve the search query from the form submission
                                $search_valid = isset($_POST['validSearch']) ? $_POST['validSearch'] : '';

                                // Construct the SQL query to filter data based on the search terms
                                // Modify this query according to your database structure and requirements
                                $validBiteQuery = "SELECT * FROM `case` AS c 
                                LEFT JOIN pet AS p ON c.petID = p.petID 
                                LEFT JOIN resident AS r ON c.residentID = r.residentID  
                                WHERE (pname LIKE ? OR victimsName LIKE ? OR name LIKE ?) 
                                    AND c.caseStatus = 1 
                                    AND caseType = 0 
                                    AND c.brgyID = ? 
                                ORDER BY c.date DESC";

                                // Prepare and execute the SQL query
                                $stmt_valid = mysqli_prepare($conn, $validBiteQuery);
                                $search_term = "%$search_valid%"; // Adding wildcards for LIKE search
                                mysqli_stmt_bind_param($stmt_valid, "sssi", $search_term, $search_term, $search_term, $brgyID); // Assuming $residentID is available
                                mysqli_stmt_execute($stmt_valid);
                                $validBiteResult = mysqli_stmt_get_result($stmt_valid);

                                // Initialize an array to store the filtered data
                                $validBite = [];

                                // Initialize total pages with default value
                                $total_pages_valid = 1;

                                // Check if search results are found
                                if ($validBiteResult && mysqli_num_rows($validBiteResult) > 0) {
                                    // Fetch all search results
                                    $allValidBite = mysqli_fetch_all($validBiteResult, MYSQLI_ASSOC);

                                    // Calculate pagination parameters for search results
                                    $total_items_valid = count($allValidBite);
                                    $total_pages_valid = ceil($total_items_valid / $items_per_page_valid);
                                    $page_valid = isset($_GET['page_valid']) ? $_GET['page_valid'] : 1;
                                    $start_index_valid = ($page_valid - 1) * $items_per_page_valid;

                                    // Slice the search results to display the current page
                                    $validBite = array_slice($allValidBite, $start_index_valid, $items_per_page_valid);
                                }
                                ?>
                                <div class="tab-pane <?= ($active_tab == 2) ? ' active show' : '' ?>" id="validBiteCase">
                                    <div class="table">
                                        <div class="card-body">
                                            <label for="ValidSrch" class="form-label"></label>
                                            <form method="POST" action="dashboardBiteCases.php?active-tab=2">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" id="validSearch" name="validSearch" placeholder="Search by Reporter's Name, Pet's Name, Or Victim's Name" value="<?php echo $search_valid; ?>">
                                                    <button class="btn btn-primary" id="validSearchBtn" type="submit">Search</button>
                                                </div>
                                            </form>
                                            <div class=table-responsive>
                                                <table class="table text-center table-bordered table-hover" style="width:100%">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Reporter's Name</th>
                                                            <th>Pet Name</th>
                                                            <th>Victim's Name</th>
                                                            <th>Species</th>
                                                            <th>Sex</th>
                                                            <th>Date</th>
                                                            <th>Description</th>
                                                            <th>View Location</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="valid-v">
                                                        <?php
                                                        // $case = new Cases();
                                                        // $items_per_page_valid_bite = 8; // Number of items per page

                                                        // // Get total number of valid bite cases
                                                        // $total_items_valid_bite = count($case->getallValidBiteCase($brgyID));
                                                        // $total_pages_valid_bite = ceil($total_items_valid_bite / $items_per_page_valid_bite);

                                                        // // Initialize $page_valid_bite and set it based on your requirements
                                                        // $page_valid_bite = isset($_GET['page_valid']) ? $_GET['page_valid'] : 1;

                                                        // // Calculate the starting index for the current page
                                                        // $start_index_valid_bite = ($page_valid_bite - 1) * $items_per_page_valid_bite;

                                                        // // Get the data for the current page
                                                        // $validBite = array_slice($case->getallValidBiteCase($brgyID), $start_index_valid_bite, $items_per_page_valid_bite);
                                                        if (empty($validBite)) {
                                                            echo '<tr><td colspan="10">No data found.</td></tr>';
                                                        } else {
                                                            foreach ($validBite as $row) {
                                                                echo '<tr class="text-center">';
                                                                echo '<td>' . $row['name'] . '</td>';
                                                                echo '<td>' . (!empty($row['pname']) ? $row['pname'] : 'Unknown') . '</td>';
                                                                echo '<td>' . $row['victimsName'] . '</td>';
                                                                echo '<td>' . ($row['petType'] == 0 ? 'Dog' : 'Cat') . '</td>';
                                                                echo '<td>' . ($row['sex'] == 0 ? 'M' : 'F') . '</td>';
                                                                // Input date as a string
                                                                $input_date = $row['date'];

                                                                // Convert the input date to a DateTime object
                                                                $date_obj = new DateTime($input_date);

                                                                // Format the date as "Month Day, Year"
                                                                $formatted_date = $date_obj->format("F j, Y");

                                                                // Print the formatted date
                                                                echo '<td>' . $formatted_date . '</td>';
                                                                echo '<td>' . $row['description'] . '</td>';
                                                                echo '<td>
                                                                    <form method="post" action="proccess_viewBitesCaseLocation.php">
                                                                    <input type="hidden" name="page_valid" value="' . $page_valid . '">
                                                                    <input type="hidden" name="caseID" value="' . $row['caseID'] . '">
                                                                    <button type="submit" name="accept" class="btn btn-success">View Location</button>
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
                                                    <?php for ($n = 1; $n <= $total_pages_valid; $n++) { ?>
                                                        <li class="page-item <?= ($n == $page_valid) ? 'active' : '' ?>">
                                                            <a class="page-link" href="./dashboardBiteCases.php?active-tab=2&page_valid=<?= $n ?>&validSearch=<?php echo urlencode($search_valid); ?>"><?= $n ?></a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <!-- Rejected Bite Cases Tab -->
                                <?php
                                // Assuming $resident is your data array, $active_tab is set elsewhere
                                $items_per_page_rej = 8; // You can adjust this value as needed

                                // Retrieve the search query from the form submission
                                $search_rej = isset($_POST['rejSearch']) ? $_POST['rejSearch'] : '';

                                // Construct the SQL query to filter data based on the search terms
                                // Modify this query according to your database structure and requirements
                                $rejBiteQuery = "SELECT * FROM `case` AS c 
                                LEFT JOIN pet AS p ON c.petID = p.petID 
                                LEFT JOIN resident AS r ON c.residentID = r.residentID  
                                WHERE (pname LIKE ? OR victimsName LIKE ? OR name LIKE ?) 
                                    AND c.caseStatus = 2
                                    AND caseType = 0 
                                    AND c.brgyID = ? 
                                ORDER BY c.date DESC";

                                // Prepare and execute the SQL query
                                $stmt_rej = mysqli_prepare($conn, $rejBiteQuery);
                                $search_term = "%$search_valid%"; // Adding wildcards for LIKE search
                                mysqli_stmt_bind_param($stmt_rej, "sssi", $search_term, $search_term, $search_term, $brgyID); // Assuming $residentID is available
                                mysqli_stmt_execute($stmt_rej);
                                $rejBiteResult = mysqli_stmt_get_result($stmt_rej);

                                // Initialize an array to store the filtered data
                                $rejBite = [];

                                // Initialize total pages with default value
                                $total_pages_rej = 1;

                                // Check if search results are found
                                if ($rejBiteResult && mysqli_num_rows($rejBiteResult) > 0) {
                                    // Fetch all search results
                                    $allRejBite = mysqli_fetch_all($rejBiteResult, MYSQLI_ASSOC);

                                    // Calculate pagination parameters for search results
                                    $total_items_rej = count($allRejBite);
                                    $total_pages_rej = ceil($total_items_rej / $items_per_page_rej);
                                    $page_rej = isset($_GET['page_rej']) ? $_GET['page_rej'] : 1;
                                    $start_index_rej = ($page_rej - 1) * $items_per_page_rej;

                                    // Slice the search results to display the current page
                                    $rejBite = array_slice($allRejBite, $start_index_rej, $items_per_page_rej);
                                }
                                ?>

                                <div class="tab-pane <?= ($active_tab == 3) ? ' active show' : '' ?>" id="rejectedBiteCase">
                                    <div class="table">
                                        <div class="card-body">
                                            <label for="rejBiteSrch" class="form-label"></label>
                                            <form method="POST" action="dashboardBiteCases.php?active-tab=3">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" id="validSearch" name="validSearch" placeholder="Search by Reporter's Name, Pet's Name, Or Victim's Name" value="<?php echo $search_rej; ?>">
                                                    <button class="btn btn-primary" id="validSearchBtn" type="submit">Search</button>
                                                </div>
                                            </form>
                                            <div class=table-responsive>
                                                <table class="table text-center table-bordered table-hover" style="width:100%">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Reporter's Name</th>
                                                            <th>Pet Name</th>
                                                            <th>Victim's Name</th>
                                                            <th>Species</th>
                                                            <th>Sex</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="valid-r">
                                                        <?php
                                                        // $case = new Cases();
                                                        // $items_per_page_rejected_bite = 8; // Number of items per page

                                                        // // Get total number of rejected bite cases
                                                        // $total_items_rejected_bite = count($case->getAllRejectedBiteCase($brgyID));
                                                        // $total_pages_rejected_bite = ceil($total_items_rejected_bite / $items_per_page_rejected_bite);

                                                        // // Initialize $page_rejected_bite and set it based on your requirements
                                                        // $page_rejected_bite = isset($_GET['page_rej']) ? $_GET['page_rej'] : 1;

                                                        // // Calculate the starting index for the current page
                                                        // $start_index_rejected_bite = ($page_rejected_bite - 1) * $items_per_page_rejected_bite;

                                                        // // Get the data for the current page
                                                        // $rejBite = array_slice($case->getAllRejectedBiteCase($brgyID), $start_index_rejected_bite, $items_per_page_rejected_bite);
                                                        if (empty($rejBite)) {
                                                            echo '<tr><td colspan="10">No data found.</td></tr>';
                                                        } else {
                                                            foreach ($rejBite as $row) {
                                                                echo '<tr>';
                                                                echo '<td>' . $row['name'] . '</td>';
                                                                echo '<td>' . (!empty($row['pname']) ? $row['pname'] : 'Unknown') . '</td>';
                                                                echo '<td>' . $row['victimsName'] . '</td>';
                                                                echo '<td>' . ($row['petType'] == 0 ? 'Dog' : 'Cat') . '</td>';
                                                                echo '<td>' . ($row['sex'] == 0 ? 'M' : 'F') . '</td>';
                                                                // echo '<td>' . $row['brgyID'] . '</td>';
                                                                $input_date = $row['date'];

                                                                // Convert the input date to a DateTime object
                                                                $date_obj = new DateTime($input_date);

                                                                // Format the date as "Month Day, Year"
                                                                $formatted_date = $date_obj->format("F j, Y");

                                                                // Print the formatted date
                                                                echo '<td>' . $formatted_date . '</td>';
                                                                echo '</tr>';
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="d-flex justify-content-center mt-4">
                                                <ul class="pagination">
                                                    <?php for ($n = 1; $n <= $total_pages_rej; $n++) { ?>
                                                        <li class="page-item <?= ($n == $page_rej) ? 'active' : '' ?>">
                                                            <a class="page-link" href="./dashboardBiteCases.php?active-tab=3&page_rej=<?= $n ?>&rejSearch=<?php echo urlencode($search_rej); ?>"><?= $n ?></a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
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