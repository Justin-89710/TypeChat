<?php
// Path: Contact\Contact.php
// Start the session
session_start();

// connect to the database
$db = new SQLite3('../Database/database.db');

// Check if the user is not logged in, if yes then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../Login/Login.php");
    exit;
}

// Get user data
$sessionusername = $_SESSION["username"];
$result = $db->query("SELECT * FROM Login WHERE name='$sessionusername'");
$row = $result->fetchArray();
$loginuseremail = $row['email'];
$loginuserid = $row['id'];
$loginuserbio = $row['bio'];
$loginuserprofilepic = $row['profilepic'];
$sessionuserid = $_SESSION['id'];

// Search script
$searchresult = null;
if (isset($_POST['searchbutton'])) {
    $search = $_POST['searchinput'];
    $searchquery = "SELECT * FROM Login WHERE name LIKE '%$search%'";
    $searchresult = $db->query($searchquery);
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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="../CSS/nav.css">
    <link rel="stylesheet" href="../CSS/Home.css">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/6d3b596002.js" crossorigin="anonymous"></script>
    <title>Thank you!!!</title>

</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-light bg-transparent navbar-expand-lg navbar-lg nav-size">
    <div class="container size">
        <!-- Navbar Logo -->
        <a class="navbar-brand" href="../home/home.php" style="margin-right: 5em;">
            <img src="../Afbeeldingen/nav-logo.png" width="50" height="50" class="d-inline-block align-top" alt="">
            <p class="navbar-brand" style="float: right; margin-top: 3%;">TypeChat</p>
        </a>

        <!-- Navbar Toggler -->
        <button class="navbar-toggler toggle" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Items -->
        <div class="collapse navbar-collapse bg-transparent  collapse-color" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="../home/home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../post/post.php">Post</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Contact/Contact.php">Contact</a>
                </li>
            </ul>

            <!-- Search Form -->
            <form method="post" class="d-flex">
                <input type="text" class="form-control me-2 color" name="searchinput" placeholder="Search">
                <button type="submit" class="btn color" name="searchbutton">Search</button>
            </form>

            <!-- Search Results -->
            <div class="search-results">
                <div class="container">
                    <?php
                    if ($searchresult !== null) {
                        while ($searchrow = $searchresult->fetchArray()) {
                            $searchname = $searchrow['name'];
                            $searchprofilepic = $searchrow['profilepic'];
                            $searchid = $searchrow['ID'];
                            ?>
                            <div class="profile-item">
                                <a href="../Profile/profile.php?id=<?php echo $searchid ?>" class="profile-name">
                                    <img src="../Afbeeldingen/<?php echo $searchprofilepic ?>" alt="Profile Picture" class="profile-picture">
                                    <?php echo $searchname ?></a>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>


            <!-- User Dropdown -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../Afbeeldingen/<?php echo $loginuserprofilepic ?>" alt="Profile Picture" class="profile-picture">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a href="../Account/acount.php" class="dropdown-item">Profile</a></li>
                        <li><a href="../Login/Logout.php" class="dropdown-item">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Thank you message -->

<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center">Thank you for contacting us!</h1>
            <p class="text-center">We will get back to you as soon as possible.</p>
            <p class="text-center">If there is somthing you need to know right away please call us at 06 40266268.</p>
            <p class="text-center">Yours sincerely,</p>
            <p class="text-center">Team TypeChat!</p>
            <p class="text-center">This is an automated response, please do not reply!</p>
            <p class="text-center">click on our logo to go back to the home page!</p>
        </div>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
