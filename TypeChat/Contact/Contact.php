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
    <!-- link to css -->
    <!-- stylesheets -->
    <style>
        .profile-picture {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 10px;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            color: #fff;
            font-weight: bold;
        }

        .nav-link {
            color: #fff;
        }

        .nav-link:hover {
            color: #e9ecef;
        }

        .profile-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .profile-item {
            display: flex;
            align-items: center;
            margin: 10px;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: #fff;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-results.active {
            display: block;
        }

        .dropdown-menu {
            background-color: #343a40;
            border: none;
        }

        .dropdown-item {
            color: #fff;
        }

        .dropdown-item:hover {
            background-color: #343a40;
            color: #e9ecef;
        }

        .profile-picture {
            width: 30px;
            height: 30px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 5px;
        }

        .dropdown-toggle::after {
            display: none;
        }

        .dropdown-menu-end {
            right: 0;
            left: auto;
        }

        .dropdown-menu-end::before {
            content: "";
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-bottom: 10px solid #343a40;
            position: absolute;
            top: -10px;
            right: 10px;
        }

        @media (min-width: 768px) {
            .navbar-collapse {
                justify-content: flex-end;
            }
        }

        @media (max-width: 767px) {
            .navbar-nav {
                flex-direction: column;
            }

            .navbar-toggler {
                margin-left: auto;
            }

            .search-form {
                flex: 1;
            }

            .search-button {
                margin-top: 10px;
                width: 100%;
            }

            .search-results {
                width: 100%;
                left: auto;
                right: auto;
                border-radius: 5px;
            }
        }
    </style>
    <!-- link to font awesome -->
    <script src="https://kit.fontawesome.com/6d3b596002.js" crossorigin="anonymous"></script>
    <!-- link to google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <!-- link to favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico"/>
    <!-- link to bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Contact</title>
</head>
<body>
<!-- navbar -->
<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark navbar-expand-lg">
    <div class="container">
        <!-- Navbar Logo -->
        <a class="navbar-brand" href="../home/home.php">
            <img src="../Afbeeldingen/Logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
            TypeChat
        </a>

        <!-- Navbar Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Items -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
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
                <input type="text" class="form-control me-2" name="searchinput" placeholder="Search">
                <button type="submit" class="btn btn-primary" name="searchbutton">Search</button>
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

<!-- contact form with name, email, message and all with bootstrap -->
<div class="container">
    <form action="Emailsend.php" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Email">
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" name="message" id="message" rows="3" placeholder="Message"></textarea>
        </div>
        <input type="submit" class="btn btn-primary" name="submit" value="Submit">
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>