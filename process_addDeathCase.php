<?php
// session_start();
// // var_dump($_POST);
require_once("class/cases.php");
require_once("class/geolocation.php");
require_once("class/pet.php");
require_once("class/notification.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $residentID = $_POST['residentID'];
    $petID = $_POST['petID'];
    $Health = $_POST['HStatus'];
    $brgyID = $_POST['brgyID'];
    $cdate = $_POST['date'];
    $description = $_POST['description'];
    $CRabies = $_POST['confirmedRabies'];
    $caseType = $_POST['caseType'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $caseStatus = $_POST['caseStatus'];
    $notifType = $_POST['notifType'];
    $notifMessage = $_POST['notifMessage'];

    $geolocation = new Geolocation();
    $geoID = $geolocation->saveGeolocation($latitude, $longitude);

    if ($geoID !== false) {
        $case = new Cases();
        $pet = new Pet();
        $notif = new Notification();
        $notifDate = date('Y-m-d H:i:s');
        $result = $case->addDeathCase($residentID, $brgyID, $petID, $geoID, $caseType, $description, $cdate, $CRabies, $caseStatus);
        $death = $pet->updateHealthStatus($petID, $Health);
        $push = $notif->addDeathNotif($brgyID, $residentID, $notifType, $notifDate, $notifMessage);
        // $push = $notif->addDeathNotif($brgyID, $residentID, $notifType, $notifDate, $notifMessage);
        if ($result === true) {
            echo '<script>alert("Report Death Case Successfully"); window.location.href = "./BAOpetdashboard.php?active-tab=1";</script>';
        } else {
            echo '<script>alert("The pet has been deceased."); window.location.href = "./BAOpetdashboard.php?active-tab=1";</script>';
        }
    }
}
?>
