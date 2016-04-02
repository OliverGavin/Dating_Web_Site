<?php
$pathToRoot = '../';
require_once $pathToRoot.'core/init.php';
require_once $pathToRoot.'core/func/profiles.php';
require_once $pathToRoot.'core/func/users.php';
require_once $pathToRoot.'core/func/interests.php';

verify_login();

if (isset($_POST['id']) && exists_profile($_POST['id'])) {
    $user_id = $_POST['id'];
    $profile = get_profile($user_id);
    if ($profile) {
        echo '<div class="profile">';
        include $pathToRoot.'core/templates/profile-single.php';
        echo '</div>';
    } else {
            // TODO 404 template
    }
}