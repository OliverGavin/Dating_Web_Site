<?php
require_once 'core/init.php';
?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <article>

            <div class="search-form-container">
                <h3>I'm looking for somebody who is</h3>
                <form role="search" method="get" class="search-form" action="">
                    <fieldset>
                        <label>
                            <input type="search" name="likes" class="search-field" placeholder="funny, movies, sport" value="" title="Search for somebody like you!">
                        </label>
                        <label>
                            <select name="sex" class="select-field fontAwesome male" onchange="$(this).toggleClass('female', 'male')">
                                <option class="female" value="0">&#xf221;</option>
                                <option class="male" value="1" selected>&#xf222;</option>
                            </select>
                        </label>
                        <label>
                            <input type="number" name="min-age" class="numeric-field" min="18" max="100" step="1" value="20">
                            <span class="search-field-label">-</span>
                            <input type="number" name="max-age" class="numeric-field" min="18" max="100" step="1" value="26">
                        </label>
                    </fieldset>

                    <input type="hidden" name="action" value="new_search">

                    <input type="submit" value=" Go ">

                    <span class="search-more-options"> <i class="fa fa-chevron-down"></i> </span>
                </form>
            </div>






            <?php
            $n = 1;
            $lastBrAt = 0;
            for ($i = 1; $i < 20; $i++) {
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
            ?>
            <div class="search-result-profile">
                <div class="profile-image">
                    <img class="profile-pic" src="http://offline.fcwinti.com/wp-content/uploads/default-avatar-500x550.jpg">
                </div>

                <div class="profile-info">
                    <div class="profile-age">
                        <p>22</p>
                    </div>
                    <!--                user_id-->
                    <!--                first_name-->
                    <!--                last_name-->
                    <div class="profile-name">
                        <p>
                            <span class="profile-first-name">Jane</span>
                            <span class="profile-last-name">Doe</span>
                        </p>
                    </div>

                    <!--                country-->
                    <!--                county-->
                    <div class="profile-location">
                        <p>Limerick, Ireland</p>
                    </div>

                </div>
            </div>
            <?php
            }
            ?>
        </article>
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
