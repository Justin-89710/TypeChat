<?php
// Start the session
session_start();

// Connect to the database
$db = new SQLite3('../Database/database.db');

// check if user is logged in
if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
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
            if ($_FILES['file']['size'] < 1000000) {
                // give file a unique name
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
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
if (isset($_POST['submit2'])) {
    $name = $_POST['name'];
    $db->exec("UPDATE Login SET name = '$name' WHERE name = '" . $_SESSION['username'] . "'");
    $_SESSION['username'] = $name;
    header("Location: acount.php");
}

// change email in database
if (isset($_POST['submit3'])) {
    $email = $_POST['email'];
    $db->exec("UPDATE Login SET email = '$email' WHERE name = '" . $_SESSION['username'] . "'");
    header("Location: acount.php");
}

// change bio in database
if (isset($_POST['submit4'])) {
    $bio = $_POST['bio'];
    $db->exec("UPDATE Login SET bio = '$bio' WHERE name = '" . $_SESSION['username'] . "'");
    header("Location: acount.php");
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit your acount!</title>
</head>
<body>
<h1>Change profile picture</h1>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="file">
    <button type="submit" name="submit">Upload</button>
</form>

<h1>Change name</h1>
<form method="POST">
    <input type="text" name="name" placeholder="New name">
    <button type="submit" name="submit2">Change</button>
</form>

<h1>Change email</h1>
<form method="POST">
    <input type="text" name="email" placeholder="New email">
    <button type="submit" name="submit3">Change</button>
</form>

<h1>Change bio</h1>
<form method="POST">
    <input type="text" name="bio" placeholder="New bio">
    <button type="submit" name="submit4">Change</button>
</form>

<h1>Change password</h1>
<a href="../Login/Wachtwoord_vergeten.php">Verander je wachtwoord!</a>

<a href="home.php">Back to home</a>
</body>
</html>
