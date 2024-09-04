<?php
require_once("class/pet.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Assuming you receive the parameters from a form submission or API request
    $petID = $_POST['petID'];
    $pname = $_POST['pname'];
    $petType = $_POST['petType'];
    $sex = $_POST['sex'];
    $neutering = $_POST['neutering'];
    $color = $_POST['color'];
    $age = $_POST['age'];
    $pdescription = $_POST['pdescription'];

    $pet = new Pet();
    $editPet = $pet->editPet($petID, $petType, $pname, $sex, $neutering, $color, $age, $pdescription);

    if ($editPet === true) {
        echo '<script>alert("Pet updated successfully"); window.location.href = "./BAOpetdashboard.php?active-tab=1";</script>';
    } else {
        echo '<script>alert("Failed to update pet: ' . $editPet . '"); window.location.href = "./BAOpetdashboard.php?active-tab=1";</script>';
    }
}
?>