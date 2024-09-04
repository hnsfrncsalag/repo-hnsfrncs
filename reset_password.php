<?php
require_once("class/db_connect.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PETSTAT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="petstaticon.png">
    <link rel="stylesheet" href="style.css">
    <?php include 'navbar.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <style>

    </style>
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

                                    <?php
                                    global $conn;
                                    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["token"])) {
                                        $token = $_GET["token"];

                                        // Check if the token exists in the database
                                        $query = "SELECT * FROM resident WHERE reset_token = '$token'";
                                        $result = mysqli_query($conn, $query);  // Assuming you are using mysqli

                                        if ($result && mysqli_num_rows($result) > 0) {
                                            // Token is valid, allow the user to reset the password
                                    ?>
                                            <form action=" reset_password.php?token=<?php echo $token; ?>" method="post">
                                                <div class="form-outline mb-4 text-start">
                                                    <label class="fs-5 form-label" for="password">New Password:</label>
                                                    <input class="form-control type=" password" name="password" required>
                                                </div>
                                                <button class="btn btn-primary mx-auto btn-sm mb-4" type="submit">Reset Password</button>
                                            </form>

                                            <form action="resetpass.php" method="post">
                                                <button class="btn btn-secondary btn-sm" type="submit">Back</button>
                                            </form>

                                    <?php
                                        } else {
                                            echo "Invalid token or token expired.";
                                        }
                                    } else {
                                        echo "Token not provided.";
                                    }

                                    // Handle form submission
                                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["password"])) {
                                        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
                                        $token = $_GET["token"];

                                        // Update the user's password and clear the reset token
                                        $query = "UPDATE resident SET password = '$password', reset_token = NULL WHERE reset_token = '$token'";
                                        mysqli_query($conn, $query);

                                        echo "Password reset successful. You can now <a href='login.php'>login</a> with your new password.";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>


</html>