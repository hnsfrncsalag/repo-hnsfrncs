<?php
session_start();
include 'class/db_connect.php';
include 'class/notification.php';

// Check if the form is submitted
if (isset($_POST['mark_all_read'])) {

    // Check if residentID is available
    $residentID = isset($_SESSION['user']['residentID']) ? $_SESSION['user']['residentID'] : '';
    if (empty($residentID)) {
        die("ResidentID not found.");
    }

    // Prepare and execute SQL query to update isRead to 1 for all notifications of the current user
    $sql = "UPDATE notification SET isRead = 1 WHERE residentID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $residentID);
    $stmt->execute();

    // Check for errors
    if ($stmt->error) {
        die("Error: " . $stmt->error);
    }

    // Close the statement
    $stmt->close();

    // Redirect back to the notifications modal or any other page as desired
    header("Location: ./dashboard.php?active-tab=1");
    exit(); // Exit to prevent further execution
}


if (isset($_GET['mark_read'])) {
    // Check if notifID and isRead are provided
    if (isset($_GET['notifID']) && isset($_GET['isRead'])) {
        // Sanitize input values
        $notifID = intval($_GET['notifID']);
        $isRead = intval($_GET['isRead']);

        // Prepare and execute SQL query to update isRead for a specific notification
        $sql = "UPDATE notification SET isRead = ? WHERE notifID = ?";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ii", $isRead, $notifID);

        // Execute statement
        $stmt->execute();

        // Check for errors
        if ($stmt->error) {
            die("Error: " . $stmt->error);
        }

        // Close the statement
        $stmt->close();

        // Redirect based on notifType
        if (isset($_GET['notifType'])) {
            $notifType = intval($_GET['notifType']);
            switch ($notifType) {
                case 9:
                case 10:
                case 11:
                    header("Location: ./dashboard.php?active-tab=1");
                    exit();
                case 12:
                    header("Location: ./dashboard.php?active-tab=2");
                    exit();
                case 13:
                    header("Location: ./dashboard.php?active-tab=4");
                    exit();
                default:
                    // Redirect to a default location if notifType is not recognized
                    header("Location: ./dashboard.php?active-tab=1");
                    exit();
            }
        }
    } else {
        echo '<script>alert("No notification found."); window.location.href = "./dashboard.php?active-tab=1";</script>';
    }
}

if (isset($_GET['BAO_read'])) {
    // Check if notifID and isRead are provided
    if (isset($_GET['notifID']) && isset($_GET['BAOread'])) {
        // Sanitize input values
        $notifID = intval($_GET['notifID']);
        $BAOread = intval($_GET['BAOread']);

        // Prepare and execute SQL query to update isRead for a specific notification
        $sql = "UPDATE notification SET BAOread = ? WHERE notifID = ?";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ii", $BAOread, $notifID);

        // Execute statement
        $stmt->execute();

        // Check for errors
        if ($stmt->error) {
            die("Error: " . $stmt->error);
        }

        // Close the statement
        $stmt->close();

        // Redirect based on notifType
        if (isset($_GET['notifType'])) {
            $notifType = intval($_GET['notifType']);
            switch ($notifType) {
                case 0:
                    header("Location: ./dashboard1.php?active-tab=1");
                    exit();
                case 1:
                    header("Location: ./dashboard1pet.php?active-tab=1");
                    exit();
                case 2:
                    header("Location: ./dashboardBiteCases.php?active-tab=1");
                    exit();
                case 3:
                    header("Location: ./dashboardRabidCases.php?active-tab=1");
                    exit();
                case 4:
                    header("Location: ./dashboard1pet.php?active-tab=2");
                    exit();
                case 6:
                    header("Location: ./BAOpetdashboard.php?active-tab=2");
                    exit();
                case 7:
                    header("Location: ./BAOpetdashboard.php?active-tab=1");
                    exit();
                case 8:
                    header("Location: ./BAOpetdashboard.php?active-tab=4");
                    exit();
                default:
                    // Redirect to a default location if notifType is not recognized
                    header("Location: ./BAOpetdashboard.php?active-tab=1");
                    exit();
            }
        }
    } else {
        echo '<script>alert("No notification found."); window.location.href = "./BAOpetdashboard.php?active-tab=1";</script>';
    }
}


if (isset($_GET['MAO_read'])) {
    // Check if notifID and MAOread are provided
    if (isset($_GET['notifID']) && isset($_GET['MAOread'])) {
        // Sanitize input values
        $notifID = intval($_GET['notifID']);
        $adminRead = intval($_GET['MAOread']);

        // Prepare and execute SQL query to update adminRead for a specific notification
        $sql = "UPDATE notification SET adminRead = ? WHERE notifID = ?";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ii", $adminRead, $notifID);

        // Execute statement
        $stmt->execute();

        // Check for errors
        if ($stmt->error) {
            die("Error: " . $stmt->error);
        }

        // Close the statement
        $stmt->close();

        // Redirect based on notifType
        if (isset($_GET['notifType'])) {
            $notifType = intval($_GET['notifType']);
            switch ($notifType) {
                case 2:
                    header("Location: ./pin_location.php?barangay_bites=1");
                    exit();
                case 4:
                    header("Location: ./pin_location.php?barangay_suspected=1");
                    exit();
                default:
                    // Redirect to a default location if notifType is not recognized
                    header("Location: ./dashboardMAO.php");
                    exit();
            }
        }
    } else {
        echo '<script>alert("No notification found."); window.location.href = "./dashboardMAO.php";</script>';
    }
}


if (isset($_POST['mark_all_read_BAO'])) {
    // Check if residentID and brgyID are available in the session
    if (!isset($_SESSION['user']['residentID']) && !isset($_SESSION['user']['brgyID'])) {
        die("ResidentID or brgyID not found.");
    }

    // Get residentID and brgyID from session
    $residentID = isset($_SESSION['user']['residentID']) ? $_SESSION['user']['residentID'] : null;
    $brgyID = isset($_SESSION['user']['brgyID']) ? $_SESSION['user']['brgyID'] : null;

    // Prepare and execute SQL query to update isRead to 1 for all notifications of the current user
    $sql = "UPDATE notification SET BAOread = 1 WHERE (residentID = ? OR brgyID = ?)";
    $stmt = $conn->prepare($sql);

    // Check if the prepare statement succeeded
    if (!$stmt) {
        die("Prepare statement failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ii", $residentID, $brgyID);

    // Execute the query
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    // Check if any rows were affected
    $rows_updated = $stmt->affected_rows;

    // Close the statement
    $stmt->close();

    // Redirect back to the notifications modal or any other page as desired
    header("Location: ./BAOpetdashboard.php?active-tab=1");
    exit(); // Exit to prevent further execution
}


if (isset($_POST['mark_all_read_admin'])) {
    // Prepare and execute SQL query to update adminRead to 1 for notifications with notifType 2, 3, 6, and 8
    $sql = "UPDATE notification SET adminRead = 1 WHERE notifType IN (2, 3, 6, 8)";
    $stmt = $conn->prepare($sql);

    // Check if the prepare statement succeeded
    if (!$stmt) {
        die("Prepare statement failed: " . $conn->error);
    }

    // Execute the query
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    // Check if any rows were affected
    $rows_updated = $stmt->affected_rows;

    // Close the statement
    $stmt->close();

    // Redirect back to the notifications modal or any other page as desired
    header("Location: dashboardMAO.php");
    exit(); // Exit to prevent further execution
}
