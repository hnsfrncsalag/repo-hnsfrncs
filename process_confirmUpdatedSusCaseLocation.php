<?php
session_start();

require_once("class/resident.php");
require_once("class/geolocation.php");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  $latitude = $_POST['lat'];
  $longitude = $_POST['lng'];
  $geoID = $_POST['geoID'];

  $geolocation = new Geolocation();
  $newLocation = $geolocation->confirmUpdatedSusLocation($geoID, $latitude, $longitude);

  if ($newLocation === true) {
    echo '<script>alert("Update Location Successfully"); window.location.href = "./dashboardRabidCases.php?active-tab=1";</script>';
} else {
    echo '<script>alert("Failed to register resident: ' . $registrationResult . '"); window.location.href = "proccess_updateResidentLocation.php";</script>';
}
}
?>
