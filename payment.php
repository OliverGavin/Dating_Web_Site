<?php
require_once 'core/init.php';
require_once 'core/func/validation.php';
require_once 'core/func/notifications.php';

verify_login();
$msg = '';
$valid = false;

if (user_is_at_least_role(ROLE_ADMIN)) {
    $message['error'][] = 'Admins cannot cannot upgrade';
} else if (user_is_role(ROLE_PAID)) {
    $message['success'][] = 'You have already upgraded. Go to <a href="profile.php">My Profile</a>';
} else {

    if (isset($_POST['action']) && $_POST['action'] == 'Upgrade') {

        $cardholder_name = validate_name($_POST['cardholder_name'], 'cardholder_name');
        $card_number = validate_card_number($_POST['card_number'], 'card_number');
        $card_cvc = validate_card_cvc($_POST['card_cvc'], 'card_cvc');
        $card_expiry_date = validate_card_expiry_date($_POST['card_expiry_month'], $_POST['card_expiry_year'], 'card_expiry_date');

        if (empty($_SESSION['form_errors'])) {

            $valid = verify_card($cardholder_name, $card_number, $card_cvc, $card_expiry_date);

            if ($valid) {
                set_user_role(ROLE_PAID);
                $message['success'][] = 'Payment accepted, your account has been upgraded. <a href="edit-profile.php">My Profile</a>';
                create_notification($_SESSION['user_id'], null, "PAYMENT");
            } else {
                $message['error'][] = 'Payment failed, your card was declined, please try again';
            }
        }

    }
}

?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main frame" role="main">

        <div class="">
            <h2 class="page-title">Upgrade</h2>

            <div class="promo">
                <h3>Limited time offer!</h3>
                <h4>Lifetime membership for only <span>€5</span></h4>
            </div>

            <?php
            if (isset($message['error']) && !empty($message['error'])) {
                echo '<div class="notice error">';
                echo '<h6 class="notice-title">WARNING</h6>';
                foreach ($message['error'] as $msg) {
                    echo '<p>';
                        echo $msg;
                    echo '</p>';
                }
                echo '</div>';
            }
            if (isset($message['success']) && !empty($message['success'])) {
                echo '<div class="notice success">';
                foreach ($message['success'] as $msg) {
                    echo '<p>';
                        echo $msg;
                    echo '</p>';
                }
                echo '</div>';
            }
            ?>

            <?php if (user_is_role(ROLE_FREE) && !$valid) { ?>

                <form method="post" class="style-rounded-dark" action="">

    <!--                Customer Name-->
    <!--                16 digit card number-->
    <!--                A two digit expiration month-->
    <!--                A two sigit expiration year-->
    <!--                A three digit security code-->
    <!--                The expiration date cannot have already passed-->

                    <div class="group both-rounded <?= get_form_field_status('cardholder_name'); ?>">
                        <label for="cardholder_name" class="visible">Full Name</label>
                        <input type="text" id="cardholder_name" name="cardholder_name" size="30" value="<?php if(isset($cardholder_name)) echo $cardholder_name?>" onblur="validate_field(this, $(this).val(), 'cardholder_name', 'name')" />
                    </div>
                    <?= get_form_field_message('cardholder_name');  ?>
                    <br>

                    <div class="group both-rounded <?= get_form_field_status('card_number'); ?> <?= get_form_field_status('card_cvc'); ?>">
                        <label for="card_number" class="visible">Card number</label>
                        <input type="number" id="card_number" name="card_number" value="<?php if(isset($card_number)) echo $card_number?>" onblur="validate_field(this, $(this).val(), 'card_number', 'card_number')" >

                        <label for="card_cvc" class="visible">CVC</label>
                        <input type="number" id="card_cvc" name="card_cvc" value="<?php if(isset($card_cvc)) echo $card_cvc?>" onblur="validate_field(this, $(this).val(), 'card_cvc', 'card_cvc')" >
                    </div>
                    <?php if (get_form_field_status('card_number') == 'invalid' || (get_form_field_status('card_number') == 'valid' && get_form_field_status('card_cvc') == 'valid')) echo get_form_field_message('card_number');  ?>
                    <?php if (get_form_field_status('card_cvc') == 'invalid') echo get_form_field_message('card_cvc');  ?>
                    <br>

                    <div class="group both-rounded <?= get_form_field_status('card_expiry_date'); ?>">
                        <label for="card_expiry_month card_expiry_year" class="visible">Expiry</label>
                        <select id="card_expiry_month" name="card_expiry_month" onchange="validate_field(this, $(this).val()+$('#card_expiry_year').val(), 'card_expiry_date', 'card_expiry_date')" >
                            <?php
                            $default = (int)(isset($card_expiry_date))? $card_expiry_date->format('m') : 1;
                            for($i = 1; $i <= 12; $i++) {
                                $value = str_pad((string)$i, 2, "0", STR_PAD_LEFT);
                                $selected = ($i == $default)? 'selected="selected"': '';
                                echo "<option $selected value=\"$value\">$i</option>";
                            }
                            ?>
                        </select>
                        <select id="card_expiry_year" name="card_expiry_year" onchange="validate_field(this, $('#card_expiry_month').val()+$(this).val(), 'card_expiry_date', 'card_expiry_date')" >
                            <?php
                            $current_year = date('Y');
                            $default = (int)(isset($card_expiry_date))? $card_expiry_date->format('Y') : $current_year;
                            for($i = $current_year; $i <= $current_year + 5; $i++) {
                                $selected = ($i == $default)? 'selected="selected"': '';
                                echo "<option $selected value=\"$i\">$i</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <?= get_form_field_message('card_expiry_date');  ?>

                    <input class="button" type="submit" name="action" value="Upgrade" />
                </form>

                <script src="js/validation.js" type="text/javascript"></script>

            <?php } ?>

        </div>
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
