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
$brgyID = isset($_SESSION['user']['brgyID']) ? $_SESSION['user']['brgyID'] : '';
$residentID = isset($_SESSION['user']['residentID']) ? $_SESSION['user']['residentID'] : '';
// Get the user's information from the session
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Report Death Case Form</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Add jQuery library -->

    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="petstaticon.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }


        .container {
            margin-top: 50px;
            background-color: #ffffff;
            padding: 8px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* .container .content { */
        /* flex: 1; */
        /* This will make the content area fill the remaining space */
        /* overflow-y: auto; */
        /* Add scroll if content overflows */
        /* } */

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
        }
        img{
            align-items: end;
        } */
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
                                <li><a class="dropdown-item" href="addDeathCaseIndiv.php">Report Death Case</a></li>
                                <li><a class="dropdown-item" href="reportRabidResident.php">Report Rabid Case</a></li>
                            </ul>
                        </li>
                    <li class="nav-item">
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" class="rounded-circle me-2 outline" viewBox="0 0 448 512">
                                    <!-- Icon for User -->
                                    <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/>
                                </svg>
                                <strong><span class="d-none d-sm-inline mx-1"><?php echo isset($user['name']) ? $user['name'] : ''; ?></span></strong>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            <!-- </div> -->
        <!-- </div> -->
    </div>
</div>

            <div class="col d-flex flex-column h-sm-100">
                <main class="row overflow-auto">
                    <div class="col-md-8 p-1 mt-2 my-auto mx-auto">
                        <div class="container mt-4">
                            <div class="">
                                <a href="./dashboard.php?active-tab=3" class="btn btn-lg align-item-start">
                                    <i class="bi bi-arrow-left"></i>
                                </a>
                            </div>
                            <h1><i class="bi bi-journal"></i> Report Death Case Form</h1>
                            <form method="POST" action="process_addDeathCaseIndiv.php" id="reportCaseForm">
                                <div class="mb-3">
                                    <label for="petName" class="form-label">Pet Name: <span class="text-danger">*</span></label>
                                    <select class="form-select" name="petID" id="petID" required>
                                        <option value="">Select Pet</option>
                                        <?php
                                        global $conn;

                                        if ($conn->connect_error) {
                                            die("Connection failed: " . $conn->connect_error);
                                        }

                                        $sql = "SELECT * FROM `pet` NATURAL JOIN resident WHERE brgyID = ? AND residentID = ? AND status = 1";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("ii", $brgyID, $residentID);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<option value="' . $row["petID"] . '" style="font-style: italic;">' . $row["pname"] . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No pets found</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description: <span class="text-danger">*</span></label>
                                    <!-- <input class="form-control" name="description" id="description" required> -->
                                    <textarea class="form-control" name="description" id="description" required style="font-style: italic;" placeholder="Deskripsiyon sang natabo"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="date" class="form-label">Date Occured: <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="date" id="date" required>
                                </div>
                                <input type="hidden" name="confirmedRabies" id="confirmedRabies" value="0">
                                    <input type="hidden" name="residentID" id="residentID" value="<?php echo $user['residentID']; ?>">
                                    <input type="hidden" name="brgyID" id="brgyID" value="<?php echo $user['brgyID']; ?>">
                                    <input type="hidden" name="userType" id="userType" value="<?php echo $userType; ?>">
                                    <input type="hidden" name="caseType" id="caseType" value="1">
                                    <input type="hidden" name="latitude" id="latitude">
                                    <input type="hidden" name="notifType" id="notifType" value="3">
                                    <input type="hidden" name="notifDate" id="notifDate" value="<?php echo date('Y-m-d'); ?>">
                                    <input type="hidden" name="notifMessage" id="notifMessage" value="A death case has been recorded.">            
                                    <input type="hidden" name="longitude" id="longitude">
                                    <input type="submit" value="Report Case" class="btn btn-primary" btn-lg onclick="getLocation()">
                            </form>

                        </div>
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
                        <!-- Add Bootstrap JS -->

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
                                
                                // Check if residentID is available
                                // if (empty($residentID)) {
                                //     die("ResidentID not found.");
                                // }
                                
                                // Replace this with the query to fetch notifications for a specific residentID
                                $sql = "SELECT notifMessage, notifDate FROM notification WHERE residentID = ? ORDER BY notifDate DESC";
                                
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
                                                            <tr>
                                                                <th>Message</th>
                                                                <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($allNotifs as $notif) { ?>
                                                                <tr>
                                                                    <td><?php echo $notif['notifMessage']; ?></td>
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

</html>