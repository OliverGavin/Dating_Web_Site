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

// Load users preferences as the default search values
$current_user_id = $_SESSION['user_id'];
$current_user_profile = new Profile($current_user_id);

if (user_is_at_least_role(ROLE_ADMIN)) {
    $msg = 'Admins cannot have suggestions';
    $profiles = null;
} else if (!$current_user_profile->fetch()) {
    $msg .= 'You need to create a profile first!';
    $profiles = null;
} else {

    $search_sex = ($current_user_profile->looking_for ?: !$current_user_profile->sex);

    $search_min_age = (isset($current_user_profile->min_age) ? $current_user_profile->min_age : (isset($current_user_profile->age) ? max($current_user_profile->age - 5, 18) : 18) );

    $search_max_age = (isset($current_user_profile->max_age) ? $current_user_profile->max_age : (isset($current_user_profile->age) ? min($current_user_profile->age + 5, 100) : 100) );

    ?>

<?php if(!$ajax_request) { ?>
    <h2 class="page-title">Suggestions</h2>
<?php } ?>

    <?php

    $search_like    = get_interests($current_user_id, true);
    $search_dislike = get_interests($current_user_id, false);

    if (is_array($search_like) && is_array($search_dislike)) {

        $search_like_text = implode(', ', array_map(function ($like) {
            return $like->content;
        }, $search_like));

        $search_dislike_text = implode(', ', array_map(function ($dislike) {
            return $dislike->content;
        }, $search_dislike));

        $join_part = "
            LEFT JOIN
                (SELECT user_id, SUM(like_dislike_score) as match_score
                 FROM
                    (SELECT *, if(likes = true, 1, -0.5) as like_dislike_score
                     FROM profile_interests LEFT JOIN interests USING(interests_id)
                     WHERE  MATCH (content) AGAINST (?)
                     UNION
                     SELECT *, if(likes = false, 1, -0.5) as like_dislike_score
                     FROM profile_interests LEFT JOIN interests USING(interests_id)
                     WHERE  MATCH (content) AGAINST (?)
                    ) t
                 GROUP BY user_id) t USING(user_id)";
        // ? is list of MY likes. 1 point if they like it too, -0.5 if they don't
        // ? is list of MY dislikes. 1 point if they dislike it too, -0.5 if they do

        $query = query_add($query, null, array($search_like_text, $search_dislike_text), "ss", $join_part);

        if (isset($search_sex)) {
            $query = query_add($query, 'sex = ?', $search_sex, 'i');
        }

        if (isset($search_min_age)) {
            // Get the difference in days between DOB and now. Divide by 365.25 to get difference in years. Round down to get age.
            $query = query_add($query, 'AND FLOOR( DATEDIFF(CURDATE(), DOB)/365.25 ) >= ?', $search_min_age, 'i');
        }

        if (isset($search_max_age)) {
            $query = query_add($query, 'AND FLOOR( DATEDIFF(CURDATE(), DOB)/365.25 ) <= ?', $search_max_age, 'i');
        }

        $query = query_add($query,
            "AND user_id NOT IN (      -- user has not been blocked by the current user
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

        $query_end_part = " AND (match_score > 0 OR match_score IS NULL) ORDER BY match_score DESC";
        $query = query_add($query, null, null, null, null, $query_end_part);

        $query_end_part = " LIMIT $limit_from,$limit_offset";
        $query = query_add($query, null, null, null, null, $query_end_part);

        // Search using query built
        $profiles = get_profiles($query->stmt_parts, $query->param_values, $query->param_types, $query->join_parts, $query->end_parts);
    }
}
?>
