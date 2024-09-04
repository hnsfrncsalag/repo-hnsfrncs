<?php
session_start();
require_once("class/db_connect.php");
global $conn;
$name = isset($_SESSION['admin']['name']) ? $_SESSION['admin']['name'] : '';
?>
<!DOCTYPE html>
<!-- <html lang="en"> -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <!-- <link rel="manifest" href="/manifest.json"> -->
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
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->

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
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="petstaticon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> -->

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


</head>
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

    #registered {
        border-collapse: collapse;
        width: 100%;
    }

    #registered th,
    #registered td {
        border: 1px solid #dddddd;
        padding: 8px;
    }

    /* Add hover effect */
    #registered tr:hover {
        background-color: #f2f2f2;
    }
</style>

<body>
    <?php
    // Count the number of new notifications
    $sql_count = "SELECT COUNT(*) AS new_notif_count FROM notification WHERE notifType IN (2, 3, 6, 8) AND adminRead = 0";
    $stmt_count = $conn->prepare($sql_count);
    // $stmt_count->bind_param("ii", $residentID, $brgyID);
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
                                <a href="dashboardMAO.php" class="nav-link text-dark px-sm-0 px-2" aria-current="page">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                        <path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z" />
                                    </svg><span class="ms-2 d-none d-sm-inline">Home</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <form method="post" action="./assign_officer.php?active-tab=1">
                                    <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                    <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                    <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                                    <input type="hidden" name="active-tab" value="1">
                                    <button type="submit" data-bs-toggle="collapse" class="nav-link text-dark px-sm-0 px-2">
                                        <strong><i class="bi bi-person-plus" style="font-size: 1.2rem;"></i></strong> <!-- Bootstrap person plus icon -->
                                        <span class="ms-2 d-none d-sm-inline">Assign Officer</span>
                                    </button>
                                </form>
                            </li>

                            <!-- <li class="dropdown">
                            <a href="#" class="nav-link dropdown-toggle text-decoration-none text-dark px-sm-0 px-1" id="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                            <!-- <path d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V299.6l-94.7 94.7c-8.2 8.2-14 18.5-16.8 29.7l-15 60.1c-2.3 9.4-1.8 19 1.4 27.8H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zM549.8 235.7l14.4 14.4c15.6 15.6 15.6 40.9 0 56.6l-29.4 29.4-71-71 29.4-29.4c15.6-15.6 40.9-15.6 56.6 0zM311.9 417L441.1 287.8l71 71L382.9 487.9c-4.1 4.1-9.2 7-14.9 8.4l-60.1 15c-5.5 1.4-11.2-.2-15.2-4.2s-5.6-9.7-4.2-15.2l15-60.1c1.4-5.6 4.3-10.8 8.4-14.9z" />
                                </svg><span class="ms-2 d-none d-sm-inline">Report Case</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark text-lg" aria-labelledby="dropdown">
                                <li><a class="dropdown-item" href="addBiteCase.php">Report Bite Case</a></li>
                                <li><a class="dropdown-item" href="addDeathCase.php">Report Death Case</a></li>
                                <li><a class="dropdown-item" href="reportRabidBao.php">Report Rabid Case</a></li>
                            </ul>
                        </li> -->
                            <!-- <li class="dropdown">
                            <a href="#" class="nav-link dropdown-toggle text-decoration-none text-dark px-sm-0 px-1" id="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                            <!-- <path d="M448 160H320V128H448v32zM48 64C21.5 64 0 85.5 0 112v64c0 26.5 21.5 48 48 48H464c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48H48zM448 352v32H192V352H448zM48 288c-26.5 0-48 21.5-48 48v64c0 26.5 21.5 48 48 48H464c26.5 0 48-21.5 48-48V336c0-26.5-21.5-48-48-48H48z" />
                                </svg><span class="ms-2 d-none d-sm-inline">Manage</span>
                            </a> -->
                            <!-- <ul class="dropdown-menu dropdown-menu-dark text-lg " aria-labelledby="dropdown">
                                <li><a class="dropdown-item" href="./dashboard1.php?active-tab=1">Manage Resident</a></li>
                                <li><a class="dropdown-item" href="./dashboard1pet.php?active-tab=1">Manage Pet</a></li>
                                <li><a class="dropdown-item" href="./dashboardBiteCases.php?active-tab=1">Manage Bite Cases</a></li>
                                <li><a class="dropdown-item" href="./dashboardRabidCases.php?active-tab=1">Manage Suspected Cases</a></li>
                                <li><a class="dropdown-item" href="./dashboardDeathCases.php?active-tab=1">Manage Death Cases</a></li>
                            </ul>
                        </li> -->
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
                                <form method="post" action="./tabular.php?active-tab=1" style="display: inline;">
                                    <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                    <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                    <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                                    <input type="hidden" name="name" value="<?php echo $name; ?>">
                                    <button type="submit" data-bs-toggle="collapse" class="nav-link text-dark px-sm-0 px-2 active"> <svg xmlns="http://www.w3.org/2000/svg" height="20" width="15" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                            <path d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128z" />
                                        </svg><span class="ms-2 d-none d-sm-inline"> Reports</span></button>
                                </form>
                            </li>


                            <li class="nav-item">
                                <form method="post" action="./pin_location.php?active-tab=1" style="display: inline;">
                                    <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                    <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                    <input type="hidden" name="name" value="<?php echo $name; ?>">
                                    <button type="submit" class="nav-link text-dark">
                                        <i class="bi bi-geo-alt"></i>
                                        <span class="ms-2 d-none d-sm-inline"> View Pin Location
                                        </span></button>
                                </form>
                            </li>

                            <!-- <li class="nav-item">
                            <form method="post" action="logout.php" style="display: inline;">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <button type="submit" class="nav-link text-dark px-sm-0 px-2">
                                    <i class="bi bi-arrow-left"></i> 
                                    <span class="ms-2 d-none d-sm-inline"> Log-out</span>
                                </button>
                            </form>
                        </li> -->
                            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">


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

                            <!-- <li class="nav-item">
                            <form method="post" action="createAccForResident.php" style="display: inline;">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="name" value="<?php echo $name; ?>"> -->
                            <!-- <button type="submit" data-bs-toggle="collapse" class="nav-link text-dark px-sm-0 px-2"> <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                            <!-- <path d="M96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3zM504 312V248H440c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V136c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H552v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z" />
                                    </svg><span class="ms-2 d-none d-sm-inline">Account for Resident</span></button>
                                </a>
                            </form>
                        </li> -->
                            <div class="dropdown py-sm-4 mt-sm-auto ms-auto ms-sm-0 flex-shrink-1">
                                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" class="rounded-circle me-2 outline" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                        <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z" />
                                    </svg>
                                    <strong><span class="d-none d-sm-inline mx-1"> <?php echo $name; ?> </span>
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
                                <!-- <div class="container"> -->
                                <!-- <div class="container mt-5"> -->
                                <!-- <form action="dashboardMAO.php">
                    <button value="Back" class="btn btn-primary">Back</button> -->
                                </form>
                                <h1>Reports</h1>
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="resident-tab" data-toggle="tab" href="#registries" role="tab" aria-controls="registries" aria-selected="false">Registries</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="bites-tab" data-toggle="tab" href="#bites" role="tab" aria-controls="bites" aria-selected="false">Bites</a>
                                    </li>
                                    <!-- <li class="nav-item">
                            <a class="nav-link" id="death-tab" data-toggle="tab" href="#death" role="tab" aria-controls="death" aria-selected="false">Death</a>
                        </li> -->
                                    <li class="nav-item">
                                        <a class="nav-link" id="suspected-tab" data-toggle="tab" href="#suspected" role="tab" aria-controls="suspected" aria-selected="false">Suspected</a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="myTabContent">
                                    <!-- resident Tab -->
                                    <div class="tab-pane fade show active" id="registries" role="tabpanel" aria-labelledby="registries-tab">
                                        <form action="" method="GET" id="registriesForm">
                                            <!-- <div class="card">
                                    <div class="card-body"> -->
                                            <div class="form-group">
                                                <select class="form-control" id="barangaySelectRegistries" name="barangay_registries" onchange="submitForm('registriesForm')">
                                                    <option value="0">Select Barangay</option>
                                                    <?php
                                                    // Database connection
                                                    global $conn;

                                                    if ($conn->connect_error) {
                                                        die("Connection failed: " . $conn->connect_error);
                                                    }
                                                    $sql = "SELECT * FROM barangay";
                                                    $result = $conn->query($sql);
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<option value='" . $row["brgyID"] . "'>" . $row["barangay"] . "</option>";
                                                        }
                                                    } else {
                                                        echo "<option value>No barangays found</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <!-- </div>
                                        </div> -->
                                            </div>
                                        </form>
                                        <div class="mb-3"></div>
                                        <!-- </div> -->
                                        <!-- Display registries data here -->
                                        <div class="table-responsive">
                                            <?php
                                            if (isset($_GET['barangay_registries'])) {
                                                $selected_barangay = $_GET['barangay_registries'];

                                                // Check connection
                                                if ($conn->connect_error) {
                                                    die("Connection failed: " . $conn->connect_error);
                                                }


                                                $query = "SELECT COUNT(*) AS count FROM resident AS r
                                                NATURAL JOIN pet AS p
                                                LEFT JOIN (
                                                    SELECT petID, MAX(lastVaccination) AS maxlastVaccination, lastVaccination
                                                    FROM vaccination
                                                    GROUP BY petID
                                                ) AS v ON p.petID = v.petID
                                                LEFT JOIN barangay AS b ON r.brgyID = b.brgyID
                                                WHERE p.status = 1 AND r.brgyID = ?";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bind_param("i", $selected_barangay);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $count = 0;
                                                if ($row = $result->fetch_assoc()) {
                                                    $count = $row['count'];
                                                }


                                                $query = "SELECT COUNT(*) AS count FROM resident AS r
                                                NATURAL JOIN pet AS p
                                                LEFT JOIN (
                                                    SELECT petID, MAX(lastVaccination) AS maxlastVaccination, lastVaccination
                                                    FROM vaccination
                                                    GROUP BY petID
                                                ) AS v ON p.petID = v.petID
                                                LEFT JOIN barangay AS b ON r.brgyID = b.brgyID
                                                WHERE p.status = 1 AND p.sex = 0 AND r.brgyID = ?";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bind_param("i", $selected_barangay);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $male = 0;
                                                if ($row = $result->fetch_assoc()) {
                                                    $male = $row['count'];
                                                }



                                                $query = "SELECT COUNT(*) AS count FROM resident AS r
                                                NATURAL JOIN pet AS p
                                                LEFT JOIN (
                                                    SELECT petID, MAX(lastVaccination) AS maxlastVaccination, lastVaccination
                                                    FROM vaccination
                                                    GROUP BY petID
                                                ) AS v ON p.petID = v.petID
                                                LEFT JOIN barangay AS b ON r.brgyID = b.brgyID
                                                WHERE p.status = 1 AND p.sex = 1 AND r.brgyID = ?";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bind_param("i", $selected_barangay);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $female = 0;
                                                if ($row = $result->fetch_assoc()) {
                                                    $female = $row['count'];
                                                }


                                                $query = "SELECT COUNT(*) AS count FROM resident AS r
                                                NATURAL JOIN pet AS p
                                                LEFT JOIN (
                                                    SELECT petID, MAX(lastVaccination) AS maxlastVaccination, lastVaccination
                                                    FROM vaccination
                                                    GROUP BY petID
                                                ) AS v ON p.petID = v.petID
                                                LEFT JOIN barangay AS b ON r.brgyID = b.brgyID
                                                WHERE p.status = 1 AND p.petType = 0 AND r.brgyID = ?";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bind_param("i", $selected_barangay);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $dog = 0;
                                                if ($row = $result->fetch_assoc()) {
                                                    $dog = $row['count'];
                                                }


                                                $query = "SELECT COUNT(*) AS count FROM resident AS r
                                                NATURAL JOIN pet AS p
                                                LEFT JOIN (
                                                    SELECT petID, MAX(lastVaccination) AS maxlastVaccination, lastVaccination
                                                    FROM vaccination
                                                    GROUP BY petID
                                                ) AS v ON p.petID = v.petID
                                                LEFT JOIN barangay AS b ON r.brgyID = b.brgyID
                                                WHERE p.status = 1 AND p.petType = 1 AND r.brgyID = ?";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bind_param("i", $selected_barangay);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $cat = 0;
                                                if ($row = $result->fetch_assoc()) {
                                                    $cat = $row['count'];
                                                }



                                                //   $stmt->close(); 

                                                // Query to fetch registries data for the selected barangay
                                                $sql = "SELECT p.*, r.*, v.*, b.*, p.petID
                                                        FROM resident AS r
                                                        NATURAL JOIN pet AS p
                                                        LEFT JOIN (
                                                            SELECT petID, MAX(lastVaccination) AS maxlastVaccination, lastVaccination
                                                            FROM vaccination
                                                            GROUP BY petID
                                                        ) AS v ON p.petID = v.petID
                                                        LEFT JOIN barangay AS b ON r.brgyID = b.brgyID
                                                        WHERE p.status = 1 AND r.brgyID = ?";

                                                // $stmt->close();
                                                $stmt = $conn->prepare($sql);
                                                $stmt->bind_param("i", $selected_barangay);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                // Count the number of rows

                                                if ($result->num_rows > 0) {

                                            ?>
                                                    <div class="mb-3" id="reg">
                                                        <tr>
                                                            <td colspan="11"><Strong>Total Number of Registries: </Strong> <?php echo $count; ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <th colspan="11"><Strong>Total Number of Male:</Strong> <?php echo $male; ?><br>
                                                            <th colspan="11"><Strong>Total Number of Female:</Strong> <?php echo $female; ?></th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <th colspan="11"><Strong>Total Number of Dog:</Strong> <?php echo $dog; ?></th><br>
                                                            <th colspan="11"><Strong>Total Number of Cat:</Strong> <?php echo $cat; ?></th><br>
                                                        </tr>
                                                    </div>
                                                    <div class="mb-3"></div>
                                                    <table id="registered" class="table text-center table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Owner's Name</th>
                                                                <th>Date of Registry</th>
                                                                <th>Species</th>
                                                                <th>Name of Pet</th>
                                                                <th>Sex</th>
                                                                <th>Age</th>
                                                                <th>Neutering</th>
                                                                <th>Color</th>
                                                                <th>Vaccination Status</th>
                                                                <th>Latest Vaccination</th>
                                                                <th>Address</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            while ($row = $result->fetch_assoc()) {
                                                                echo "<tr>";
                                                                echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                                                                $input_date = $row['regDate'];
                                                                $formatted_date = date("F j, Y", strtotime($input_date));
                                                                echo "<td>" . $formatted_date . "</td>";
                                                                echo "<td>" . ($row["petType"] == 0 ? 'Dog' : 'Cat') . "</td>";
                                                                echo "<td>" . htmlspecialchars($row["pname"]) . "</td>";
                                                                echo "<td>" . ($row["sex"] == 0 ? 'Male' : 'Female') . "</td>";
                                                                echo "<td>" . htmlspecialchars($row["age"]) . "</td>";
                                                                echo "<td>" . ($row["Neutering"] == 0 ? 'Not Neutered' : 'Spayed') . "</td>";
                                                                echo "<td>" . htmlspecialchars($row["color"]) . "</td>";
                                                                echo "<td>" . ($row["statusVac"] == 0 ? 'Susceptible' : 'Vaccinated') . "</td>";
                                                                $input_date = $row['currentVac'];
                                                                $formatted_date = date("F j, Y", strtotime($input_date));
                                                                echo "<td>" . $formatted_date . "</td>";
                                                                echo "<td>" . htmlspecialchars($row["barangay"]) . "</td>";
                                                                echo "</tr>";
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                            <?php
                                                } else {
                                                    echo "<p>No registries found in selected barangay</p>";
                                                }
                                            } else {
                                                echo "<p>No barangay selected</p>";
                                            }
                                            ?>
                                        </div>
                                        <!-- </div> -->
                                    </div>

                                    <!-- Bites Tab -->
                                    <div class="tab-pane fade" id="bites" role="tabpanel" aria-labelledby="bites-tab">
                                        <form action="" method="GET" id="bitesForm">
                                            <div class="form-group">
                                                <select class="form-control" id="barangaySelectBites" name="barangay_bites" onchange="submitForm('bitesForm')">
                                                    <option value="0">Select Barangay</option>
                                                    <?php

                                                    if ($conn->connect_error) {
                                                        die("Connection failed: " . $conn->connect_error);
                                                    }
                                                    $sql = "SELECT * FROM barangay";
                                                    $result = $conn->query($sql);
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<option value='" . $row["brgyID"] . "'>" . $row["barangay"] . "</option>";
                                                        }
                                                    } else {
                                                        echo "<option value>No barangays found</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <!-- </form> -->
                                            <div class="mb-3"></div>
                                        </form>
                                        <!-- Display bites data here -->
                                        <div>
                                            <?php
                                            // Check if the selected barangay is set
                                            if (isset($_GET['barangay_bites'])) {
                                                $selected_barangay = $_GET['barangay_bites'];

                                                // Check connection
                                                if ($conn->connect_error) {
                                                    die("Connection failed: " . $conn->connect_error);
                                                }

                                                $query = "SELECT COUNT(*) AS count  FROM `case` AS c
                                    INNER JOIN pet AS p ON c.petID = p.petID
                                    INNER JOIN resident AS r ON p.residentID = r.residentID
                                    INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                    WHERE c.caseStatus = 1 AND c.caseType = 0 AND c.brgyID = ?
                                    ORDER BY c.date DESC";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bind_param("i", $selected_barangay);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $bite = 0;
                                                if ($row = $result->fetch_assoc()) {
                                                    $bite = $row['count'];
                                                }

                                                $query = "SELECT COUNT(*) AS count  FROM `case` AS c
                                    INNER JOIN pet AS p ON c.petID = p.petID
                                    INNER JOIN resident AS r ON p.residentID = r.residentID
                                    INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                    WHERE c.caseStatus = 1 AND c.caseType = 0 AND p.sex = 0 AND c.brgyID = ?
                                    ORDER BY c.date DESC";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bind_param("i", $selected_barangay);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $maleb = 0;
                                                if ($row = $result->fetch_assoc()) {
                                                    $maleb = $row['count'];
                                                }


                                                $query = "SELECT COUNT(*) AS count  FROM `case` AS c
                                    INNER JOIN pet AS p ON c.petID = p.petID
                                    INNER JOIN resident AS r ON p.residentID = r.residentID
                                    INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                    WHERE c.caseStatus = 1 AND c.caseType = 0 AND p.sex = 1 AND c.brgyID = ?
                                    ORDER BY c.date DESC";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bind_param("i", $selected_barangay);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $femaleb = 0;
                                                if ($row = $result->fetch_assoc()) {
                                                    $femaleb = $row['count'];
                                                }


                                                $query = "SELECT COUNT(*) AS count  FROM `case` AS c
                                    INNER JOIN pet AS p ON c.petID = p.petID
                                    INNER JOIN resident AS r ON p.residentID = r.residentID
                                    INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                    WHERE c.caseStatus = 1 AND c.caseType = 0 AND p.petType = 0 AND c.brgyID = ?
                                    ORDER BY c.date DESC";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bind_param("i", $selected_barangay);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $dogb = 0;
                                                if ($row = $result->fetch_assoc()) {
                                                    $dogb = $row['count'];
                                                }


                                                $query = "SELECT COUNT(*) AS count  FROM `case` AS c
                                    INNER JOIN pet AS p ON c.petID = p.petID
                                    INNER JOIN resident AS r ON p.residentID = r.residentID
                                    INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                    WHERE c.caseStatus = 1 AND c.caseType = 0 AND p.petType = 0 AND c.brgyID = ?
                                    ORDER BY c.date DESC";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bind_param("i", $selected_barangay);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $catb = 0;
                                                if ($row = $result->fetch_assoc()) {
                                                    $catb = $row['count'];
                                                }

                                                // Query to fetch all bites data for the selected barangay
                                                $sql = "SELECT c.*, p.*, r.*, b.*
                                            FROM `case` AS c
                                            INNER JOIN pet AS p ON c.petID = p.petID
                                            INNER JOIN resident AS r ON p.residentID = r.residentID
                                            INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                            WHERE c.caseStatus = 1 AND c.caseType = 0 AND c.brgyID = ?
                                            ORDER BY c.date DESC";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->bind_param("i", $selected_barangay);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                // Check if there are bites found in the selected barangay
                                                if ($result->num_rows > 0) {
                                            ?>

                                                    <tr>
                                                        <!-- <div class="mb-3" id="bite"> -->
                                                        <td colspan="11"><Strong>Total Number of Bite Cases: </Strong> <?php echo $bite; ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <th colspan="11"><Strong>Total Number of Male:</Strong> <?php echo $maleb; ?><br>
                                                        <th colspan="11"><Strong>Total Number of Female:</Strong> <?php echo $femaleb; ?></th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <th colspan="11"><Strong>Total Number of Dog:</Strong> <?php echo $dogb; ?></th><br>
                                                        <th colspan="11"><Strong>Total Number of Cat:</Strong> <?php echo $catb; ?></th><br>
                                                        <!-- </div> -->
                                                    </tr>


                                            <?php
                                                    echo "<div class='table-responsive'>";
                                                    echo "<table id='bite' class='table text-center table-bordered table-hover'>";
                                                    echo "<thead>
                                                <tr>
                                                    <th>Victim's Name</th>
                                                    <th>Species</th>
                                                    <th>Pet's Name</th>
                                                    <th>Sex</th>
                                                    <th>Owner's Name</th>
                                                    <th>Date Occurred</th>
                                                    <th>Description</th>
                                                    <th>Rabies</th>
                                                    <th>Vaccination Status</th>
                                                    <th>Barangay</th>

                                                </tr>
                                            </thead>";
                                                    echo "<tbody>";
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row["victimsName"]) . "</td>";
                                                        echo "<td>" . ($row["petType"] == 0 ? 'Dog' : 'Cat') . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["pname"]) . "</td>";
                                                        echo "<td>" . ($row["sex"] == 0 ? 'Male' : 'Female') . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                                                        $input_date = $row['date'];
                                                        $date_obj = new DateTime($input_date);
                                                        $formatted_date = $date_obj->format("F j, Y");
                                                        echo "<td>" . $formatted_date . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                                                        echo "<td>" . ($row['confirmedRabies'] == 0 ? 'No' : 'Yes') . "</td>";
                                                        echo "<td>" . ($row["statusVac"] == 0 ? 'Susceptible' : 'Vaccinated') . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["barangay"]) . "</td>";

                                                        // echo '<td>
                                                        //         <form method="post" action="processBAO_viewBites.php">
                                                        //         <input type="hidden" name="caseID" value="' . $row['caseID'] . '">
                                                        //         <input type="hidden" name="brgyID" value="' . $selected_barangay . '">
                                                        //         <button type="submit" name="accept" class="btn btn-success">View Location</button>
                                                        //         </form>
                                                        //     </td>';
                                                        echo "</tr>";
                                                    }
                                                    echo "</tbody></table>";
                                                    echo "</div>"; // end of table-responsive
                                                } else {
                                                    echo "<p>No bites found in selected barangay</p>";
                                                }

                                                // Close statement and database connection
                                                $stmt->close();
                                            } else {
                                                echo "<p>No barangay selected</p>";
                                            }
                                            ?>
                                        </div>
                                    </div>



                                    <!-- <div class="tab-pane fade" id="death" role="tabpanel" aria-labelledby="death-tab">
                            <form action="" method="GET" id="deathForm">
                                <div class="form-group">
                                    <select class="form-control" id="barangaySelectDeath" name="barangay_death" onchange="submitForm('deathForm')">
                                        <option value="0">Select Barangay</option>
                                <?php

                                // if ($conn->connect_error) {
                                //     die("Connection failed: " . $conn->connect_error);
                                // }
                                // $sql = "SELECT * FROM barangay";
                                // $result = $conn->query($sql);
                                // if ($result->num_rows > 0) {
                                //     while($row = $result->fetch_assoc()) {
                                //     echo "<option value='" . $row["brgyID"] . "'>" . $row["barangay"] . "</option>";
                                //     }
                                // } else {
                                //     echo "<option value>No barangays found</option>";
                                // }
                                // 
                                ?>
                                </select>
                            </div>
                            <div class="mb-3"></div>
                            </form>
                            <div>
                        <?php
                        //    if(isset($_GET['barangay_death'])) {
                        //     $selected_barangay = $_GET['barangay_death'];


                        //     if ($conn->connect_error) {
                        //         die("Connection failed: " . $conn->connect_error);
                        //     }

                        //     $sql = "SELECT c.*, p.*, r.name, p.pname, b.barangay 
                        //             FROM `case` as c 
                        //             NATURAL JOIN pet as p 
                        //             NATURAL JOIN resident as r 
                        //             INNER JOIN barangay as b ON r.brgyID = b.brgyID 
                        //             WHERE c.caseStatus = 1 AND c.caseType = 1 AND c.brgyID = ?
                        //             ORDER BY c.date DESC";
                        //     $stmt = $conn->prepare($sql);
                        //     $stmt->bind_param("i", $selected_barangay);
                        //     $stmt->execute();
                        //     $result = $stmt->get_result();

                        //         if ($result->num_rows > 0) {
                        //             echo "<div class='table-responsive'>";
                        //             echo "<table id='deaths' class='table text-center table-bordered table-hover' style='width:100%'>";
                        //             echo "<thead>
                        //                     <tr>
                        //                         <th>Owner's Name</th>
                        //                         <th>Pet's Name</th>

                        //                         <th>Date Occurred</th>
                        //                         <th>Description</th>
                        //                         <th>Address</th>
                        //                         <th>Rabies</th>
                        //                     </tr>
                        //                 </thead>";
                        //             echo "<tbody>";
                        //             while ($row = $result->fetch_assoc()) {
                        //                 echo "<tr>";
                        //                 echo '<td>' . $row['name'] . '</td>';
                        //                 echo '<td>' . $row['pname'] . '</td>';



                        //                 $input_date = $row['date'];
                        //                 $date_obj = new DateTime($input_date);

                        //                 $formatted_date = $date_obj->format("F j, Y");

                        //                 echo '<td>' . $formatted_date . '</td>';
                        //                 echo '<td>' . $row['description'] . '</td>';
                        //                 echo '<td>' . $row['barangay'] . '</td>';
                        //                 echo '<td>' . ($row['confirmedRabies'] == 0 ? 'Natural Cause' : 'Rabies') . '</td>';                                //     <input type="hidden" name="brgyID" value="' . $selected_barangay . '">
                        //                 echo "</tr>";                                    
                        //             }
                        //             echo "</tbody></table>";
                        //             echo "</div>"; 

                        //         } else {
                        //             echo "<p>No death records found in selected barangay</p>";
                        //         }

                        //         $stmt->close();
                        //     } else {
                        //         echo "<p>No barangay selected</p>";
                        //     }
                        ?>
                    </div>
                    </div> -->


                                    <!-- Suspected Tab -->
                                    <div class="tab-pane fade" id="suspected" role="tabpanel" aria-labelledby="suspected-tab">
                                        <form action="" method="GET" id="suspectedForm">
                                            <div class="form-group">
                                                <select class="form-control" id="barangaySelectSuspected" name="barangay_suspected" onchange="submitForm('suspectedForm')">
                                                    <option value="0">Select Barangay</option>
                                                    <?php

                                                    if ($conn->connect_error) {
                                                        die("Connection failed: " . $conn->connect_error);
                                                    }
                                                    $sql = "SELECT * FROM barangay";
                                                    $result = $conn->query($sql);
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<option value='" . $row["brgyID"] . "'>" . $row["barangay"] . "</option>";
                                                        }
                                                    } else {
                                                        echo "<option value>No barangays found</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </form>
                                        <div>
                                            <div class="mb-3"></div>
                                            <?php
                                            if (isset($_GET['barangay_suspected'])) {
                                                $selected_barangay = $_GET['barangay_suspected'];



                                                // Check connection
                                                if ($conn->connect_error) {
                                                    die("Connection failed: " . $conn->connect_error);
                                                }

                                                $query = "SELECT COUNT(*) AS count FROM pet AS p
                                INNER JOIN resident AS r ON p.residentID = r.residentID
                                INNER JOIN `case` AS c ON c.petID = p.petID
                                INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                WHERE c.caseStatus = 1 AND c.caseType = 2 AND c.brgyID = ?
                                ORDER BY c.date DESC";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bind_param("i", $selected_barangay);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $sus = 0;
                                                if ($row = $result->fetch_assoc()) {
                                                    $sus = $row['count'];
                                                }


                                                $query = "SELECT COUNT(*) AS count FROM pet AS p
                                INNER JOIN resident AS r ON p.residentID = r.residentID
                                INNER JOIN `case` AS c ON c.petID = p.petID
                                INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                WHERE c.caseStatus = 1 AND c.caseType = 2 AND p.sex = 0 AND c.brgyID = ?
                                ORDER BY c.date DESC";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bind_param("i", $selected_barangay);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $males = 0;
                                                if ($row = $result->fetch_assoc()) {
                                                    $males = $row['count'];
                                                }

                                                $query = "SELECT COUNT(*) AS count FROM pet AS p
                                INNER JOIN resident AS r ON p.residentID = r.residentID
                                INNER JOIN `case` AS c ON c.petID = p.petID
                                INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                WHERE c.caseStatus = 1 AND c.caseType = 2 AND p.sex = 1 AND c.brgyID = ?
                                ORDER BY c.date DESC";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bind_param("i", $selected_barangay);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $females = 0;
                                                if ($row = $result->fetch_assoc()) {
                                                    $females = $row['count'];
                                                }

                                                $query = "SELECT COUNT(*) AS count FROM pet AS p
                                INNER JOIN resident AS r ON p.residentID = r.residentID
                                INNER JOIN `case` AS c ON c.petID = p.petID
                                INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                WHERE c.caseStatus = 1 AND c.caseType = 2 AND p.petType = 0 AND c.brgyID = ?
                                ORDER BY c.date DESC";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bind_param("i", $selected_barangay);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $dogs = 0;
                                                if ($row = $result->fetch_assoc()) {
                                                    $dogs = $row['count'];
                                                }

                                                $query = "SELECT COUNT(*) AS count FROM pet AS p
                                INNER JOIN resident AS r ON p.residentID = r.residentID
                                INNER JOIN `case` AS c ON c.petID = p.petID
                                INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                WHERE c.caseStatus = 1 AND c.caseType = 2 AND p.petType = 1 AND c.brgyID = ?
                                ORDER BY c.date DESC";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bind_param("i", $selected_barangay);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $cats = 0;
                                                if ($row = $result->fetch_assoc()) {
                                                    $cats = $row['count'];
                                                }



                                                // Query to fetch suspected records for the selected barangay
                                                $sql = "SELECT c.*, p.*, r.*, b.* 
                                        FROM pet AS p
                                        INNER JOIN resident AS r ON p.residentID = r.residentID
                                        INNER JOIN `case` AS c ON c.petID = p.petID
                                        INNER JOIN barangay AS b ON c.brgyID = b.brgyID
                                        WHERE c.caseStatus = 1 AND c.caseType = 2 AND c.brgyID = ?
                                        ORDER BY c.date DESC";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->bind_param("i", $selected_barangay);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if ($result->num_rows > 0) {
                                            ?>
                                                    <tr>
                                                        <!-- <div class="mb-3" id="sus"> -->
                                                        <td colspan="11"><Strong>Total Number of Suspected Case: </Strong> <?php echo $sus; ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <th colspan="11"><Strong>Total Number of Male:</Strong> <?php echo $males; ?><br>
                                                        <th colspan="11"><Strong>Total Number of Female:</Strong> <?php echo $females; ?></th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <th colspan="11"><Strong>Total Number of Dog:</Strong> <?php echo $dogs; ?></th><br>
                                                        <th colspan="11"><Strong>Total Number of Cat:</Strong> <?php echo $cats; ?></th><br>
                                                        <!-- </div> -->
                                                    </tr>


                                            <?php
                                                    echo "<div class='table-responsive'>";
                                                    echo "<table id='sus' class='table text-center table-bordered table-hover' style='width:100%'>";
                                                    echo "<thead>
                                            <tr>
                                                <th>Owner's Name</th>
                                                <th>Pet's Name</th>
                                                <th>Species</th>
                                                <th>Sex</th>
                                                <th>Date Discovered</th>
                                                <th>Description</th>
                                                <th>Rabies</th>
                                                <th>Address</th>

                                            </tr>
                                        </thead>";
                                                    echo "<tbody>";
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo '<tr>';
                                                        echo '<td>' . $row['name'] . '</td>';
                                                        echo '<td>' . $row['pname'] . '</td>';
                                                        echo "<td>" . ($row["petType"] == 0 ? 'Dog' : 'Cat') . "</td>";
                                                        echo "<td>" . ($row["sex"] == 0 ? 'Male' : 'Female') . "</td>";
                                                        $input_date = $row['date'];

                                                        // Convert the input date to a DateTime object
                                                        $date_obj = new DateTime($input_date);

                                                        // Format the date as "Month Day, Year"
                                                        $formatted_date = $date_obj->format("F j, Y");

                                                        // Print the formatted date
                                                        echo '<td>' . $formatted_date . '</td>';
                                                        echo '<td>' . $row['description'] . '</td>';
                                                        echo '<td>' . ($row['confirmedRabies'] == 0 ? 'No' : 'Yes') . '</td>';
                                                        echo '<td>' . $row['barangay'] . '</td>';

                                                        // echo '<td>
                                                        // <form method="post" action="processBAO_viewSus.php">
                                                        //     <input type="hidden" name="caseID" value="' . $row['caseID'] . '">
                                                        //     <input type="hidden" name="brgyID" value="' . $selected_barangay . '">
                                                        //     <button type="submit" name="accept" class="btn btn-success">View Location</button>
                                                        // </form>
                                                        // </td>';
                                                        echo '</tr>';
                                                    }
                                                    echo "</tbody></table>";
                                                    echo "</div>";
                                                } else {
                                                    echo "<p>No suspected records found in selected barangay</p>";
                                                }

                                                // Close statement and database connection
                                                $stmt->close();
                                            } else {
                                                echo "<p>No barangay selected</p>";
                                            }
                                            ?>


                                        </div>

                                    </div>
                                </div>


                                <script>
                                    jQuery(document).ready(function($) {
                                        // Initialize DataTable
                                        $('#registered').DataTable({
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


                                                    }
                                                },
                                                'print'
                                            ]
                                        });

                                        $('#bite').DataTable({
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
                                                        var tableColumnWidths = ['12%', '4%', '7%', '7%', '13%', '8%', '15%', '5%', '13%', '13%'];
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
                                                    }
                                                },
                                                'print'

                                            ]
                                        });

                                        // $('#deaths').DataTable({
                                        //     // Apply default sorting on the 4th column (index 3) in descending order
                                        //     "order": [[3, 'desc']],
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
                                        //                 doc.pageSize = { width: 330.2, height: 215.9 }; // 13 * 25.4, 8.5 * 25.4
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
                                        //             }
                                        //         },
                                        //         'print'
                                        //     ]
                                        // });


                                        $('#sus').DataTable({
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
                                                        var tableColumnWidths = ['13%', '13%', '7%', '7%', '18%', '18%', '13%', '13%'];
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
                                                    }
                                                },
                                                'print'
                                            ]
                                        });
                                    });
                                </script>

                                <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
                                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
                                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
                                <script>
                                    function submitForm(formId) {
                                        document.getElementById(formId).submit();
                                    }

                                    // JavaScript to redirect to the appropriate tab and selected barangay after form submission
                                    window.onload = function() {
                                        // Check if there is a selected barangay for each tab and redirect accordingly
                                        <?php if (isset($_GET['barangay_registries'])) { ?>
                                            document.getElementById('registries-tab').click();
                                        <?php } elseif (isset($_GET['barangay_bites'])) { ?>
                                            document.getElementById('bites-tab').click();
                                        <?php } elseif (isset($_GET['barangay_death'])) { ?>
                                            document.getElementById('death-tab').click();
                                        <?php } elseif (isset($_GET['barangay_suspected'])) { ?>
                                            document.getElementById('suspected-tab').click();
                                        <?php } ?>
                                    }
                                </script>

                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>

    </body>

    <?php
    // session_start();
    require_once("class/db_connect.php");
    require_once("class/barangay.php");
    require_once("class/notification.php");
    require_once("class/cases.php");
    require_once("class/resident.php");

    // Check if the user is logged in
    // if (!isset($_SESSION['user'])) {
    //     header("Location: login.php");
    //     exit();
    // }

    // Replace these with your actual database credentials
    global $conn;

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }


    // Prepare the SQL query
    $sql = "SELECT n.notifID, n.adminRead, n.notifMessage, n.notifDate, n.notifType, r.name, b.barangay FROM notification AS n INNER JOIN resident as r ON r.residentID = n.residentID JOIN barangay as b ON b.brgyID = r.brgyID
        WHERE n.notifType IN (2, 3, 6, 8) 
        ORDER BY n.notifDate DESC";

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error in preparing statement: " . $mysqli->error);
    }

    // Execute the query
    if (!$stmt->execute()) {
        die("Error in executing statement: " . $stmt->error);
    }

    // Get the result
    $result = $stmt->get_result();

    // Initialize an empty array to store notifications
    $allNotifs = [];

    // Fetch notifications as an associative array
    if ($result->num_rows > 0) {
        $allNotifs = $result->fetch_all(MYSQLI_ASSOC);
    }

    // Close the statement
    $stmt->close();

    // Close the database connection
    $conn->close();
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
                                    <th>Barangay</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allNotifs as $notif) { ?>
                                    <tr>
                                        <?php
                                        $redirectUrl = '';

                                        switch ($notif['notifType']) {
                                            case 2:
                                                $redirectUrl = './pin_location.php?barangay_bites=1';
                                                break;
                                            case 4:
                                                $redirectUrl = './pin_location.php?barangay_suspected=1';
                                                break;
                                            default:
                                                // Default case if notifType doesn't match any of the above
                                                $redirectUrl = '#';
                                        }

                                        ?>

                                        <td style="background-color: <?php echo ($notif['adminRead'] == 0) ? '#d3d3d3' : ''; ?>">
                                            <form method="get" action="mark_all_read.php" style="margin: 0;">
                                                <input type="hidden" name="MAOread" value="1">
                                                <input type="hidden" name="notifID" value="<?php echo $notif['notifID'] ?>">
                                                <input type="hidden" name="notifType" value="<?php echo $notif['notifType'] ?>">
                                                <button type="submit" name="MAO_read" class="btn btn-link" style="border: none; text-decoration: none; text-align: justify; color: black; padding: 0;">
                                                    <?php echo $notif['notifMessage'] . ' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ' ?>
                                                </button>
                                            </form>
                                        </td>
                                        <td style="background-color: <?php echo ($notif['adminRead'] == 0) ? '#d3d3d3' : ''; ?>">
                                            <form method="get" action="mark_all_read.php" style="margin: 0;">
                                                <input type="hidden" name="MAOread" value="1">
                                                <input type="hidden" name="notifID" value="<?php echo $notif['notifID'] ?>">
                                                <input type="hidden" name="notifType" value="<?php echo $notif['notifType'] ?>">
                                                <button type="submit" name="MAO_read" class="btn btn-link" style="border: none; text-decoration: none; text-align: justify; color: black; padding: 0;">
                                                    <?php echo date('F j, Y, g:i A', strtotime($notif['notifDate'])); ?>
                                                </button>
                                            </form>
                                        </td>

                                        <td style="background-color: <?php echo ($notif['adminRead'] == 0) ? '#d3d3d3' : ''; ?>">
                                            <form method="get" action="mark_all_read.php" style="margin: 0;">
                                                <input type="hidden" name="MAOread" value="1">
                                                <input type="hidden" name="notifID" value="<?php echo $notif['notifID'] ?>">
                                                <input type="hidden" name="notifType" value="<?php echo $notif['notifType'] ?>">
                                                <button type="submit" name="MAO_read" class="btn btn-link" style="border: none; text-decoration: none; text-align: justify; color: black; padding: 0;">
                                                    <?php echo $notif['barangay']; ?>
                                                </button>
                                            </form>
                                        </td>

                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <p>No notifications available.</p>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <form method="post" action="mark_all_read.php">
                        <button type="submit" name="mark_all_read_admin" class="btn btn-primary">Read All</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    </div>

    </html>