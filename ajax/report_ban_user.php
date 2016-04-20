<?php
$pathToRoot = '../';
require_once $pathToRoot.'core/init.php';
require_once $pathToRoot.'core/func/notifications.php';

verify_login();

if (isset($_POST['id'], $_POST['action'], $_POST['res']) && $_POST['res'] == 'get_form' && ($_POST['action'] == 'report' || $_POST['action'] == 'ban')) {
    ?>

    <div id="report-form">
        <h3><?=ucfirst($_POST['action'])?> user</h3>
        <p><?php if($_POST['action']=='report') echo 'Please fill out this form to help us decide what action to take';
            else if($_POST['action']=='ban') echo 'Please tell us why this user is being banned and for how long'; ?></p>

        <br>

        <form id="form" role="report_ban_user.php" method="post" class="style-rounded-dark" action="">

            <?php if($_POST['action']=='ban') { ?>
                <select name="ban_duration" style="background-color: #db1c5d; border-radius: 2px;">
                    <option value="1">1 day</option>
                    <option value="3">3 days</option>
                    <option value="7">1 week</option>
                    <option value="14">2 weeks</option>
                    <option value="30">1 month</option>
                    <option value="0">Forever</option>
                </select>
            <?php } ?>

            <fieldset class="reason-text">
                <textarea name="reason" rows="6" cols="70"></textarea>
            </fieldset>

            <fieldset class="actions">
                <input id="cancel" class="button" type="button" onclick="dialog_action(false)" value="Cancel">
                <input id="submit" class="button" type="submit" onclick="dialog_action(true)" value="<?php if($_POST['action']=='report') echo 'Send Report';
                                                                                                            else if($_POST['action']=='ban') echo 'Ban user'; ?>">
            </fieldset>

            <input type="hidden" name="id" value="<?=$_POST['id']?>">
            <input type="hidden" name="action" value="<?=$_POST['action']?>">

        </form>
        <script>
            function dialog_action(send) {
                event.preventDefault();
                var form = document.getElementById("report-form");
                var modal = $(form).parent().parent();

                if (send) {
                    $.post('ajax/report_ban_user.php', $('form').serialize(), function(data) {
                        // Callback function
                        if (data == 'success') {
                            modal.remove();
                        } else {
                            alert(data);
                        }
                    });
                } else {
                    modal.remove();
                }

            }
        </script>
    </div>

    <?php
} else if (isset($_POST['id'], $_POST['action']) && $_POST['action']=='ban') {

    if (isset($_POST['reason'], $_POST['ban_duration']) && !empty($_POST['reason'])) {     // TODO Validate
        if (user_can(PERM_BAN_USERS) && get_user_role_weight($_SESSION['user_id']) > get_user_role_weight($_POST['id'])) {     // TODO notification to admin
            $duration = (int) $_POST['ban_duration'];
            if ($duration == 0) {
                delete_user($_POST['reason'], $_POST['id']);
                echo 'success';
            } else if ($duration > 0) {
                ban_user($_POST['reason'], $duration, $_POST['id']);
                echo 'success';
            } else {
                echo 'An error occurred';
            }
        } else {
            echo 'Sorry, you are not permitted to ban this user';
        }
    } else {
        echo 'Please tell us about the problem';
    }

} else if (isset($_POST['id'], $_POST['action']) && $_POST['action']=='report') {

    if (isset($_POST['reason']) && !empty($_POST['reason'])) {     // TODO Validate
        if (true) {     // TODO notification to admin
            create_notification($_POST['id'], $_POST['reason'], "REPORT");
            echo 'success';
        } else {
            echo 'An error occurred';
        }
    } else {
        echo 'Please tell us about the problem';
    }

}
?>