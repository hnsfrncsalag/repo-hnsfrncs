<?php

require_once("class/pet.php");
require_once("class/notification.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Assuming you receive the parameters from a form submission or API request
    $residentID = $_POST['residentID'];
    $pname = $_POST['pname'];
    $petType = $_POST['petType'];
    $sex = $_POST['sex'];
    $neutering = isset($_POST['neutering']) ? $_POST['neutering'] : 0; // Set to 0 if not set
    $statusVac = isset($_POST['statusVac']) ? $_POST['statusVac'] : 0; // Set to 0 if not set
    $currentVac = isset($_POST['currentVac']) ? $_POST['currentVac'] : null; // Set to null if not set
    $color = $_POST['color'];
    $vetVac = isset($_POST['vetVac']) ? $_POST['vetVac'] : 0; // Set to 0 if not set
    $age = $_POST['age'];
    $pdescription = $_POST['pdescription'];
    $brgyID = $_POST['brgyID'];
    $notifType = $_POST['notifType'];
    $notifMessage = $_POST['notifMessage'];
    // $MAOID = $_POST['residentID'];

    $regDate = date('Y-m-d H:i:s');
    $notifDate = date('Y-m-d H:i:s');
    $pet = new Pet();
    $notif = new Notification();

    $newPet = $pet->addPetRes($residentID, $petType, $pname, $sex, $neutering, $color, $vetVac, $age, $regDate, $statusVac, $pdescription);
    $addNotif = $notif->addPetNotifRes($brgyID, $residentID, $notifType, $notifDate, $notifMessage);
    if ($newPet) {

        echo '<script>alert("Register Pet Successfully"); window.location.href = "./dashboard.php?active-tab=1";</script>';
    } else {
        echo '<script>alert("Failed to register Pet"); window.location.href = "./dashboard.php?active-tab=1";</script>';
    }
}
?>
