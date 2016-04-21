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
	
	function get_notifications($user_id)
	{
		global $db;
				
		$user_id = $_SESSION['user_id'];

		$query = $db->prepare("
			SELECT `content`, `notification_id`, `sender_id`, `seen`, `type`
			FROM `notifications` NATURAL JOIN notification_type
			WHERE `user_id`=? AND `date_time` BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
				AND `type` != 'REPORT'
			");
	
		$query->bind_param('i', $user_id);
	
		if(!$query->execute()){
			return false;
		}
       			
		$query->bind_result($content, $notification_id, $sender_id, $seen, $type);
		
		$notifications = array();

    	while ($query->fetch()) {
        	array_push($notifications, (object) array(
            	'content'   => $content,
            	'notification_id'           => $notification_id,
            	'sender_id'        => $sender_id,
            	'seen' => $seen,
				'type' => $type
       		));
    	}

    	return $notifications;
							
	}
	
	function remove_old_notifications()
	{
		global $db;

		$query = $db->prepare("DELETE FROM `notifications` WHERE `date_time` <= DATE_SUB(SYSDATE(), INTERVAL 30 DAY)");
	
		if (!$query->execute()) {
			return false;
		}
		return true;
	
		$query->free_result();
	}
	
	function create_notification($receiver_user_id, $content, $type)
	{
		global $db;

		$sender_user_id = $_SESSION['user_id'];

//		require_once 'core/func/profiles.php';
		
		$profile = get_profile($sender_user_id);
		
		switch ($type) {
		case "MESSAGE"://MESSAGE:
		
		break;
		
		case "LIKE"://LIKE:
			$content = $profile->first_name . ' ' . $profile->last_name . " has liked your profile.";
		break;

		case "MUTUAL_LIKE"://LIKE:
			$content = "It's a match! " . $profile->first_name . ' ' . $profile->last_name . " has liked you back. You can now chat.";
		break;
		
		case "WARNING"://WARNING: to be displayed after ban time has expired
			$content = "Your ban has been lifted, please respect the website nad its users.";
			$sender_user_id= null;	// sent by system, not user
		break;
		
		case "PAYMENT"://PAYMENT
			$content = "Payment successful, you may now use our services.";
			$sender_user_id= null;	// sent by system, not user
		break;
		
		case "REPORT"://REPORT: user does this
		
		break;
		
		case "SYSTEM"://SYSTEM
		
		break;
		
		default:
		return;
		}
		
		$type_id = find_type_id($type);
		
		$query = $db->prepare("INSERT INTO `notifications` (`notification_id`, `user_id`, `sender_id`, `seen`, `content`, `link`, `type_id`, `date_time`)
		VALUES (NULL, ?, ?, b'0', ?, NULL, ?, CURRENT_TIMESTAMP)");
		
		$query->bind_param('iisi', $receiver_user_id, $sender_user_id, $content, $type_id);
		
		$query->execute();
		
	}
	function find_type_id($type)
	{
		global $db;

		$query = $db->prepare("SELECT `type_id` FROM `notification_type` WHERE `type` = ?");
	
		$query->bind_param('s', $type);
	
		$query->execute();
	
		$query->bind_result($type_id);
	
		$query->fetch();
		
		$query->free_result();
	
		return $type_id;
	}
	
	function get_report_notifications($notification_id = null)
	{
		global $db;

		$query_extra = "";

		if($notification_id)
		{
			$notification_id = (int) $notification_id;
			$query_extra = " AND notification_id = $notification_id ";
		}

		$query = $db->prepare("
			SELECT `content`, `notification_id`, `user_id`, `sender_id`, `seen`, `date_time`
			FROM `notification_type` NATURAL JOIN `notifications`
			WHERE `type` = 'REPORT' $query_extra
			ORDER BY `date_time` DESC
			");
	
		if(!$query->execute()){
			return false;
		}
	
		$query->bind_result($content, $notification_id, $user_id, $sender_id, $seen, $date_time);
		
		$reports = array();

    		while ($query->fetch()) {
        		array_push($reports, (object) array(
            			'content'   => $content,
            			'notification_id'           => $notification_id,
				'user_id'			=> $user_id,
            			'sender_id'        => $sender_id,
            			'seen' => $seen,
				'date_time' =>	$date_time,
       			));
    		}

    		return $reports;
	}
	function truncate($string, $length, $dots = "...")
	{
    	return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
	}
	
	function get_unseen_report_notification_count()
	{
		
		global $db;
		
		$query = $db->prepare("SELECT count(*) FROM notification_type NATURAL JOIN notifications WHERE seen=FALSE AND type='REPORT'");
	
		$query->execute();
	
		$query->bind_result($count);
	
		$query->fetch();
		
		$query->free_result();
	
		return $count;
	}	

?>
