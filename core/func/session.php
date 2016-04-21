<?php
global $pathToRoot;
require_once $pathToRoot.'core/func/profiles.php';
require_once $pathToRoot.'core/func/roles.php';

$attempted_user_id;

session_start();

if (isset($_GET['logout'])) {
    logout();

}

function logout($timeout = null) {

    unset($_SESSION);

    // set the session cookie to a time in the past so that it is deleted
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-60*60 , "/");
    }

    session_destroy();

    // redirect to the main page
    if ($timeout) {
        $current_page = $_SERVER['REQUEST_URI'];
        header("Location: index.php?timeout=true&redirect=$current_page");
    } else {
        header('Location: index.php');
    }
    exit();

}

function login($email, $password) {
    global $db, $message, $attempted_user_id;

    $password = hash("sha256", $password, false);
    $ip = $_SERVER['REMOTE_ADDR'];

    $users = $db->prepare("
          SELECT user_id, first_name, last_name FROM users WHERE email = ? AND password = ?
        ");

    $users->bind_param('ss', $email, $password);

    $users->execute();

    $users->bind_result($user_id, $first_name, $last_name); //i.e. binding to SELECTed attributes

    $users->fetch();

    //Free query result
    $users->free_result();

    if (!$user_id) {
        array_push($message['error'], INCORRECT_USER_PASS);
        return;
    }

    if (user_is_role(ROLE_BANNED, $user_id)) {
        $attempted_user_id = $user_id;
        if (date_create() > date_create(get_ban_details()->until_date_time)) {
            set_user_role(ROLE_FREE, $user_id);
            // TODO add notification
        } else {
            array_push($message['error'], USER_BANNED);
            return;
        }
    } else if (user_is_role(ROLE_DELETED, $user_id)) {
        $attempted_user_id = $user_id;
        array_push($message['error'], USER_DELETED);
        return;
    }

    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_ip'] = $ip;
    $_SESSION['first_name'] = $first_name;
    $_SESSION['last_name'] = $last_name;

    // redirect to their Dashboard
    if (isset($_GET['redirect'])) {
        header("Location: " . $_GET['redirect']);
        exit();
    } else {
        header("Location: dashboard.php");
        exit();
    }

}

function register($email, $password, $first_name, $last_name, $DOB_day, $DOB_month, $DOB_year, $sex) {
    global $db, $message, $pathToRoot;
    require_once $pathToRoot.'core/func/notifications.php';

    $password = hash("sha256", $password, false);

    $prepared = $db->prepare("
            INSERT INTO users (email, password, first_name, last_name)
            VALUES (?, ?, ?, ?)
        ");

    $prepared->bind_param('ssss', $email, $password, $first_name, $last_name); //s - string

    if ($prepared->execute()) {
        array_push($message['success'], "Your account has been created, please log in");
//        create_notification($prepared->insert_id, "Welcome to swoon!", "SYSTEM");
        // Create a profile for the user using their assigned user_id
        $profile = new Profile($prepared->insert_id);
        $profile->create_profile($DOB_day, $DOB_month, $DOB_year, $sex);
        if ($profile->error) {

        }

    } else {
        // Error code 1062 - duplicate
        if($prepared->errno === 1062) {
            array_push($message['error'], ALREADY_EXISTS);
        } else if ($prepared->errno) {
            array_push($message['error'], ERROR);
        }
    }

    $prepared->free_result();

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

        $timeout = 60*60*0.5;   // 30 minutes
        if (isset($_SESSION['LAST_REQUEST']) && (time() - $_SESSION['LAST_REQUEST'] > $timeout)) {
            // last request was more than 30 minutes ago
            logout(true);
        }
        $_SESSION['LAST_REQUEST'] = time();

    } else {
        // remember where the user was going
        if (!isset($_GET['logout']) && isset($_SERVER['REQUEST_URI'])) {
            $current_page = $_SERVER['REQUEST_URI'];
            header("Location: index.php?redirect=$current_page");
            exit();
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