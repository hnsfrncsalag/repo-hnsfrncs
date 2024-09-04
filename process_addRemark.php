<?php
session_start();

require_once("class/pet.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Assuming you receive the parameters from a form submission or API request
    $petID = $_POST['petID'];
    $page = $_POST['pageValid'];
    $BAOremarks = $_POST['BAOremarks'];

    $pet = new Pet();
    $remarks=$pet->addRemarks($petID, $BAOremarks);
    if ($remarks) {
        echo '<script>alert("Remarks Added Successfully"); window.location.href = "./dashboard1pet.php?active-tab=2&page_valid='. $page .'";</script>';
    } else {
        echo '<script>alert("Failed to register Pet"); window.location.href = "addPetBAO.php";</script>';
    }
}
?>
