<?php

// Start the session

session_start();

// Connect to the database

$db = new SQLite3('../Database/database.db');

// Check if the user is already logged in, if yes then redirect him to welcome page

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {

    header("location: ../Account/acount.php");

    exit;

}

if (isset($_POST['submit'])) {
//login system with cookies for remember me and auto login
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = $_POST['Rememberme'];

    $result = $db->query("SELECT * FROM Login WHERE email='$email'");
    $row = $result->fetchArray();
    $hash = $row['password'];
    $id = $row['ID'];

    if (password_verify($password, $hash)) {
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $row['name'];
        $_SESSION["id"] = $id;

        if ($remember == 'on') {
            setcookie("email", $email, time() + (86400 * 30), "/");
            setcookie("password", $password, time() + (86400 * 30), "/");
        }

        header("location: ../Account/acount.php");
    } else {
        echo '<script>alert("Username or password is incorrect")</script>';
    }
}

//auto login
if (isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
    $email = $_COOKIE['email'];
    $password = $_COOKIE['password'];

    $result = $db->query("SELECT * FROM Login WHERE email='$email'");
    $row = $result->fetchArray();
    $hash = $row['password'];
    $id = $row['ID'];

    if (password_verify($password, $hash)) {
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $row['name'];
        $_SESSION["id"] = $id;

        header("location: ../Account/acount.php");
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

    <!-- Icon Logo -->

    <link rel="icon" href="../Afbeeldingen/Logo.png">

    <!-- link to css -->
    <link rel="stylesheet" href="../CSS/Login.css">
    <!-- Bootstrap CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <title>Login</title>

</head>

<body>
<section class="login-block">
    <div class="container">
        <div class="row">
            <div class="col-md-4 login-sec">
                <h2 class="text-center">Login Now</h2>
                <form class="login-form" method="post">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="text-uppercase">Email</label>
                        <input type="text" class="form-control" placeholder="" name="email">

                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1" class="text-uppercase">Password</label>
                        <input type="password" class="form-control" placeholder="" name="password">
                    </div>


                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="Rememberme">
                            <small>Remember Me</small>
                        </label>
                        <button type="submit" class="btn btn-login float-right" name="submit">Submit</button>
                    </div>
                    <!-- link naar register pagina -->
                    <div class="copy-text-2">Don't have an account yet? <a href="Register.php">Register</a></div>
                    <!-- link naar wachtwoord vergeten pagina -->
                    <div class="copy-text-1">Forgot your password? <a href="Wachtwoord_vergeten.php">Click here</a></div>

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