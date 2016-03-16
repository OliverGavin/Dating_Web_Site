<?php
require_once 'core/init.php';

verify_login();
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



            <div class="search-result-profile">
                <div class="profile-image">
                    <img class="profile-pic" src="http://orig06.deviantart.net/b682/f/2013/135/4/3/profile_picture_by_mellodydoll_stock-d65fbf8.jpg">
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
            <div class="search-result-profile">
                <div class="profile-image">
                    <img class="profile-pic" src="http://img05.deviantart.net/038e/i/2010/150/b/0/me_2010_by_axy_stock.jpg">
                </div>

                <div class="profile-info">
                    <div class="profile-age">
                        <p>20</p>
                    </div>
                    <!--                user_id-->
                    <!--                first_name-->
                    <!--                last_name-->
                    <div class="profile-name">
                        <p>
                            <span class="profile-first-name">Emily</span>
                            <span class="profile-last-name">O'Brien</span>
                        </p>
                    </div>

                    <!--                country-->
                    <!--                county-->
                    <div class="profile-location">
                        <p>Dublin, Ireland</p>
                    </div>

                </div>
            </div>
            <div class="search-result-profile">
                <div class="profile-image">
                    <img class="profile-pic" src="http://previews.123rf.com/images/gabivali/gabivali1002/gabivali100200010/6353499-Profile-of-pretty-face-model-as-Prom-Queen-at-party-Stock-Photo.jpg">
                </div>

                <div class="profile-info">
                    <div class="profile-age">
                        <p>25</p>
                    </div>
                    <!--                user_id-->
                    <!--                first_name-->
                    <!--                last_name-->
                    <div class="profile-name">
                        <p>
                            <span class="profile-first-name">Sarah</span>
                            <span class="profile-last-name">Kelly</span>
                        </p>
                    </div>

                    <!--                country-->
                    <!--                county-->
                    <div class="profile-location">
                        <p>Dublin, Ireland</p>
                    </div>

                </div>
            </div>
            <div class="search-result-profile">
                <div class="profile-image">
                    <img class="profile-pic" src="https://pbs.twimg.com/profile_images/598496020880871424/UdTNk_Ko_400x400.jpg">
                </div>

                <div class="profile-info">
                    <div class="profile-age">
                        <p>25</p>
                    </div>
                    <!--                user_id-->
                    <!--                first_name-->
                    <!--                last_name-->
                    <div class="profile-name">
                        <p>
                            <span class="profile-first-name">Frankie</span>
                            <span class="profile-last-name">Ying</span>
                        </p>
                    </div>

                    <!--                country-->
                    <!--                county-->
                    <div class="profile-location">
                        <p>Dublin, Ireland</p>
                    </div>

                </div>
            </div><br>
            <div class="search-result-profile">
                <div class="profile-image">
                    <img class="profile-pic" src="http://previews.123rf.com/images/gabivali/gabivali1002/gabivali100200010/6353499-Profile-of-pretty-face-model-as-Prom-Queen-at-party-Stock-Photo.jpg">
                </div>

                <div class="profile-info">
                    <div class="profile-age">
                        <p>25</p>
                    </div>
                    <!--                user_id-->
                    <!--                first_name-->
                    <!--                last_name-->
                    <div class="profile-name">
                        <p>
                            <span class="profile-first-name">Sarah</span>
                            <span class="profile-last-name">Kelly</span>
                        </p>
                    </div>

                    <!--                country-->
                    <!--                county-->
                    <div class="profile-location">
                        <p>Dublin, Ireland</p>
                    </div>

                </div>
            </div>
            <div class="search-result-profile">
                <div class="profile-image">
                    <img class="profile-pic" src="https://www.xing.com/image/7_f_e_8fa603d62_16371245_5/stefanie-hermann-foto.1024x1024.jpg">
                </div>

                <div class="profile-info">
                    <div class="profile-age">
                        <p>26</p>
                    </div>
                    <!--                user_id-->
                    <!--                first_name-->
                    <!--                last_name-->
                    <div class="profile-name">
                        <p>
                            <span class="profile-first-name">Mary</span>
                            <span class="profile-last-name">Butler</span>
                        </p>
                    </div>

                    <!--                country-->
                    <!--                county-->
                    <div class="profile-location">
                        <p>Clare, Ireland</p>
                    </div>

                </div>
            </div>

            <div class="search-result-profile">
                <div class="profile-image">
                    <img class="profile-pic" src="http://img05.deviantart.net/038e/i/2010/150/b/0/me_2010_by_axy_stock.jpg">
                </div>

                <div class="profile-info">
                    <div class="profile-age">
                        <p>20</p>
                    </div>
                    <!--                user_id-->
                    <!--                first_name-->
                    <!--                last_name-->
                    <div class="profile-name">
                        <p>
                            <span class="profile-first-name">Emily</span>
                            <span class="profile-last-name">O'Brien</span>
                        </p>
                    </div>

                    <!--                country-->
                    <!--                county-->
                    <div class="profile-location">
                        <p>Dublin, Ireland</p>
                    </div>

                </div>
            </div><br>



            <?php
            $n = 1;
            $lastBrAt = 0;
            for ($i = 1; $i < 15; $i++) {
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
                    <img class="profile-pic" src=<?php echo get_profile_image(300); ?>>
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
                        <p>Dublin, Ireland</p>
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
