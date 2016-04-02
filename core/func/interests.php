<?php
function get_interest() {

}

function add_interest($user_id, $likes, $content) {
    global $db;

    $content = ucfirst($content);

    $is_owner = ($user_id == $_SESSION['user_id']);
    $can_edit_others = false;   // TODO change to admin?

    if (!$is_owner && !$can_edit_others) {
        // Owner or admin are allowed to make this change
        // Exit otherwise
        return false;
    }

    $prepared = $db->prepare("
            CALL add_interest( ?, ?, ? );
        ");

    $prepared->bind_param('iis', $user_id, $likes, $content);

    if (!$prepared->execute()) {
        // error push('failed');
        return false;
    }

    return true;

}

function remove_interest($user_id, $interests_id) {
    global $db;

    $is_owner = ($user_id == $_SESSION['user_id']);
    $can_edit_others = false;   // TODO change to admin?

    if (!$is_owner && !$can_edit_others) {
        // Owner or admin are allowed to make this change
        // Exit otherwise
        return false;
    }

    $prepared = $db->prepare("
            DELETE FROM profile_interests
            WHERE user_id = ? AND interests_id = ?
        ");

    $prepared->bind_param('ii', $user_id, $interests_id);

    if (!$prepared->execute()) {
        // error push('failed');
        return false;
    }

    return true;

}


function get_interests($user_id, $likes = null) {  // TODO add extra query conditions? sorting?
    global $db;

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

    $prepared->execute();
    // TODO error detection
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
