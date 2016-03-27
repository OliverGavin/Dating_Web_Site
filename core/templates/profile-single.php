<?php
$is_owner = ($profile->user_id == $_SESSION['user_id']);

// TODO add edit / edit_others permission
$can_edit = ($is_owner && true);
$can_edit_others = false;

$status = get_relationship($user_id);

?>
<article>
    <div class="profile-actions profile-actions-bad">
        <?php if (($is_owner && $can_edit) || $can_edit_others) { ?>
            <a href="edit-profile.php<?php if ($can_edit_others) echo "?id=$user_id"; ?>">
                <div class="action action-edit">
                    <p><i class="fa fa-pencil"></i></p>
                    <p>EDIT</p>
                </div>
            </a>
            <a href="edit-profile.php?action=delete<?php if ($can_edit_others) echo "&id=$user_id"; ?>">
                <div class="action action-delete">
                    <p><i class="fa fa-trash"></i></p>
                    <p>DELETE</p>
                </div>
            </a>
        <?php   } else if (!$is_owner) {
            if ($can_edit_others) { ?>
                <div class="action action-ban">
                    <p><i class="fa fa-times"></i></p>
                    <p>BAN</p>
                </div>
            <?php       } else { ?>
                <a href="<?=$_SERVER['REQUEST_URI']?>&status=BLOCK" class="status-action <?=($status=='BLOCK'? 'current-status':'')?>" onclick="set_relationship(<?=$user_id?>, 'BLOCK', this);">
                    <div class="action action-block">
                        <p><i class="fa fa-times"></i></p>
                        <p>BLOCK</p>
                    </div>
                </a>
                <div class="action action-report">
                    <p><i class="fa fa-flag"></i></p>
                    <p>REPORT</p>
                </div>
            <?php       }
        } ?>
        <!-- TODO add delete and ban -->
    </div>

    <div class="profile-image">
        <img class="profile-pic" src="<?php echo get_profile_image(300, $user_id); ?>">
        <div class="profile-actions profile-actions-good">
            <?php if (!$is_owner && !$can_edit_others) { ?>
                <a href="<?=$_SERVER['REQUEST_URI']?>&status=LIKE" class="status-action <?=($status=='LIKE'? 'current-status':'')?>" onclick="set_relationship(<?=$user_id?>, 'LIKE', this);">
                    <div class="action action-like">
                        <p><i class="fa fa-heart"></i></p>
                        <p>LIKE</p>
                    </div>
                </a>
            <?php } ?>
        </div>
    </div>
    <div class="profile-info">
        <!--                user_id-->
        <!--                first_name-->
        <!--                last_name-->
        <div class="profile-field profile-name">
            <h2><?php echo $profile->first_name; ?> <?php echo $profile->last_name; ?></h2>
        </div>

        <div class="profile-field profile-age">
            <h4><?php echo $profile->age; ?></h4>
        </div>

        <div class="profile-field profile-sex">
            <h4><?php echo (($profile->sex) ? 'Man' : 'Woman'); ?></h4>
        </div>

        <div class="profile-field profile-description">
            <h3>Description</h3>
            <p><?php echo $profile->description; ?></p>
        </div>
        <!--                country-->
        <!--                county-->
        <div class="profile-field profile-location">
            <h3>Location</h3>
            <h4><?php
                echo $profile->county;
                if (!empty($profile->county) && !empty($profile->country)) echo ', ';
                echo $profile->country;
                ?>
            </h4>
        </div>

        <div class="profile-field profile-looking-for">
            <h3>Looking for:</h3>
            <h4><?php echo (($profile->looking_for) ? 'Man' : 'Woman'); /*b'0'*/?></h4>
        </div>
        <!--                min_age-->
        <!--                max_age-->
        <div class="profile-field profile-age-range">
            <h3>Aged:</h3>
            <p>
                <?php echo (isset($profile->min_age) ? $profile->min_age : $profile->age)?>
                -
                <?php echo (isset($profile->max_age) ? $profile->max_age : $profile->age)?>
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

<script>
    function set_relationship(target_user_id, status_name, el) {
        event.preventDefault()
        $.post('ajax/set_relationship.php', {id:target_user_id, status:status_name}, function(data) {
            // Callback function
            if (data == 'success') {
                $('.status-action').removeClass('current-status');
                $(el).addClass('current-status');
            }
        });
    }
</script>
