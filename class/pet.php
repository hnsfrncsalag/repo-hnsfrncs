<?php
require_once "db_connect.php";

class Pet {
    private $conn;


    public function addPet($residentID, $petType, $pname, $sex, $neutering, $color, $vetVac, $age, $regDate, $currentVac, $statusVac, $pdescription, $status) {
        global $conn;
    
        // Check if the pet already exists
        $existingPet = $this->getPetByDetails($residentID, $pname, $sex, $color, $age);
        if ($existingPet) {
            return true; // Pet already exists, return success
        }
    
        // Prepare the SQL statement for inserting into the "pet" table
        $stmt = $conn->prepare("INSERT INTO pet (residentID, petType, pname, sex, neutering, color, vetVac, age, regDate, currentVac, statusVac, pdescription, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
        if ($stmt) {
            // Bind the parameters and execute the query
            $stmt->bind_param("iisiisiissssi", $residentID, $petType, $pname, $sex, $neutering, $color, $vetVac, $age, $regDate, $currentVac, $statusVac, $pdescription, $status);
    
            if ($stmt->execute()) {
                // Check if the insertion was successful
                if ($stmt->affected_rows > 0) {
                    $stmt->close();
                    return true; // Pet added successfully
                }
            }
            $stmt->close();
        }
        return false; // Failed to add pet
    }
    
    public function addPetRes($residentID, $petType, $pname, $sex, $neutering, $color,  $vetVac, $age, $regDate, $statusVac, $pdescription) {
        global $conn;
    
        // Check if the pet already exists
        $existingPet = $this->getPetByDetails($residentID, $pname, $sex, $color, $age);
        if ($existingPet) {
            return true; // Pet already exists, return success
        }
    
        // Prepare the SQL statement for inserting into the "pet" table
        $stmt = $conn->prepare("INSERT INTO pet (residentID, petType, pname, sex, neutering, color,  vetVac, age, regDate, statusVac, pdescription) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
        if ($stmt) {
            // Bind the parameters and execute the query
            $stmt->bind_param("iisiisiisss", $residentID, $petType, $pname, $sex, $neutering, $color, $vetVac, $age, $regDate, $statusVac, $pdescription);
    
            if ($stmt->execute()) {
                // Check if the insertion was successful
                if ($stmt->affected_rows > 0) {
                    $stmt->close();
                    return true; // Pet added successfully
                }
            }
            $stmt->close();
        }
        return false; // Failed to add pet
    }
    
        private function getPetByDetails($residentID, $pname, $sex, $color, $age) {
            global $conn;
        
            $stmt = $conn->prepare("SELECT * FROM pet WHERE residentID = ? AND pname = ? AND sex = ? AND color = ? AND age = ?");
            $stmt->bind_param("issis", $residentID, $pname, $sex, $color, $age);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            }
        
            return null;
        }
    
    public function getPetsByResidentID($residentID) {
        global $conn;
    
        try {
            $stmt = $conn->prepare("SELECT p.*, r.*, v.*, p.petID, p.pname
            FROM resident AS r
            NATURAL JOIN pet AS p
            LEFT JOIN (
                SELECT petID, MAX(lastVaccination) AS maxlastVaccination, lastVaccination
                FROM vaccination
                GROUP BY petID
            ) AS v ON p.petID = v.petID
            WHERE r.residentID = ?
            ORDER BY currentVac DESC");
            $stmt->bind_param("i", $residentID);
            $stmt->execute();
            $result = $stmt->get_result();
    
            // Fetch all pets as an associative array
            $pets = $result->fetch_all(MYSQLI_ASSOC);
    
            $stmt->close();
            return $pets;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    
    public function getAllNewPets($brgyID) {
        global $conn;
    
        try {
            $query = "SELECT p.*, r.*, v.*, p.petID
            FROM resident AS r
            NATURAL JOIN pet AS p
            LEFT JOIN (
                SELECT petID, MAX(lastVaccination) AS maxlastVaccination, lastVaccination
                FROM vaccination
                GROUP BY petID
            ) AS v ON p.petID = v.petID
            WHERE p.status = 0 AND brgyID = ?
            ORDER BY p.currentVac DESC";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $brgyID);
            $stmt->execute();
            $result = $stmt->get_result();
    
            $newPets = $result->fetch_all(MYSQLI_ASSOC);
            
            $stmt->close();
            return $newPets;
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public function getAllValidPets($brgyID) {
        global $conn;
    
        try {
            $query = "SELECT p.*, r.*, v.*, p.petID, p.vetVac
            FROM resident AS r
            NATURAL JOIN pet AS p
            LEFT JOIN (
                SELECT petID, MAX(lastVaccination) AS maxlastVaccination, lastVaccination
                FROM vaccination
                GROUP BY petID
            ) AS v ON p.petID = v.petID
            WHERE p.status = 1 AND p.Health = 0 AND brgyID = ?
            ORDER BY p.currentVac DESC";
    
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $brgyID);
            $stmt->execute();
            $result = $stmt->get_result();
    
            $validPets = $result->fetch_all(MYSQLI_ASSOC);
            
            $stmt->close();
            return $validPets;
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    // public function getAllValidPetByBarangay($brgyID)
    // {
    //     global $conn;

    //     $stmt = $conn->prepare("SELECT `R`.`geoID`, `P`.*, `G`.`latitude`, `G`.`longitude`
    //     FROM `resident` AS `R` 
    //         LEFT JOIN `pet` AS `P` ON `P`.`residentID` = `R`.`residentID` 
    //         LEFT JOIN `geolocation` AS `G` ON `R`.`geoID` = `G`.`geoID`
    //     WHERE `resident`.`brgyID` = ? AND `pet`.`statusVac` = 1 AND p.Health = 0;");
    //     $stmt->bind_param("i", $brgyID);
    //     $stmt->execute();

    //     return $stmt->get_result(); // Return the result set, not just a single row
    // }
    public function getAllRejectedPets($brgyID) {
        global $conn;
    
        try {
            $query = "SELECT p.*, r.*, v.*, p.petID
            FROM resident AS r
            NATURAL JOIN pet AS p
            LEFT JOIN (
                SELECT petID, MAX(lastVaccination) AS maxlastVaccination, lastVaccination
                FROM vaccination
                GROUP BY petID
            ) AS v ON p.petID = v.petID
            WHERE p.status = 2 AND brgyID = ?
            ORDER BY  p.currentVac DESC";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $brgyID);
            $stmt->execute();
            $result = $stmt->get_result();
    
            $rejectedPets = $result->fetch_all(MYSQLI_ASSOC);
            
            $stmt->close();
            return $rejectedPets;
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public function updatePetStatus($petID, $status){
        global $conn;

        $stmt = $conn->prepare("UPDATE pet SET status = ? WHERE petID = ?");
        $stmt->bind_param("ii", $status, $petID);

        try {
            if ($stmt->execute()) {
                // Update successful
                return true;
            } else {
                // Failed to update user status
                return "Failed to update user status: " . $stmt->error;
            }
        } catch (Exception $e) {
            // Handle the exception
            return "Failed to update user status: " . $e->getMessage();
        } finally {
            $stmt->close();
        }
    }
    public function getRegistries($brgyID) {
        global $conn;
    
        try {
            $stmt = $conn->prepare("SELECT p.*, r.*, v.*, b.*, p.petID
            FROM resident AS r
            NATURAL JOIN pet AS p
            LEFT JOIN (
                SELECT petID, MAX(lastVaccination) AS maxlastVaccination, lastVaccination
                FROM vaccination
                GROUP BY petID
            ) AS v ON p.petID = v.petID
            LEFT JOIN barangay AS b ON r.brgyID = b.brgyID
            WHERE p.status = 1 and r.brgyID = ? ;
            ");
            $stmt->bind_param("i", $brgyID);
            $stmt->execute();
            
    
            $result = $stmt->get_result();
    
            // Fetch the data into an associative array
            $registries = [];
            while ($row = $result->fetch_assoc()) {
                $registries[] = $row;
            }
    
            $stmt->close();
            return $registries;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function cancelReg($petID) {
        global $conn;
    
        try {
            // Start a transaction
            $conn->begin_transaction();
    
            // Delete records from the vaccination table first
            $stmtDeleteVaccination = $conn->prepare("DELETE FROM vaccination WHERE petID = ?");
            if (!$stmtDeleteVaccination) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
            $stmtDeleteVaccination->bind_param("i", $petID);
            $stmtDeleteVaccination->execute();
            // Check for errors after execution
            if ($stmtDeleteVaccination->error) {
                throw new Exception("Error deleting vaccination records: " . $stmtDeleteVaccination->error);
            }
            $stmtDeleteVaccination->close();
    
            // Delete records from the notification table
            $stmtDeleteNotification = $conn->prepare("DELETE FROM notification WHERE petID = ?");
            if (!$stmtDeleteNotification) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
            $stmtDeleteNotification->bind_param("i", $petID);
            $stmtDeleteNotification->execute();
            // Check for errors after execution
            if ($stmtDeleteNotification->error) {
                throw new Exception("Error deleting notification records: " . $stmtDeleteNotification->error);
            }
            $stmtDeleteNotification->close();
    
            // Then, delete the record from the pet table
            $stmtDeletePet = $conn->prepare("DELETE FROM pet WHERE petID = ?");
            if (!$stmtDeletePet) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
            $stmtDeletePet->bind_param("i", $petID);
            $stmtDeletePet->execute();
            // Check for errors after execution
            if ($stmtDeletePet->error) {
                throw new Exception("Error deleting pet record: " . $stmtDeletePet->error);
            }
            $stmtDeletePet->close();
    
            // Commit the transaction
            $conn->commit();
    
            return true;
        } catch (Exception $e) {
            // An error occurred, rollback the transaction
            $conn->rollback();
            return "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
        }
    }
    
    
    public function updateVacStatus($petID, $currentVac, $statusVac) {
        global $conn;
    
        // Start a transaction
        $conn->begin_transaction();
    
        try {
            // Retrieve the current vaccination data
            $stmtSelectCurrentVac = $conn->prepare("SELECT * FROM pet WHERE petID = ?");
            $stmtSelectCurrentVac->bind_param("i", $petID);
            $stmtSelectCurrentVac->execute();
            $result = $stmtSelectCurrentVac->get_result();
            $currentVacData = $result->fetch_assoc();
            $stmtSelectCurrentVac->close();
    
            // Insert the current vaccination data into the history table
            $stmtInsertHistory = $conn->prepare("INSERT INTO vaccination (petID, lastVaccination) VALUES (?, ?)");
            $stmtInsertHistory->bind_param("is", $currentVacData['petID'], $currentVacData['currentVac']);
            if (!$stmtInsertHistory->execute()) {
                throw new Exception("Insert into vaccination failed: " . $stmtInsertHistory->error);
            }
            $stmtInsertHistory->close();
    
            // Update the current vaccination status in the main table
            $stmtUpdateVacStatus = $conn->prepare("UPDATE pet SET currentVac = ?, statusVac = ? WHERE petID = ?");
            $stmtUpdateVacStatus->bind_param("sii", $currentVac, $statusVac, $petID);
            if (!$stmtUpdateVacStatus->execute()) {
                throw new Exception("Update pet table failed: " . $stmtUpdateVacStatus->error);
            }
            $stmtUpdateVacStatus->close();
    
            // Commit the transaction
            if (!$conn->commit()) {
                throw new Exception("Transaction commit failed: " . $conn->error);
            }
    
            return true;
        } catch (Exception $e) {
            // An error occurred, rollback the transaction
            $conn->rollback();
            return "Error: " . $e->getMessage();
        }
    }
    public function editPet($petID, $petType, $pname, $sex, $neutering, $color, $age, $pdescription) {
        global $conn;
    
        $stmt = $conn->prepare("UPDATE pet SET petType = ?, pname = ?, sex = ?, Neutering = ?, color = ?, age = ?, pdescription = ? WHERE petID = ?");
        // Changed the parameter order in bind_param to match the order in the SQL query
        $stmt->bind_param("issisisi", $petType, $pname, $sex, $neutering, $color, $age, $pdescription, $petID);
    
        try {
            if ($stmt->execute()) {
                // Update successful
                return true;
            } else {
                // Failed to update pet
                return "Failed to update pet: " . $stmt->error;
            }
        } catch (Exception $e) {
            // Handle the exception
            return "Failed to update pet: " . $e->getMessage();
        } finally {
            $stmt->close();
        }
    }
    

    public function getVaccinations($petID){
        global $conn;

        $query = "SELECT p.*, r.*, v.*, p.petID
        FROM resident AS r
        NATURAL JOIN pet AS p
        LEFT JOIN (
            SELECT petID, MAX(lastVaccination) AS maxlastVaccination, lastVaccination
            FROM vaccination
            GROUP BY petID
        ) AS v ON p.petID = v.petID
        WHERE petID = ?
        ORDER BY v.maxlastVaccination DESC
        limit 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $petID);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if (!$result) {
            return false; // Return false if the query fails
        }
    
        return $result;
    }
    public function getVacByPetID($residentID){
        global $conn;

        $query = "SELECT p.*, r.*, v.*, p.petID
        FROM resident AS r
        NATURAL JOIN pet AS p
        LEFT JOIN (
            SELECT petID, lastVaccination
            FROM vaccination
            GROUP BY petID
        ) AS v ON p.petID = v.petID
        WHERE residentID = ?
        ORDER BY lastVaccination DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $petID);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if (!$result) {
            return false; // Return false if the query fails
        }
    
        return $result;
    }

    public function addRemarks($petID, $BAOremarks){
        global $conn;

        $stmt = $conn->prepare("UPDATE pet SET BAOremarks = ? WHERE petID = ?");
        $stmt->bind_param("si", $BAOremarks, $petID);

        try {
            if ($stmt->execute()) {
                // Update successful
                return true;
            } else {
                // Failed to update user status
                return "Failed to update user status: " . $stmt->error;
            }
        } catch (Exception $e) {
            // Handle the exception
            return "Failed to update user status: " . $e->getMessage();
        } finally {
            $stmt->close();
        }
    }
    // public function searchDesc($query) {
    //   global $conn;

    //     $sql = "SELECT * FROM pet WHERE pdescription LIKE '%$query%'";
    //     $result = $conn->query($sql);

    //     if ($result->num_rows > 0) {
    //         return $result->fetch_all(MYSQLI_ASSOC);
    //     } else {
    //         return [];
    //     }
    // }
    function countPet($brgyID)
    {
        global $conn;

        // Query to count cases per month for a specific brgyID
        $sql = "SELECT
        EXTRACT(MONTH FROM currentVac) AS month,
        EXTRACT(YEAR FROM currentVac) AS year,
        COUNT(*) AS count_per_month
        FROM `pet` AS `P` 
        LEFT JOIN `resident` AS `R` ON `P`.`residentID` = `R`.`residentID`
        WHERE `R`.`brgyID` = ? AND statusVac = 1 AND YEAR(currentVac) = YEAR(CURRENT_DATE)
        GROUP BY 
                EXTRACT(MONTH FROM currentVac), EXTRACT(YEAR FROM currentVac)
            ORDER BY 
                year, month;";

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $brgyID); // "i" indicates an integer parameter

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the result into an array
        $counts = [];
        while ($row = $result->fetch_assoc()) {
            $counts[] = $row;
        }

        // Close the prepared statement
        $stmt->close();

        return $counts;
    }
    function countAllPet($brgyID)
    {
        global $conn;

        // Query to count cases per month for a specific brgyID
        $sql = "SELECT
        EXTRACT(MONTH FROM currentVac) AS month,
        EXTRACT(YEAR FROM currentVac) AS year,
        COUNT(*) AS count_per_month
        FROM `pet` AS `P` 
        LEFT JOIN `resident` AS `R` ON `P`.`residentID` = `R`.`residentID`
        WHERE `R`.`brgyID` = ? AND YEAR(currentVac) = YEAR(CURRENT_DATE)
        GROUP BY 
                EXTRACT(MONTH FROM currentVac), EXTRACT(YEAR FROM currentVac)
            ORDER BY 
                year, month";


        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $brgyID); // "i" indicates an integer parameter

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the result into an array
        $counts1 = [];
        while ($row = $result->fetch_assoc()) {
            $counts1[] = $row;
        }

        // Close the prepared statement
        $stmt->close();

        return $counts1;
    }
    function countAllPetPerYear($brgyID)
    {
        global $conn;

        // Query to count cases per month for a specific brgyID
        $sql = "SELECT 
    EXTRACT(YEAR FROM currentVac) AS year,
    COUNT(*) AS count_per_year
    FROM `pet` AS `P` 
        LEFT JOIN `resident` AS `R` ON `P`.`residentID` = `R`.`residentID`
        WHERE `R`.`brgyID` = ? AND YEAR(currentVac) = YEAR(CURRENT_DATE)
GROUP BY 
    EXTRACT(YEAR FROM currentVac)
ORDER BY 
    year";

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $brgyID); // "i" indicates an integer parameter

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the result into an array
        $counts2 = [];
        while ($row = $result->fetch_assoc()) {
            $counts2[] = $row;
        }

        // Close the prepared statement
        $stmt->close();

        return $counts2;
    }
    function countAllValidPetPerYear($brgyID)
    {
        global $conn;

        // Query to count cases per month for a specific brgyID
        $sql = "SELECT 
    EXTRACT(YEAR FROM currentVac) AS year,
    COUNT(*) AS count_per_year
    FROM `pet` AS `P` 
        LEFT JOIN `resident` AS `R` ON `P`.`residentID` = `R`.`residentID`
        WHERE `R`.`brgyID` = ? AND statusVac = 1 AND YEAR(currentVac) = YEAR(CURRENT_DATE)
GROUP BY 
    EXTRACT(YEAR FROM currentVac)
ORDER BY 
    year";

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $brgyID); // "i" indicates an integer parameter

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the result into an array
        $counts3 = [];
        while ($row = $result->fetch_assoc()) {
            $counts3[] = $row;
        }

        // Close the prepared statement
        $stmt->close();

        return $counts3;
    }

    function updateHealthStatus($petID, $Health) {
        global $conn;
    
        // Prepare the SQL statement
        $stmt = $conn->prepare("UPDATE pet SET Health = ? WHERE petID = ?");
        
        // Bind the parameters
        $stmt->bind_param("ii", $Health, $petID);
        
        // Execute the update query
        $stmt->execute();
        
        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            // Update successful
            $stmt->close();
            return true;
        } else {
            // Update failed
            $stmt->close();
            return false;
        }
    }
    
}
?>