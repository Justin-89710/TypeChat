<?php
// start session
session_start();

// connect to database
$db = new SQLite3('../Database/database.db');

// check if connection is successful
if (!$db) {
    die("Connection failed: " . $db->connect_error);
}

// change password
if (isset($_POST['submit'])) {
    // get password
    $password = $_POST['newpassword'];
    // check if password is valid
    if (strlen($password) < 8) {
        $error = "Wachtwoord moet minimaal 8 tekens lang zijn.";
    } elseif (!preg_match("#[0-9]+#", $password)) {
        $error = "Wachtwoord moet minimaal 1 nummer bevatten.";
    } elseif (!preg_match("#[A-Z]+#", $password)) {
        $error = "Wachtwoord moet minimaal 1 hoofdletter bevatten.";
    } elseif (!preg_match("#[a-z]+#", $password)) {
        $error = "Wachtwoord moet minimaal 1 kleine letter bevatten.";
    } else {
        // check if password is the same as the confirmation password
        if ($_POST['newpassword'] != $_POST['confirmpassword']) {
            $error = "Wachtwoorden komen niet overeen.";
        } else {
            // hash password
            $password = password_hash($password, PASSWORD_DEFAULT);
            // get email
            $email = $_SESSION['email'];
            // update password
            $db->exec("UPDATE Login SET password='$password' WHERE email='$email'");
            // redirect to login page
            header("Location: ../home/home.php");
        }
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
    <title>Wachtwoord reseten</title>

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
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="text-uppercase">New password</label>
                        <input type="password" class="form-control" placeholder="" name="newpassword">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1" class="text-uppercase">Confirm password</label>
                        <input type="password" class="form-control" placeholder="" name="confirmpassword">
                    </div>

                    <!-- error message -->

                    <?php if (isset($error)) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                    <?php } ?>

                    <div class="form-check" style="margin-top: 25% ">
                        <button type="submit" class="btn btn-login float-right" name="submit">Submit</button>
                    </div>

                    <!-- description of what will happen-->
                    <div class="copy-text">put in the code that has been send to your mail.</div>
                    <!-- link to login page -->
                    <div class="copy-text-1">Already have an account? <a href="Login.php">Login</a></div>

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