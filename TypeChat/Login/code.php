<?php
// start session
session_start();

// connect to database
$db = new SQLite3('../Database/database.db');

// check if connection is successful
if (!$db) {
    die("Connection failed: " . $db->connect_error);
}

// check if user is already logged in
if (isset($_SESSION["username"])) {
    header("Location: home.php");
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

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

</head>
<body>
<form method="post">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-container">
                    <h1>Wachtwoord resetten</h1>
                    <div class="form-group">
                        <label for="code">4 Diget code</label>
                        <input type="number" class="form-control" id="code" placeholder="Enter Code" name="code">
                    </div>
                    <button type="submit" class="btn btn-block" name="submit">Send mail</button>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>
