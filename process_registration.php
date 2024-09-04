<?php
require_once("class/resident.php");
require_once("class/geolocation.php");
require_once("class/barangay.php");
require_once("class/notification.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $barangay = $_POST["barangay"];
    $contactNo = $_POST["contactNo"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $notifType = $_POST["notifType"];
    $notifMessage = $_POST["notifMessage"];
    
    $notifDate = date('Y-m-d H:i:s');
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
                echo '<script>alert("Email already registered."); window.location.href = "index.php";</script>';
            } else {
                // Register the resident
                $notif = new Notification();
                $notifDate = date('Y-m-d H:i:s');
                $push = $notif->addResNotif($brgyID, $notifType, $notifDate, $notifMessage);
                $registrationResult = $resident->registerResident($name, $geoID, $brgyID, $contactNo, $email, $password);  

                if ($registrationResult === true) {
                    echo '<script>alert("Registered Successfully"); window.location.href = "login.php";</script>';
                } else {
                    echo '<script>alert("Failed to register resident: ' . $registrationResult . '"); window.location.href = "registration.php";</script>';
                }
            }
            
        } else {
            echo "Failed to get Barangay.";
        }
    }
}
?>
