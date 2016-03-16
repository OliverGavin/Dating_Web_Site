<?php
require_once 'core/init.php';

verify_login();
// TODO permissions

//$edit = (isset($_GET['action']) && $_GET['action']==='edit');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $user_id = $_GET['id'];
    $owner = false;
} else {
    $user_id = $_SESSION['user_id'];
    $owner = true;
}

$can_view = true;
if (!$can_view) {
    header("Location: upgrade.php");
    exit();
}
$blocked = false;

$prepared = $db->prepare("
              SELECT    first_name, last_name,
                        DOB, sex, description, country,
                        county, looking_for, min_age,
                        max_age, date_time_updated
              FROM users NATURAL JOIN profiles
              WHERE user_id = ?
            ");

$prepared->bind_param('s', $user_id);

$prepared->execute();
// TODO error detection
$prepared->bind_result( $first_name, $last_name,
                        $DOB, $sex, $description, $country,
                        $county, $looking_for, $min_age,
                        $max_age, $date_time_updated); //i.e. binding to SELECTed attributes

$profile_exists = $prepared->fetch();
if (!$profile_exists || $blocked) {
    header("Location: 404.php");
    exit();
}

$age = date_diff(date_create($DOB), date_create('now'))->y;

// TODO add edit / edit_others permission
$can_edit = ($user_id === $_SESSION['user_id'] && true);
$can_edit_others = false;

?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <article>
            <div class="profile-actions profile-actions-bad">
                <?php if ($can_edit || $can_edit_others) { ?>
                    <a href="edit-profile.php<?php if ($can_edit_others) echo "?id=$user_id"; ?>">
                        <div class="action action-edit">
                            <p><i class="fa fa-pencil"></i></p>
                            <p>EDIT</p>
                        </div>
                    </a>
                <?php } else { ?>
                    <div class="action action-block">
                        <p><i class="fa fa-times"></i></p>
                        <p>BLOCK</p>
                    </div>
                    <div class="action action-report">
                        <p><i class="fa fa-flag"></i></p>
                        <p>REPORT</p>
                    </div>
                <?php } ?>
                <!-- TODO add delete and ban -->
            </div>

            <div class="profile-image">
                <img class="profile-pic" src="<?php echo get_profile_image(300, $user_id); ?>">
                <div class="profile-actions profile-actions-good">
                    <?php if (!$can_edit && !$can_edit_others) { ?>
                        <div class="action action-like">
                            <p><i class="fa fa-heart"></i></p>
                            <p>LIKE</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="profile-info">
<!--                user_id-->
<!--                first_name-->
<!--                last_name-->
                <div class="profile-field profile-name">
                    <h2><?php echo $first_name; ?> <?php echo $last_name; ?></h2>
                </div>

                <div class="profile-field profile-age">
                    <h4><?php echo $age; ?></h4>
                </div>

                <div class="profile-field profile-sex">
                    <h4><?php echo (($sex) ? 'Man' : 'Woman'); ?></h4>
                </div>

                <div class="profile-field profile-description">
                    <h3>Description</h3>
                    <p><?php echo $description; ?></p>
                </div>
<!--                country-->
<!--                county-->
                <div class="profile-field profile-location">
                    <h3>Location</h3>
                    <h4><?php
                        echo $county;
                        if (isset($county, $country)) echo ', ';
                        echo $country;
                        ?>
                    </h4>
                </div>

                <div class="profile-field profile-looking-for">
                    <h3>Looking for:</h3>
                    <h4><?php echo (($looking_for) ? 'Man' : 'Woman'); /*b'0'*/?></h4>
                </div>
<!--                min_age-->
<!--                max_age-->
                <div class="profile-field profile-age-range">
                    <h3>Aged:</h3>
                    <p>
                        <?php echo (isset($min_age) ? $min_age : $age)?>
                         -
                        <?php echo (isset($max_age) ? $max_age : $age)?>
                    </p>
                </div>
                <!-- TODO likes/dislikes -->
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
