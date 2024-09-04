<?php
session_start();
// var_dump($_POST);
require_once("class/cases.php");
require_once("class/geolocation.php");
require_once("class/pet.php");
require_once("class/Notification.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $residentID = $_POST['residentID'];
    $brgyID = $_POST['brgyID'];
    $victimsNames = isset($_POST['victimsNames']) ? (is_array($_POST['victimsNames']) ? $_POST['victimsNames'] : []) : [];
    $dates = isset($_POST['dates']) ? (is_array($_POST['dates']) ? $_POST['dates'] : []) : [];
    // $bpartsBitten = isset($_POST['bpartsBitten']) ? (is_array($_POST['bpartsBitten']) ? $_POST['bpartsBitten'] : []) : [];
    $descriptions = isset($_POST['descriptions']) ? (is_array($_POST['descriptions']) ? $_POST['descriptions'] : []) : [];
    $caseType = $_POST['caseType'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $notifType = $_POST['notifType'];
    $notifMessage = $_POST['notifMessage'];
    $caseStatus = $_POST['caseStatus'];

    $geolocation = new Geolocation();
    $geoID = $geolocation->saveGeolocation($latitude, $longitude);

    if ($geoID !== false) {
        $case = new Cases();
        $notif = new Notification();
        $notifDate = date('Y-m-d H:i:s');
        $push = $notif->addBiteUnknown($brgyID, $residentID, $notifType, $notifDate, $notifMessage);

        for ($i = 0; $i < count($victimsNames); $i++) {
            $result = $case->addUnknown($residentID, $brgyID, $geoID, $victimsNames, $caseType, $descriptions, $dates, $caseStatus);

            if ($result !== true) {
                echo '<script>alert("Failed to Report Case: ' . $result . '"); window.location.href = "addBiteCaseIndiv.php";</script>';
                exit; // Stop processing if there's an error
            }
        }

        echo '<script>alert("Report Bite Case Successfully"); window.location.href = "./dashboard.php?active-tab=2";</script>';
    }
}
?>
