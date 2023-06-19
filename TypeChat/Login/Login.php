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

// Login script

if (isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

        // Check if username exists
        $stmt = $db->prepare("SELECT * FROM Login WHERE email = :email");
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray();

        if ($row) {
            // Check if password is correct
            if (password_verify($password, $row['password'])) {
                // Password is correct, so start a new session
                session_start();

                // Store data in session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $row['ID'];
                $_SESSION["username"] = $row['name'];

                // Redirect user to welcome page
                header("location: ../Account/acount.php");
            } else {
                // Display an error message if password is not valid
                $error = "The password you entered was not valid.";
            }
        } else {
            // Display an error message if username doesn't exist
            $error = "No account found with that username.";
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

    <!-- CSS -->

    <link rel="stylesheet" href="../CSS/Login.css">

    <!-- Bootstrap CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <title>Login</title>

</head>

<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-container">
                <h1>Login</h1>
                <form method="POST">
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
                    </div>
                    <?php if (isset($error)) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                    </div>
                    <button type="submit" class="btn btn-block" name="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

<!-- Bootstrap scripts -->

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>

</html>