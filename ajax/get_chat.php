<?php
$pathToRoot = '../';
require_once $pathToRoot.'core/init.php';
require_once $pathToRoot.'core/func/users.php';

require_once $pathToRoot.'core/func/messaging.php';

verify_login();

if (isset($_POST['id']) && can_message_each_other($_POST['id'], $_SESSION['user_id'])) {
    $user_id = $_POST['id'];
    $user = get_user_name($user_id);

    if (isset($_POST['res']) && ($_POST['res'] == 'get_chat' || $_POST['res'] == 'get_chat_messages')) {
        if ($user) {
            include $pathToRoot . 'core/templates/chat.php';
        } else {
            echo 'failed';
        }
    } else if (isset($_POST['action']) && $_POST['action'] == 'send') {
        if (isset($_POST['message']) && !empty($_POST['message'])) {
            if (send_message($user_id, $_POST['message'])) {
                echo 'success';
            } else {
                echo 'failed';
            }
        } else {

        }
    }
} else {
    echo 'failed';
}