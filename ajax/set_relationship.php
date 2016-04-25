<?php
/*
 * Facilitates ajax requests for setting a relationship status with another user based on a specified user ID
 */

$pathToRoot = '../';
require_once $pathToRoot.'core/init.php';
require_once $pathToRoot.'core/func/users.php';

verify_login();

if (isset($_POST['id'], $_POST['status'])) {

    if (set_relationship($_POST['id'], $_POST['status'])) {
        echo 'success';
    } else {
        echo 'failed';
    }

}