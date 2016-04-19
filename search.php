<?php
require_once 'core/init.php';
require_once 'core/func/profiles.php';
require_once 'core/func/interests.php';

verify_login();
// TODO permissions

$profiles = false;
$msg = '';


$_GET['page'] = (int) isset($_GET['page']) ? $_GET['page'] : 1;
$page_number = $_GET['page'];
$profiles_per_page = 11;

$_GET['page']--;
$nav_back = $_SERVER['PHP_SELF'] .'?'. http_build_query($_GET);

$_GET['page'] = $_GET['page'] + 2;
$nav_forward = $_SERVER['PHP_SELF'] .'?'. http_build_query($_GET);

$_GET['page']--;
?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main frame" role="main">
        <article class="entry">

            <?php if (isset($_GET['action']) && $_GET['action'] == 'browse') {
//                $profiles = get_all_profiles();
                include 'core/templates/browse-profiles.php';
            } else if (isset($_GET['action']) && $_GET['action'] == 'suggestions') {
                include 'core/templates/suggest-profiles.php';
            } else {
                include 'core/templates/search-profiles.php';
            } ?>


            <div class="search-results-container <?php if ($_GET['page'] == 1) echo 'first-page' ?> <?php if (count($profiles) < $profiles_per_page) echo 'last-page' ?>">
                <div id="search-result-page-container">
                    <?php

                    if ($profiles === false) {
                        if (in_array(MSG_UPGRADE_REQUIRED, $message['error'])) {
                            echo 'upgrade required';
                        } else {
                            echo 'error occurred';
                        }
                    } else {

                        if (strlen($msg) > 0) {
                            echo '<p>'.$msg.'</p>';
                        } else if (count($profiles) == 0) {
                            echo '<div class="search-no-result-message">';
                                if (isset($_GET['action']) && $_GET['action'] == 'browse') {
                                    if (isset($_GET['blocked'])) {
                                        echo "<p>You haven't blocked anybody!</p>";
                                    } else {
                                        echo "<p>Nobody was found!</p>";}
                                } else if (isset($_GET['action']) && $_GET['action'] == 'suggestions') {
                                    echo "<p>Sorry, we couldn't find any matches.</p>";
                                    echo "<p>Try broadening your interests!</p>";
                                } else {
                                    echo "<p>Sorry, we couldn't find anybody.</p>";
                                    echo "<p>Try broadening your search!</p>";
                                }
                            echo '</div>';
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

                            $profile = $profiles[$i - 1];

                            // TODO template?
                            ?>
                            <div id="profile_<?= $profile->user_id ?>" class="search-result-profile">
                                <a href="" onclick="get_profile(<?= $profile->user_id ?>)">
                                    <div class="profile-image">
                                        <img class="profile-pic"
                                             src=<?php echo get_profile_image(IMG_SMALL, $profile->user_id); ?>>
                                    </div>

                                    <div class="profile-info">
                                        <div class="profile-age">
                                            <p><?= $profile->age ?></p>
                                        </div>
                                        <!--                user_id-->
                                        <!--                first_name-->
                                        <!--                last_name-->
                                        <div class="profile-name">
                                            <p>
                                                <span class="profile-first-name"><?= $profile->first_name ?></span>
                                                <span class="profile-last-name"><?= $profile->last_name ?></span>
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
                    }
                    ?>

                    <script>
                        function get_profile(id) {
                            event.preventDefault()
                            $.post('ajax/get_profile.php', {id:id}, function(data) {
                                // Callback function
                                show_modal(data, 'modal-profile');
                            });
                        }
                    </script>
                </div>

                <div class="search-result-profile search-navigation search-navigation-left">
                    <a href="<?=$nav_back?>" onclick="">
                        <div class="profile-image">
                            <span class="fa-stack fa-lg">
                              <i class="fa fa-circle fa-stack-1x"></i>
                              <i class="fa fa-chevron-circle-left fa-stack-1x"></i>
                            </span>
                        </div>
                    </a>
                </div>
                <div class="search-result-profile search-navigation search-navigation-right">
                    <a href="<?=$nav_forward?>" onclick="">
                        <div class="profile-image">
                            <span class="fa-stack fa-lg">
                              <i class="fa fa-circle fa-stack-1x"></i>
                              <i class="fa fa-chevron-circle-right fa-stack-1x"></i>
                            </span>
                        </div>
                    </a>
                </div>
                <style>
                    .search-navigation {
                        margin-top: <?= (count($profiles) <= 7) ? '-190px' : '-380px' ?>;
                    }
                    .search-navigation .profile-image {
                        margin-top: 10px !important;
                    }

                    <?php if(count($profiles) == 4 || count($profiles) == 0) { ?>
                    .search-navigation {
                        margin-top: 0;
                        float: none;
                    }
                    <?php } ?>
                </style>

            </div>

        </article>
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
