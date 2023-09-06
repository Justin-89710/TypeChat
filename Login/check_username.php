<?php

//connect to database

$db = new SQLite3('Database.db');

//check if username is available

if (isset($_POST['username'])) {
    $username = $_POST['username'];

    if (empty($username)) {
        $response = array('error' => 'Username is required');
        echo json_encode($response);
    } else if (preg_match('/[\'^£$%&*()}{@#~?<>,|=_+¬-]/', $username)) {
        $response = array('error' => 'Username must not contain special characters');
        echo json_encode($response);
    } else if (strlen($username) < 4) {
        $response = array('error' => 'Username must be at least 4 characters');
        echo json_encode($response);
    } else if (strlen($username) > 20) {
        $response = array('error' => 'Username must be less than 20 characters');
        echo json_encode($response);
    } else if (preg_match('/[A-Z]/', $username) == 0) {
        $response = array('error' => 'Username must contain at least one uppercase letter');
        echo json_encode($response);
    } else if (preg_match('/[a-z]/', $username) == 0) {
        $response = array('error' => 'Username must contain at least one lowercase letter');
        echo json_encode($response);
    } else {
        $stmt = $db->prepare("SELECT COUNT(*) FROM login WHERE username = :username");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $result = $stmt->execute();
        $count = $result->fetchArray()[0];

        //return result

        if ($count > 0) {
            $response = array('available' => false);
            echo json_encode($response);
        } else {
            $response = array('available' => true);
            echo json_encode($response);
        }
    }
}