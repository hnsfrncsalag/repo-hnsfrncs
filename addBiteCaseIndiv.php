<?php
session_start();
require_once("class/db_connect.php");
require_once("class/barangay.php");

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
if (isset($_POST['userType'])) {
    // Retrieve the brgyID
    $userType = $_POST['userType'];

    // Now, you can use $brgyID as needed
}
if (isset($_POST['name'])) {
    // Retrieve the brgyID
    $name = $_POST['name'];

    // Now, you can use $brgyID as needed
}
$brgyID = isset($_SESSION['user']['brgyID']) ? $_SESSION['user']['brgyID'] : '';
$residentID = isset($_SESSION['user']['residentID']) ? $_SESSION['user']['residentID'] : '';
$userType = isset($_SESSION['user']['userType']) ? $_SESSION['user']['userType'] : '';
$name = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : '';

// Get the user's information from the session
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Bite Case Form</title>
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

        /* #main-content {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
        } */
        #map {
            /* max-width: 80%; */
            height: 50vh;
            /* margin: 10px auto; */
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;

        }

        .modal-dialog {
            margin: 0 auto;
        }

        #map,
        #mapUnknown {
            height: 500px;
        }

        @media (min-width: 576px) {
            .h-sm-100 {
                height: 100%;
            }
        }


        /* .container {
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

        /* .container .content { */
        /* flex: 1; */
        /* This will make the content area fill the remaining space */
        /* overflow-y: auto; */
        /* Add scroll if content overflows */
        /* } */


        @media (min-width: 576px) {
            .h-sm-100 {
                height: 100%;
            }
        }


        /* .chart-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: start;
        } */

        @media (min-width: 768px) {
            .chart-container {
                flex-wrap: nowrap;
            }
        }

        canvas {
            max-width: 200%;
            height: auto;
        }

        /* 
        .col-12,
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
        }

        img {
            align-items: end;
        }

        .modal-dialog {
            width: 100%;
        } */
    </style>
</head>

<?php
// Count the number of new notifications
$sql_count = "SELECT COUNT(*) AS new_notif_count FROM notification WHERE notifType IN (9, 10, 11, 12, 13) AND residentID = ? AND isRead = 0";
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

                    <ul class="nav nav-underline flex-sm-column flex-row flex-nowrap flex-shrink-1 flex-sm-grow-0 flex-grow-1 mb-sm-auto justify-content-around align-items-center align-items-sm-start" id="menu">
                        <li class="nav-item">
                            <a href="./dashboard.php?active-tab=1" class="flex-sm-fill text-sm-center nav-link text-dark" aria-current="page">
                                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                    <path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z" />
                                </svg><span class="ms-2 d-none d-sm-inline">Home</span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="nav-link dropdown-toggle text-decoration-none text-dark px-sm-0 px-1 active" id="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
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
                            <div class="col-md-auto">
                                <a href="./dashboard.php?active-tab=2" class="btn rounded-circle p-2">
                                    <i class="bi bi-arrow-left"></i>
                                </a>
                            </div>
                            <!-- <div class="col-md"> -->
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#Known">Identified Pet</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#Unknown">Unidentified Pet</a>
                                </li>
                            </ul>
                            <!-- </div> -->
                            <!-- </div> -->





                            <div class="tab-content">
                                <!-- Known Pet Section -->
                                <div class="tab-pane fade show active" id="Known">
                                    <!-- <div class="container"> -->
                                    <div class="mb-3">
                                        <h1><i class="bi bi-journal"></i> Report Bite Case Form</h1>

                                        <form method="POST" action="addBiteCaseIndiv.php" id="searchForm">
                                            <div class="row mb-3">
                                                <div class="input-group mb-3">
                                                    <!-- <label for=" searchTerm" class="form-label"></label> -->
                                                    <input type="text" name="searchTerm" id="searchTerm" class="form-control" placeholder="Search using Owner's Name, Pet Name, Color, or Description">
                                                    <button type="submit" name="search" class="btn btn-primary ">Search</button>
                                                </div>
                                            </div>

                                    </div>
                                    </form>
                                    <?php
                                    global $conn;

                                    if ($conn->connect_error) {
                                        die("Connection failed: " . $conn->connect_error);
                                    }

                                    // Pagination variables
                                    $results_per_page = 10000; // Number of results per page
                                    $current_page = isset($_GET['page']) ? $_GET['page'] : 1; // Get the current page number
                                    $searchTerm = isset($_POST['searchTerm']) ? '%' . $_POST['searchTerm'] . '%' : '';

                                    if (empty($searchTerm)) {
                                        echo '<p>No search term provided.</p>';
                                    } else {
                                        // Count total search results
                                        $sql_count = "SELECT COUNT(*) AS total FROM (
                                                       SELECT DISTINCT p.petID
                                                       FROM pet p
                                                       INNER JOIN resident r ON p.residentID = r.residentID
                                                       INNER JOIN barangay b ON r.brgyID = b.brgyID
                                                       WHERE p.status = 1 
                                                       AND (p.pdescription LIKE ? OR p.pname LIKE ? OR r.name LIKE ? OR p.color LIKE ? OR b.barangay LIKE ?)
                                                   ) AS count_table";

                                        $stmt_count = $conn->prepare($sql_count);
                                        $stmt_count->bind_param("sssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
                                        $stmt_count->execute();
                                        $result_count = $stmt_count->get_result();
                                        $row_count = $result_count->fetch_assoc();
                                        $total_results = $row_count['total'];

                                        // Calculate total pages
                                        $total_pages = ceil($total_results / $results_per_page);
                                        $offset = ($current_page - 1) * $results_per_page;

                                        // Fetch search results with pagination
                                        $sql_results = "SELECT DISTINCT p.petID, p.pname, p.pdescription, p.color, r.name, b.barangay, p.petType, b.brgyID
                                        FROM pet p
                                        INNER JOIN resident r ON p.residentID = r.residentID
                                        INNER JOIN barangay b ON r.brgyID = b.brgyID
                                        WHERE p.status = 1 AND Health = 0
                                        AND (p.pdescription LIKE ? OR p.pname LIKE ? OR r.name LIKE ? OR p.color LIKE ? OR b.barangay LIKE ?)
                                        LIMIT ?, ?";

                                        $stmt_results = $conn->prepare($sql_results);
                                        $stmt_results->bind_param("ssssssi", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $offset, $results_per_page);
                                        $stmt_results->execute();
                                        $result_results = $stmt_results->get_result();

                                        if ($result_results->num_rows > 0) {
                                            echo '<div class="table-responsive">';
                                            echo '<table id="search" class="table text-center table-bordered table-hover" style="width:100%">';
                                            echo '<thead>';
                                            echo '<tr>';
                                            echo '<th>Pet Name</th>';
                                            echo '<th>Sex</th>';
                                            echo '<th>Description</th>';
                                            echo '<th>Owner</th>';
                                            echo '<th>Color</th>';
                                            echo '<th>Barangay</th>';
                                            echo '<th>Action</th>';
                                            echo '</tr>';
                                            echo '</thead>';
                                            echo '<tbody>';

                                            while ($row = $result_results->fetch_assoc()) {
                                                echo '<tr>';
                                                echo '<td>' . $row['pname'] . '</td>';
                                                echo '<td>' . ($row['petType'] ? 'Male' : 'Female') . '</td>';
                                                echo '<td>' . $row['pdescription'] . '</td>';
                                                echo '<td>' . $row['name'] . '</td>';
                                                echo '<td>' . $row['color'] . '</td>';
                                                echo '<td>' . $row['barangay'] . '</td>';
                                                echo '<td>';
                                                echo '<form method="POST" action="#">';
                                                echo '<input type="hidden" name="petID" value="' . $row['petID'] . '">';
                                                echo '<button type="button" class="btn btn-danger reportPet" data-bs-toggle="modal" data-bs-target="#reportModal" data-petid="' . $row['petID'] . '" data-petname="' . $row['pname'] . '">';
                                                echo 'Report';
                                                echo '</button>';
                                                echo '</form>';
                                                echo '</td>';
                                                echo '</tr>';
                                                //   }

                                    ?>



                                                <!-- Add this script to handle the data-petid when the Report button is clicked -->
                                                <script>
                                                    // Update modal input fields when the Report button is clicked
                                                    $('.reportPet').on('click', function() {
                                                        var petID = $(this).data('petid');

                                                        // Update the hidden input field for petID in the modal
                                                        $('#petID').val(petID);

                                                        // Update other hidden input fields if needed
                                                        // $('#otherHiddenInput').val(someValue);

                                                        // You can include more hidden input fields if necessary

                                                        // Trigger the modal to open
                                                        $('#reportModal').modal('show');
                                                    });
                                                </script>

                                                <script>
                                                    // JavaScript to set the pet name in the modal title
                                                    $(document).on('click', '.reportPet', function() {
                                                        var petName = $(this).data('petname');
                                                        $('#petName').text(petName);
                                                    });
                                                </script>
                                                <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="addBiteCaseModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="addBiteCaseModalLabel">Report Bite for <span id="petName"></span></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form method="POST" action="process_addBiteCaseIndiv.php" id="reportBiteCaseForm">
                                                                    <!-- Your existing form fields go here -->
                                                                    <!-- Remove the hidden input field for petID -->
                                                                    <div class="mb-3">
                                                                        <label for="victimsName" class="form-label"><strong>Victim Name / Ngalan sang Biktima: </strong> <span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" name="victimsNames[]" id="victimsName' + victimCounter + '" required placeholder="Ngalan sang Biktima" style="font-style: italic;">
                                                                    </div>
                                                                    <input type="hidden" name="petID" id="petID" value="">
                                                                    <div class="mb-3">
                                                                        <label for="date" class="form-label"><Strong>Date & Time of Bite / Adlaw kag Oras sang Insidente: </Strong> <span class="text-danger">*</span></label>
                                                                        <input type="datetime-local" class="form-control" name="dates[]" id="date' + victimCounter + '" required>
                                                                    </div>
                                                                    <!-- <div class="mb-3">
                                                    <label for="bpartBitten" class="form-label">Body Part Bitten: <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="bpartsBitten[]" id="bpartBitten' + victimCounter + '" required>
                                                        <option value="">Select Body Part</option>
                                                        <option value="0" style="font-style: italic;">Head and Neck Area / Ulo kag Liog nga parte sang lawas</option>
                                                        <option value="1" style="font-style: italic;">Thorax Area / Dughan nga parte sang lawas</option>
                                                        <option value="2" style="font-style: italic;">Abdomen Area / Tiyan nga parte sang lawas</option>
                                                        <option value="3" style="font-style: italic;">Upper Extremities Area / Kamot kag Butkon nga parte sang lawas</option>
                                                        <option value="4" style="font-style: italic;">Lower Extremities Area/ Hita kag Tiil nga parte sang lawas</option>
                                                    </select>
                                                </div> -->
                                                                    <div class="mb-3">
                                                                        <label for="description" class="form-label"><strong>Description / Deskripsiyon sang natabo kag sang sapat:</strong> <span class="text-danger">*</span></label>
                                                                        <textarea class="form-control" name="descriptions[]" id="description' + victimCounter + '" required placeholder="Deskripsiyon sang natabo kag sang sapat" style="font-style: italic;"></textarea>
                                                                    </div>
                                                                    <button type="button" class="btn btn-success" onclick="addVictimField()">+</button>
                                                                    <div id="additionalFields"></div>

                                                                    <div class="mb-3">
                                                                        <label class="form-label">Location:</label>
                                                                        <div id="map"></div>

                                                                        <script>
                                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                                var longitude = 122.48181;
                                                                                var latitude = 10.87993;

                                                                                // Initialize text elements
                                                                                var latTextElement = document.getElementById('latText');
                                                                                var lngTextElement = document.getElementById('lngText');

                                                                                var map = L.map('map').setView([10.87993, 122.48181], 18);

                                                                                L.tileLayer('https://api.maptiler.com/maps/dataviz/{z}/{x}/{y}@2x.png?key=A8yOIIILOal2yE0Rvb63', {
                                                                                    attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>',
                                                                                }).addTo(map);

                                                                                var marker = L.marker([latitude, longitude], {
                                                                                    draggable: true
                                                                                }).addTo(map);

                                                                                // Update lat, lng, and text elements on marker drag
                                                                                marker.on('dragend', function(e) {
                                                                                    var latLng = e.target.getLatLng();
                                                                                    var lat = latLng.lat.toFixed(6);
                                                                                    var lng = latLng.lng.toFixed(6);

                                                                                    // Set values in hidden form fields
                                                                                    document.getElementById('latitude').value = lat;
                                                                                    document.getElementById('longitude').value = lng;

                                                                                    // Update text elements
                                                                                    latTextElement.innerText = 'Latitude: ' + lat;
                                                                                    lngTextElement.innerText = 'Longitude: ' + lng;
                                                                                });
                                                                            });
                                                                        </script>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <!-- Your existing hidden input fields go here -->
                                                                        <input type="hidden" name="victimCounter" id="victimCounter" value="1">
                                                                        <input type="hidden" name="residentID" id="residentID" value="<?php echo $user['residentID']; ?>">
                                                                        <input type="hidden" name="brgyID" id="brgyID" value="<?php echo $row['brgyID']; ?>">
                                                                        <input type="hidden" name="caseType" id="caseType" value="0">
                                                                        <input type="hidden" name="caseStatus" id="caseStatus" value="0">
                                                                        <input type="hidden" name="notifType" id="notifType" value="2">
                                                                        <input type="hidden" name="notifDate" id="notifDate" value="<?php echo date('Y-m-d'); ?>">
                                                                        <input type="hidden" name="notifMessage" id="notifMessage" value="A new bite case has been recorded.">
                                                                        <input type="hidden" name="latitude" id="latitude">
                                                                        <input type="hidden" name="longitude" id="longitude">
                                                                        <input type="submit" class="btn btn-danger" value="Report" onclick="getLocationAndSubmit()" form="reportBiteCaseForm">

                                                                </form>
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- </div> -->
                                    <?php
                                            }

                                            echo '</tbody>';
                                            echo '</table>';
                                            echo '</div>';

                                            // Display pagination links
                                            //  echo '<div class="d-flex justify-content-center mt-4">';
                                            //  echo '<ul class="pagination">';
                                            //  for ($b = 1; $b <= $total_pages; $b++) {
                                            //      echo '<li class="page-item ' . (($b == $current_page) ? 'active' : '') . '">';
                                            //      echo '<a class="page-link" href="addBiteCase.php?page=' . $b . '&searchTerm=' . urlencode($_POST['searchTerm']) . '">' . $b . '</a>';
                                            //      echo '</li>';
                                            //  }
                                            //  echo '</ul>';
                                            //  echo '</div>';
                                        } else {
                                            echo '<p>No pets found with the specified criteria</p>';
                                        }
                                    }
                                    //  } else {
                                    //      echo '<p class=" pb-3">Please search for pets.</p>';
                                    //  }
                                    ?>
                                    <script>
                                        var victimCounter = parseInt(document.getElementById('victimCounter').value);

                                        function addVictimField() {
                                            var additionalFieldsContainer = document.getElementById('additionalFields');
                                            var newField = document.createElement('div');
                                            var victimId = 'victim' + victimCounter;

                                            // Add a container div with a horizontal line and black border as a separator between victims
                                            newField.innerHTML =
                                                '<div id="' + victimId + '"">' +
                                                '<hr style="border-top: 5px solid black; margin: 10px 0;">' +
                                                // Add fields for the current victim
                                                '<div class="mb-3">' +
                                                '<label for="victimsName' + victimCounter + '" class="form-label"><strong>Victim Name / Ngalan sang Biktima ' + victimCounter + ': </strong>  <span class="text-danger">*</span></label>' +
                                                '<div class="input-group">' +
                                                '<input type="text" class="form-control" name="victimsNames[]" id="victimsName' + victimCounter + '" required placeholder="Ngalan sang Biktima" style="font-style: italic;">' +
                                                '</div>' +
                                                '</div>' +
                                                '<div class="mb-3">' +
                                                '<label for="date' + victimCounter + '" class="form-label"><Strong> Date & Time of Bite / Adlaw kag Oras sang Insidente: </strong> <span class="text-danger">*</span></label>' +
                                                '<input type="datetime-local" class="form-control" name="dates[]" id="date' + victimCounter + '" required>' +
                                                '</div>' +
                                                // '<div class="mb-3">' +
                                                // '<label for="bpartBitten' + victimCounter + '" class="form-label">Body Part Bitten: <span class="text-danger">*</span></label>' +
                                                // '<select class="form-control" name="bpartsBitten[]" id="bpartBitten' + victimCounter + '" required>' +
                                                // '<option value="">Select Body Part</option>' +
                                                // '<option value="0" style="font-style: italic;">Head and Neck Area/ Ulo kag Ilog nga parte sang lawas</option>' +
                                                // '<option value="1" style="font-style: italic;">Thorax Area / Dughan nga parte sang lawas</option>' +
                                                // '<option value="2" style="font-style: italic;">Abdomen Area / Tiyan nga parte sang lawas</option>' +
                                                // '<option value="3" style="font-style: italic;">Upper Extremity Area / Kamot kag Butkon nga parte sang lawas</option>' +
                                                // '<option value="4" style="font-style: italic;">Lower Extremity Area / Hita kag Tiil nga parte sang lawas</option>' +
                                                // '</select>' +
                                                // '</div>' +
                                                '<div class="mb-3">' +
                                                '<label for="description' + victimCounter + '" class="form-label"><strong>Description / Deskripsiyon sang natabo kag sang sapat: </Strong> <span class="text-danger">*</span></label>' +
                                                '<textarea type="text" class="form-control" name="descriptions[]" id="description' + victimCounter + '" required style="font-style: italic;" placeholder="Deskripsiyon sang natabo kag sang sapat"></textarea>' +
                                                '</div>' +
                                                '<button type="button" class="btn btn-danger" onclick="removeVictimFields(\'' + victimId + '\')">-</button>' + // Remove button
                                                '</div>';

                                            additionalFieldsContainer.appendChild(newField);
                                            document.getElementById('victimCounter').value = ++victimCounter;
                                        }

                                        function removeVictimFields(victimId) {
                                            var victimFieldsToRemove = document.getElementById(victimId);
                                            victimFieldsToRemove.parentNode.removeChild(victimFieldsToRemove);
                                        }
                                    </script>
                                </div>


                                <!-- Unknown Pet Section -->
                                <!-- <div class="tab-content"> -->
                                <div class="tab-pane fade" id="Unknown">
                                    <!-- <div class="container"> -->
                                    <div class="mb-3">
                                        <h1><i class="bi bi-journal"></i> Report Bite Case Form</h1>

                                        <form method="POST" action="process_unknownRes.php" id="addUnknown">
                                            <div class="mb-3">
                                                <label for="victimsName" class="form-label"><strong>Victim Name / Ngalan sang Biktima: </strong> <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="victimsNames[]" id="victimsName' + victimCounter + '" required placeholder="Ngalan sang Biktima" style="font-style: italic;">
                                            </div>
                                            <input type="hidden" name="petID" id="petID" value="">
                                            <div class="mb-3">
                                                <label for="date" class="form-label"><Strong>Date & Time of Bite / Adlaw kag Oras sang Insidente: </Strong> <span class="text-danger">*</span></label>
                                                <input type="datetime-local" class="form-control" name="dates[]" id="date' + victimCounter + '" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="description" class="form-label"><strong>Description / Deskripsiyon sang natabo kag sang sapat:</strong> <span class="text-danger">*</span></label>
                                                <textarea class="form-control" name="descriptions[]" id="description' + victimCounter + '" required placeholder="Deskripsiyon sang natabo kag sang sapat" style="font-style: italic;"></textarea>
                                            </div>
                                            <button type="button" class="btn btn-success" onclick="addUnknown()">+</button>
                                            <div id="unknownFields"></div>
                                            <label for="Map">Location:</label>
                                            <div id="mapUnknown"></div>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    var longitude = 122.48181;
                                                    var latitude = 10.87993;

                                                    // Initialize text elements
                                                    var latTextElement = document.getElementById('latText');
                                                    var lngTextElement = document.getElementById('lngText');

                                                    var map = L.map('mapUnknown').setView([10.87993, 122.48181], 18);

                                                    L.tileLayer('https://api.maptiler.com/maps/dataviz/{z}/{x}/{y}@2x.png?key=A8yOIIILOal2yE0Rvb63', {
                                                        attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>',
                                                    }).addTo(map);

                                                    var marker = L.marker([latitude, longitude], {
                                                        draggable: true
                                                    }).addTo(map);

                                                    // Update lat, lng, and text elements on marker drag
                                                    marker.on('dragend', function(e) {
                                                        var latLng = e.target.getLatLng();
                                                        var lat = latLng.lat.toFixed(6);
                                                        var lng = latLng.lng.toFixed(6);

                                                        // Set values in hidden form fields
                                                        document.getElementById('latitude').value = lat;
                                                        document.getElementById('longitude').value = lng;


                                                        // Update text elements
                                                        latTextElement.innerText = 'Latitude: ' + lat;
                                                        lngTextElement.innerText = 'Longitude: ' + lng;
                                                    });
                                                });
                                            </script>
                                            <div class="mb-3"></div>
                                            <input type="hidden" name="residentID" id="residentID" value="<?php echo $user['residentID']; ?>">
                                            <input type="hidden" name="brgyID" id="brgyID" value="<?php echo $user['brgyID']; ?>">
                                            <input type="hidden" name="caseType" id="caseType" value="0">
                                            <input type="hidden" name="caseStatus" id="caseStatus" value="0">
                                            <input type="hidden" name="notifType" id="notifType" value="2">
                                            <input type="hidden" name="notifDate" id="notifDate" value="<?php echo date('Y-m-d'); ?>">
                                            <input type="hidden" name="notifMessage" id="notifMessage" value="An unknown bite case has been recorded.">
                                            <input type="hidden" name="latitude" id="latitude">
                                            <input type="hidden" name="longitude" id="longitude">
                                            <input type="submit" class="btn btn-danger" value="Report Bite Case" onclick="getLocationAndSubmit()" form="addUnknown">
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        var victimCounter = parseInt(document.getElementById('victimCounter').value);

                        function addUnknown() {
                            var additionalFieldsContainer = document.getElementById('unknownFields');
                            var newField = document.createElement('div');
                            var victimId = 'victim' + victimCounter;

                            // Add a container div with a horizontal line and black border as a separator between victims
                            newField.innerHTML =
                                '<div id="' + victimId + '"">' +
                                '<hr style="border-top: 5px solid black; margin: 10px 0;">' +
                                // Add fields for the current victim
                                '<div class="mb-3">' +
                                '<label for="victimsName' + victimCounter + '" class="form-label"><strong>Victim Name / Ngalan sang Biktima ' + victimCounter + ': </strong>  <span class="text-danger">*</span></label>' +
                                '<div class="input-group">' +
                                '<input type="text" class="form-control" name="victimsNames[]" id="victimsName' + victimCounter + '" required placeholder="Ngalan sang Biktima" style="font-style: italic;">' +
                                '</div>' +
                                '</div>' +
                                '<div class="mb-3">' +
                                '<label for="date' + victimCounter + '" class="form-label"><Strong> Date & Time of Bite / Adlaw kag Oras sang Insidente: </strong> <span class="text-danger">*</span></label>' +
                                '<input type="datetime-local" class="form-control" name="dates[]" id="date' + victimCounter + '" required>' +
                                '</div>' +
                                // '<div class="mb-3">' +
                                // '<label for="bpartBitten' + victimCounter + '" class="form-label">Body Part Bitten: <span class="text-danger">*</span></label>' +
                                // '<select class="form-control" name="bpartsBitten[]" id="bpartBitten' + victimCounter + '" required>' +
                                // '<option value="">Select Body Part</option>' +
                                // '<option value="0" style="font-style: italic;">Head and Neck Area/ Ulo kag Ilog nga parte sang lawas</option>' +
                                // '<option value="1" style="font-style: italic;">Thorax Area / Dughan nga parte sang lawas</option>' +
                                // '<option value="2" style="font-style: italic;">Abdomen Area / Tiyan nga parte sang lawas</option>' +
                                // '<option value="3" style="font-style: italic;">Upper Extremity Area / Kamot kag Butkon nga parte sang lawas</option>' +
                                // '<option value="4" style="font-style: italic;">Lower Extremity Area / Hita kag Tiil nga parte sang lawas</option>' +
                                // '</select>' +
                                // '</div>' +
                                '<div class="mb-3">' +
                                '<label for="description' + victimCounter + '" class="form-label"><strong>Description / Deskripsiyon sang natabo kag sang sapat: </Strong> <span class="text-danger">*</span></label>' +
                                '<textarea type="text" class="form-control" name="descriptions[]" id="description' + victimCounter + '" required style="font-style: italic;" placeholder="Deskripsiyon sang natabo kag sang sapat"></textarea>' +
                                '</div>' +
                                '<button type="button" class="btn btn-success" onclick="addUnknown(\'' + victimId + '\')">+</button>' +
                                '<button type="button" class="btn btn-danger" onclick="removeUnknown(\'' + victimId + '\')">-</button>' + // Remove button
                                '</div>';

                            additionalFieldsContainer.appendChild(newField);
                            document.getElementById('victimCounter').value = ++victimCounter;
                        }

                        function removeUnknown(victimId) {
                            var victimFieldsToRemove = document.getElementById(victimId);
                            victimFieldsToRemove.parentNode.removeChild(victimFieldsToRemove);
                        }
                    </script>
            </div>
        </div>
    </div>
    </main>
    </div>

</body>
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

</div>
</div>
<script>
    jQuery(document).ready(function($) {
        // Initialize DataTable
        $('#search').DataTable({
            // Apply default sorting on the 4th column (index 3) in descending order
            "order": [
                [3, 'desc']
            ],
            // Configure layout for DataTables buttons
            "dom": 'Bfrtip',
            "buttons": []
        });

        // $('#bites').DataTable({
        //     // Apply default sorting on the 4th column (index 3) in descending order
        //     "order": [[3, 'desc']],
        //     // Configure layout for DataTables buttons
        //     "dom": 'Bfrtip',
        //     "buttons": [
        //     ]
        // });

        // $('#death').DataTable({
        //     // Apply default sorting on the 4th column (index 3) in descending order
        //     "order": [[3, 'desc']],
        //     // Configure layout for DataTables buttons
        //     "dom": 'Bfrtip',
        //     "buttons": [
        //     ]
        // });

        // $('#suspected').DataTable({
        //     // Apply default sorting on the 4th column (index 3) in descending order
        //     "order": [[3, 'desc']],
        //     // Configure layout for DataTables buttons
        //     "dom": 'Bfrtip',
        //     "buttons": [
        //     ]
        // });
    });
</script>

</html>