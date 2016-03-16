<?php
function get_header() {
    require_once 'header.php';
}

function get_footer() {
    require_once 'footer.php';
}

function get_profile_image($size, $user_id = null) {
    if ($user_id == null) $user_id = $_SESSION['user_id'];

    if (false) {

    } else {
        // no image found
        return "http://offline.fcwinti.com/wp-content/uploads/default-avatar-500x550.jpg";
    }
}