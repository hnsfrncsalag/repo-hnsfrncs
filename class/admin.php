<?php
require_once "db_connect.php";

class Admin {
    public function adminLogin($email, $password) {
        global $conn;
        $stmt = $conn->prepare("SELECT name, email FROM admin WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $admin = $result->fetch_assoc();
    
        if ($admin) {
            unset($admin['password']); // Remove the password from the returned data
            return $admin;
        } else {
            return false;
        }
    }
    
        // function getAdminName($email, $password) {
        //     global $conn;
        //     $stmt = $conn->prepare("SELECT * FROM resident WHERE email = ?");
        //     $stmt->bind_param("s", $email);
        //     $stmt->execute();
        //     $result = $stmt->get_result();
        
        //     if ($result->num_rows === 0) {
        //         // User not found
        //         return false;
        //     }
        
        //     $user = $result->fetch_assoc();
        
        //     if (password_verify($password, $user['password'])) {
        //         // Password is correct, remove the password field and return the user data
        //         unset($user['password']);
        //         return $user;
        //     } else {
        //         // Incorrect password
        //         return false;
        //     }
        // }
    }