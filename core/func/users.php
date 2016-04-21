<?php
DEFINE('BLOCK',		"BLOCK");
DEFINE('DISLIKE',	"DISLIKE");
DEFINE('LIKE',		"LIKE");


function get_user_name($target_user_id) {
    global $db;

    $prepared = $db->prepare("
            SELECT first_name, last_name
            FROM users
            WHERE user_id = ?
        ");

    $prepared->bind_param('i', $target_user_id);

    if (!$prepared->execute()) {
        $message['error'][] = ERROR;
        return false;
    }

    $prepared->bind_result(
        $first_name,
        $last_name
    );

    $prepared->fetch();

    return (object) array(
            'first_name'    => $first_name,
            'last_name'    => $last_name
        );
}

/**
 * Sets the relationship between the current user and another user
 * @param integer $target_user_id
 * @param string $status                BLOCK, DISLIKE, LIKE
 * @return bool
 */
function set_relationship($target_user_id, $status) {
    global $db, $pathToRoot;
    require_once $pathToRoot.'core/func/notifications.php';
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

    if ($status == LIKE) {
        if (get_relationship($user_id, $target_user_id) == LIKE) {  // mutual like
            create_notification($target_user_id, null, "MUTUAL_LIKE");

        } else {
            create_notification($target_user_id, null, LIKE);

        }
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

function user_is_blocked_by($target_user_id, $user_id = null) {
    if (!isset($user_id)) $user_id = $_SESSION['user_id'];
    return get_relationship($user_id, $target_user_id) === BLOCK;
}
