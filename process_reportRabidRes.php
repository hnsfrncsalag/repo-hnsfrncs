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
    $caseType = $_POST['caseType'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $notifType = $_POST['notifType'];
    $notifType = $_POST['notifType'];
    $notifMessage = $_POST['notifMessage'];

    // Get the current date and time
    

    $geolocation = new Geolocation();
    $geoID = $geolocation->saveGeolocation($latitude, $longitude);

    if ($geoID !== false) {
        $case = new Cases();
        $notif = new Notification();
        $notifDate = date('Y-m-d H:i:s');
        $result = $case->addSuspectedCaseRes($residentID, $brgyID, $petID, $geoID, $caseType, $description, $date);
        $push = $notif->addSusNotif($brgyID, $petID, $residentID, $notifType, $notifDate, $notifMessage);

        if ($result === true) {
            echo '<script>alert("Report Suspected Rabid Case Successfully"); window.location.href = "./dashboard.php?active-tab=4";</script>';
        } else {
            echo '<script>alert("Failed to Report Case: ' . $result . '"); window.location.href = "reportRabidResident.php";</script>';
        }
    }
}
?>
