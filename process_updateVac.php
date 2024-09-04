<?php
session_start();
require_once("class/pet.php");

if (isset($_POST['update'])) {
    $pet = new Pet();

    // $userType = $_POST['userType'];
    $petID = $_POST['petID'];
    $currentVac = $_POST['currentVac'];
    $statusVac = $_POST['statusVac'];

    // Assuming $pet->addPet() method is used to update the pet's vaccination status and the database
    $result = $pet->updateVacStatus($petID, $currentVac, $statusVac);

    if ($result === true) {
        // Successful updat
            echo '<script>alert("Vaccination status updated successfully"); window.location.href = "./BAOpetdashboard.php?active-tab=1";</script>';
        exit;
    } else {
        // Failed to update vaccination status
        echo '<script>alert("Failed to update vaccination status: ' . $result . '"); window.location.href = "dashboard1Pet.php";</script>';
        exit;
    }
}
?>
