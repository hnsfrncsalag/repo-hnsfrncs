<?php
require_once "db_connect.php";

class Notification {

    public function addRegNotif($brgyID, $residentID, $MAOID, $notifType, $notifDate, $notifMessage) {
        global $conn;
    
        try {
            $stmt = $conn->prepare("INSERT INTO `notification` (brgyID, residentID, MAOID, notifType, notifDate, notifMessage) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiiss", $brgyID, $residentID, $MAOID, $notifType, $notifDate, $notifMessage);
    
            if ($stmt->execute()) {
                $stmt->close();
                return true; // Case added successfully
            } else {
                $stmt->close();
                return "Failed to add the case to the database. Error: " . $conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function addResNotif($brgyID, $notifType, $notifDate, $notifMessage) {
        global $conn;
    
        try {
            $stmt = $conn->prepare("INSERT INTO `notification` (brgyID, notifType, notifDate, notifMessage) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $brgyID, $notifType, $notifDate, $notifMessage);
    
            if ($stmt->execute()) {
                $stmt->close();
                return true; // Case added successfully
            } else {
                $stmt->close();
                return "Failed to add the case to the database. Error: " . $conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function getNotifications($brgyID, $notifType) {
        global $conn;
    
        $stmt = $conn->prepare("SELECT * FROM `notification` WHERE notifType = ? AND brgyID = ?");
        $stmt->bind_param("ii", $notifType, $brgyID);
        $stmt->execute();
    
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $notifications = array();
    
            while ($row = $result->fetch_assoc()) {
                $notifications[] = $row;
            }
    
            return $notifications;
        }
    
        return array(); // Return an empty array if there are no notifications
    }
    
    public function addPetNotif($brgyID, $residentID, $MAOID, $notifType, $notifDate, $notifMessage) {
        global $conn;
    
        try {
            $stmt = $conn->prepare("INSERT INTO `notification` (brgyID, residentID, MAOID, notifType, notifDate, notifMessage) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiiss", $brgyID, $MAOID, $residentID, $notifType, $notifDate, $notifMessage);
    
            if ($stmt->execute()) {
                $stmt->close();
                return true; // Case added successfully
            } else {
                $stmt->close();
                return "Failed to add the case to the database. Error: " . $conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function addPetNotifRes($brgyID, $residentID, $notifType, $notifDate, $notifMessage) {
        global $conn;
    
        try {
            $stmt = $conn->prepare("INSERT INTO `notification` (brgyID, residentID, notifType, notifDate, notifMessage) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiss", $brgyID, $residentID, $notifType, $notifDate, $notifMessage);
    
            if ($stmt->execute()) {
                $stmt->close();
                return true; // Case added successfully
            } else {
                $stmt->close();
                return "Failed to add the case to the database. Error: " . $conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function addBiteNotif($brgyID, $residentID, $notifType, $notifDate, $notifMessage) {
        global $conn;
    
        try {
            $stmt = $conn->prepare("INSERT INTO `notification` (brgyID, residentID, notifType, notifDate, notifMessage) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiss", $brgyID, $residentID, $notifType, $notifDate, $notifMessage);
    
            if ($stmt->execute()) {
                $stmt->close();
                return true; // Case added successfully
            } else {
                $stmt->close();
                return "Failed to add the case to the database. Error: " . $conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function addBiteUnknown($brgyID, $residentID, $notifType, $notifDate, $notifMessage) {
        global $conn;
    
        try {
            $stmt = $conn->prepare("INSERT INTO `notification` (brgyID, residentID, notifType, notifDate, notifMessage) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiss", $brgyID, $residentID, $notifType, $notifDate, $notifMessage);
    
            if ($stmt->execute()) {
                $stmt->close();
                return true; // Case added successfully
            } else {
                $stmt->close();
                return "Failed to add the case to the database. Error: " . $conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function addDeathNotif($brgyID, $residentID, $notifType, $notifDate, $notifMessage) {
        global $conn;
    
        try {
            $stmt = $conn->prepare("INSERT INTO `notification` (brgyID, petID, residentID, notifType, notifDate, notifMessage) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiiss", $brgyID, $petID, $residentID, $notifType, $notifDate, $notifMessage);
    
            if ($stmt->execute()) {
                $stmt->close();
                return true; // Case added successfully
            } else {
                $stmt->close();
                return "Failed to add the case to the database. Error: " . $conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function addSusNotif($brgyID, $petID, $residentID, $notifType, $notifDate, $notifMessage) {
        global $conn;
    
        try {
            $stmt = $conn->prepare("INSERT INTO `notification` (brgyID, petID, residentID, notifType, notifDate, notifMessage) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiiss", $brgyID, $petID, $residentID, $notifType, $notifDate, $notifMessage);
    
            if ($stmt->execute()) {
                $stmt->close();
                return true; // Case added successfully
            } else {
                $stmt->close();
                return "Failed to add the case to the database. Error: " . $conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function addUnknownSusNotif($brgyID, $residentID, $notifType, $notifDate, $notifMessage) {
        global $conn;
    
        try {
            $stmt = $conn->prepare("INSERT INTO `notification` (brgyID, residentID, notifType, notifDate, notifMessage) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiss", $brgyID, $residentID, $notifType, $notifDate, $notifMessage);
    
            if ($stmt->execute()) {
                $stmt->close();
                return true; // Case added successfully
            } else {
                $stmt->close();
                return "Failed to add the case to the database. Error: " . $conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function addResidentStatus($residentID, $notifType, $notifDate, $notifMessage) {
        global $conn;
    
        try {
            $stmt = $conn->prepare("INSERT INTO `notification` (residentID, notifType, notifDate, notifMessage) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $residentID, $notifType, $notifDate, $notifMessage);
    
            if ($stmt->execute()) {
                $stmt->close();
                return true; // Case added successfully
            } else {
                $stmt->close();
                return "Failed to add the case to the database. Error: " . $conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function updateStatus($caseID, $brgyID, $MAOID, $residentID, $notifType, $notifDate, $notifMessage) {
        global $conn;
    
        try {
            $stmt = $conn->prepare("INSERT INTO `notification` (caseID, brgyID, MAOID, residentID, notifType, notifDate, notifMessage) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiiiss", $caseID, $brgyID, $MAOID, $residentID, $notifType, $notifDate, $notifMessage);
    
            if ($stmt->execute()) {
                $stmt->close();
                return true; // Case added successfully
            } else {
                $stmt->close();
                return "Failed to add the case to the database. Error: " . $conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function rabiesNotif($caseID, $brgyID, $residentID, $notifType, $notifDate, $notifMessage) {
        global $conn;
    
        try {
            $stmt = $conn->prepare("INSERT INTO `notification` (caseID, brgyID, residentID, notifType, notifDate, notifMessage) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiiss", $caseID, $brgyID, $residentID, $notifType, $notifDate, $notifMessage);
    
            if ($stmt->execute()) {
                $stmt->close();
                return true; // Case added successfully
            } else {
                $stmt->close();
                return "Failed to add the case to the database. Error: " . $conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function assignNotif ($residentID,  $barangay, $notifType, $notifDate, $notifMessage) {
        global $conn;
    
        try {
            $stmt = $conn->prepare("INSERT INTO `notification` (residentID, brgyID, notifType, notifDate, notifMessage) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiss", $residentID, $barangay, $notifType, $notifDate, $notifMessage);
    
            if ($stmt->execute()) {
                $stmt->close();
                return true; // Case added successfully
            } else {
                $stmt->close();
                return "Failed to add the case to the database. Error: " . $conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function revokeNotif ($residentID,  $barangay, $notifType, $notifDate, $notifMessage) {
        global $conn;
    
        try {
            $stmt = $conn->prepare("INSERT INTO `notification` (residentID, brgyID, notifType, notifDate, notifMessage) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiss", $residentID, $barangay, $notifType, $notifDate, $notifMessage);
    
            if ($stmt->execute()) {
                $stmt->close();
                return true; // Case added successfully
            } else {
                $stmt->close();
                return "Failed to add the case to the database. Error: " . $conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
    