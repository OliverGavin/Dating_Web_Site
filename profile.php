<?php
require_once 'core/init.php';
require_once 'core/func/profiles.php';
require_once 'core/func/users.php';
require_once 'core/func/interests.php';

verify_login();
// TODO permissions

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    $user_id = $_SESSION['user_id'];
}


if (isset($_GET['id']) && isset($_GET['status'])) {
    // Fallback if their is no JavaScript for an Ajax request
    set_relationship($_GET['id'], $_GET['status']);
}

$profile = get_profile($user_id);

if (!$profile) {
    if ($user_id == $_SESSION['user_id'] && in_array(NOT_FOUND, $message['error'])) {
        $msg  = 'No profile was found, would you like to create one?';
        $msg .= '<a href="edit-profile.php">Create profile</a>';
        // TODO template
    } else if (in_array(MSG_UPGRADE_REQUIRED, $message['error'])) {
        $msg  = 'You must upgrade your account to continue';
        $msg .=  '<a href="payment.php">Upgrade</a>';
        // TODO template
    } else {
        // TODO 404 template
        header("Location: 404.php");
    }
}

?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php
        if ($profile) {
            include 'core/templates/profile-single.php';
        } else {
            echo $msg;
        }
        ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
