<?php
session_start();

// reset errors
$_SESSION['error'] = array();

if (isset($_GET['logout'])) {
    logout();

}

else if (isset($_GET['login']) && isset($_POST['action'])) {
    if ($_POST['action'] == 'Login') {
        login();

    } else if ($_POST['action'] == 'Register') {
        register();

    }
}

function logout() {

    unset($_SESSION);

    // set the session cookie to a time in the past so that it is deleted
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-5000 , "/");
    }

    session_destroy();

    // redirect to the main page
    header('Location: index.php');

}

function login() {
    global $db;

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

        if (!$user_id) {
            array_push($_SESSION['error'], "Login failed");
            return;
        }

        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_ip'] = $ip;

        // redirect to their Dashboard
        if (isset($_GET['redirect'])) {
            header("Location: " . $_GET['redirect']);

        } else {
            header("Location: profile.php");
        }

        //Free query result
        $users->free_result();

    }
}

function register() {
    global $db;

    if (isset($_POST['email'], $_POST['password'], $_POST['password2'])) {

        if ($_POST['password'] !== $_POST['password2']) {
            array_push($_SESSION['error'], "Passwords don't match");
            return;
        }

        $email = $_POST['email'];
        $password = hash("sha256", $_POST['password'], false);

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

    if ( is_user_logged_in() ) {

        if ($_SERVER['REMOTE_ADDR'] != $_SESSION['user_ip']) {
            logout();
        }

    } else {
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
    return (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']));
}