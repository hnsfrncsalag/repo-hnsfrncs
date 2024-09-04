<?php
require_once('class/pet.php');
require_once('class/cases.php');
require_once('class/notification.php');

$errorMessage = "";
if (isset($_POST['cancel_reg'])) {
    if (isset($_POST['petID'])) {
        $pet = new Pet();  
        $notif = new Notification(); 
        $petID = $_POST['petID'];

        // Call the function to cancel the pet registration
        $result = $pet->cancelReg($petID);
        // $notif = $notif->cancelPet($petID);

        if ($result === true) {
                echo '<script>alert("Pet Registration has Cancelled"); window.location.href = "./dashboard.php?active-tab=1";</script>';
        }else {
                echo '<script>alert("Cancellation Error! There are existing report for this pet."); window.location.href = "./dashboard.php?active-tab=1";</script>';
            }
    } else {
        $errorMessage = "Missing POST data for canceling the pet registration.";
    }
} else if (isset($_POST['cancel_bite'])) {
    if (isset($_POST['caseID'])) {
        $cases = new Cases();
        $caseID = $_POST['caseID'];

        // Call the function to cancel the bite registration
        $result2 = $cases->cancelBite($caseID);

        if ($result2) {
                echo '<script>alert("Case has been cancelled"); window.location.href = "./dashboard.php?active-tab=2";</script>';
            } else {
                echo '<script>alert("Invalid user type"); window.location.href = "./dashboard.php?active-tab=2";</script>';
            }
        } else {
            $errorMessage = "Missing POST data for canceling the bite case.";
        }
    } else if (isset($_POST['cancel_death'])) {
        if (isset($_POST['caseID'])) {
            $cases = new Cases();
            $caseID = $_POST['caseID'];
    
            // Call the function to cancel the bite registration
            $result2 = $cases->cancelBite($caseID);
    
            if ($result2) {
                    echo '<script>alert("Case has been cancelled"); window.location.href = "./dashboard.php?active-tab=3";</script>';
                } else {
                    echo '<script>alert("Invalid user type"); window.location.href = "./dashboard.php?active-tab=3";</script>';
                }
            }else {
                $errorMessage = "Missing POST data for canceling the bite case.";
            }
        } else if (isset($_POST['cancel_sus'])) {
            if (isset($_POST['caseID'])) {
                $cases = new Cases();
                $caseID = $_POST['caseID'];
        
                // Call the function to cancel the bite registration
                $result2 = $cases->cancelBite($caseID);
        
                if ($result2) {
                        echo '<script>alert("Case has been cancelled"); window.location.href = "./dashboard.php?active-tab=4";</script>';
                    } else {
                        echo '<script>alert("Invalid user type"); window.location.href = "./dashboard.php?active-tab=4";</script>';
                    }
                }
            } else {
                $errorMessage = "Missing POST data for canceling the bite case.";
            }
    


// Handle any other code or error messages if needed
?>
