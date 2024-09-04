<?php
session_start();

require_once("class/pet.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Assuming you receive the parameters from a form submission or API request
    $residentID = $_POST['residentID'];
    $pname = $_POST['pname'];
    $petType = $_POST['petType'];
    $sex = $_POST['sex'];
    $neutering = isset($_POST['neutering']) ? $_POST['neutering'] : 0; // Set to 0 if not set
    $statusVac = isset($_POST['statusVac']) ? $_POST['statusVac'] : 0; // Set to 0 if not set
    $currentVac = isset($_POST['currentVac']) ? $_POST['currentVac'] : null; // Check if it's empty
    $color = $_POST['color'];
    $vetVac = $_POST['vetVac'];
    $age = $_POST['age'];
    $regDate = $_POST['regDate'];
    $pdescription = $_POST['pdescription'];
    $status = $_POST['status'];

    $regDate = date('Y-m-d H:i:s');

    $pet = new Pet();

    $newPet = $pet->addPet($residentID, $petType, $pname, $sex, $neutering, $color, $vetVac, $age, $regDate, $currentVac, $statusVac, $pdescription, $status);

    if ($newPet) {
        // Pet registration successful, now send notification to the officer
        // $notifMessage = "New pet registered: $pname";

        // // Assuming you have a method in your notification class to add a notification
        // // Adjust the class and method names accordingly
        // $notification = new Notifications();
        // $notification->addRegNotif($residentID, $notifMessage, $notifType);

        echo '<script>alert("Register Pet Successfully"); window.location.href = "./BAOpetdashboard.php?active-tab=1";</script>';
    } else {
        echo '<script>alert("Failed to register Pet"); window.location.href = "addPetBAO.php";</script>';
    }
}
?>
