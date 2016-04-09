<?php
function send_message($user_id, $content) {
    global $db;
    global $message;

    if (!user_can(PERM_SEND_MESSAGES)) {
        $message['error'][] = MSG_UPGRADE_REQUIRED;
        return false;
    }

    if (!can_message_each_other($user_id, $_SESSION['user_id'])) {
        $message['error'][] = MSG_PERMISSION_DENIED;
        return false;
    }

    $prepared = $db->prepare("
              INSERT INTO messages (sender_id, receiver_id, content)
              VALUES (?, ?, ?)
            ");

    $prepared->bind_param('iis', $_SESSION['user_id'], $user_id, $content);

    if (!$prepared->execute()) {
        $message['error'][] = ERROR;
        return false;
    }

    return true;
}

function get_messages($user_id, $seen = null) {
    global $db;
    global $message;

    if (!user_can(PERM_SEND_MESSAGES)) {
        $message['error'][] = MSG_UPGRADE_REQUIRED;
        return false;
    }

    if (!can_message_each_other($user_id, $_SESSION['user_id'])) {
        $message['error'][] = MSG_PERMISSION_DENIED;
        return false;
    }

    $prepared = $db->prepare("
              SELECT message_id, sender_id, receiver_id, time_date, content
              FROM messages
              WHERE (sender_id = ? AND receiver_id = ?)
                 OR (sender_id = ? AND receiver_id = ?)
              ORDER BY time_date
            ");

    echo $db->error;

    $prepared->bind_param('iiii', $user_id, $_SESSION['user_id'], $_SESSION['user_id'], $user_id);

    if (!$prepared->execute()) {
        $message['error'][] = ERROR;
        return false;
    }

    $prepared->bind_result(
        $message_id,
        $sender_id,
        $receiver_id,
        $time_date,
        $content
    );

    $messages = array();

    while ($prepared->fetch()) {
        array_push($messages, (object) array(
            'message_id'   => $message_id,
            'sender_id'           => $sender_id,
            'receiver_id'        => $receiver_id,
            'interest_score' => $time_date,
            'content' => $content
        ));
    }

    return $messages;
}

function set_messages_from_user_seen($sender_id) {
    global $db;
    global $message;

    $prepared = $db->prepare("
              UPDATE messages
              SET seen = TRUE
              WHERE sender_id = ? AND receiver_id = ?
            ");

    $prepared->bind_param('ii', $sender_id, $_SESSION['user_id']);

    if (!$prepared->execute()) {
        $message['error'][] = ERROR;
        return false;
    }

    return true;

}

function get_latest_messages() {
    global $db;
    global $message;

    if (!user_can(PERM_SEND_MESSAGES)) {
        $message['error'][] = MSG_UPGRADE_REQUIRED;
        return false;
    }

    // TODO mutual and blocking
//    if (!can_message_each_other($user_id, $_SESSION['user_id'])) {
//        $message['error'][] = MSG_PERMISSION_DENIED;
//        return false;
//    }

//            SELECT message_id, sender_id, receiver_id, time_date, content, seen,
//                  if(us.user_id = ?, CONCAT(ur.first_name, ' ', ur.last_name), CONCAT(us.first_name, ' ', us.last_name)) as target_name
//            FROM messages JOIN users us on sender_id = us.user_id JOIN users ur on receiver_id = ur.user_id
//            WHERE message_id IN (SELECT MAX(message_id)
//                                 FROM messages
//                                 GROUP BY if(sender_id > receiver_id, CONCAT(sender_id, receiver_id), CONCAT(receiver_id, sender_id))
//                                )
//                AND (sender_id = ? OR receiver_id = ?)
//
//            SELECT messages.*, if(us.user_id = 23, CONCAT(ur.first_name, ' ', ur.last_name), CONCAT(us.first_name, ' ', us.last_name)) as target_name, unseen_count
//            FROM messages JOIN users us on sender_id = us.user_id JOIN users ur on receiver_id = ur.user_id
//				JOIN (SELECT receiver_id as rid, COUNT(seen) as unseen_count
//					  FROM messages
//                      WHERE seen = FALSE
//                      GROUP BY receiver_id) t on receiver_id = rid
//                      WHERE message_id IN (SELECT MAX(message_id)
//					 FROM messages
//                     GROUP BY if(sender_id > receiver_id, CONCAT(sender_id, receiver_id), CONCAT(receiver_id, sender_id))
//					)
//                AND (sender_id = 23 OR receiver_id = 23)

//    SELECT messages.*, if(us.user_id = 23, CONCAT(ur.first_name, ' ', ur.last_name), CONCAT(us.first_name, ' ', us.last_name)) as target_name, unseen_count
//    FROM messages JOIN users us on sender_id = us.user_id JOIN users ur on receiver_id = ur.user_id
//                    JOIN (SELECT sender_id as sid, receiver_id as rid, COUNT(seen) as unseen_count
//                          FROM messages
//                          WHERE seen = FALSE AND receiver_id = 23
//                          GROUP BY sender_id) t on (receiver_id = rid AND sender_id = sid) OR (receiver_id = sid AND sender_id = rid)
//    WHERE message_id IN (SELECT MAX(message_id)
//                         FROM messages
//                         GROUP BY if(sender_id > receiver_id, CONCAT(sender_id, receiver_id), CONCAT(receiver_id, sender_id))
//                        )
//    AND (sender_id = 23 OR receiver_id = 23)

    $prepared = $db->prepare("
            SELECT message_id, sender_id, receiver_id, time_date, content, unseen_count,
                  if(us.user_id = ?, CONCAT(ur.first_name, ' ', ur.last_name), CONCAT(us.first_name, ' ', us.last_name)) as target_name
            FROM messages JOIN users us on sender_id = us.user_id JOIN users ur on receiver_id = ur.user_id
                LEFT JOIN (SELECT sender_id as sid, receiver_id as rid, COUNT(seen) as unseen_count
                           FROM messages
                           WHERE seen = FALSE AND receiver_id = ?
                           GROUP BY sender_id) t on (receiver_id = rid AND sender_id = sid) OR (receiver_id = sid AND sender_id = rid)
            WHERE message_id IN (SELECT MAX(message_id)
                                 FROM messages
                                 GROUP BY if(sender_id > receiver_id, CONCAT(sender_id, receiver_id), CONCAT(receiver_id, sender_id))
                                )
                AND (sender_id = ? OR receiver_id = ?)
            ");

    echo $db->error;

    $prepared->bind_param('iiii', $_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id']);

    if (!$prepared->execute()) {
        $message['error'][] = ERROR;
        return false;
    }

    $prepared->bind_result(
        $message_id,
        $sender_id,
        $receiver_id,
        $time_date,
        $content,
        $seen,
        $target_name
    );

    $messages = array();

    while ($prepared->fetch()) {
        array_push($messages, (object) array(
            'message_id'   => $message_id,
            'sender_id'           => $sender_id,
            'receiver_id'        => $receiver_id,
            'interest_score' => $time_date,
            'content' => $content,
            'seen' => $seen,
            'target_name' => $target_name
        ));
    }

    return $messages;
}