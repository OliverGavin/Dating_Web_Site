<?php
require_once 'core/init.php';
require_once 'core/func/profiles.php';
require_once 'core/func/users.php';
require_once 'core/func/interests.php';

verify_login();
// TODO permissions

$profile = false;
$msg = '';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    $user_id = $_SESSION['user_id'];
}

$is_owner = $user_id == $_SESSION['user_id'];

if ($is_owner && user_is_at_least_role(ROLE_ADMIN)) {
    $message['error'][] = 'Admins cannot have a profile';
} else {
    if (isset($_GET['id']) && isset($_GET['status'])) {
        // Fallback if their is no JavaScript for an Ajax request
        set_relationship($_GET['id'], $_GET['status']);
    }

    $profile = get_profile($user_id);

    if (!$profile) {
        if ($user_id == $_SESSION['user_id'] && in_array(NOT_FOUND, $message['error'])) {

        } else if (in_array(MSG_UPGRADE_REQUIRED, $message['error'])) {

        } else {
            // TODO 404 template
            header("Location: 404.php");
        }
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
            if (isset($message['error']) && !empty($message['error'])) {
                echo '<div class="notice error">';
                echo '<h6 class="notice-title">WARNING</h6>';
                foreach (array_unique($message['error']) as $msg) {
                    echo '<p>';
                    switch ($msg) {

                        case NOT_FOUND:
                            echo 'No profile was found, would you like to create one? <a href="edit-profile.php">Create profile</a>';

                            break;

                        case MSG_UPGRADE_REQUIRED:
                            echo 'You must upgrade your account to continue <a href="payment.php">Upgrade</a>';
                            break;

                        default:
                            echo 'Error: ' . $msg;
                            break;
                    }
                    echo '</p>';
                }
                echo '</div>';
            }

        }
        ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
