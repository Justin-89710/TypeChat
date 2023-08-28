<?php
//start session
session_start();
//connect to the database
$db = new SQLite3('../Database/database.db');

//check if the user is not logged in, if yes then redirect him to login page
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
    <!-- bootstrap css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <title>Profile</title>
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
</head>
<body>
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

<!-- profile of person with the same id as the link -->
<?php
// Get the id from the link
$id = $_GET['id'];
// Get the data from the database
$result = $db->query("SELECT * FROM Login WHERE id='$id'");
$row = $result->fetchArray();
// Show the data
?>

<div class="container profile-container" style="margin-top: 3em">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <h1 class="profile-name"><?php echo $row['name']; ?></h1>
            <img src="../Afbeeldingen/<?php echo $row['profilepic']; ?>" alt="Profile picture" class="profile-picture img-fluid">
            <p class="profile-bio"><?php echo $row['bio']; ?></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>
