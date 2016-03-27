<?php
function set_relationship($target_user_id, $status) {
    global $db;
    $user_id = $_SESSION['user_id'];

    $is_owner = ($target_user_id == $user_id);
    $can_edit_others = false;   // TODO change to admin?

    if ($is_owner || $can_edit_others) {
        // A user may not set a relationship with him/herself
        // An admin may not set a relationship
        return false;
    }
    // Current user can set a relationship

// TODO fix user_relataionship_status typo
    $prepared = $db->prepare("
            REPLACE INTO user_relationships (user_id, target_user_id, status_id)
            VALUES (
                ?,
                ?,
                (SELECT status_id
                 FROM user_relataionship_status
                 WHERE status = ?)
            )
        ");

    $prepared->bind_param('iis', $user_id, $target_user_id, $status);

    if (!$prepared->execute()) {
        // error push('failed');
        return false;
    }

    return true;
}

function get_relationship($target_user_id, $user_id = null) {
    global $db;
    if (!isset($user_id)) $user_id = $_SESSION['user_id'];

// TODO fix user_relataionship_status typo
    $prepared = $db->prepare("
            SELECT status
            FROM user_relationships NATURAL JOIN user_relataionship_status
            WHERE user_id = ? AND target_user_id = ?
        ");

    $prepared->bind_param('ii', $user_id, $target_user_id);

    if (!$prepared->execute()) {
        // error push('failed');
//        return false;
    }

    $prepared->bind_result(
        $status
    );

    $prepared->fetch();

    return $status;
}
