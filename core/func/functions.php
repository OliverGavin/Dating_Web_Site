<?php
/**
 * Displays the site header
 */
function get_header() {
    require_once 'header.php';
}

/**
 * Displays the site footer
 */
function get_footer() {
    require_once 'footer.php';
}

/**
 * Gets the users profile image
 * @param $size
 * @param null $user_id
 * @return string
 */
function get_profile_image($size, $user_id = null) {
    if ($user_id == null && is_user_logged_in()) $user_id = $_SESSION['user_id'];
    $img = 'images/profiles/'.$size.'_'.$user_id.'.jpg';
    if (file_exists($img) || file_exists('../'.$img)) {
        return $img;
    } else {
        // no image found
        // TODO
        return 'images/'.$size.'_default.jpg';
    }
}

/**
 * Checks if the password for the current user is correct
 * @param $pass
 * @return bool
 */
function check_password($pass) {
    global $db;
    global $message;

    $user_id = $_SESSION['user_id'];
    $password = hash("sha256", $pass, false);
    $count = 0;

    $users = $db->prepare("
              SELECT count(*) FROM users WHERE user_id = ? AND password = ?
            ");

    $users->bind_param('is', $user_id, $password);

    if (!$users->execute()) {
        $message['error'][] = ERROR;
    }

    $users->bind_result($count);

    $users->fetch();

    //Free query result
    $users->free_result();

    if ($count != 1) {
        $message['error'][] = INCORRECT_PASSWORD;
        return false;
    }

    return true;
}