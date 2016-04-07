<?php
require_once 'core/init.php';
require_once 'core/func/profiles.php';
require_once 'core/func/interests.php';

verify_login();
// TODO permissions and validation

$profile = false;
$msg = '';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    $user_id = $_SESSION['user_id'];
}

$is_owner = ($user_id == $_SESSION['user_id']);

if ($is_owner && user_is_at_least_role(ROLE_ADMIN)) {
    $msg = 'Admins cannot have a profile';
} else {

    $can_edit = user_can(PERM_EDIT_PROFILE);
    $can_edit_others = user_can(PERM_EDIT_OTHERS_PROFILE);

    // Unauthorised user
    if (!$is_owner && !$can_edit_others) {
        header("Location: 401.php");        //TODO
        exit();
    }
    // Authorised, but not permitted to edit (upgrade required)
    if (!$can_edit) {
        header("Location: upgrade.php");    //TODO
        exit();
    }

    // TODO function
    if (isset($_FILES['fileToUpload']) && file_exists($_FILES['fileToUpload']['tmp_name']) && is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
        require_once 'core/func/image-upload.php';
    }

    if (isset($_GET['delete_interest']) && !empty($_GET['delete_interest'])) {
        // rollback if no JavaScript
        // Validation
        remove_interest($user_id, $_GET['delete_interest']);
        header("Location: edit-profile.php?id=$user_id");        //TODO
        exit();

    } else if (isset($_GET['action']) && $_GET['action'] === 'delete') {
        delete_profile($user_id);
        header("Location: dashboard.php");
        exit();

    } else if (isset($_POST['action']) && $_POST['action'] === 'Save') {
        // Submit changes to DB

        $user_id = $_POST['user_id'];
        $profile = new Profile($user_id);
        $profile->submit();

        if ($profile->error) {
            // TODO error
        }

        if (isset($_POST['new_interest_like']) && !empty($_POST['new_interest_like'])) {
            add_interest($user_id, true, $_POST['new_interest_like']);
        }

        if (isset($_POST['new_interest_dislike']) && !empty($_POST['new_interest_dislike'])) {
            add_interest($user_id, false, $_POST['new_interest_dislike']);
        }

    } else {
        // Load data from DB
        $profile = new Profile($user_id);
        $profile->fetch();


        if ($profile->error) {
            if ($user_id == $_SESSION['user_id']) {
                // create profile
                $profile->first_name = $_SESSION['first_name'];
                $profile->last_name = $_SESSION['last_name'];

            } else {
                header("Location: 404.php");
                exit();
            }
        }

    }
}
?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php
        if ($profile) {
            include 'core/templates/edit-profile-single.php';
        } else {
            echo $msg;
        }
        ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
