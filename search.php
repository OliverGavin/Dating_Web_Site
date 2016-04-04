<?php
require_once 'core/init.php';
require_once 'core/func/profiles.php';

verify_login();
// TODO permissions

?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <article class="entry">

            <?php if (isset($_GET['action']) && $_GET['action'] == 'browse') {
                $profiles = get_all_profiles();
            }else {
                include 'core/templates/search-profiles.php';
                // Search using query built
                $profiles = get_profiles($query->stmt_parts, $query->param_values, $query->param_types, $query->join_parts, $query->end_parts);
            } ?>

            <div class="search-results-container">
                <?php

                if (count($profiles) == 0) {
                    echo "<p>Sorry, we couldn't find any matches.</p>";
                    echo "<p>Try broadening your search!</p>";
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
