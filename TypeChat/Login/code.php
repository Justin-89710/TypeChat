<?php
// start session
session_start();

// connect to database
$db = new SQLite3('../Database/database.db');

// check if connection is successful
if (!$db) {
    die("Connection failed: " . $db->connect_error);
}

// check if code is correct
if (isset($_POST['submit'])) {
    $code = $_POST['code'];
    $email = $_SESSION['email'];

    // check if code is correct
    if ($code == $_SESSION['code']) {
        // send to reset page
        header("Location: reset.php");
    } else {
        $error = "Code is ongeldig.";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Wachtwoord vergeten</title>

    <!-- CSS -->

    <link rel="stylesheet" href="../CSS/Login.css">
    <link rel="icon" href="afbeeldingen/Logo.png">
<!-- Bootstrap CSS -->
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>

<section class="login-block">
    <div class="container">
        <div class="row">
            <div class="col-md-4 login-sec">
                <h2 class="text-center">Reset password now</h2>
                <form class="login-form" method="post">
                    <div class="form-group" style="margin-top: 30%">
                        <label for="exampleInputEmail1" class="text-uppercase">Code</label>
                        <input type="text" class="form-control" placeholder="" name="code">
                    </div>

                    <div class="form-check" style="margin-top: 25% ">
                        <button type="submit" class="btn btn-login float-right" name="submit">Submit</button>
                    </div>

                    <!-- description of what will happen-->
                    <div class="copy-text">put in the code that has been send to your mail.</div>
                    <!-- link to login page -->
                    <div class="copy-text-1">Already have an account? <a href="Login.php">Login</a></div>
                    <br>
                    <!-- error message -->
                    <div class="alert alert-danger"><?php if (isset($error)) {
                            echo $error;
                        } ?></div>
                </form>
            </div>
            <div class="col-md-8 banner-sec">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <img class="d-block img-fluid" src="../Afbeeldingen/MEnsen.jpg" alt="First slide">
                            <div class="carousel-caption d-none d-md-block">
                                <div class="banner-text">
                                    <h2>Type Chat</h2>
                                    <p>Starded as a joke now a full fletched socialmedia platform.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

</section>
</body>
</html>
