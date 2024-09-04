<?php
require_once("class/resident.php");
require_once("class/geolocation.php");
require_once("class/barangay.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $barangay = $_POST["barangay"];
    $contactNo = $_POST["contactNo"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $userStatus = $_POST['userStatus'];

    // Create a new instance of the Geolocation class
    $geolocation = new Geolocation();
    $geoID = $geolocation->saveGeolocation($latitude, $longitude);

    if ($geoID !== false) {
        // Create a new instance of the Barangay class
        $barangayObj = new Barangay();
        $brgyID = $barangayObj->getBrgyID($barangay);

        if ($brgyID !== false) {
            // Create a new instance of the Resident class
            $resident = new Resident();

            // Check if email already exists
            $emailExists = $resident->checkEmailExists($email);

            if ($emailExists) {
                echo '<script>alert("Email already registered."); window.location.href = "createAccBao.php";</script>';
            } else {
                // Register the resident
                $createAcc = $resident->createAcc($name, $geoID, $brgyID, $contactNo, $email, $password, $userStatus);
            
                if ($createAcc === true) {
                    echo '<script>alert("Registered Successfully"); window.location.href = "./dashboard1.php?active-tab=2";</script>';
                } 
                    echo '<script>alert("Failed to register resident: ' . $createAcc . '"); window.location.href = "createAccForResident.php";</script>';
                }
            }
            
        } else {
            echo "Failed to get Barangay.";
        }
}
?>
