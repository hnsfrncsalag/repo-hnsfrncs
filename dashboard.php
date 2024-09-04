<?php
session_start();

$active_tab = $_GET['active-tab'];

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get the user's information from the session
$user = $_SESSION['user'];

$brgyID = isset($_SESSION['user']['brgyID']) ? $_SESSION['user']['brgyID'] : '';
$residentID = isset($_SESSION['user']['residentID']) ? $_SESSION['user']['residentID'] : '';
$userType = isset($_SESSION['user']['userType']) ? $_SESSION['user']['userType'] : '';
$name = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : '';
$userStatus = isset($_SESSION['user']['userStatus']) ? $_SESSION['user']['userStatus'] : '';
// Include the Pet class
require_once("class/pet.php");
require_once("class/cases.php");
$pet = new Pet();
$cases = new Cases();

// Get all pets belonging to the user
$pets = $pet->getPetsByResidentID($user['residentID']);
$bites = $cases->getBitesByResidentID($user['residentID']);
$death = $cases->getDeathByResidentID($user['residentID']);
$suspected = $cases->getSuspectedCase($user['residentID']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Add jQuery library -->

    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .navbar-nav .nav-link.notification-button {
            cursor: pointer;
        }

        ::placeholder {
            font-style: italic;
        }

        #petType {
            font-style: italic;
        }

        #sex {
            font-style: italic;
        }

        #neutering {
            font-style: italic;
        }

        #statusVac {
            font-style: italic;
        }

        #currentVac {
            font-style: italic;
        }

        #age {
            font-style: italic;
        }

        #pdescription {
            font-style: italic;
        }

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
            color: black;
            font-weight: bold;
        }

        .left-sidebar--sticky-container {
            width: 100%;
        }

        .nav-tabs {
            margin-bottom: 1rem;
        }

        @media (min-width: 576px) {
            .h-sm-100 {
                height: 100%;
            }
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            background-color: #ff6666;
            /* Red background color */
            color: white;
            /* White text color */
            border-radius: 20px;
            /* Round corners */
            font-size: 14px;
        }

        .status-icon {
            display: inline-block;
            padding: 5px 10px;
            background-color: lightblue;
            /* Red background color */
            color: white;
            /* White text color */
            border-radius: 20px;
            /* Round corners */
            font-size: 14px;
        }

        .button-container {
            display: flex;
            align-items: end;
        }

        .button-container form {
            margin-left: 5px;
            align-items: center;
        }

        .pet-info {
            margin-bottom: 20px;
        }

        .pet-section {
            border: 2px solid #ccc;
            /* Add border around each pet section */
            padding: 10px;
            /* Add padding inside each section */
            margin-bottom: 10px;
            /* Add margin between sections */
        }

        .pet-section h6 {
            /* font-weight: bold; */
            margin-bottom: 5px;
        }

        .pet-section h4 {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .pet-section ul {
            list-style-type: none;
            padding-left: 0;
        }

        .pet-section li {
            margin-bottom: 5px;
        }

        .no-history {
            font-style: italic;
            color: #888;
        }

        .mb-6 {
            margin-bottom: 24px;
            /* Adjust the value as needed */
        }

        .col-12.col-sm-3 {
            position: sticky;
            top: 0;
        }

        .wider-column {
            width: 120px;
            /* Set the desired width */
        }
    </style>
</head>
<?php
// Count the number of new notifications
$sql_count = "SELECT COUNT(*) AS new_notif_count FROM notification WHERE notifType IN (9, 10, 11, 12, 13) AND (residentID = ?) AND isRead = 0";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("i", $residentID);
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
                            <a href="#" class="flex-sm-fill text-sm-center nav-link text-dark active" aria-current="page">
                                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                    <path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z" />
                                </svg><span class="ms-2 d-none d-sm-inline">Home</span>
                            </a>
                        </li>
                        <li class="nav-item" style="display: none;">
                            <form method="post" action="#">
                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                                <input type="hidden" name="active-tab" value="1">
                                <button type="submit" data-bs-toggle="collapse" class="nav-link active text-dark px-sm-0 px-2"> <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
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
                                <li><a class="dropdown-item" href="./addBiteCaseIndiv.php?active-tab=1">Report Bite Case</a></li>
                                <!-- <li><a class="dropdown-item" href="addDeathCaseIndiv.php">Report Death Case</a></li> -->
                                <li><a class="dropdown-item" href="reportRabidResident.php">Report Rabid Case</a></li>
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
                        <!-- <div class="container mt-2 d-flex justify-content-between align-items-center"> -->
                        <div class="container mt-2 p-1    d-flex flex-column align-items-start">
                            <h1 class="p-1 m-1">Welcome, <?php echo isset($user['name']) ? $user['name'] : ''; ?>!
                                <?php if ($userStatus == 0) {
                                    echo '<span class="status">Not Verified</span>'; // Red X sign
                                } else {
                                    echo '<span class="status-icon dark green">&#10004;</span>'; // Green check
                                } ?>
                            </h1>
                            <p class="p-1 m-1">Email: <?php echo isset($user['email']) ? $user['email'] : ''; ?></p>
                        </div>
                        <!-- <div>
                            <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#notificationModal">
                                <img src="bell-icon.jpg" alt="Bell Icon" style="width: 40px; height: 40px;">
                            </button>
                        </div> -->


                        <div class="container mt-4">
                            <form method="POST" action="" id="petform" name="petform">
                                <ul class="nav nav-tabs">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link<?= ($active_tab == 1) ? ' active' : '' ?>" id="resident-tab" data-toggle="tab" href="./dashboard.php?active-tab=1&page_add=1#addPets" role="tab" aria-controls="resident" aria-selected="true">My Pets</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link<?= ($active_tab == 2) ? ' active' : '' ?>" id="bites-tab" data-toggle="tab" href="./dashboard.php?active-tab=2&page_bite=1#bitePets" role="tab" aria-controls="bites" aria-selected="false">Bite Cases</a>
                                        </li>
                                        <!-- <li class="nav-item">
                                            <a class="nav-link<?= ($active_tab == 3) ? ' active' : '' ?>" id="death-tab" data-toggle="tab" href="./dashboard.php?active-tab=3&page_death=1#deathPets" role="tab" aria-controls="death" aria-selected="false">Death Cases</a>
                                            </li> -->
                                        <li class="nav-item">
                                            <a class="nav-link<?= ($active_tab == 4) ? ' active' : '' ?>" id="suspected-tab" data-toggle="tab" href="./dashboard.php?active-tab=4&page_sus=1#suspectedPets" role="tab" aria-controls="suspected" aria-selected="false">Suspected Cases</a>
                                        </li>
                                    </ul>
                                </ul>
                            </form>

                            <div class="tab-content">

                                <!-- Add Pet Section -->
                                <?php
                                // Assuming $bites is your data array, $active_tab is set elsewhere
                                // $items_per_page_add = 5;
                                // $total_items_add = count($pets);
                                // $total_pages_add = ceil($total_items_add / $items_per_page_add);

                                // // Initialize $page and set it based on your requirements
                                // $page_add = isset($_GET['page_add']) ? $_GET['page_add'] : 1;

                                // // Calculate the starting index for the current page
                                // $start_index_add = ($page_add - 1) * $items_per_page_add;

                                // // Get the data for the current page
                                // $current_page_data_add = array_slice($pets, $start_index_add, $items_per_page_add);
                                ?>

                                <!-- Add Pet Section -->
                                <div class="tab-pane <?= ($active_tab == 1) ? ' active show' : '' ?>" id="addPets">
                                    <form method="POST" action="addPetRes.php">
                                        <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                        <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                        <input type="hidden" name="userType" id="userType" value="<?php echo $userType; ?>">
                                        <button type="submit" class="btn btn-primary">Add Pet</button>
                                    </form>

                                    <div class="table-responsive">
                                        <table id="pets" class="table text-center table-bordered table-hover" style="width:100%">
                                            <h4><strong> My Pets: </strong></h4>
                                            <thead>
                                                <tr>
                                                    <!-- <tr> -->
                                                    <th>Pet's Name</th>
                                                    <!-- <th>Date of Registry</th> -->
                                                    <th>Pet Type</th>
                                                    <th>Sex</th>
                                                    <!-- <th>Color</th> -->
                                                    <!-- <th>Description</th> -->
                                                    <th>Vaccination Status</th>
                                                    <th>Neutering</th>
                                                    <th>Current Vaccination</th>
                                                    <th>Health Status</th>
                                                    <!-- <th>Age</th> -->
                                                    <th>Status</th>
                                                    <th style="width: 100;">Action</th> <!-- Set colspan="2" to make the "Action" column wider -->
                                                </tr>
                                            </thead>
                                            <tbody id="valid-a">
                                                <?php
                                                if (empty($pets)) {
                                                    echo '<tr><td colspan="12">No data found.</td></tr>';
                                                } else {
                                                    foreach ($pets as $pet) {
                                                ?>
                                                        <tr>
                                                            <td>
                                                                <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#petModal_<?php echo $pet['petID']; ?>">
                                                                    <?php echo $pet['pname']; ?>
                                                                </button>
                                                            </td>
                                                            </td>
                                                            <td><?php echo ($pet['petType'] == 0) ? 'Dog' : 'Cat'; ?></td>
                                                            <td><?php echo ($pet['sex'] == 0) ? 'Male' : 'Female'; ?></td>
                                                            <!-- <td><?php echo $pet['color']; ?></td> -->
                                                            <!-- <td><?php echo $pet['pdescription']; ?></td> -->
                                                            <td><?php echo ($pet['statusVac'] == 0) ? 'Unvaccinated' : 'Vaccinated'; ?></td>
                                                            <td class="wider-column"><?php echo ($pet['Neutering'] == 0) ? 'Not Neutered' : 'Neutered'; ?></td>
                                                            <td class="d-flex justify-content-center">
                                                                <div>
                                                                    <?php
                                                                    $input_date = new DateTime($pet['currentVac']);
                                                                    echo $input_date->format("F j, Y");
                                                                    ?>
                                                                </div>
                                                            </td>
                                                            <td><?php echo ($pet['Health'] == 0) ? 'Alive' : 'Deceased'; ?></td>
                                                            <!-- <td><?php echo $pet['age']; ?></td> -->
                                                            <td>
                                                                <?php if ($pet['status'] == 1) { ?>
                                                                    <i class="bi bi-check-circle text-success"></i> Verified
                                                                <?php } else if ($pet['status'] == 2) { ?>
                                                                    <i class="bi bi-x-circle text-danger"></i> Rejected
                                                                <?php } else { ?>
                                                                    <i class="bi bi-question-circle text-warning"></i> Not Verified
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($pet['status'] == 0) { ?>
                                                                    <div class="button-container col-12 d-flex justify-content-center align-items-center">

                                                                        <form method="post" action="process_cancel.php" style="margin-left: 5px;">
                                                                            <input type="hidden" name="petID" value="<?php echo $pet['petID']; ?>">
                                                                            <button type="submit" name="cancel_reg" class="btn btn-danger btn-sm">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" height="14" width="10.5" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                                                    <path fill="#ffffff" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
                                                                                </svg>
                                                                            </button>
                                                                        </form>
                                                                        &nbsp;
                                                                        <a href="#edit_<?php echo $pet['petID'] ?>" data-bs-toggle="modal" class="editPet" data-petid="<?php echo $pet['petID'] ?>">
                                                                            <button type='button' class='btn btn-warning btn-sm'>
                                                                                <input type="hidden" name="petID" value="<?php echo $pet['petID'] ?>">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" height="12" width="12" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                                                    <path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z" />
                                                                                </svg>
                                                                            </button>
                                                                        </a>

                                                                    </div>

                                                                <?php } else if ($pet['status'] == 1) { ?>
                                                                    <div class="button-container col-12 d-flex justify-content-center align-items-center wider-column" style="margin-left: 50px;">
                                                                        <button class="btn btn-sm" disabled>Reviewed</button>
                                                                        <div style="margin: 0 5px;"> <!-- Adjust the margin value as needed -->
                                                                            <a href="#edit_<?php echo $pet['petID'] ?>" data-bs-toggle="modal" class="editPet" data-petid="<?php echo $pet['petID'] ?>">
                                                                                <button type='button' class='btn btn-warning btn-sm'>
                                                                                    <input type="hidden" name="petID" value="<?php echo $pet['petID'] ?>">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" height="12" width="12" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                                                        <path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z" />
                                                                                    </svg>
                                                                                </button>
                                                                            </a>
                                                                        </div>
                                                                    </div>

                                                                <?php } else { ?>
                                                                    <div class="col-12 d-flex justify-content-center align-items-center">
                                                                        <div class="button-container col-6 d-flex justify-content-center align-items-center">
                                                                            <button class="btn btn-sm wider-column" disabled>Reviewed</button>
                                                                            <form method="post" action="process_cancel.php" style="margin-left: 5px;">
                                                                                <input type="hidden" name="petID" value="<?php echo $pet['petID']; ?>">
                                                                                <button type="submit" name="cancel_reg" class="btn btn-danger btn-sm">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" height="14" width="10.5" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                                                        <path fill="#ffffff" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
                                                                                    </svg>
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>


                                                            </td>
                                                        </tr>
                                                        <script>
                                                            // Update modal input fields when the Report button is clicked
                                                            $('.editPet').on('click', function() {
                                                                var petID = $(this).data('petid');

                                                                // Update the hidden input field for petID in the modal
                                                                $('#petID').val(petID);

                                                                // Update other hidden input fields if needed
                                                                // $('#otherHiddenInput').val(someValue);

                                                                // You can include more hidden input fields if necessary

                                                                // Trigger the modal to open
                                                                $('#edit').modal('show');
                                                            });
                                                        </script>


                                                        <div id="edit_<?php echo $pet['petID'] ?>" class="modal fade" role="dialog">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Edit Pet Details</h4>
                                                                        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form method="post" action="process_editPetRes.php">
                                                                            <input type="hidden" name="petID" id="petID" value="<?php echo $pet['petID'] ?>">
                                                                            <div class="mb-2">
                                                                                <label for="petName" class="form-label"><strong>Pet's Name / Ngalan sang imo sapat:</strong></label>
                                                                                <input type="text" name="pname" value="<?php echo $pet['pname']; ?>" placeholder="Ngalan sang sapat" class="form-control">
                                                                            </div>

                                                                            <div class="mb-2">
                                                                                <label for="registryDate" class="form-label"><strong>Date of Registry / Adlaw sang pag rehistro:</strong></label>
                                                                                <input type="text" name="regDate" value="<?php $input_date = new DateTime($pet['regDate']);
                                                                                                                            echo $input_date->format("F j, Y"); ?>" placeholder="Date of Registry" class="form-control" disabled>
                                                                            </div>

                                                                            <div class="mb-2">
                                                                                <label for="petType" class="form-label"><strong>Pet Type / Klase sang Sapat:</strong></label>
                                                                                <select class="form-select" name="petType" id="petType">
                                                                                    <option value=""><?php echo ($pet['petType'] == 0) ? 'Dog' : 'Cat'; ?></option>
                                                                                    <option value="0">Dog / Ido</option>
                                                                                    <option value="1">Cat / Kuring</option>
                                                                                </select>
                                                                            </div>

                                                                            <div class="mb-2">
                                                                                <label for="petSex" class="form-label"><strong>Sex / Kasarian sang imo sapat:</strong></label>
                                                                                <select class="form-select" name="sex" id="sex">
                                                                                    <option value=""><?php echo ($pet['sex'] == 0) ? 'Male' : 'Female'; ?></option>
                                                                                    <option value="0">Male / Lalaki</option>
                                                                                    <option value="1">Female / Babayi</option>
                                                                                </select>
                                                                            </div>

                                                                            <div class="mb-2">
                                                                                <label for="color" class="form-label"><strong>Color / Kolor sang Imo sapat:</strong></label>
                                                                                <input type="text" name="color" value="<?php echo $pet['color']; ?>" placeholder="Kolor" class="form-control">
                                                                            </div>

                                                                            <div class="mb-2">
                                                                                <label for="vaccinationStatus" class="form-label"><strong>Vaccination Status / Napabukanahan o Wala:</strong></label>
                                                                                <input type="text" name="statusVac" value="<?php echo $pet['statusVac'] == 0 ? 'Vaccinated' : 'Unvaccinated'; ?>" placeholder="Vaccination Status" class="form-control" disabled>
                                                                            </div>

                                                                            <div class="mb-2">
                                                                                <label for="neuteringStatus" class="form-label"><strong>Neutering Status / Nakapon O Wala:</strong></label>
                                                                                <select class="form-select" name="neutering" id="neutering">
                                                                                    <option value=""><?php echo ($pet['Neutering'] == 0) ? 'Neutered' : 'Not Neutered'; ?></option>
                                                                                    <option value="0">Neutered / Nakapon</option>
                                                                                    <option value="1">Not Neutered / Wala nakapon</option>
                                                                                </select>
                                                                            </div>

                                                                            <div class="mb-2">
                                                                                <label for="currentVaccination" class="form-label"><strong>Current Vaccination / Adlaw sang bakuna:</strong></label>
                                                                                <input type="text" name="currentVac" value="<?php $input_date = new DateTime($pet['currentVac']);
                                                                                                                            echo $input_date->format("F j, Y"); ?>" placeholder="Current Vaccination" class="form-control" disabled>
                                                                            </div>

                                                                            <div class="mb-2">
                                                                                <label for="petAge" class="form-label"><strong>Age / Edad sang imo sapat:</strong></label>
                                                                                <input type="text" name="age" value="<?php echo $pet['age']; ?>" placeholder="Age" class="form-control">
                                                                            </div>

                                                                            <div class="mb-2">
                                                                                <label for="pdescription" class="form-label"><strong>Pet Features:</strong></label>
                                                                                <textarea name="pdescription" placeholder="Deskription o Pinasahi nga bahin sang imo sapat?" class="form-control"><?php echo $pet['pdescription']; ?></textarea>
                                                                            </div>

                                                                            <div class="mb-2">
                                                                                <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                                                                <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                                                                <input type="submit" name="edit" value="Submit" class="btn btn-primary">
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                <?php
                                                        // End of foreach loop 
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php foreach ($pets as $pet) { ?>
                                    <div class="modal fade petModal" id="petModal_<?php echo $pet['petID']; ?>" tabindex="-1" aria-labelledby="petModalLabel_<?php echo $pet['petID']; ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title" id="petModalLabel_<?php echo $pet['petID']; ?>">Pet Name: <?php echo $pet['pname']; ?></h3>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Display pet vaccination history and update form -->
                                                    <?php
                                                    echo '<div class="pet-info">';
                                                    echo '<div class="pet-section">';
                                                    echo "<h6><strong>Age:</strong> " . $pet['age'] . "</h6>";
                                                    echo '</div>'; // End of pet-section

                                                    echo '<div class="pet-section">';
                                                    $input_date = new DateTime($pet['regDate']);
                                                    echo "<h6><strong>Date of Registry:</strong> " . $input_date->format("F j, Y") . "</h6>";
                                                    echo '</div>'; // End of pet-section

                                                    echo '<div class="pet-section">';
                                                    echo "<h6><strong>Color:</strong> " . $pet['color'] . "</h6>";
                                                    echo '</div>'; // End of pet-section

                                                    echo '<div class="pet-section">';
                                                    echo "<h6><strong>Description:</strong> " . $pet['pdescription'] . "</h6>";
                                                    echo '</div>'; // End of pet-section

                                                    $petID = $pet['petID'];
                                                    $allVaccinationsQuery = "SELECT lastVaccination FROM vaccination WHERE petID = ? ORDER BY lastVaccination DESC";
                                                    $stmt = mysqli_prepare($conn, $allVaccinationsQuery);
                                                    mysqli_stmt_bind_param($stmt, "i", $petID);
                                                    mysqli_stmt_execute($stmt);
                                                    $allVaccinationsResult = mysqli_stmt_get_result($stmt);

                                                    echo '<div class="pet-section">';
                                                    if ($allVaccinationsResult && mysqli_num_rows($allVaccinationsResult) > 0) {
                                                        echo '<h4>Recent Vaccinations:</h4>';
                                                        echo '<ul>';
                                                        while ($vaccinationRow = mysqli_fetch_assoc($allVaccinationsResult)) {
                                                            $formattedDate = ($vaccinationRow['lastVaccination'] ? date('F j, Y', strtotime($vaccinationRow['lastVaccination'])) :  'Not Available');
                                                            echo '<li>' . $formattedDate . '</li>';
                                                        }
                                                        echo '</ul>';
                                                    } else {
                                                        echo '<h4>Last Vaccination Date:</h4>';
                                                        echo '<h6 class="no-history">No vaccination history available.</h6>';
                                                    }
                                                    echo '    <div class="mb-6"></div>';
                                                    echo '</div>'; // End of pet-section

                                                    echo '</div>'; // End of pet-info

                                                    // $sql = "SELECT c.caseStatus 
                                                    // FROM `case` AS c 
                                                    // JOIN pet AS p ON c.petID = p.petID 
                                                    // WHERE c.caseType = 1 AND c.caseStatus = 1 and p.petID = ?";                                

                                                    echo '<form method="post" action="process_updateVacRes.php">';
                                                    echo '    <input type="hidden" name="petID" value="' . $pet['petID'] . '">';
                                                    echo '    <input type="hidden" name="statusVac" id="statusVac" value="1">';
                                                    echo '    <label for="updateDate">Add New Vaccination Date:</label>';
                                                    echo '    <input type="date" name="currentVac" id="currentVac" required ' . (($pet['vetVac'] == 1 && $pet['Health'] == 1) || ($pet['vetVac'] == 0 && $pet['Health'] == 1) || ($pet['vetVac'] == 0 && $pet['Health'] == 0) ? 'disabled' : '') . '>';
                                                    echo '    <div class="mb-6"></div>'; // Add space here
                                                    echo '    <input type="hidden" name="petID" value="' . $pet['petID'] . '">';
                                                    echo '    <div class="mb-6"></div>'; // Add space here
                                                    // Button to trigger the modal
                                                    echo '  <button type="submit" name="update" class="btn btn-success btn-sm" ' . (($pet['vetVac'] == 1 && $pet['Health'] == 1) || ($pet['vetVac'] == 0 && $pet['Health'] == 1) || ($pet['vetVac'] == 0 && $pet['Health'] == 0) ? 'disabled' : '') . '>Update</button>';

                                                    if ($pet['Health'] == 0 && $pet['status'] == 1) {
                                                        echo '  <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deathReportModal_' . $pet['petID'] . '">Report Death</button>';
                                                    }

                                                    echo '</form>';

                                                    ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    echo '<div class="modal fade" id="deathReportModal_' . $pet['petID'] . '" tabindex="-1" aria-labelledby="deathReportModalLabel" aria-hidden="true">';
                                    echo '<div class="modal-dialog">';
                                    echo '<div class="modal-content">';
                                    echo '<div class="modal-header">';
                                    echo '<h5 class="modal-title" id="deathReportModalLabel">Report Pet Death for ' . $pet['pname'] . '</h5>';
                                    echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                                    echo '</div>';
                                    echo '<div class="modal-body lg">';
                                    echo '<form method="post" action="process_addDeathCaseIndiv.php">';
                                    echo '<input type="hidden" name="petID" value="' . $pet['petID'] . '">';
                                    echo '<div class="mb-3">';
                                    echo '<label for="petName" class="form-label">Pet Name / Ngalan sang Sapat:<span class="text-danger">*</span></label>';
                                    echo '<input type="text" class="form-control" id="pname" name="pname" value="' . $pet['pname'] . '" readonly>';
                                    echo '</div>';
                                    echo '<div class="mb-3">';
                                    echo '<label for="date" class="form-label">Date of Death / Adlaw sang Pagkamatay: <span class="text-danger">*</span></label>';
                                    echo '<input type="date" class="form-control" id="date" name="date" required>';
                                    echo '</div>';
                                    echo '<div class="mb-3">';
                                    echo '<label for="causeOfDeath" class="form-label">Description of Death/ Deskripsiyon sang pagkamatay: <span class="text-danger">*</span></label>';
                                    echo '<textarea class="form-control" id="description" name="description" rows = "3" required placeholder = "Deskripsiyon sang pagkamatay"></textarea>';
                                    echo '</div>';
                                    echo '<input type="hidden" name="confirmedRabies" id="confirmedRabies" value="0">';
                                    echo '<input type="hidden" name="residentID" id="residentID" value="' . $user['residentID'] . '">';
                                    echo '<input type="hidden" name="petID" id="petID" value="' . $pet['petID'] . '">';
                                    echo '<input type="hidden" name="HStatus" id="HStatus" value="1">';
                                    echo '<input type="hidden" name="brgyID" id="brgyID" value="' . $user['brgyID'] . '">';
                                    echo '<input type="hidden" name="userType" id="userType" value="' . $userType . '">';
                                    echo '<input type="hidden" name="caseType" id="caseType" value="1">';
                                    echo '<input type="hidden" name="latitude" id="latitude">';
                                    echo '<input type="hidden" name="notifType" id="notifType" value="4">';
                                    echo '<input type="hidden" name="notifDate" id="notifDate" value="' . date('Y-m-d') . '">';
                                    echo '<input type="hidden" name="notifMessage" id="notifMessage" value="A death case has been recorded.">';
                                    echo '<input type="hidden" name="longitude" id="longitude">';
                                    echo '<button type="submit" name="reportDeath" class="btn btn-danger btn-sm" onclick="getLocation()">Submit</button>';
                                    echo '</form>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                    ?>
                                <?php } ?>

                                <script>
                                    function getLocation() {
                                        if (navigator.geolocation) {
                                            navigator.geolocation.getCurrentPosition(showPosition, showError);
                                        } else {
                                            // Geolocation is not supported by the browser
                                            // Handle the lack of support accordingly
                                        }
                                    }

                                    function showPosition(position) {
                                        var latitude = position.coords.latitude;
                                        var longitude = position.coords.longitude;

                                        document.getElementById("latitude").value = latitude;
                                        document.getElementById("longitude").value = longitude;

                                        document.getElementById("reportCaseForm").submit();
                                    }

                                    function showError(error) {
                                        switch (error.code) {
                                            case error.PERMISSION_DENIED:
                                                // User denied permission
                                                break;
                                            case error.POSITION_UNAVAILABLE:
                                                // Location information is unavailable
                                                break;
                                            case error.TIMEOUT:
                                                // The request to get user location timed out
                                                break;
                                            case error.UNKNOWN_ERROR:
                                                // An unknown error occurred
                                                break;
                                        }
                                    }
                                </script>





                                <?php
                                // Assuming $bites is your data array, $active_tab is set elsewhere
                                // $items_per_page_bite = 5;
                                // $total_items_bite = count($bites);
                                // $total_pages_bite = ceil($total_items_bite / $items_per_page_bite);

                                // // Initialize $page_bite and set it based on your requirements
                                // $page_bite = isset($_GET['page_bite']) ? $_GET['page_bite'] : 1;

                                // // Calculate the starting index for the current page
                                // $start_index_bite = ($page_bite - 1) * $items_per_page_bite;

                                // // Get the data for the current page
                                // $current_page_data_bite = array_slice($bites, $start_index_bite, $items_per_page_bite);
                                ?>

                                <div class="tab-pane <?= ($active_tab == 2) ? ' active show' : '' ?>" id="bitePets">
                                    <div class="input-group mb-3">
                                        <form method="POST" action="addBiteCaseIndiv.php">
                                            <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                            <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                            <input type="hidden" name="userType" id="userType" value="<?php echo $userType; ?>">
                                            <button type="submit" class="btn btn-primary">Report Bite Case</button>
                                        </form>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="bites" class="table text-center table-bordered table-hover" style="width:100%">
                                            <h4>Bite Reports:</h4>
                                            <thead>
                                                <tr>
                                                    <th>Pet's Name</th>
                                                    <th>Victim</th>
                                                    <th>Description</th>
                                                    <!-- <th>Body Part Bitten</th> -->
                                                    <th>Date Occurred</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="valid-b">
                                                <?php if (empty($bites)) { ?>
                                                    <tr>
                                                        <td colspan="7">No data found.</td>
                                                    </tr>
                                                <?php } else { ?>
                                                    <?php foreach ($bites as $cases) { ?>
                                                        <tr>
                                                            <td><?php echo $cases['pname']; ?></td>
                                                            <td><?php echo $cases['victimsName']; ?></td>
                                                            <td><?php echo $cases['description']; ?></td>
                                                            <!-- <td>
                            <?php
                                                        // echo ($cases['bpartBitten'] == 0 ? 'Head and Neck Area' :
                                                        //     ($cases['bpartBitten'] == 1 ? 'Thorax Area' :
                                                        //     ($cases['bpartBitten'] == 2 ? 'Abdomen Area' :
                                                        //         ($cases['bpartBitten'] == 3 ? 'Upper Extremity Area' :
                                                        //         ($cases['bpartBitten'] == 4 ? 'Lower Extremity Area' : 'Unknown')))));
                            ?>
                        </td> -->
                                                            <td>
                                                                <?php
                                                                $input_date = new DateTime($cases['date']);
                                                                echo $input_date->format("F j, Y, H:i:s");
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($cases['caseStatus'] == 0) { ?>
                                                                    <i class="bi bi-question-circle text-warning"></i> Not Verified
                                                                <?php } else if ($cases['caseStatus'] == 1) { ?>
                                                                    <i class="bi bi-check-circle text-success"></i> Verified
                                                                <?php } else { ?>
                                                                    <i class="bi bi-x-circle text-danger"></i> Rejected
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($cases['caseStatus'] == 1) { ?>
                                                                    <button class="btn btn-sm" disabled>Reviewed</button>
                                                                <?php } else { ?>
                                                                    <form method="post" action="process_cancel.php" style="display: inline-block; margin-left: 5px;">
                                                                        <input type="hidden" name="caseID" value="<?php echo $cases['caseID']; ?>">
                                                                        <button type="submit" name="cancel_bite" class="btn btn-danger btn-sm">
                                                                            <i class="bi bi-x"></i> <!-- X icon for Delete -->
                                                                        </button>
                                                                    </form>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>

                                        <!-- <div class="d-flex justify-content-center mt-4">
        <ul class="pagination">
            <?php for ($b = 1; $b <= $total_pages_bite; $b++) { ?>
                <li class="page-item <?= ($b == $page_bite) ? 'active' : '' ?>">
                    <a class="page-link" href="./dashboard.php?active-tab=2&page_bite=<?= $b ?>"><?= $b ?></a>
                </li>
            <?php } ?>
        </ul>
    </div> -->
                                    </div>
                                </div>


                                <?php
                                // Assuming $death is your data array, $active_tab is set elsewhere
                                // $items_per_page_death = 5;
                                // $total_items_death = count($death);
                                // $total_pages_death = ceil($total_items_death / $items_per_page_death);

                                // // Initialize $page_death and set it based on your requirements
                                // $page_death = isset($_GET['page_death']) ? $_GET['page_death'] : 1;

                                // // Calculate the starting index for the current page
                                // $start_index_death = ($page_death - 1) * $items_per_page_death;

                                // // Get the data for the current page
                                // $death = array_slice($death, $start_index_death, $items_per_page_death);
                                ?>

                                <!-- <div class="tab-pane <?= ($active_tab == 3) ? ' active show' : '' ?>" id="deathPets">
    <form method="POST" action="addDeathCaseIndiv.php">
        <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
        <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
        <input type="hidden" name="userType" id="userType" value="<?php echo $userType; ?>">
        <button type="submit" class="btn btn-primary">Report Death Case</button>
    </form> -->

                                <!-- <label for="residentSearch" class="form-label"></label>
<div class="input-group mb-3">
    <input type="text" class="form-control" id="deathSrch" placeholder="Search...">
    <button class="btn btn-primary" id="deathBtn" type="button">Search</button>
</div> -->

                                <!-- <div class="table-responsive">
    <table id="death" class="table text-center table-bordered table-hover" style="width:100%">
        <h4>Death Reports:</h4>
        <thead>
            <tr>
                <th>Pet's Name</th>
                <th>Date Occurred</th>
                <th>Description</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="valid-d">
            <?php if (empty($death)) { ?>
                <tr>
                    <td colspan="5">No data found.</td>
                </tr>
            <?php } else { ?>
                <?php foreach ($death as $cases) { ?>
                    <tr>
                        <td><?php echo $cases['pname']; ?></td>
                        <td>
                            <?php
                            $input_date = $cases['date'];
                            $date_obj = new DateTime($input_date);
                            $formatted_date = $date_obj->format("F j, Y");
                            echo $formatted_date;
                            ?>
                        </td>
                        <td><?php echo $cases['description']; ?></td>
                        <td>
                            <?php if ($cases['caseStatus'] == 0) { ?>
                                <i class="bi bi-question-circle text-warning"></i> Not Verified
                            <?php } else if ($cases['caseStatus'] == 1) { ?>
                                <i class="bi bi-check-circle text-success"></i> Verified
                            <?php } else { ?>
                                <i class="bi bi-x-circle text-danger"></i> Rejected  
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($cases['caseStatus'] == 1) { ?>
                                <button class="btn btn-sm" disabled>Reviewed</button>
                            <?php } else { ?>
                                <form method="post" action="process_cancel.php" style="display: inline-block; margin-left: 5px;">
                                    <input type="hidden" name="caseID" value="<?php echo $cases['caseID']; ?>">
                                    <button type="submit" name="cancel_death" class="btn btn-danger btn-sm">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
</div> -->
                                <!-- <div class="d-flex justify-content-center mt-4">
    <ul class="pagination">
        <?php for ($d = 1; $d <= $total_pages_death; $d++) { ?>
            <li class="page-item <?= ($d == $page_death) ? 'active' : '' ?>">
                <a class="page-link" href="./dashboard.php?active-tab=3&page_death=<?= $d ?>"><?= $d ?></a>
            </li>
        <?php } ?>
    </ul>
</div> -->
                                <!-- </div> -->


                                <?php
                                // Assuming $suspected is your data array, $active_tab is set elsewhere
                                // $items_per_page_sus = 5;
                                // $total_items_sus = count($suspected);
                                // $total_pages_sus = ceil($total_items_sus / $items_per_page_sus);

                                // // Initialize $page_sus and set it based on your requirements
                                // $page_sus = isset($_GET['page_sus']) ? $_GET['page_sus'] : 1;

                                // // Calculate the starting index for the current page
                                // $start_index_sus = ($page_sus - 1) * $items_per_page_sus;

                                // // Get the data for the current page
                                // $current_page_data_sus = array_slice($suspected, $start_index_sus, $items_per_page_sus);
                                ?>

                                <!-- Suspected Pets Tab -->
                                <div class="tab-pane <?= ($active_tab == 4) ? ' active show' : '' ?>" id="suspectedPets">
                                    <!-- Suspected Pets Content -->
                                    <form method="POST" action="reportRabidResident.php">
                                        <input type="hidden" name="brgyID" value="<?php echo $brgyID; ?>">
                                        <input type="hidden" name="residentID" value="<?php echo $residentID; ?>">
                                        <input type="hidden" name="userType" id="userType" value="<?php echo $userType; ?>">
                                        <button type="submit" class="btn btn-primary">Report Suspected Case</button>
                                    </form>

                                    <!-- <label for="residentSearch" class="form-label"></label>
<div class="input-group mb-3">
<input type="text" class="form-control" id="susSrch" placeholder="Search...">
<button class="btn btn-primary" id="susBtn" type="button">Search</button>
</div> -->
                                    <div class="table-responsive">
                                        <table id="suspected" class="table text-center table-bordered table-hover" style="width:100%">
                                            <h4>Suspected Reports:</h4>
                                            <thead>
                                                <tr>
                                                    <th>Pet's Name</th>
                                                    <th>Date Noticed</th>
                                                    <th>Signs</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="valid-s">
                                                <?php if (empty($suspected)) { ?>
                                                    <tr>
                                                        <td colspan="5">No data found.</td>
                                                    </tr>
                                                <?php } else { ?>
                                                    <?php foreach ($suspected as $cases) { ?>
                                                        <tr>
                                                            <td><?php echo $cases['pname']; ?></td>
                                                            <td>
                                                                <?php
                                                                $input_date = $cases['date'];
                                                                $date_obj = new DateTime($input_date);
                                                                $formatted_date = $date_obj->format("F j, Y, H:i:s");
                                                                echo $formatted_date;
                                                                ?>
                                                            </td>
                                                            <td><?php echo $cases['description']; ?></td>
                                                            <td>
                                                                <?php if ($cases['caseStatus'] == 0) { ?>
                                                                    <i class="bi bi-question-circle text-warning"></i> Not Verified
                                                                <?php } else if ($cases['caseStatus'] == 1) { ?>
                                                                    <i class="bi bi-check-circle text-success"></i> Verified
                                                                <?php } else { ?>
                                                                    <i class="bi bi-x-circle text-danger"></i> Rejected
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($cases['caseStatus'] == 1) { ?>
                                                                    <button class="btn btn-sm" disabled>Reviewed</button>
                                                                <?php } else { ?>
                                                                    <form method="post" action="process_cancel.php" style="display: inline-block; margin-left: 5px;">
                                                                        <input type="hidden" name="caseID" value="<?php echo $cases['caseID']; ?>">
                                                                        <button type="submit" name="cancel_sus" class="btn btn-danger btn-sm">
                                                                            <i class="bi bi-x"></i> <!-- X icon for Delete -->
                                                                        </button>
                                                                    </form>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- <div class="d-flex justify-content-center mt-4">
<ul class="pagination">
    <?php for ($sus = 1; $sus <= $total_pages_sus; $sus++) { ?>
        <li class="page-item <?= ($sus == $page_sus) ? 'active' : '' ?>">
            <a class="page-link" href="./dashboard.php?active-tab=4&page_sus=<?= $sus ?>"><?= $sus ?></a>
        </li>
    <?php } ?>
</ul>
</div> -->
                                </div>



                                <!-- </body> -->

                                <?php
                                // Replace these with your actual database credentials
                                global $conn;

                                // Check connection
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $mysqli->connect_error);
                                }

                                // Get the residentID from the session
                                $residentID = isset($_SESSION['user']['residentID']) ? $_SESSION['user']['residentID'] : '';
                                $brgyID = isset($_SESSION['user']['brgyID']) ? $_SESSION['user']['brgyID'] : '';

                                // Check if residentID is available
                                // if (empty($residentID)) {
                                //     die("ResidentID not found.");
                                // }

                                // Replace this with the query to fetch notifications for a specific residentID
                                $sql = "SELECT notifID, isRead, notifMessage, notifDate, notifType FROM notification WHERE notifType IN (9, 10, 11, 12, 13) AND (residentID = ?) ORDER BY notifDate DESC";


                                // Prepare the statement
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $residentID);
                                $stmt->execute();

                                // Get the result
                                $result = $stmt->get_result();

                                // Initialize an empty array to store notifications
                                $allNotifs = [];

                                // Fetch notifications as an associative array
                                while ($row = $result->fetch_assoc()) {
                                    $allNotifs[] = $row;
                                }

                                // Close the statement
                                $stmt->close();
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
                                                            <!-- <h4>Notifications</h4> -->
                                                            <tr>
                                                                <th>Messages</th>
                                                                <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($allNotifs as $notif) { ?>
                                                                <tr>
                                                                    <?php
                                                                    // $redirectUrl = '';

                                                                    // switch ($notif['notifType']) {
                                                                    //     case 9:
                                                                    //     case 10:
                                                                    //     case 11:
                                                                    //         $redirectUrl = 'dashboard.php?active-tab=1';
                                                                    //         break;
                                                                    //     case 12:
                                                                    //         $redirectUrl = 'dashboard.php?active-tab=2';
                                                                    //         break;
                                                                    //     case 13:
                                                                    //         $redirectUrl = 'dashboard.php?active-tab=4';
                                                                    //         break;
                                                                    //     default:
                                                                    //         // Default case if notifType doesn't match any of the above
                                                                    //         $redirectUrl = '#';
                                                                    // }
                                                                    ?>

                                                                    <td style="background-color: <?php echo ($notif['isRead'] == 0) ? '#d3d3d3' : ''; ?>;">
                                                                        <form method="get" action="mark_all_read.php" style="margin: 0;">
                                                                            <!-- <input type="hidden" name="isRead" value="1"> -->
                                                                            <form method="get" action="mark_all_read.php" style="margin: 0;">
                                                                                <input type="hidden" name="isRead" value="1">
                                                                                <input type="hidden" name="notifID" value="<?php echo $notif['notifID'] ?>">
                                                                                <input type="hidden" name="notifType" value="<?php echo $notif['notifType'] ?>">
                                                                                <button type="submit" name="mark_read" class="btn btn-link" style="border: none; text-decoration: none; text-align: justify; color: black; padding: 0;">
                                                                                    <?php echo $notif['notifMessage']; ?>
                                                                                </button>
                                                                            </form>

                                                                    </td>
                                                                    <td style="background-color: <?php echo ($notif['isRead'] == 0) ? '#d3d3d3' : ''; ?>;">
                                                                        <form method="get" action="mark_all_read.php" style="margin: 0;">
                                                                            <!-- <input type="hidden" name="isRead" value="1"> -->
                                                                            <form method="get" action="mark_all_read.php" style="margin: 0;">
                                                                                <input type="hidden" name="isRead" value="1">
                                                                                <input type="hidden" name="notifID" value="<?php echo $notif['notifID'] ?>">
                                                                                <input type="hidden" name="notifType" value="<?php echo $notif['notifType'] ?>">
                                                                                <button type="submit" name="mark_read" class="btn btn-link" style="border: none; text-decoration: none; text-align: justify; color: black; padding: 0;">
                                                                                    <?php echo date('F j, Y, g:i A', strtotime($notif['notifDate'])); ?>
                                                                                </button>
                                                                            </form>

                                                                    </td>
                                                                    </form>



                                                                    <!-- <td></td> -->
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
                                                    <input type="hidden" name="residentID" id="residentID" value="<?php echo $row['residentID'] ?>">
                                                    <input type="hidden" name="residentID" id="residentID" value="<?php echo $residentID ?>">
                                                    <button type="submit" name="mark_all_read" class="btn btn-primary">Read All</button>
                                                </form>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <script>
                            $(document).ready(function() {
                                // Function to enable/disable the "Add Pet" button based on vaccination status
                                function updateAddPetButton() {
                                    var vaccinationStatus = $('#statusVac').val();
                                    var currentVacInput = $('#currentVacInput');

                                    // Disable the currentVacInput if the vaccination status is "1" (Wala)
                                    if (vaccinationStatus === "1") {
                                        currentVacInput.prop('disabled', true);
                                    } else {
                                        currentVacInput.prop('disabled', false);
                                    }
                                }

                                // Attach the function to the change event of the vaccination status field
                                $('#statusVac').on('change', function() {
                                    updateAddPetButton();
                                });

                                // Initially, update the button state on page load
                                updateAddPetButton();
                            });
                        </script>
                        <script>
                            jQuery(document).ready(function($) {
                                // Initialize DataTable
                                $('#pets').DataTable({
                                    // Apply default sorting on the 4th column (index 3) in descending order
                                    "order": [
                                        [3, 'desc']
                                    ],
                                    // Configure layout for DataTables buttons
                                    "dom": 'Bfrtip',
                                    "buttons": []
                                });

                                $('#bites').DataTable({
                                    // Apply default sorting on the 4th column (index 3) in descending order
                                    "order": [
                                        [3, 'desc']
                                    ],
                                    // Configure layout for DataTables buttons
                                    "dom": 'Bfrtip',
                                    "buttons": []
                                });

                                $('#death').DataTable({
                                    // Apply default sorting on the 4th column (index 3) in descending order
                                    "order": [
                                        [3, 'desc']
                                    ],
                                    // Configure layout for DataTables buttons
                                    "dom": 'Bfrtip',
                                    "buttons": []
                                });

                                $('#suspected').DataTable({
                                    // Apply default sorting on the 4th column (index 3) in descending order
                                    "order": [
                                        [3, 'desc']
                                    ],
                                    // Configure layout for DataTables buttons
                                    "dom": 'Bfrtip',
                                    "buttons": []
                                });
                            });
                        </script>
</body>

</html>