<?php
require_once 'core/init.php';
require_once 'core/func/profiles.php';

verify_login();
// TODO permissions

$current_user_id = $_SESSION['user_id'];
// Load users preferences as the default search values
$current_user_profile = new Profile($current_user_id);
$current_user_profile->fetch();

$search_text = null;
$search_sex = null;
$search_min_age = null;
$search_max_age = null;

if (isset($_POST['search_text']))       $search_text = $_POST['search_text'];
else                                    $search_text = "";

if (isset($_POST['sex']))               $search_sex = $_POST['sex'];
else                                    $search_sex = ($current_user_profile->looking_for ?: !$current_user_profile->sex);

if (isset($_POST['min_age']))           $search_min_age = $_POST['min_age'];
else                                    $search_min_age = ($current_user_profile->min_age ?: $current_user_profile->age-5);

if (isset($_POST['max_age']))           $search_max_age = $_POST['max_age'];
else                                    $search_max_age = ($current_user_profile->max_age ?: $current_user_profile->age+5);


//$search_sex = ($current_user_profile->looking_for ?: !$current_user_profile->sex);
//$search_min_age = ($current_user_profile->min_age ?: $current_user_profile->age-5);
//$search_max_age = ($current_user_profile->max_age ?: $current_user_profile->age+5);

?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <article class="entry">

            <div class="search-form-container">
                <h3>I'm looking for somebody who is</h3>
                <form role="search" method="post" class="search-form style-rounded-dark" action="">
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
                            <input type="text" id="" name="">
                        </div>
                    </fieldset>
                </form>
            </div>

            <div class="search-results-container">
                <?php
                /**
                 * @param object $query
                 * @param string $stmt_part
                 * @param array|integer $param_value
                 * @param string $param_type
                 * @return object mixed
                 */
                function query_add($query, $stmt_part, $param_value, $param_type) {

                    $query->stmt_parts  .= ' '.$stmt_part;
                    if (is_array($param_value)) {
                        array_merge($query->param_values, $param_value);
                    } else {
                        array_push($query->param_values, $param_value);
                    }
                    $query->param_types .= $param_type;

                    return $query;
                }

                $query = (object) array(
                        'stmt_parts'   => '',
                        'param_values' => array(),
                        'param_types'  => ''
                    );

                if (isset($search_text)) {
                    // TODO
                    // $query = query_add();
                }

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

                // Search using query built
                $profiles = get_profiles($query->stmt_parts, $query->param_values, $query->param_types);

                if (count($profiles) == 0) {
                    echo "Sorry, we couldn't find any matches.";
                    echo "Try broadening your search!";
                }

                $n = 1;
                $lastBrAt = 0;
                for ($i = 1; $i <= count($profiles); $i++) {
                    // Determines when a line break is outputted so that profiles appear as groups of 4,3,4,3,....
                    if ($n % 5 === 0 && $lastBrAt !== 5) {
                        echo '<br />';
                        $lastBrAt = 5;
                        $n = 1;
                    } else if ($n % 4 === 0 && $lastBrAt !== 4 && $i !== 4) {
                        echo '<br />';
                        $lastBrAt = 4;
                        $n = 1;
                    }
                    $n++;

                    $profile = $profiles[$i-1];

                    // TODO template?
                ?>
                    <div id="profile_<?=$profile->user_id?>" class="search-result-profile">
                        <a href="" onclick="get_profile(<?=$profile->user_id?>)">
                            <div class="profile-image">
                                <img class="profile-pic" src=<?php echo get_profile_image(300, $profile->user_id); ?>>
                            </div>

                            <div class="profile-info">
                                <div class="profile-age">
                                    <p><?=$profile->age?></p>
                                </div>
                                <!--                user_id-->
                                <!--                first_name-->
                                <!--                last_name-->
                                <div class="profile-name">
                                    <p>
                                        <span class="profile-first-name"><?=$profile->first_name?></span>
                                        <span class="profile-last-name"><?=$profile->last_name?></span>
                                    </p>
                                </div>

                                <!--                country-->
                                <!--                county-->
                                <div class="profile-location">
                                    <p>
                                        <?php
                                        echo $profile->county;
                                        if (!empty($profile->county) && !empty($profile->country)) echo ', ';
                                        echo $profile->country;
                                        ?>
                                    </p>
                                </div>

                            </div>
                        </a>
                    </div>
                <?php
                }
                ?>

                <script>
                    function get_profile(id) {
                        event.preventDefault()
                        $.post('ajax/get_profile.php', {id:id}, function(data) {
                            // Callback function
                            show_modal(data);
                        });
                    }
                </script>
            </div>
        </article>
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
