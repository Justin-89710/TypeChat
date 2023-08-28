<?php
// start session
session_start();

// connect to database
$db = new SQLite3('../Database/database.db');

// check if connection is successful
if (!$db) {
    die("Connection failed: " . $db->connect_error);
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
                        <label for="exampleInputEmail1" class="text-uppercase">Email</label>
                        <input type="text" class="form-control" placeholder="" name="email">
                    </div>

                    <div class="form-check" style="margin-top: 25% ">
                        <button type="submit" class="btn btn-login float-right" name="submit">Submit</button>
                    </div>

                    <!-- description of what will happen-->
                    <div class="copy-text">A code will be send to your email when you enter your email addres that is linked to your account.</div>
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