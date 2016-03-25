<?php
require_once 'core/init.php';
require_once 'core/func/profiles.php';

verify_login();
// TODO permissions

$current_user_id = $_SESSION['user_id'];
// Load users preferences as the default search values
$current_user_profile = new Profile($current_user_id);
$current_user_profile->fetch();

$search_text = "";
$search_sex = (isset($current_user_profile->looking_for) ? $current_user_profile->looking_for : !$current_user_profile->sex);
$search_min_age = (isset($current_user_profile->min_age) ? $current_user_profile->min_age : $current_user_profile->age-5);
$search_max_age = (isset($current_user_profile->max_age) ? $current_user_profile->max_age : $current_user_profile->age+5);

?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <article class="entry">

            <div class="search-form-container">
                <h3>I'm looking for somebody who is</h3>
                <form role="search" method="get" class="search-form" action="">
                    <fieldset>
                        <label>
                            <input type="search" id="text" name="text" class="search-field" placeholder="funny, movies, sport" value="<?=$search_text?>" title="Search for somebody like you!">
                        </label>
                        <label>
                            <select id="sex" name="sex" class="select-field fontAwesome <?php echo ($search_sex == 1) ? 'male':'female'?>" onchange="$(this).toggleClass('female').toggleClass('male')">
                                <option class="male" <?php echo (($search_sex == 1) ? "selected=\"selected\"" : ""); ?> value="1" selected>&#xf222;</option>
                                <option class="female" <?php echo (($search_sex == 0) ? "selected=\"selected\"" : ""); ?> value="0">&#xf221;</option>
                            </select>
                        </label>
                        <label>
                            <input type="number" id="min_age" name="min_age" class="numeric-field" min="18" max="100" step="1" value="<?=$search_min_age?>">
                            <span class="search-field-label">-</span>
                            <input type="number" id="max_age" name="max_age" class="numeric-field" min="18" max="100" step="1" value="<?=$search_max_age?>">
                        </label>
                    </fieldset>

                    <input type="hidden" name="action" value="new_search">

                    <input type="submit" value=" Go ">

                    <span class="search-more-options"> <i class="fa fa-chevron-down"></i> </span>
                </form>
            </div>

            <div class="search-results-container">
                <?php
                $profiles = get_profiles();

                $n = 1;
                $lastBrAt = 0;
                for ($i = 1; $i <= count($profiles); $i++) {
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
