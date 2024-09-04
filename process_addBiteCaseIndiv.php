<?php
session_start();
// var_dump($_POST);
require_once("class/cases.php");
require_once("class/geolocation.php");
require_once("class/pet.php");
require_once("class/notification.php");


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $residentID = $_POST['residentID'];
    $petID = $_POST['petID'];
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
    
    $geolocation = new Geolocation();
    $geoID = $geolocation->saveGeolocation($latitude, $longitude);

    if ($geoID !== false) {
        $case = new Cases();
        $notif = new Notification();
        $notifDate = date('Y-m-d H:i:s');
        $push = $notif->addBiteNotif($brgyID, $residentID, $notifType, $notifDate, $notifMessage);
        $result = $case->addBiteCaseRes($residentID, $brgyID, $petID, $geoID, $victimsNames, $caseType, $descriptions, $dates);
        
        if ($result === true) {
            echo '<script>alert("Report Bite Case Successfully"); window.location.href = "./dashboard.php?active-tab=2";</script>';
        } else {
            echo '<script>alert("Failed to Report Case: ' . $result . '"); window.location.href = "addBiteCaseIndiv.php";</script>';
        }
    }
}
?>
