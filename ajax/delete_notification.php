<?php
/*
 * Facilitates ajax requests for deleting a notification with a specified ID
 */

$pathToRoot = '../';
require_once $pathToRoot.'core/init.php';
require_once $pathToRoot.'core/func/notifications.php';

verify_login();

if(isset($_POST['notification_id'])){
	
	if(delete_notification($_POST['notification_id'])){
		
		echo "success";
	}
	else{
		echo "failed";
	}

}
?>
