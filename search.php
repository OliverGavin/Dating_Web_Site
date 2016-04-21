<?php
require_once 'core/init.php';
require_once 'core/func/profiles.php';
require_once 'core/func/interests.php';

$ajax_request = false;
if (isset($_GET['ajax']) && $_GET['ajax'] == true) {
    $ajax_request = true;
    $_GET = array_merge($_GET, $_GET['$_GET_query']);
    unset($_GET['$_GET_query']);
}

verify_login();
// TODO permissions

$profiles = false;
$msg = '';


$_GET['page'] = (int) isset($_GET['page']) ? $_GET['page'] : 1;
$page_number = $_GET['page'];
$profiles_per_page = 11;
$has_more = true;

$limit_from = $profiles_per_page*$page_number-$profiles_per_page;
$limit_offset = $profiles_per_page + 1;

$_GET['page']--;
$nav_back = $_SERVER['PHP_SELF'] .'?'. http_build_query($_GET);

$_GET['page'] = $_GET['page'] + 2;
$nav_forward = $_SERVER['PHP_SELF'] .'?'. http_build_query($_GET);

$_GET['page']--;
?>

<?php if(!$ajax_request) { ?>
<?php get_header(); ?>
<div id="primary" class="content-area">
    <main id="main" class="site-main frame" role="main">
        <article class="entry">
<?php } ?>

            <?php if (isset($_GET['action']) && $_GET['action'] == 'browse') {
//                $profiles = get_all_profiles();
                include 'core/templates/browse-profiles.php';
            } else if (isset($_GET['action']) && $_GET['action'] == 'suggestions') {
                include 'core/templates/suggest-profiles.php';
            } else {
                include 'core/templates/search-profiles.php';
            }

            if ($ajax_request && count($profiles) == 0) {
                echo 'no records';
                exit();
            }
            ?>


            <?php if(!$ajax_request) { ?>
            <div id="slider">
            <?php } ?>
            <div id="results" class="search-results-container <?php if ($_GET['page'] == 1) echo 'first-page' ?> <?php if (count($profiles) < $profiles_per_page) echo 'last-page' ?>">
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

                        if (count($profiles) <= $profiles_per_page) $has_more = false;

                        $n = 1;
                        $lastBrAt = 0;
                        for ($i = 1; $i <= count($profiles) && $i <= $profiles_per_page; $i++) {    // an extra profile is loaded to see if there are more pages, don't show it
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
                                                echo $profile->location;
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
                            $('#main').css({ "cursor": "wait" });
                            event.preventDefault()
                            $.post('ajax/get_profile.php', {id:id}, function(data) {
                                // Callback function
                                show_modal(data, 'modal-profile');
                                $('#main').css({ "cursor": "" });
                            });
                        }
                    </script>
                </div>

                <div id="search-navigation-left" class="search-result-profile search-navigation search-navigation-left">
                    <a href="<?=$nav_back?>" onclick="paginate_back()">
                        <div class="profile-image">
                            <span class="fa-stack fa-lg">
                              <i class="fa fa-circle fa-stack-1x"></i>
                              <i class="fa fa-chevron-circle-left fa-stack-1x"></i>
                            </span>
                        </div>
                    </a>
                </div>
                <?php if ($has_more) { ?>
                <div id="search-navigation-right" class="search-result-profile search-navigation search-navigation-right">
                    <a href="<?=$nav_forward?>" onclick="paginate_next()">
                        <div class="profile-image">
                            <span class="fa-stack fa-lg">
                              <i class="fa fa-circle fa-stack-1x"></i>
                              <i class="fa fa-chevron-circle-right fa-stack-1x"></i>
                            </span>
                        </div>
                    </a>
                </div>
                <?php } ?>
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

            <?php if(!$ajax_request) { ?>
            </div>
            <?php } ?>

<?php if(!$ajax_request) { ?>
            <script>
                var $_GET_query = <?php echo json_encode($_GET); ?>;

                var busy = false;

                var previous_page = null;
                var next_page = null;

                pre_load_page(1);   // pre load the next page

                function paginate_next() {
                    event.preventDefault();
                    $_GET_query.page++;
                    if (!paginate(1)) {
                        $_GET_query.page--;
                    }
                }

                function paginate_back() {
                    event.preventDefault();
                    $_GET_query.page--;
                    if (!paginate(-1)) {
                        $_GET_query.page++;
                    }
                }

                function paginate(direction) {
                    if (busy) return false;
                    busy = true;

                    if (direction == 1 && next_page != null) {
                        swap_page(direction, next_page);    // from cache
                        busy = false;
                        return true;
                    } else if (direction == -1 && previous_page != null) {
                        swap_page(direction, previous_page);    // from cache
                        busy = false;
                        return true;
                    }

                    $.get('<?= $_SERVER['PHP_SELF'] ?>', {$_GET_query:$_GET_query, ajax:true}, function(data) {
                        // Callback function
                        if (data != 'failed') {
                            // swap
                            swap_page(direction, data);

                        }
                        busy = false;
                    });
                    return true;
                }

                function swap_page(direction, data) {

                    var width = $('#results').outerWidth(true);
                    var height = $('#slider').height();
                    $('#slider').css({ "height":height });
                    if (direction == 1) {
                        previous_page = $('#results').clone();   // cache it
                        next_page = null;
                        pre_load_page(direction);
                        $('#results').after(data);
                        var $oldBox = $('#results');
                        var $newBox = $('#results').next();
                        $newBox.css({ "opacity": "0" });
                    } else {
                        next_page = $('#results').clone();   // cache it
                        previous_page = null;
                        pre_load_page(direction);
                        $('#results').before(data);
                        var $newBox = $('#results');
                        $newBox.css({ "margin-left": "-900px", "opacity": "0" });
                        var $oldBox = $('#results').next();
                    }


                    if (direction == 1) {
                        $oldBox.animate({
                            "margin-left": -width * direction + "px",
                            "opacity": "0"
                        }, 300, function () {
                            $oldBox.css({ "margin-left": "", "margin-right": "", "visibility": "hidden" });
                            $oldBox.remove();
                        });
                        $newBox.animate({
                            "opacity": "1"
                        }, 300);
                    } else {
                        $newBox.animate({
                            "margin-left": "0px",
                            "opacity": "1"
                        }, 300, function () {
                            $oldBox.css({ "margin-left": "", "margin-right": "", "visibility": "hidden" });
                            $oldBox.remove();
                        });
                        $oldBox.animate({
                            "opacity": "0"
                        }, 300);
                    }

                }

                function pre_load_page(direction) {
                    var $_GET_query_future = JSON.parse(JSON.stringify($_GET_query));
                    if (direction == 1) {
                        $_GET_query_future.page++;
                    } else {
                        $_GET_query_future.page--;
                    }
                    console.log('preloading page: '+$_GET_query_future.page);
                    $.get('<?= $_SERVER['PHP_SELF'] ?>', {$_GET_query:$_GET_query_future, ajax:true}, function(data) {
                        // Callback function
                        if (data != 'failed') {

                            if (data.trim() == 'no records') {
                                if (direction == 1) {
//                                    $('#search-navigation-right').remove();
                                } else {
//                                    $('#search-navigation-left').remove();
                                }
                                console.log('no records at page: '+$_GET_query_future.page);
                                return;
                            }

                            if (direction == 1) {
                                next_page = data;   // cache it
                            } else {
                                previous_page = data;   // cache it
                            }

                            console.log('done preloading page: '+$_GET_query_future.page);
                        }
                    });
                }
            </script>
            <style>
                #slider {
                    text-align: left;
                    width: 200%;
                }
                #slider #results {
                    text-align: center;
                }
                div#results {
                    width: calc(50% - 5px);
                    display: inline-block !important;
                    vertical-align: top;
                    /* float: left; */
                }
            </style>
        </article>
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
<?php } ?>
