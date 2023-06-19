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

//post script
if (isset($_POST['submit'])) {
    //get the data from the form
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $sql = $db->query("SELECT * FROM Login WHERE name = '" . $_SESSION['username'] . "'");
    $row = $sql->fetchArray();
    $id = $row['ID'];

    //check if the image is not empty
    if (!empty($image)) {
        //upload the image to the server
        move_uploaded_file($image_tmp, "../Afbeeldingen/$image");
        //insert the data into the database
        $db->exec("INSERT INTO Post (title, content, image, user_id) VALUES ('$title', '$content', '$image', '$id')");
    } else {
        //insert the data into the database
        $db->exec("INSERT INTO Post (title, content, user_id) VALUES ('$title', '$content', '$id')");

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
    <title>Post</title>
</head>
<body>
<!-- post form -->
<form action="post.php" method="post" enctype="multipart/form-data">
    <label for="title">Title</label>
    <input type="text" name="title" id="title" required>
    <label for="content">Content</label>
    <textarea name="content" id="content" cols="30" rows="10" required></textarea>
    <label for="image">Image *optinal*</label>
    <input type="file" name="image" id="image">
    <input type="submit" name="submit" value="Post">
</form>
</body>
</html>
