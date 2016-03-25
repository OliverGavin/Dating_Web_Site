<?php
require_once 'core/init.php';
require_once 'core/func/profiles.php';

verify_login();
// TODO permissions

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    $user_id = $_SESSION['user_id'];
}


?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php
        $profile = get_profile($user_id);
        if ($profile) {
            include 'core/templates/profile-single.php';
        } else {
            if ($user_id == $_SESSION['user_id']) {
                echo 'No profile was found, would you like to create one?';
                echo '<a href="edit-profile.php">Create profile</a>';
                // TODO template
            } else {
                // TODO 404 template
            }
        }
        ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
