<?php
DEFINE('BLOCK',		"BLOCK");
DEFINE('DISLIKE',	"DISLIKE");
DEFINE('LIKE',		"LIKE");

/**
 * Sets the relationship between the current user and another user
 * @param integer $target_user_id
 * @param string $status                BLOCK, DISLIKE, LIKE
 * @return bool
 */
function set_relationship($target_user_id, $status) {
    global $db;
    $user_id = $_SESSION['user_id'];

    $is_owner = ($target_user_id == $user_id);

    if ($is_owner || user_is_at_least_role(ROLE_ADMIN)) {
        // A user may not set a relationship with him/herself
        // An admin may not set a relationship
        return false;
    }
    // Current user can set a relationship

    $prepared = $db->prepare("
            REPLACE INTO user_relationships (user_id, target_user_id, status_id)
            VALUES (
                ?,
                ?,
                (SELECT status_id
                 FROM user_relationship_status
                 WHERE status = ?)
            )
        ");

    $prepared->bind_param('iis', $user_id, $target_user_id, $status);

    if (!$prepared->execute()) {
        $message['error'][] = ERROR;
        return false;
    }

    return true;
}

/**
 * Gets the relationship between the current user and another user
 * @param $target_user_id
 * @param null $user_id
 * @return bool|string
 */
function get_relationship($target_user_id, $user_id = null) {
    global $db;
    if (!isset($user_id)) $user_id = $_SESSION['user_id'];

    $prepared = $db->prepare("
            SELECT status
            FROM user_relationships NATURAL JOIN user_relationship_status
            WHERE user_id = ? AND target_user_id = ?
        ");

    $prepared->bind_param('ii', $user_id, $target_user_id);

    if (!$prepared->execute()) {
        $message['error'][] = ERROR;
        return false;
    }

    $prepared->bind_result(
        $status
    );

    $prepared->fetch();

    return $status;
}
