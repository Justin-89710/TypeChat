<?php
// Start the session
session_start();
// connect to the database
$db = new SQLite3('../Database/database.db');

// Check if the user is not logged in, if yes then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {

    header("location: ../Login/Login.php");

    exit;

}

$name = $_SESSION["username"];
$result = $db->query("SELECT * FROM Login WHERE name='$name'");
$row = $result->fetchArray();
$email = $row['email'];
$userID = $row['id'];
$bio = $row['bio'];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <style>
        .profile-picture {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }

        .logo {
            width: 50px;
            height: 50px;
        }
        #img{
            height: 300px;
            width: 300px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="../Afbeeldingen/Logo.png" alt="Logo" class="logo">
            <!-- search bar to search other users -->
            <form action="" method="post">
                <input type="text" name="search" placeholder="Search">
                <input type="submit" value="Search" name="search2">
            </form>
            <!-- results of the search -->
            <?php
            if (isset($_POST['search2'])) {
                $search = $_POST['search'];
                $query = "SELECT * FROM Login WHERE name LIKE '%$search%'";
                $result = $db->query($query);
                while ($row = $result->fetchArray()) {
                    $name = $row['name'];
                    $profilepic2 = $row['profilepic'];
                    $id = $row['ID'];
                    echo "<div class='row'>
                            <div class='col-2'>
                                <img src='../Afbeeldingen/$profilepic2' alt='Profile Picture' class='profile-picture'>
                            </div>
                            <div class='col-10'>
                                <a href='../Profile/profile.php?id=$id'>$name</a>
                            </div>
                        </div>";
                }
            }
            ?>
        </a>
        <!-- dropdown -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <!-- profile picture -->
                    <?php
                        $query = "SELECT * FROM Login WHERE name='$name'";
                        $result = $db->query($query);
                        $row = $result->fetchArray();
                        $profilepic = $row['profilepic'];
                        ?>
                    <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="../Afbeeldingen/<?php echo $profilepic ?>>" alt="Profile Picture" class="profile-picture">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a href="../Account/acount.php" class="dropdown-item">Profile</a></li>
                        <li><a href="../post/post.php" class="dropdown-item">Post</a></li>
                        <li><a href="../Login/Logout.php" class="dropdown-item">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- posts -->
<?php
$query = "SELECT * FROM Post";
$result = $db->query($query);

//display the posts
while ($row = $result->fetchArray()) {
    $postID = $row['ID'];
    $userID = $row['user_id'];
    $content = $row['content'];
    $image = $row['image'];
    $title = $row['title'];
    $result2 = $db->query("SELECT * FROM Login WHERE ID='$userID'");
    $row2 = $result2->fetchArray();
    $name = $row2['name'];
    $profilepic3 = $row2['profilepic'];
    echo "<div class='container'>
                <div class='row'>
                    <div class='col-2'>
                        <img src='../Afbeeldingen/$profilepic3' alt='Profile Picture' class='profile-picture'>
                    </div>
                    <div class='col-10'>
                        <a href='../Profile/profile.php?id=$userID'>$name</a>
                        <div class='row'>
                            <div class='col-12'>
                                <h1>$title</h1>
                            </div>
                            <div class='col-12'>
                                <p>$content</p>
                                </div>
                            <div class='col-12'>
                                <img src='../Afbeeldingen/$image' alt='Post Image' class='img-fluid' id='img'>
                                </div>
                    </div>
                </div>
            </div>";
}
?>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</html>
