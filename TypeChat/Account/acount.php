<?php
// Start the session
session_start();

// Connect to the database
$db = new SQLite3('../Database/database.db');

// Check if the user is not logged in, if yes then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {

    header("location: ../Login/Login.php");

    exit;

}

// Get user data
$name = $_SESSION["username"];
$result = $db->query("SELECT * FROM Login WHERE name='$name'");
$row = $result->fetchArray();
$email = $row['email'];
$userID = $row['id'];
$profilepic = $row['profilepic'];
$bio = $row['bio'];
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Acount</title>
    <!-- Icon Logo -->

    <link rel="icon" href="../Afbeeldingen/Logo.png">

    <!-- CSS -->

    <link rel="stylesheet" href="../CSS/Acount.css">

    <!-- Bootstrap CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <h1 class="text-center mt-5 mb-4">Account Details</h1>
            <div class="text-center">
                <img src="../Afbeeldingen/<?php echo $profilepic?>" alt="Profile Picture" class="img-fluid rounded-circle mb-3" style="max-width: 200px;">
                <br>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <p id="name"><?php echo $name?></p>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <p id="email"><?php echo $email?></p>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <p id="password">********</p>
            </div>

            <div class="mb-3">
                <label for="bio" class="form-label">Bio:</label>
                <p id="bio"><?php echo $bio?></p>
            </div>

            <div class="text-center">
                <a href="editaccount.php" class="btn btn-primary">Edit Account</a>
            </div>
        </div>
    </div>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</html>