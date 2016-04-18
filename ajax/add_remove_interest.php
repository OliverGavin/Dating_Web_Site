<?php
$pathToRoot = '../';
require_once $pathToRoot.'core/init.php';
require_once $pathToRoot.'core/func/users.php';
require_once $pathToRoot.'core/func/interests.php';
require_once $pathToRoot.'core/func/validation.php';

verify_login();

if (isset($_POST['id'], $_POST['delete_interest']) && !empty($_POST['delete_interest'])) {

    if (remove_interest($_POST['id'], $_POST['delete_interest'])) {
        echo 'success';
    } else {
        echo 'failed';
    }

} else if (isset($_POST['id'], $_POST['likes'], $_POST['add_interest']) && !empty($_POST['add_interest'])) {

    $interest = validate_text($_POST['add_interest'], 'add_interest');
    if (add_interest($_POST['id'], ($_POST['likes']==='like'), $interest)) {
        $user_id = $_POST['id'];
        include $pathToRoot.'core/templates/edit-profile-interests.php';
    } else {
        echo 'failed';
    }

}