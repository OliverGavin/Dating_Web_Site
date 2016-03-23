<?php

function user_can($permission){
	
	$permission = mysql_real_escape_string($permission);
	$user_id = $_SESSION['user_id'];
	$result = false;

	$sqlfindRoleId = "SELECT 'role_id' FROM `users` WHERE `user_id` = ".$user_id."";
    $roleId = mysql_query($sqlfindRoleId);
	if (!$roleId) {
    echo 'Could not run query (roleId): ' . mysql_error();
    exit;
	}
	
	$sqlfindCanRole = "SELECT CAST(".$permission." AS unsigned integer) FROM `roles` WHERE `role_id` = ".$roleId."";
	//$sqlfindCanRole = "SELECT ".$permission." FROM `roles` WHERE `role_id` = ".$roleId."";
	$role = mysql_query($sqlfindCanRole);
	if (!$role) {
    echo 'Could not run query: (permission): ' . mysql_error();
    exit;
	}
	
	if($role == "1"){
		$result = true;
	}
	else if($role == "0"){
		$result = false;
	}
	
	return $result;
	exit;
}

?>
