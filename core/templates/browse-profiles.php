<?php
/*
 * Browse template for search.php
 * Loads all users, allowing pagination
 * Hides users that have blocked the current user
 * Hides all users the current user has blocked, unless $_GET['blocked'] is set, in which case only blocked users are shown (for unblocking)
 */

global $message;

$query = (object) array(
    'stmt_parts'   => '',
    'param_values' => array(),
    'param_types'  => '',
    'join_parts'  => '',
    'end_parts'  => ''
);

if (isset($_GET['blocked']) && user_is_at_least_role(ROLE_ADMIN)) {
    $msg = 'Admins cannot block users';
    $profiles = null;
} else {


    $in_or_not = 'NOT IN';  // default to hiding blocked users

    if (isset($_GET['blocked'])) {
        $in_or_not = 'IN';
    }

    if (!user_is_at_least_role(ROLE_ADMIN)) {       // Admins can see all users i.e. they cannot be blocked by another user
        $query = query_add($query,
            "user_id $in_or_not (      -- user has not been blocked by the current user
                    SELECT target_user_id
                    FROM user_relationships NATURAL JOIN user_relationship_status
                    WHERE status = 'BLOCK' AND user_id = ? AND target_user_id = users.user_id
                )
            AND user_id NOT IN (      -- current user has been blocked
                    SELECT user_id
                    FROM user_relationships NATURAL JOIN user_relationship_status
                    WHERE status = 'BLOCK' AND user_id = users.user_id AND target_user_id = ?
                )",
            array(
                $_SESSION['user_id'],
                $_SESSION['user_id']
            ),
            'ii'
        );
    }

    $query_end_part = " LIMIT $limit_from,$limit_offset";
    $query = query_add($query, null, null, null, null, $query_end_part);

    // Search using query built
    $profiles = get_profiles($query->stmt_parts, $query->param_values, $query->param_types, $query->join_parts, $query->end_parts);

    ?>

    <?php if(!$ajax_request) { ?>
        <h2 class="page-title">Browse</h2>
    <?php } ?>
<?php } ?>