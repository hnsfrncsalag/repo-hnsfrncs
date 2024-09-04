<?php
session_start();
require_once("class/cases.php");
require_once("class/notification.php");
require_once("class/pet.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
if (isset($_POST['update'])) {
    $case = new Cases();
    $caseID = $_POST['caseID'];
    $confirmedRabies = $_POST['confirmedRabies'];
    $petID = $_POST['petID'];

    // Assuming $case->updateRabies() method is used to update the rabies status in the database
    $result = $case->updateRabies($caseID);

    if ($result === true) {
        // Successful update
        $notif = new Notification();
        $notifMessage = $_POST['notifMessage'];
        $notifType = 9;
        $residentID = $_POST['residentID'];
        $brgyID = $_POST['brgyID'];
        $notifDate = date('Y-m-d H:i:s');

        $push = $notif->rabiesNotif($caseID, $brgyID, $residentID, $notifDate, $notifType, $notifMessage);

        echo '<script>alert("Rabies status updated successfully"); window.location.href = "./dashboardDeathCases.php?active-tab=2";</script>';
        exit;
    } else {
        // Failed to update rabies status
        echo '<script>alert("Failed to update rabies status: ' . $result . '"); window.location.href = "./dashboardDeathCases.php?active-tab=2";</script>';
        exit;
    }
}
}
?>
