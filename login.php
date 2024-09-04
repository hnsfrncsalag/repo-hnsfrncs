<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
                                    <h2 class="mb-5">Sign in</h2>
                                    <form method="POST" action="process_login.php">
                                        <div class="form-outline mb-4 text-start">
                                            <label for="email" class="fs-5 form-label">Email<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" id="email" required placeholder="E-mail Address" style="font-style: italic;">
                                        </div>

                                        <div class="form-outline mb-4 text-start">
                                            <label for="password" class="fs-5 form-label">Password<span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="password" id="password" required placeholder="Password" style="font-style: italic;">
                                        </div>
                                        <button class="btn btn-primary" type="submit">Login</button>
                                        
                                        <!-- <a href="index.php" class="btn btn-primary btn-lg">Home</a> -->

                                    </form>
                                    <div style="text-align: center;">
                                            <a href="resetpass.php">Reset Password</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>




    <!-- <div class="container">
        <div class="row">
            <div class="col">
                <p class="p-3 fs-5"> Login </p>
            </div>

        </div>
        <form method=" POST" action="process_login.php">
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <div class="btn-container">
                <button type="submit" class="btn btn-primary btn-lg">Login</button>
                <a href="index.php" class="btn btn-primary btn-lg">Home</a>
            </div>
        </form>
    </div> -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>