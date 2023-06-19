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
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>
</head>
<body>
<!-- profile of person with the same id as the link -->
<?php
//get the id from the link
$id = $_GET['id'];
//get the data from the database
$result = $db->query("SELECT * FROM Login WHERE id='$id'");
$row = $result->fetchArray();
//show the data
echo "<h1>" . $row['name'] . "</h1>";
echo "<img src='../Afbeeldingen/" . $row['profilepic'] . "' alt='Profile picture' class='profile-picture'>";
echo "<p>" . $row['bio'] . "</p>";
?>
</body>
</html>
