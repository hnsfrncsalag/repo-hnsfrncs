<?php 

$submit = $_POST["text"];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    .col-md-6 {
        /* margin-left: 200px; */
        text-align: center;
    }
</style>
<body>
    <div class="col-md-6 search-form" style="padding:13px 0;">
    <form action="">
    <label for="text">Name:</label>
    <input type="text" id="text" name="text">
    <button>Submit</button>
    </form>
    </div>
</body>
</html>