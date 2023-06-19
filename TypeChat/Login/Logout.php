<?php
// Purpose: Destroys the session and redirects the user to the home page
session_start();
session_destroy();
header("Location: ../home/home.php");
?>