<?php   // TODO refactor and add reverse checks
global $message;
/**
 * Builds query parts ready for use with prepared statements
 * @param object $query
 * @param string $stmt_part
 * @param array|integer $param_value
 * @param string $param_type
 * @param string $join_part
 * @return object mixed
 */
function query_add($query, $stmt_part, $param_value = null, $param_type = null, $join_part = null, $end_part = null) {

    $query->stmt_parts  .= ' '.$stmt_part;
    if (isset($param_value) && isset($param_type)) {
        if (is_array($param_value)) {
            $query->param_values = array_merge($query->param_values, $param_value);
        } else {
            array_push($query->param_values, $param_value);
        }
        $query->param_types .= $param_type;
    }
    $query->join_parts  .= $join_part;
    $query->end_parts  .= $end_part;

    return $query;
}

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