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
    $profilepicid = 3;

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
            } else {
                // hash password
                $password = password_hash($password, PASSWORD_DEFAULT);
                // insert user into database
                $db->exec("INSERT INTO Login (name, email, password, profilepicid) VALUES ('$name', '$email', '$password', '$profilepicid')");
                // redirect to login page
                header("Location: login.php");
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

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-container">
                <h1>Register</h1>
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                    </div>
                    <button type="submit" class="btn btn-block" name="submit">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>

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
</script>

<!-- Dark mode script -->

<script>
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