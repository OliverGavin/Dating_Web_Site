<?php
$is_owner = ($profile->user_id == $_SESSION['user_id']);

// TODO ban block report
$can_edit = user_can(PERM_EDIT_PROFILE);
$can_edit_others = user_can(PERM_EDIT_OTHERS_PROFILE);

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
        <?php }
        if (!$is_owner) {
            if ($can_edit_others) { ?>
                <a href="<?=$_SERVER['REQUEST_URI']?>&action=ban" class="ban-action" onclick="report_ban_user(<?=$user_id?>, 'ban');">
                    <div class="action action-ban">
                        <p><i class="fa fa-times"></i></p>
                        <p>BAN</p>
                    </div>
                </a>
            <?php } else { ?>
                <a href="<?=$_SERVER['REQUEST_URI']?>&status=<?=BLOCK?>" class="status-action <?=($status==BLOCK? 'current-status':'')?>" onclick="set_relationship(<?=$user_id?>, '<?=BLOCK?>', this);">
                    <div class="action action-block">
                        <p><i class="fa fa-times"></i></p>
                        <p>BLOCK</p>
                    </div>
                </a>
                <a href="<?=$_SERVER['REQUEST_URI']?>&action=report" class="report-action" onclick="report_ban_user(<?=$user_id?>, 'report');">
                    <div class="action action-report">
                        <p><i class="fa fa-flag"></i></p>
                        <p>REPORT</p>
                    </div>
                </a>
            <?php }
        } ?>
    </div>

    <div class="profile-image">
        <img class="profile-pic" src="<?php echo get_profile_image(IMG_MEDIUM, $user_id); ?>">
        <div class="profile-actions profile-actions-good">
            <?php if (!$is_owner && !$can_edit_others) { ?>
                <a href="<?=$_SERVER['REQUEST_URI']?>&status=<?=LIKE?>" class="status-action <?=($status==LIKE? 'current-status':'')?>" onclick="set_relationship(<?=$user_id?>, '<?=LIKE?>', this);">
                    <div class="action action-like">
                        <p><i class="fa fa-heart"></i></p>
                        <p>LIKE</p>
                    </div>
                </a>
            <?php } ?>
            <?php if (!$is_owner && can_message_each_other($_SESSION['user_id'], $profile->user_id)) { ?>
            <a href="chat.php?id=<?=$user_id?>" class="message-action" onclick="open_chat(<?=$user_id?>)" style="<?php if ($can_edit_others) echo 'margin-left:0;' ?>">
                <div class="action action-ban">
                    <p><i class="fa fa-comment"></i></p>
                    <p>CHAT</p>
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
            <p><?php echo nl2br($profile->description); ?></p>
        </div>
        <!--                country-->
        <!--                county-->
        <div class="profile-field profile-location">
            <h3>Location</h3>
            <h4><?php
                echo $profile->location;
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

        <?php
        $likes = get_interests($user_id, true);

        if (isset($likes) && !empty($likes)) {
            echo '<div class="profile-field profile-likes">';
                echo '<h3>Likes</h3>';
                echo '<ul>';

                foreach ($likes as $like) {
                    echo '<li>' . $like->content . '</li>';
                }

                echo '</ul>';
            echo '</div>';
        }
        ?>

        <?php
        $dislikes = get_interests($user_id, false);

        if (isset($dislikes) && !empty($dislikes)) {
            echo '<div class="profile-field profile-dislikes">';
                echo '<h3>Dislikes</h3>';
                echo '<ul>';

                foreach ($dislikes as $dislike) {
                    echo '<li>' . $dislike->content . '</li>';
                }

                echo '</ul>';
            echo '</div>';
        }
        ?>

    </div>
</article>

<script>
    function set_relationship(target_user_id, status_name, el) {
        event.preventDefault();
        if($(el).hasClass('current-status')) status_name = null;
        $.post('ajax/set_relationship.php', {id:target_user_id, status:status_name}, function(data) {
            // Callback function
            if (data == 'success') {
                $('.status-action').removeClass('current-status');
                if (status_name != null)
                    $(el).addClass('current-status');
            }
        });
    }

    function report_ban_user(target_user_id, action) {
        event.preventDefault()
        $.post('ajax/report_ban_user.php', {id:target_user_id, action:action, res:'get_form'}, function(data) {
            // Callback function
            show_modal(data, 'modal-dialog dialog-report');
        });
    }
</script>
