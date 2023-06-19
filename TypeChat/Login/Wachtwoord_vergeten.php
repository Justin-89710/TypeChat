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

if (isset($_POST['submit'])){
    //get email
    $email = $_POST['email'];

    //check if email exists
    $stmt = $db->prepare("SELECT * FROM Login WHERE email = :email");
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray();

    //generate random 4 digit code
    $code = rand(1000, 9999);

    //send mail with this code
    $to = $email;
    $subject = "Wachtwoord vergeten";
    $message = "Uw code is: " . $code;
    $headers = "From: Team TypeChat";

    mail($to, $subject, $message, $headers);

    //store code in session
    $_SESSION['code'] = $code;

    //store email in session
    $_SESSION['email'] = $email;

    //send to code page
    header("Location: code.php");
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

        <title>Wachtwoord vergeten</title>

        <!-- Stylesheets -->

        <link rel="stylesheet" href="../CSS/Login.css">

        <!-- Bootstrap CSS -->

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    </head>
<body>

<!-- Main -->
<form method="post">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-container">
                <h1>Wachtwoord resetten</h1>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
                </div>
                <button type="submit" class="btn btn-block" name="submit">Send mail</button>
                <p class="text-muted" style="font-size: 15px">If u submit this form u get a mail with a code if you put that code in to the next page you can reset your password.</p>
            </div>
        </div>
    </div>
</div>
</form>

<!-- Script -->

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

<!-- Error Or Succses -->

<?php
if(isset($_SESSION['error'])) {
    echo "<p class='error'>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']);
}
if(isset($_SESSION['success'])) {
    echo "<p class='success'>" . $_SESSION['success'] . "</p>";
    unset($_SESSION['success']);
}
?>