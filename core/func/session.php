<?php
session_start();

// reset errors
//unset($_SESSION['error']);
$_SESSION['error'] = array();

if (isset($_GET['logout'])) {

    unset($_SESSION);

    // set the session cookie to a time in the past so that it is deleted
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-5000 , "/");
    }

    session_destroy();

    // redirect to the main page
    header('Location: index.php');
}

if (isset($_GET['login'])) {
    if (isset($_POST['email'], $_POST['password']) &&
        !empty($_POST['email']) &&
        !empty($_POST['password'])  ) {

        $email = $_POST['email'];
        $password = hash("sha256", $_POST['password'], false);
        $ip = $_SERVER['REMOTE_ADDR'];

        $users = $db->prepare("
          SELECT user_id FROM users WHERE email = ? AND password = ?
        ");

        $users->bind_param('ss', $email, $password);

        $users->execute();

        $users->bind_result($user_id); //i.e. binding to SELECTed attributes

        $users->fetch();

        if ($users->num_rows !== 1) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_ip'] = $ip;

            // redirect to their Dashboard
            if (isset($_GET['redirect'])) {
                header("Location: " . $_GET['redirect']);

            } else {
                header("Location: profile.php");
            }

        } else {
            array_push($_SESSION['error'], "Login failed");
        }

        //Free query result
        $users->free_result();

    }
}

if (isset($_GET['register'])) {

    if (isset($_POST['email'], $_POST['password'])) {
        $email = $_POST['email'];
        $password = hash("sha256", $_POST['password'], false);

        //validate

        $prepared = $db->prepare("
            INSERT INTO users (email, password)
            VALUES (?, ?)
        ");

        $prepared->bind_param('ss', $email, $password); //s - string

        $prepared->execute();

        $prepared->free_result();
    }
}


/**
 * Verifies the users login state.
 * Redirects the user if they are not logged in
 *                    or their IP is different
 */
function verify_login() {
    //if wrong ip...
    //if not logged in redirect

    if ($_SERVER['REMOTE_ADDR'] != $_SESSION['user_ip']) {

    }

    if ( is_user_logged_in() ) {
        // remember where the user was going
        if (!isset($_GET['logout']) && isset($_SERVER[REQUEST_URI])) {
            $current_page = $_SERVER[REQUEST_URI];
            header("Location: index.php?redirect=$current_page");
        }

    }
}


/**
 * Checks if the user is logged in
 * @return bool
 */
function is_user_logged_in() {
//    return isset($_SESSION['user_id']);
    return (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']));
}