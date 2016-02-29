<?php
require_once 'core/init.php';
?>

<?php get_header(); ?>

<div id="primary" class="content-area profile">
    <main id="main" class="site-main" role="main">
        <article>
            <div class="profile-image">
                <img class="profile-pic" src="http://offline.fcwinti.com/wp-content/uploads/default-avatar-500x550.jpg">
            </div>
            <div class="profile-info">
                <!--                user_id-->
                <!--                first_name-->
                <!--                last_name-->
                <div class="profile-field profile-name">
                    <input type="text" value="Jane Doe"/>
                </div>

                <div class="profile-field profile-age">
                    <select>
                        <option value="22">22</option>
                    </select>
                </div>

                <div class="profile-field profile-sex">
                    <select>
                        <option value="Woman">Woman</option>
                    </select>
                </div>

                <div class="profile-field profile-description">
                    <h3>Description</h3>
                    <textarea cols="60" rows="10"></textarea>
                </div>
                <!--                country-->
                <!--                county-->
                <div class="profile-field profile-location">
                    <h3>Location</h3>
                    <select>
                        <option value="Man">Ireland</option>
                    </select>
                    <select>
                        <option value="Man">Limerick</option>
                    </select>
                </div>

                <div class="profile-field profile-looking-for">
                    <h3>Looking for:</h3>
                    <select>
                        <option value="Man">Man</option>
                    </select>
                </div>
                <!--                min_age-->
                <!--                max_age-->
                <div class="profile-field profile-age-range">
                    <h3>Aged:</h3>
                    <select>
                        <option value="23">23</option>
                    </select>
                    -
                    <select>
                        <option value="26">26</option>
                    </select>
                </div>

                <div class="profile-field profile-likes">
                    <h3>Likes</h3>
                        <input type="text" value="Horse riding" />
                        <br />
                        <input type="text" value="Walking" />
                        <br />
                        <input type="text" value="Talking" />
                        <br />
                        <input type="text" value="Movies" />
                        <br />
                        <input type="text" value="" />
                </div>

                <div class="profile-field profile-dislikes">
                    <h3>Dislikes</h3>
                        <input type="text" value="Sports" />
                        <br />
                        <input type="text" value="Card games" />
                        <br />
                        <input type="text" value="Spicy food" />
                        <br />
                        <input type="text" value="" />
                </div>

            </div>
        </article>
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
