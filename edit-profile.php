<?php
require_once 'core/init.php';
require_once 'core/func/profiles.php';

verify_login();
// TODO permissions and validation

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    $user_id = $_SESSION['user_id'];
}

$is_owner = ($user_id == $_SESSION['user_id']);


// TODO add edit / edit_others permission
$can_edit = ($is_owner && true);
$can_edit_others = false;

// Unauthorised user
if (!$is_owner && !$can_edit_others) {
    header("Location: 401.php");        //TODO
    exit();
}
// Authorised, but not permitted to edit (upgrade required)
if (!$can_edit) {
    header("Location: upgrade.php");    //TODO
    exit();
}


if (isset($_GET['action']) && $_GET['action']==='delete') {
    delete_profile($user_id);
    header("Location: profile.php");        //TODO
    exit();

} else if (isset($_POST['action']) && $_POST['action']==='Save') {
    // Submit changes to DB

    $user_id = $_POST['user_id'];
    $profile = new Profile($user_id);
    $profile->submit();


    if ($profile->error) {
        // TODO error
    }

} else {
    // Load data from DB
    $profile = new Profile($user_id);
    $profile->fetch();


    if ($profile->error) {
        if ($user_id == $_SESSION['user_id']) {
            // create profile
            $profile->first_name = $_SESSION['first_name'];
            $profile->last_name = $_SESSION['last_name'];

        } else {
            header("Location: 404.php");
            exit();
        }
    }

}

?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <article>
            <?php
            if (isset($message['error'])) {
                foreach ($message['error'] as $error) {
                    echo 'Error: ' . $error;
                }
            }
            ?>
            <form action="" method="post" onSubmit="">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <div class="profile-image">
                    <img class="profile-pic" src="<?php echo get_profile_image(500, $user_id)?>">
                </div>
                <div class="profile-info">
                    <!--                user_id-->
                    <!--                first_name-->
                    <!--                last_name-->
                    <div class="profile-field profile-name">
                        <label for="first_name" hidden="hidden">First name</label>
                        <input type="text" id="first_name" name="first_name" size="8" maxlength="30" value="<?php echo $profile->first_name; ?>" placeholder="First Name" />

                        <label for="last_name" hidden="hidden">Last name</label>
                        <input type="text" id="last_name" name="last_name" size="12" maxlength="30" value="<?php echo $profile->last_name; ?>" placeholder="Last Name" />
                    </div>

    <!--                Photo: <input type="file">-->

                    <div class="profile-field profile-DOB">
                        <label for="DOB_day" hidden="hidden">Date of birth</label>
                        <select id="DOB_day" name="DOB_day">
                            <?php
                            for($i = 1; $i <= 31; $i++) {
                                $default = (($i == $profile->DOB_day) ? "selected=\"selected\"" : "");
                                echo "<option " . $default . " value=\"$i\">$i</option>";
                            }
                            ?>
                        </select>
                        <label for="DOB_month" hidden="hidden">Month of birth</label>
                        <select id="DOB_month" name="DOB_month">
                            <?php
                            $months = array("January", "February", "March", "April", "May", "June", "July",
                                "August", "September", "October", "November", "December");
                            for($i = 0; $i < 12; $i++) {
                                $default = (($i + 1 == $profile->DOB_month) ? "selected=\"selected\"" : "");
                                echo "<option " . $default . " value=\"" . ($i+1) . "\">$months[$i]</option>";
                            }
                            ?>
                        </select>
                        <label for="DOB_year" hidden="hidden">Year of birth</label>
                        <select id="DOB_year" name="DOB_year">
                            <?php
                            $current_year = date("Y");
                            for($i = $current_year; $i > $current_year - 100; $i--) {
                                $default = (($i == $profile->DOB_year) ? "selected=\"selected\"" : "");
                                echo "<option " . $default . " value=\"$i\">$i</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="profile-field profile-sex">
                        <label for="sex" hidden="hidden">Sex</label>
                        <select id="sex" name="sex">
                            <option <?php echo (($profile->sex == 1) ? "selected=\"selected\"" : ""); ?> value="1">Man</option>
                            <option <?php echo (($profile->sex == 0) ? "selected=\"selected\"" : ""); ?> value="0">Woman</option>
                        </select>
                    </div>

                    <div class="profile-field profile-description">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" cols="60" rows="10" placeholder="Tell us a little about yourself..."><?php echo $profile->description; ?></textarea>
                    </div>
                    <!--                country-->
                    <!--                county-->
                    <div class="profile-field profile-location">
                        <label for="location">Location</label>
                        <fieldset id="location" style="border: hidden">
                            <label for="country" hidden="hidden">Country</label>
                            <select id="country" name="country">
                                <option value="IRL">Ireland</option>
                            </select>

                            <label for="county" hidden="hidden">County</label>
                            <select id="county" name="county">
                                <option value=""></option>
                                <option value="LK">Limerick</option>
                            </select>
                        </fieldset>
                    </div>

                    <div class="profile-field profile-looking-for">
                        <label for="looking_for">Looking for:</label>
                        <select id="looking_for" name="looking_for">
                            <option <?php echo (($profile->looking_for == 1) ? "selected=\"selected\"" : ""); ?> value="1">Man</option>
                            <option <?php echo (($profile->looking_for == 0) ? "selected=\"selected\"" : ""); ?> value="0">Woman</option>
                        </select>
                    </div>
                    <!--                min_age-->
                    <!--                max_age-->
                    <div class="profile-field profile-age-range">
                        <label for="age_range">Aged:</label>
                        <fieldset id="age_range" style="border: hidden">
                            <label for="min_age" hidden="hidden">Minimum age</label>
                            <select id="min_age" name="min_age">
                                <?php
                                for($i = 18; $i <= 100; $i++) {
                                    $default = (($i == (isset($profile->min_age) ? $profile->min_age : $profile->age)) ? "selected=\"selected\"" : "");
                                    echo "<option " . $default . " value=\"$i\">$i</option>";
                                }
                                ?>
                            </select>
                            -
                            <label for="max_age" hidden="hidden">Maximum age</label>
                            <select id="max_age" name="max_age">
                                <?php
                                for($i = 18; $i <= 100; $i++) {
                                    $default = (($i == (isset($profile->max_age) ? $profile->max_age : $profile->age)) ? "selected=\"selected\"" : "");
                                    echo "<option " . $default . " value=\"$i\">$i</option>";
                                }
                                ?>
                            </select>
                        </fieldset>
                    </div>
                    <br />

                    <!-- TODO likes/dislikes -->
                    <div class="profile-field profile-likes">
                        <h3>Likes</h3>
                            <input type="text" value="Horse riding" />
                            <i class="fa fa-times"></i>
                            <br />
                            <input type="text" value="Walking" />
                            <i class="fa fa-times"></i>
                            <br />
                            <input type="text" value="Talking" />
                            <i class="fa fa-times"></i>
                            <br />
                            <input type="text" value="Movies" />
                            <i class="fa fa-times"></i>
                            <br />
                            <input type="text" value="" />
                    </div>

                    <div class="profile-field profile-dislikes">
                        <h3>Dislikes</h3>
                            <input type="text" value="Sports" />
                            <i class="fa fa-times"></i>
                            <br />
                            <input type="text" value="Card games" />
                            <i class="fa fa-times"></i>
                            <br />
                            <input type="text" value="Spicy food" />
                            <i class="fa fa-times"></i>
                            <br />
                            <input type="text" value="" />
                    </div>

                    <input type="submit" name="action" value="Save">

                </div>

            </form>
        </article>
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
