<?php
	
	function get_unseen_notification_count($user_id)
	{
		
		global $db;
			
    	$user_id = $_SESSION['user_id'];
		
		$query = $db->prepare("SELECT count(*) FROM notifications WHERE seen=FALSE AND user_id=?");
		
		$query->bind_param('i', $user_id);
	
		$query->execute();
	
		$query->bind_result($count);
	
		$query->fetch();
		
		$query->free_result();
	
		return $count;
	}
	
	function delete_notification($notification_id)
	{
		global $db;

		$query = $db->prepare("DELETE FROM `notifications` WHERE `notification_id` = ?");
	
		$query->bind_param('i', $notification_id);
	
		if (!$query->execute()) {
			return false;
		}
		return true;
	
		$query->free_result();
	}
	
	function set_notification_seen($notification_id)
	{	
		global $db;

		$query = $db->prepare("UPDATE `notifications` SET `seen`=TRUE WHERE `notification_id`=?");
		
		$query->bind_param('i', $notification_id);
	
		if (!$query->execute()) {
			return false;
		}
		return true;
	
		$query->free_result();
	}

?>
