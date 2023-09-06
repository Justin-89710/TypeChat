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

//post script
if (isset($_POST['submit'])) {
    //get the data from the form
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $video = $_FILES['video']['name'];
    $video_tmp = $_FILES['video']['tmp_name'];
    //set max file size
    $maxsize = 5242880; // 5MB
    $sql = $db->query("SELECT * FROM Login WHERE name = '" . $_SESSION['username'] . "'");
    $row = $sql->fetchArray();
    $id = $row['ID'];
    $like = 0;

    //check if the image is not empty
    if (!empty($image)) {
        //check if the image is not to big
        if ($_FILES['image']['size'] > $maxsize) {
            echo "File is too large. Max file size is 5MB.";
            exit;
        }
        //upload the image to the server
        move_uploaded_file($image_tmp, "../Afbeeldingen/$image");
        //insert the data into the database
        $db->exec("INSERT INTO Post (title, content, image, user_id, Likes) VALUES ('$title', '$content', '$image', '$id', '$like')");
    } elseif (!empty($video)) {
        //check if the video is not to big
        if ($_FILES['video']['size'] > $maxsize) {
            echo "File is too large. Max file size is 5MB.";
            exit;
        }
        //upload the video to the server
        move_uploaded_file($video_tmp, "../Videos/$video");
        //insert the data into the database
        $db->exec("INSERT INTO Post (title, content, Video, user_id, Likes) VALUES ('$title', '$content', '$video', '$id', '$like')");
    } else {
        //insert the data into the database
        $db->exec("INSERT INTO Post (title, content, user_id, Likes) VALUES ('$title', '$content', '$id', '$like')");
    }
}

?>

<!-- HTML -->
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
    <title>Home</title>
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
                    <a class="nav-link home" href="../home/home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="text-decoration: underline; text-decoration-color: #17a2b8; text-decoration-thickness: 3px;" href="../post/post.php">Post</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link contact" href="../Contact/Contact.php">Contact</a>
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



<form method="post" enctype="multipart/form-data" style="margin-top: 3em" class="margin">
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control color" name="title" id="title" required>
    </div>
    <div class="form-group">
        <label for="content">Content</label>
        <textarea class="form-control color" name="content" id="content" cols="30" rows="10" required></textarea>
    </div>
    <div class="form-group">
        <label for="image" class="col-form-label">Upload Image <em>(optional)</em></label>
        <div class="custom-file">
            <input type="file" class="custom-file-input form-control-file" name="image" id="image" accept="image/*">
            <label class="custom-file-label btn color" for="image">Choose file</label>
        </div>
        <small class="form-text text-muted">Select an image file to upload.</small>
    </div>
    <!-- add video -->
    <div class="form-group">
        <label for="image" class="col-form-label">Upload Video <em>(optional)</em></label>
        <div class="custom-file">
            <input type="file" class="custom-file-input form-control-file" name="video" id="video" accept="video/*">
            <label class="custom-file-label btn color" for="image">Choose file</label>
        </div>
        <small class="form-text text-muted">Select an Video file to upload.</small>
    </div>
    <p><em>You can only upload a video or a image not both.</em></p>
    <button type="submit" class="btn color" name="submit">Post</button>
</form>

<!-- Include Bootstrap JS files -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>
