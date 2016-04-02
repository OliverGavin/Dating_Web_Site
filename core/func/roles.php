<?php
	//You might just want to hard code the values and just run roles.php and forget the function for now.
function user_can($permission)
{
	global $db;
    	$user_id = $_SESSION['user_id']; //could pass in the user id with the permission an set it to null
	
	//MYSQLI
	
	$db->real_escape_string($permission);

	$query = $db->prepare("SELECT CAST(? AS unsigned integer) FROM `users` NATURAL JOIN `roles` WHERE `user_id` = ?");
	
	$query->bind_param('si', $permission, $user_id);
	
	$query->execute();
	
	$query->bind_result($role);
	
	while($query->fetch()){
		echo $role . '<br />'; //i think the problem might be here it always echos 0.
	}
	
	/*if(!$role)
	{
		$result = FALSE;
	}*/
	
	/*I was just trying everything i used === to try and find out what type
	$role and $query were giving me obviously you can change them to ==*/
	
	if($role === 1)//IF 1
	{
		$result = true;
		echo "TRUE role <br />";
	}
	else if($role === 0)
	{
		$result = FALSE;
		echo "FALSE role <br />";
	}
	
	if($role === "1")//IF 2
	{
		$result = true;
		echo "TRUE role String <br />";
	}
	else if($role === "0")
	{
		$result = FALSE;
		echo "FALSE role String<br />";
	}
	
	if($query === TRUE)//IF 3
	{
		$result = true;
		echo "TRUE query <br />";
	}
	else if($query === FALSE)
	{
		$result = FALSE;
		echo "FALSE query <br />";
	}
	
	if($query === 1)//IF 4
	{
		$result = true;
		echo "TRUE query no <br />";
	}
	else if($query === 0)
	{
		$result = FALSE;
		echo "FALSE query no <br />";
	}
	
	$query->free_result();
	
	return $result;
}

?>
