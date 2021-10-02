<?php
session_start();
require_once 'dbSettings.php';

if (!isset($_POST['username'], $_POST['password'])) {
    exit('Please fill both the username and password fields!');
}

// Prepared Login Statement
if ($stmt = $connection->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch(); // Check user exists
        //if ($_POST['password'] === $password) { // Debug purpose only
        if (password_verify($_POST['password'], $password)) { // User exists
            // User logged in
            session_regenerate_id(); // Session generate
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            //echo 'Welcome ' . $_SESSION['name'] . '!'; // remove??????
            header('Location: home.php');
        } else {
            echo 'Incorrect password!';
        }
    } else {
        echo 'Incorrect username!';
    }

    $stmt->close();
}





?>