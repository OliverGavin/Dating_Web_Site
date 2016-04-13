<?php
// Constants useful for IDE auto-complete and refactoring
DEFINE('PERM_DELETE_USERS',				'delete_users');
DEFINE('PERM_BAN_USERS',				'ban_users');
DEFINE('PERM_EDIT_USERS',				'edit_users');
DEFINE('PERM_LIST_USERS',				'list_users');
DEFINE('PERM_EDIT_OTHERS_PROFILE',		'edit_others_profile');
DEFINE('PERM_DELETE_OTHERS_PROFILE',	'delete_others_profile');
DEFINE('PERM_VIEW_ADMIN_DASHBOARD',		'view_admin_dashboard');
DEFINE('PERM_CREATE_PROFILE',			'create_profile');
DEFINE('PERM_EDIT_PROFILE',				'edit_profile');
DEFINE('PERM_VIEW_PROFILES',			'view_profiles');
DEFINE('PERM_SEND_MESSAGES',			'send_messages');
DEFINE('PERM_DELETE_PROFILE',			'delete_profile');
DEFINE('PERM_SEARCH_PROFILES',			'search_profiles');
DEFINE('PERM_EDIT_SETTINGS',			'edit_settings');
DEFINE('PERM_VIEW_USER_DASHBOARD',		'view_user_dashboard');

DEFINE('ROLE_ADMIN',			'Admin');
DEFINE('ROLE_BANNED',			'Banned');
DEFINE('ROLE_DELETED',			'Deleted');
DEFINE('ROLE_FREE',				'Free');
DEFINE('ROLE_PAID',				'Paid');
DEFINE('ROLE_SUPER_ADMIN',		'Super admin');

DEFINE('MSG_PERMISSION_DENIED',		"permission denied");
DEFINE('MSG_UPGRADE_REQUIRED',		"upgrade required");


/**
 * Checks if a user has a permission
 * @param string			$permission
 * @param integer|null		$user_id
 * @return bool				true if the user can
 */
function user_can($permission, $user_id = null) {

	global $db;
	if ($user_id == null)
		// defaults to the current user
    	$user_id = $_SESSION['user_id'];

	$db->real_escape_string($permission);

	// The problem was that a string was passed in the select part for an attribute
	// SQL->				SELECT 'edit_settings' FROM....
	// When it should be	SELECT  edit_settings FROM....
	// Prepared statements cannot do this:
	// http://stackoverflow.com/questions/11312737/can-i-parameterize-the-table-name-in-a-prepared-statement
	// It's ok as the data will always be safe from sql injection as the user never submits this parameter
	// $db->real_escape_string($permission); does the job even though it isn't hugely needed
	$query = $db->prepare("SELECT ".$permission." FROM `users` NATURAL JOIN `roles` WHERE `user_id` = ?");

	if ($db->error) {
		// An error may occur if the permission does not exist
		return false;
	}

	$query->bind_param('i', $user_id);
	
	$query->execute();
	
	$query->bind_result($can);

	$query->fetch();
	
	$query->free_result();

	return $can;
}

//// To test...
//if (user_can(PERM_EDIT_OTHERS_PROFILE)) {
//	echo 'can';
//} else {
//	echo 'can\'t';
//}
//echo '<br>';
//if (user_is_at_least_role(ROLE_BANNED)) {
//	echo 'is';
//} else {
//	echo 'isn\'t';
//}

/**
 * Gets a users role name
 * @param integer|null $user_id
 * @return string
 */
function get_user_role_name($user_id = null) {

	global $db;
	if ($user_id == null)
		// defaults to the current user
		$user_id = $_SESSION['user_id'];

	$query = $db->prepare("SELECT name FROM `users` NATURAL JOIN `roles` WHERE `user_id` = ?");

	$query->bind_param('i', $user_id);

	$query->execute();

	$query->bind_result($name);

	$query->fetch();

	$query->free_result();

	return $name;
}

/**
 * Gets a users role weight
 * @param integer|null $user_id
 * @return integer
 */
function get_user_role_weight($user_id = null) {

	global $db;
	if ($user_id == null)
		// defaults to the current user
		$user_id = $_SESSION['user_id'];

	$query = $db->prepare("SELECT weight FROM `users` NATURAL JOIN `roles` WHERE `user_id` = ?");

	$query->bind_param('i', $user_id);

	$query->execute();

	$query->bind_result($weight);

	$query->fetch();

	$query->free_result();

	return $weight;
}

/**
 * Gets the weight of a role
 * @param $role
 * @return mixed
 */
function get_role_weight($role) {

	global $db;

	$query = $db->prepare("SELECT weight FROM `roles` WHERE `name` = ?");

	$query->bind_param('s', $role);

	$query->execute();

	$query->bind_result($weight);

	$query->fetch();

	$query->free_result();

	return $weight;
}

// Checks if a user has a certain role
/**
 * @param $role
 * @param integer|null $user_id
 * @return integer
 */
function user_is_role($role, $user_id = null) {
	if ($user_id == null)
		// defaults to the current user
		$user_id = $_SESSION['user_id'];

	return get_user_role_weight($user_id) === get_role_weight($role);
}

// Checks if a user has at least a certain role
/**
 * @param string $role
 * @param integer|null $user_id
 * @return bool
 */
function user_is_at_least_role($role, $user_id = null) {
	if ($user_id == null)
		// defaults to the current user
		$user_id = $_SESSION['user_id'];

	return get_user_role_weight($user_id) >= get_role_weight($role);
}

function set_user_role($role, $user_id = null) {
	global $db;
	if ($user_id == null)
		// defaults to the current user
		$user_id = $_SESSION['user_id'];

	$prepared = $db->prepare("
            UPDATE users
			Set role_id = (SELECT role_id FROM roles WHERE name = ?)
			Where user_id = ?
        ");

	$prepared->bind_param('si', $role, $user_id);

	if (!$prepared->execute()) {
		$message['error'][] = ERROR;
		return false;
	}

	return true;
}

function ban_user($reason, $duration, $user_id) {
	if(!set_user_role(ROLE_BANNED, $user_id)) {
		return false;
	}
	add_ban_details($reason, $duration, $user_id);

	return true;
}

function delete_user($reason, $user_id) {
	if(!set_user_role(ROLE_DELETED, $user_id)) {
		return false;
	}
	add_ban_details($reason, null, $user_id);
	// TODO
	delete_profile($user_id);

	return true;
}

function add_ban_details($reason, $duration, $user_id) {
	global $db;

	$prepared = $db->prepare("
            INSERT INTO user_bans (user_id, until_date_time, reason)
            VALUES (
                ?,
                if(? IS NOT NULL, NOW() + INTERVAL ? DAY, NULL),
                ?
            )
        ");

	$prepared->bind_param('iiis', $user_id, $duration, $duration, $reason);

	if (!$prepared->execute()) {
		$message['error'][] = ERROR;
		return false;
	}

	return true;
}

function get_ban_details() {
	global $db;
	global $attempted_user_id;

	$prepared = $db->prepare("
			SELECT date_time, until_date_time, reason
			FROM user_bans
			WHERE user_id = ? AND
				date_time >= ALL (
						SELECT date_time
						FROM user_bans
						WHERE user_id = ?
						)
		");

	$prepared->bind_param('ii', $attempted_user_id, $attempted_user_id);

	if (!$prepared->execute()) {
		$message['error'][] = ERROR;
		return false;
	}

	$prepared->bind_result(
		$date_time,
		$until_date_time,
		$reason
	);

	$prepared->fetch();

	$prepared->free_result();

	return (object) array(
			'date_time'			=> $date_time,
			'until_date_time'	=> $until_date_time,
			'reason'			=> $reason
		);
}

function can_message_each_other($user_id1, $user_id2) {

	if (user_is_at_least_role(ROLE_ADMIN, $user_id1) || user_is_at_least_role(ROLE_ADMIN, $user_id2)) {
		// Everyone can talk to an admin
		return true;
	} else if (get_relationship($user_id1, $user_id2) == LIKE && get_relationship($user_id2, $user_id1) == LIKE) {
		// If they like each other mutually
		return true;
	}

	return false;
}
?>
