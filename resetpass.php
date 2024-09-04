<?php
require_once("class/db_connect.php");
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// reset_password_request.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    global $conn;

    // Check if the email exists in the database
    $query = "SELECT * FROM resident WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));

        // Store token in the database
        $query1 = "UPDATE resident SET reset_token = '$token' WHERE email = '$email'";
        mysqli_query($conn, $query1);

        // Send email with reset link using PHPMailer
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = 'smtp.porkbun.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'admin@capstone-petstat.wiki';
        $mail->Password = 'Asadawe123';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('admin@capstone-petstat.wiki', 'PetStat Admin');
        $mail->addAddress($email);

        $mail->Subject = 'Password Reset';
        $mail->Body = "Hi!,<br><br>
                There was a request to change your password!<br>
                If you did not make this request, please ignore this email.<br><br>
                Otherwise, please click this link to change your password: <a href='https://petstat.ink/reset_password.php?token=$token'>Reset Password</a><br><br>
        
        Yours,<br><br>
        The Petstat Team";

        $mail->isHTML(true);  // Set content type to HTML

        if ($mail->send()) {
            echo "Password reset instructions sent to your email.";
        } else {
            echo "Error sending email: " . $mail->ErrorInfo;
        }
    } else {
        echo "Email not found in our records.";
    }
}
?>


<!-- <head>
    <meta charset="UTF-8">
    <title>Password Reset Request</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>

<body>
    <h2>Forgot Password</h2>
    <form action="resetpass.php" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <button type="submit">Reset Password</button>
    </form>
    <form action="dashboardBAO.php" method="post">
        <button type="submit">Back</button>
    </form>
</body> -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PETSTAT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="petstaticon.png">
    <link rel="stylesheet" href="style.css">
    <?php include 'navbar.php'; ?>

</head>

<body>
    <div class="container">
        <div class="wrapper">
            <section class=" vh-100">
                <div class="container py-5">
                    <div class="row d-flex justify-content-center h-100">
                        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h2 class="mb-5">Reset Password</h2>
                                    <form action="resetpass.php" method="post">
                                        <div class="form-outline mb-4 text-start">
                                            <label class="fs-5 form-label" for="email">Email:</label>
                                            <input class="form-control" type="email" name="email" required>
                                        </div>
                                        <button class="btn btn-primary btn-sm" type="submit">Reset Password</button>
                                    </form>

                                    <!-- <div class="form-outline mb-4 text-start">
                                        <label for="password" class="fs-5 form-label">Password<span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password" id="password" required placeholder="Password" style="font-style: italic;">
                                    </div> -->
                                    <!-- <button class="btn btn-primary" type="submit">Login</button> -->

                                    <!-- <a href="index.php" class="btn btn-primary btn-lg">Home</a> -->

                                    <!-- </form> -->
                                    <!-- <div style="text-align: center;">
                                        <a href="resetpass.php">Reset Password</a>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>