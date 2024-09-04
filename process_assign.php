<?php
require_once ('class/resident.php');

if (isset($_POST['Assign'])) {
    $resident = new Resident();
    $residentID = $_POST['residentID'];
    $brgyID = $_POST['brgyID'];
    $page = $_POST['page'];

    $isOfficer = $resident->isResidentOfficerInBarangay($residentID, $brgyID);

    if ($isOfficer) {
        echo '<script>alert("This resident is already as an officer."); window.location.href = "assign_officer.php";</script>';
        exit();
    }

    $result = $resident->assignOfficer($residentID, $brgyID);
    // $getBrgyID = $barangay->specgetBrgy($brgyID);
    if ($result) {
        echo '<script>alert("Resident has been successfully assigned as an officer. ' . $brgyID . '"); window.location.href = "assign_officer.php?barangay=' . $brgyID . '&page=' . $page .'#ValidResidents";</script>';
        exit();
} else {
    $errorMessage = "Failed to assign resident";
    echo '<script>alert("' . $errorMessage . '"); window.location.href = "assign_officer.php?brgyID=' . $brgyID . '";</script>';
    exit();
}


}
?>
