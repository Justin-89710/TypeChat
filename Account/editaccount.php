<?php
// Start the session
session_start();

// Connect to the database
$db = new SQLite3('../Database/database.db');

// check if user is logged in
if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
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

// put img in database
if (isset($_POST['submit'])) {
    $file = $_FILES['file'];

    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileError = $_FILES['file']['error'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png');

    // check if file is allowed
    if (in_array($fileActualExt, $allowed)) {
        // check if there is an error
        if ($fileError === 0) {
            // check if file is not too big
            if ($_FILES['file']['size'] < 10000000) {
                // give file a unique name
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                //delete old profile pic
                if ($loginuserprofilepic != "default.png") {
                    unlink("../Afbeeldingen/" . $loginuserprofilepic);
                }
                // set file destination
                $fileDestination = '../Afbeeldingen/' . $fileNameNew;
                // move file to destination
                move_uploaded_file($fileTmpName, $fileDestination);
                // update database
                $db->exec("UPDATE Login SET profilepic = '$fileNameNew' WHERE name = '" . $_SESSION['username'] . "'");
                header("Location: acount.php");
            } else {
                $error = "Bestand is te groot.";
            }
        } else {
            $error = "Er is een fout opgetreden.";
        }
    } else {
        $error = "Dit bestandstype is niet toegestaan.";
    }
}


// change name in database
if (isset($_POST['submit2']) && !empty($_POST['name'])) {
    $name = $_POST['name'];
    $db->exec("UPDATE Login SET name = '$name' WHERE name = '" . $_SESSION['username'] . "'");
    $_SESSION['username'] = $name;
    header("Location: acount.php");
} else if (isset($_POST['submit2']) && empty($_POST['name'])) {
    $error2 = "Vul een naam in.";
}
// change email in database
if (isset($_POST['submit3']) && !empty($_POST['email'])) {
    // genarate 4 diget code
    $code = rand(1000, 9999);
    // send email
    $to = $_POST['email'];
    $subject = "Type Chat - Verifieer je email";
    $message = "Je code is: " . $code;
    $headers = "From:
    Type Chat";
    mail($to, $subject, $message, $headers);
    // save data in session
    $_SESSION['newemail'] = $_POST['email'];
    $_SESSION['newcode'] = $code;
    header("Location: Verefymail.php");
}

// change bio in database
if (isset($_POST['submit4']) && !empty($_POST['bio'])) {
    $bio = $_POST['bio'];
    $db->exec("UPDATE Login SET bio = '$bio' WHERE name = '" . $_SESSION['username'] . "'");
    header("Location: acount.php");
} else if (isset($_POST['submit4']) && empty($_POST['bio'])) {
    $error4 = "Vul een bio in.";
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

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/6d3b596002.js" crossorigin="anonymous"></script>
    <title>Edit your acount!</title>

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

<!-- main -->
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <h1>Change profile picture</h1>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <input type="file" name="file" class="form-control">
                </div>
                <!-- error message -->
                <?php
                if (isset($error)) {
                    echo "<div class='alert alert-danger' role='alert'>$error</div>";
                }
                ?>
                <button type="submit" name="submit" class="btn color">Upload</button>
            </form>
        </div>
        <div class="col-md-6">
            <h1>Change name</h1>
            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="name" placeholder="New name" class="form-control">
                    <!-- error message -->
                    <?php
                    if (isset($error2)) {
                        echo "<div class='alert alert-danger' role='alert'>$error2</div>";
                    }
                    ?>
                </div>
                <button type="submit" name="submit2" class="btn color">Change</button>
            </form>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h1>Change email</h1>
            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="email" placeholder="New email" class="form-control">
                </div>
                <!-- error message -->
                <?php
                if (isset($error3)) {
                    echo "<div class='alert alert-danger' role='alert'>$error3</div>";
                }
                ?>
                <button type="submit" name="submit3" class="btn color">Change</button>
            </form>
        </div>
        <div class="col-md-6">
            <h1>Change bio</h1>
            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="bio" placeholder="New bio" class="form-control">
                </div>
                <!-- error message -->
                <?php
                if (isset($error4)) {
                    echo "<div class='alert alert-danger' role='alert'>$error4</div>";
                }
                ?>
                <button type="submit" name="submit4" class="btn color">Change</button>
            </form>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h1>Change password</h1>
            <a href="../Login/Wachtwoord_vergeten.php" class="btn color">Verander je wachtwoord!</a>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <a href="../home/home.php" class="btn color">Back to home</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
