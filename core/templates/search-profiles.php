<?php
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
$current_user_profile->fetch();

$search_text = null;
$search_sex = null;
$search_min_age = null;
$search_max_age = null;
$location = null;
$name = null;

if (isset($_GET['search_text'])) $search_text = $_GET['search_text'];
else                                    $search_text = "";

if (isset($_GET['sex'])) $search_sex = $_GET['sex'];
else                                    $search_sex = ($current_user_profile->looking_for ?: !$current_user_profile->sex);

if (isset($_GET['min_age'])) $search_min_age = $_GET['min_age'];
else                                    $search_min_age = (isset($current_user_profile->min_age) ? $current_user_profile->min_age : (isset($current_user_profile->age) ? max($current_user_profile->age - 5, 18) : 18) );

if (isset($_GET['max_age'])) $search_max_age = $_GET['max_age'];
else                                    $search_max_age = (isset($current_user_profile->max_age) ? $current_user_profile->max_age : (isset($current_user_profile->age) ? min($current_user_profile->age + 5, 100) : 100) );

if (isset($_GET['location'])) $location = trim(preg_replace('/\s+/', ' ', $_GET['location']));

if (isset($_GET['name'])) $name = trim(preg_replace('/\s+/', ' ', $_GET['name']));

?>

<?php if(!$ajax_request) { ?>
<h2 class="page-title">Search</h2>

<div class="search-form-container">
    <h3>I'm looking for somebody who likes...</h3>
    <form role="search" method="GET" class="search-form style-rounded-dark" action="">
        <fieldset>
            <div class="group left-rounded">
                <label>
                    <input type="text" id="search_text" name="search_text" placeholder="funny, movies, sport" value="<?=$search_text?>" title="Search for somebody like you!">
                </label>
                <label>
                    <select id="sex" name="sex" class="fontAwesome <?php echo ($search_sex == 1) ? 'male':'female'?>" onchange="$(this).toggleClass('female').toggleClass('male')">
                        <option class="male" <?php echo (($search_sex == 1) ? "selected=\"selected\"" : ""); ?> value="1" selected>&#xf222;</option>
                        <option class="female" <?php echo (($search_sex == 0) ? "selected=\"selected\"" : ""); ?> value="0">&#xf221;</option>
                    </select>
                </label>
                <label>
                    <input type="number" id="min_age" name="min_age" min="18" max="100" step="1" value="<?=$search_min_age?>">
                    <span class="numeric-field-range">-</span>
                    <input type="number" id="max_age" name="max_age" min="18" max="100" step="1" value="<?=$search_max_age?>">
                </label>
            </div>
        </fieldset>

        <input type="hidden" name="action" value="new_search">

        <input type="submit" value=" Go ">

        <span class="search-more-options" title="Advanced search" onclick="$('#advanced-search').toggleClass('expanded')"> <i class="fa fa-chevron-down"></i> </span>
        <fieldset id="advanced-search">
            <div class="group both-rounded">
                <label class="visible"><i class="fa fa-globe"></i>&nbsp; Location</label>
                <input type="text" id="location" name="location" value="<?=$location?>">
            </div>
            <div class="group both-rounded">
                <label class="visible"><i class="fa fa-globe"></i>&nbsp; Name</label>
                <input type="text" id="name" name="name" value="<?=$name?>">
            </div>
        </fieldset>
    </form>
</div>
<?php } ?>

<?php

if (isset($search_text) && !empty($search_text)) {
    $search_text = trim($search_text);

    $join_part = "
        LEFT JOIN
            (SELECT *, SUM(if(likes = true, 1, -0.5)) as like_score
             FROM profile_interests LEFT JOIN interests USING(interests_id)
             WHERE  MATCH (content) AGAINST (?)
             GROUP BY user_id) t USING(user_id)
        ";


//    $join_part = "
//        RIGHT JOIN
//            (SELECT user_id, COUNT(*) as like_score
//             FROM profile_interests LEFT JOIN interests USING(interests_id)
//             WHERE  MATCH (content) AGAINST (?) AND likes = TRUE
//             GROUP BY user_id) t USING(user_id)
//        ";
//
//
//    $join_part = "
//            LEFT JOIN
//                (SELECT user_id, SUM(like_dislike_score) as match_score
//                 FROM
//                    (SELECT *, if(likes = true, 1, -0.5) as like_dislike_score
//                     FROM profile_interests LEFT JOIN interests USING(interests_id)
//                     WHERE  MATCH (content) AGAINST (?)
//                     UNION
//                     SELECT *, if(likes = false, 1, -0.5) as like_dislike_score
//                     FROM profile_interests LEFT JOIN interests USING(interests_id)
//                     WHERE  MATCH (content) AGAINST (?)
//                    ) t
//                 GROUP BY user_id) t USING(user_id)";

    $query = query_add($query, null, $search_text, "s", $join_part);
}

if (isset($search_sex)) {
    $query = query_add($query, 'sex = ?', $search_sex, 'i');
}

if (isset($location) && !empty($location)) {
    $query = query_add($query,
        'AND  MATCH (location) AGAINST (?)',
        $location, 's');
}

if (isset($name) && !empty($name)) {
    $query = query_add($query,
        "AND CONCAT_WS(' ', first_name, last_name) LIKE CONCAT('%', ?, '%')",
        $name, 's');
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


if (isset($search_text) && !empty($search_text)) {
    $query_end_part = " AND (like_score > 0 OR like_score IS NULL) ORDER BY like_score DESC";
    $query = query_add($query, null, null, null, null, $query_end_part);
}

$query_end_part = " LIMIT $limit_from,$limit_offset";
$query = query_add($query, null, null, null, null, $query_end_part);

// Search using query built
$profiles = get_profiles($query->stmt_parts, $query->param_values, $query->param_types, $query->join_parts, $query->end_parts);

?>
