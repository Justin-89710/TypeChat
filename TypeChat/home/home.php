<?php
// Start the session
session_start();

// connect to the database
$db = new SQLite3('../Database/database.db');

//// Check for device token cookie
//if (isset($_COOKIE["device_token"])) {
//    $device_token = $_COOKIE["device_token"];
//
//    // Perform a database query to check if the token is associated with a user
//    // and retrieve the associated user's information
//    $query = $db->prepare("SELECT ID, email FROM Login WHERE device_token = :device_token");
//    $query->bindValue(":device_token", $device_token, SQLITE3_TEXT);
//    $result = $query->execute();
//
//    if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
//        // Token is valid, log the user in automatically
//        $_SESSION["loggedin"] = true;
//        $_SESSION["user_id"] = $row["ID"]; // Store user's ID in the session
//        $_SESSION["username"] = $row['name']; // Store user's name in the session
//        exit;
//    }
//} else {
//    // send the user to the login page
//    header("location: ../Login/Login.php");
//}

// Check if the user is already logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"])) {
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

// Like script
if (isset($_POST['likebutton'])) {
    $postid = $_POST['postid'];

    // Check if the user has already liked the post
    $checklikequery = "SELECT COUNT(*) FROM Like WHERE postID = '$postid' AND userID = '$sessionuserid'";
    $checklikeresult = $db->querySingle($checklikequery);
    $alreadyLiked = ($checklikeresult === 1);

    if ($alreadyLiked) {
        // Unlike the post
        $unlikequery = "DELETE FROM Like WHERE postID = '$postid' AND userID = '$sessionuserid'";
        $db->exec($unlikequery);

        // Update the likes count in the Post table
        $likesquery = "SELECT * FROM Post WHERE ID = '$postid'";
        $likesresult = $db->query($likesquery);
        $likesrow = $likesresult->fetchArray();
        $likes = $likesrow['Likes'];
        $likes = $likes - 1;
        $likesupdatequery = "UPDATE Post SET Likes = '$likes' WHERE ID = '$postid'";
        $db->exec($likesupdatequery);
    } else {
        // Like the post
        $likequery = "INSERT INTO Like (postID, userID) VALUES ('$postid', '$sessionuserid')";
        $db->exec($likequery);

        // Update the likes count in the Post table
        $likesquery = "SELECT * FROM Post WHERE ID = '$postid'";
        $likesresult = $db->query($likesquery);
        $likesrow = $likesresult->fetchArray();
        $likes = $likesrow['Likes'];
        $likes = $likes + 1;
        $likesupdatequery = "UPDATE Post SET Likes = '$likes' WHERE ID = '$postid'";
        $db->exec($likesupdatequery);
    }
}

// Comment script
if (isset($_POST['commentbutton'])) {
    $postid = $_POST['postid'];
    $comment = $_POST['commentinput'];
    $commentquery = "INSERT INTO Comment (post_ID, user_ID, Comment) VALUES ('$postid', '$sessionuserid', '$comment')";
    if ($comment == ""){
        $commenterror = "Please enter a comment";
    } else {
        $db->exec($commentquery);
    }
}

// Delete comment script
if (isset($_POST['deletecomment'])) {
    $commentid = $_POST['commentid'];

    // Delete the comment from the Comment table
    $deletequery = "DELETE FROM Comment WHERE ID = '$commentid' AND user_ID = '$sessionuserid'";
    $db->exec($deletequery);

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
                    <a class="nav-link" style="text-decoration: underline; text-decoration-color: #17a2b8; text-decoration-thickness: 3px;" href="../home/home.php">Home</a>
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

<!-- Posts -->
<div class="container post-container">
    <?php
    $query = "SELECT * FROM Post ORDER BY ID DESC";
    $result = $db->query($query);

    // Display the posts
    while ($row = $result->fetchArray()) {
        $postID = $row['ID'];
        $userID = $row['user_id'];
        $content = $row['content'];
        $image = $row['image'];
        $title = $row['title'];
        $postresult = $db->query("SELECT * FROM Login WHERE ID='$userID'");
        $postrow = $postresult->fetchArray();
        $postusername = $postrow['name'];
        $postuserprofilepic = $postrow['profilepic'];
        $likes = $row['Likes'];
        $video = $row['Video'];

        // Check if the user has liked the post
        $checklikequery = "SELECT COUNT(*) FROM Like WHERE postID = '$postID' AND userID = '$sessionuserid'";
        $checklikeresult = $db->query($checklikequery);
        $checklikerow = $checklikeresult->fetchArray();
        $alreadyLiked = $checklikerow['COUNT(*)'];
        ?>
        <div class="post">
            <div class="post-header">
                <a href="../Profile/profile.php?id=<?php echo $userID ?>" class="profile-name">
                <img src="../Afbeeldingen/<?php echo $postuserprofilepic ?>" alt="Profile Picture" class="profile-picture">
                <?php echo $postusername ?></a>
            </div>
            <div class="post-body">
                <h5 class="post-title"><?php echo $title ?></h5>
                <?php if ($image !== null) { ?>
                    <img src="../Afbeeldingen/<?php echo $image ?>" alt="Post Image" class="post-image">
                <?php } elseif ($video !== null) { ?>
                    <video controls class="post-video">
                        <source src="../Videos/<?php echo $video ?>" type="video/mp4">
                    </video>
                <?php } ?>
                <p class="post-content"><?php echo $content ?></p>
            </div>
            <div class="post-footer">
                <div class="likes">
                    <!-- Like Button -->
                    <form method="post">
                        <input type="hidden" name="postid" value="<?php echo $postID ?>">
                        <?php
                        // Check if the user has already liked the post
                        $checklikequery = "SELECT COUNT(*) FROM Like WHERE postID = '$postID' AND userID = '$sessionuserid'";
                        $checklikeresult = $db->querySingle($checklikequery);
                        $alreadyLiked = ($checklikeresult === 1);

                        if ($alreadyLiked) {
                            echo '<button type="submit" class="btn btn-outline-danger" name="likebutton">Unlike ' . $likes . '</button>';
                        } else {
                            echo '<button type="submit" class="btn color" name="likebutton">Like ' . $likes . '</button>';
                        }
                        ?>
                    </form>
                </div>
                <div class="comments">
                    <div class="comment-list" style="margin-bottom: 2em">
                        <?php
                        $commentquery = "SELECT * FROM Comment WHERE post_ID = '$postID'";
                        $commentresult = $db->query($commentquery);
                        while ($commentrow = $commentresult->fetchArray()) {
                            $commentuserid = $commentrow['user_ID'];
                            $commenttext = $commentrow['Comment'];
                            $commentuserresult = $db->query("SELECT * FROM Login WHERE ID='$commentuserid'");
                            $commentuserrow = $commentuserresult->fetchArray();
                            $commentusername = $commentuserrow['name'];
                            ?>
                            <div class="comment-item" style="margin-bottom: 2em">
                                <a href="../Profile/profile.php?id=<?php echo $commentuserid ?>" class="profile-name"><?php echo $commentusername ?></a>
                                <span class="comment-content"><?php echo $commenttext ?></span>

                                <!-- Delete Comment Button -->
                                <?php
                                if ($commentuserid === $sessionuserid) {
                                    ?>
                                    <form method="post" style="float: right;">
                                        <input type="hidden" name="commentid" value="<?php echo $commentrow['ID'] ?>">
                                        <!-- trash icon -->
                                        <button type="submit" name="deletecomment" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <form method="post" class="comment-form">
                        <input type="hidden" name="postid" value="<?php echo $postID ?>">
                        <!-- error message -->
                        <?php
                        if (isset($commenterror)) {
                            echo '<div class="alert alert-danger">' . $commenterror . '</div>';
                        }
                        ?>
                        <input type="text" name="commentinput" class="comment-input" placeholder="Add a comment...">
                        <button type="submit" name="commentbutton" class="btn color">Comment</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
