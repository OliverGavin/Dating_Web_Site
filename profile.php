<?php
require_once 'core/init.php';

$edit = (isset($_GET['action']) && $_GET['action']==='edit');

?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <article>

            <div class="profile-actions profile-actions-bad">
                <div class="action action-block">
                    <p><i class="fa fa-times"></i></p>
                    <p>BLOCK</p>
                </div>
                <div class="action action-report">
                    <p><i class="fa fa-flag"></i></p>
                    <p>REPORT</p>
                </div>
            </div>

            <div class="profile-image">
                <img class="profile-pic" src="http://offline.fcwinti.com/wp-content/uploads/default-avatar-500x550.jpg">
                <div class="profile-actions profile-actions-good">
                    <div class="action action-like">
                        <p><i class="fa fa-heart"></i></p>
                        <p>LIKE</p>
                    </div>
                </div>
            </div>
            <div class="profile-info">
<!--                user_id-->
<!--                first_name-->
<!--                last_name-->
                <div class="profile-field profile-name">
                    <h2>Jane Doe</h2>
                </div>

                <div class="profile-field profile-age">
                    <h4>22</h4>
                </div>

                <div class="profile-field profile-sex">
                    <h4>Woman</h4>
                </div>

                <div class="profile-field profile-description">
                    <h3>Description</h3>
                    <p>kbvf dvbiv fvfbv vhfiv fkvbifdv fdjvbfivb kbvf dvbiv fvfbv vhfiv fkvbifdv fdjvbfivb kbvf dvbiv fvfbv vhfiv fkvbifdv fdjvbfivb kbvf dvbiv fvfbv vhfiv fkvbifdv fdjvbfivb </p>
                </div>
<!--                country-->
<!--                county-->
                <div class="profile-field profile-location">
                    <h3>Location</h3>
                    <h4>Limerick, Ireland</h4>
                </div>

                <div class="profile-field profile-looking-for">
                    <h3>Looking for:</h3>
                    <h4>Man</h4>
                </div>
<!--                min_age-->
<!--                max_age-->
                <div class="profile-field profile-age-range">
                    <h3>Aged:</h3>
                    <p>23 - 26</p>
                </div>

                <div class="profile-field profile-likes">
                    <h3>Likes</h3>
                    <ul>
                        <li>Horse riding</li>
                        <li>Walking</li>
                        <li>Talking</li>
                        <li>Movies</li>
                    </ul>
                </div>

                <div class="profile-field profile-dislikes">
                    <h3>Dislikes</h3>
                    <ul>
                        <li>Sports</li>
                        <li>Card games</li>
                        <li>Spicy food</li>
                    </ul>
                </div>

            </div>
        </article>
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
