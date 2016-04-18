<?php
/**
 * Adds a like/dislike for a user
 * @param integer $user_id      the user's id for which to add the like/dislike
 * @param boolean $likes        true for a like, false for a dislike
 * @param string $content       what the interest is
 * @return bool                 true on success, false on error/permission denied
 */
function add_interest($user_id, $likes, $content) {
    global $db;

    $is_owner = ($user_id == $_SESSION['user_id']);

    if (!$is_owner && !user_can(PERM_EDIT_OTHERS_PROFILE)) {
        // Owner or admin are allowed to make this change
        // Exit otherwise
        $message['error'][] = MSG_PERMISSION_DENIED;
        return false;
    }
    if (!user_can(PERM_EDIT_PROFILE)) {
        // Owner must be allowed to edit their profile
        // Exit otherwise
        $message['error'][] = MSG_UPGRADE_REQUIRED;
        return false;
    }

    $content = ucfirst(trim($content));
    if (strlen($content) == 0) {
        $message['error'][] = 'empty string provided';
        return false;
    }

    $prepared = $db->prepare("
            CALL add_interest( ?, ?, ? );
        ");

    $prepared->bind_param('iis', $user_id, $likes, $content);

    if (!$prepared->execute()) {
        $message['error'][] = 'an error occurred';
        return false;
    }

    return true;

}

/**
 * Removes a like/dislike for a user
 * @param integer $user_id      the user's id for which to remove the like/dislike
 * @param $interests_id         the id of the like/dislike which is to be removed
 * @return bool                 true on success, false on error/permission denied
 */
function remove_interest($user_id, $interests_id) {
    global $db;

    $is_owner = ($user_id == $_SESSION['user_id']);

    if (!$is_owner && !user_can(PERM_EDIT_OTHERS_PROFILE)) {
        // Owner or admin are allowed to make this change
        // Exit otherwise
        $message['error'][] = MSG_PERMISSION_DENIED;
        return false;
    }
    if (!user_can(PERM_EDIT_PROFILE)) {
        // Owner must be allowed to edit their profile
        // Exit otherwise
        $message['error'][] = MSG_UPGRADE_REQUIRED;
        return false;
    }

    $prepared = $db->prepare("
            DELETE FROM profile_interests
            WHERE user_id = ? AND interests_id = ?
        ");

    $prepared->bind_param('ii', $user_id, $interests_id);

    if (!$prepared->execute()) {
        $message['error'][] = 'an error occurred';
        return false;
    }

    return true;

}


/**
 * Gets all the users like/dislikes (interests)
 * @param $user_id          the user's id for which we are getting the likes of
 * @param null $likes       true to return like only, false to return dislikes only
 * @return array            a list of interests
 */
function get_interests($user_id, $likes = null) {  // TODO add extra query conditions? sorting?
    global $db;
    global $message;

    if (!user_can(PERM_VIEW_PROFILES)) {
        $message['error'][] = MSG_UPGRADE_REQUIRED;
        return false;
    }

    $query_parts  = "";
    if (isset($likes))
        if ($likes)
            $query_parts  = " AND likes = TRUE";
        else
            $query_parts  = " AND likes = FALSE";

    $prepared = $db->prepare("
              SELECT interests_id, `likes`, content, interest_score
              FROM profile_interests NATURAL JOIN interests
              WHERE user_id = ? $query_parts
              ORDER BY content ASC
            ");

    $prepared->bind_param('i', $user_id);

    if (!$prepared->execute()) {
        $message['error'][] = ERROR;
        return false;
    }

    $prepared->bind_result(
        $interests_id,
        $likes,
        $content,
        $interest_score
    );

    $interests = array();

    while ($prepared->fetch()) {
        array_push($interests, (object) array(
            'interests_id'   => $interests_id,
            'like'           => $likes,
            'content'        => $content,
            'interest_score' => $interest_score
        ));
    }

    return $interests;

}
