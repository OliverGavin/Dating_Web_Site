<?php
function get_header() {
    require_once 'header.php';
}

function get_footer() {
    require_once 'footer.php';
}

function get_profile_image($size, $user_id = null) {
    if ($user_id == null && is_user_logged_in()) $user_id = $_SESSION['user_id'];

    if (false) {

    } else {
        // no image found
        return "http://offline.fcwinti.com/wp-content/uploads/default-avatar-500x550.jpg";
    }
}

function check_password($pass) {
    global $db;

    $user_id = $_SESSION['user_id'];
    $password = hash("sha256", $pass, false);
    $count = 0;

    $users = $db->prepare("
              SELECT count(*) FROM users WHERE user_id = ? AND password = ?
            ");

    $users->bind_param('is', $user_id, $password);

    $users->execute();

    $users->bind_result($count);

    $users->fetch();

    //Free query result
    $users->free_result();

    if ($count != 1) {
//        array_push($message['error'], "Incorrect password");
        return false;
    }

    return true;
}