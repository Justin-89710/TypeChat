<?php

// start session

session_start();

// check if user is already logged in

if (isset($_SESSION["username"])) {
    header("Location: home.php");
}

// connect to database

$db = new SQLite3('../Database/database.db');

// check if connection is successful

if (!$db) {
    die("Connection failed: " . $db->connect_error);
}


// Make a new user

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $password = $_POST['password'];

    // check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email is ongeldig.";
    } else {
        // check if email is already in use
        $result = $db->query("SELECT * FROM Login WHERE email='$email'");
        if ($result->fetchArray()) {
            $error = "Email is al in gebruik.";
        } else {
            // check if username is already in use
            $result = $db->query("SELECT * FROM Login WHERE name='$name'");
            if ($result->fetchArray()) {
                $error = "Username is al in gebruik.";
                //if there is an error, show it
            } elseif (isset($error)) {
                echo "<div class='alert alert-danger' role='alert'>" . $error . "</div>";
            } elseif (strlen($password) < 6) {
                $error = "Wachtwoord moet minimaal 6 tekens lang zijn.";
            } elseif (!preg_match("#[0-9]+#", $password)) {
                $error = "Wachtwoord moet minimaal 1 nummer bevatten.";
            } elseif (!preg_match("#[A-Z]+#", $password)) {
                $error = "Wachtwoord moet minimaal 1 hoofdletter bevatten.";
            } elseif (!preg_match("#[a-z]+#", $password)) {
                $error = "Wachtwoord moet minimaal 1 kleine letter bevatten.";
            } else {
                // if there is no error, make a new user
                $password = password_hash($password, PASSWORD_DEFAULT);
                $db->exec("INSERT INTO Login (email, name, password) VALUES ('$email', '$name', '$password')");

                //set profile pic to default
                $result = $db->query("SELECT * FROM Login WHERE email='$email'");
                $row = $result->fetchArray();

                //Login the user
                $result = $db->query("SELECT * FROM Login WHERE email='$email'");
                $row = $result->fetchArray();
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $row['ID'];
                $_SESSION["username"] = $row['name'];
                if ($row['profile_pic'] == null) {
                    $db->exec("UPDATE Login SET profilepic = 'default.jpg' WHERE email='$email'");
                }
                if ($row['bio'] == null) {
                    $db->exec("UPDATE Login SET bio = 'Dit is mijn bio!' WHERE email='$email'");
                }
                if (isset($error)){
                    echo "<div class='alert alert-danger' role='alert'>" . $error . "</div>";
                } else {
                    header("Location: ../home/home.php");
                }
            }
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

    <!-- Icon Logo -->

    <link rel="icon" href="afbeeldingen/Logo.png">

    <title>Register</title>

    <!-- CSS -->

    <link rel="stylesheet" href="../CSS/Login.css">

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
                <h2 class="text-center">Register Now</h2>
                <form class="login-form" method="post">
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="text-uppercase">Email</label>
                        <input type="text" class="form-control" placeholder="" name="email">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="text-uppercase">Name</label>
                        <input type="text" class="form-control" placeholder="" name="name">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1" class="text-uppercase">Password</label>
                        <input type="password" class="form-control" placeholder="" name="password">
                    </div>


                    <div class="form-check">
                        <button type="submit" class="btn btn-login float-right" name="submit">Submit</button>
                    </div>
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

<script>

    //check username

    let timerId;

    function checkUsername() {
        let username = document.getElementById("username").value;
        clearTimeout(timerId);
        if (username.length >= 4) {
            timerId = setTimeout(function() {
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "check_username.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        let response = JSON.parse(xhr.responseText);
                        let message = document.getElementById("message");
                        let status = document.querySelector(".username-status");
                        if (response.error) {
                            message.innerHTML = response.error;
                            message.style.color = "red";
                            status.innerHTML = "";
                        } else if (response.available) {
                            message.innerHTML = "Username is available";
                            message.style.color = "green";
                            status.innerHTML = "";
                        } else {
                            message.innerHTML = "Username is taken";
                            message.style.color = "red";
                            status.innerHTML = "";
                        }
                    }
                };
                xhr.send("username=" + username);
            }, 1000);
        }
    }

    document.getElementById("username").addEventListener("input", checkUsername);

    for (let i = 0; i < document.querySelectorAll(".form-control").length; i++) {
        document.querySelectorAll(".form-control")[i].addEventListener("input", function() {
            if (document.querySelectorAll(".form-control")[i].value.length > 0) {
                document.querySelectorAll(".form-control")[i].classList.add("not-empty");
            } else {
                document.querySelectorAll(".form-control")[i].classList.remove("not-empty");
            }
        });
    }

    // Check if user's browser is in dark mode
    const isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

    // If user's browser is in dark mode, set the page to dark mode
    if (isDarkMode) {
        document.body.classList.add('dark-mode');
        document.querySelector('.box').classList.add('dark-mode');
        document.querySelector('form').classList.add('dark-mode');
    }


</script>
</body>
</html>