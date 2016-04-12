<?php
require_once 'core/init.php';

verify_login();
$msg = '';
$valid = false;

if (user_is_at_least_role(ROLE_ADMIN)) {
    $msg = 'Admins cannot cannot upgrade';
} else if (user_is_role(ROLE_PAID)) {
    $msg = 'You have already upgraded. Go to profile';
} else {

    if (isset($_POST['action']) && $_POST['action'] == 'Upgrade') {

        if (true) { // TODO validation
            $cardholder_name = $_POST['cardholder_name'];
            $card_number = $_POST['card_number'];
            $card_cvc = $_POST['card_cvc'];
            $card_expiry_month = $_POST['card_expiry_month'];
            $card_expiry_year = $_POST['card_expiry_year'];

            $valid = verify_card($cardholder_name, $card_number, $card_cvc, $card_expiry_month, $card_expiry_year);

            if ($valid) {
                set_user_role(ROLE_PAID);
                $msg = 'Payment accepted, your account has been upgraded. Go to profile';
            } else {
                $msg = 'Payment failed, your card was declined, please try again';
            }
        }

    }
}

function verify_card($cardholder_name, $card_number, $card_cvc, $card_expiry_month, $card_expiry_year) {

    $data = array(
        'fullname'  =>$cardholder_name,
        'ccNumber'  =>$card_number,
        'month'     =>$card_expiry_month,
        'year'      =>$card_expiry_year,
        'security'  =>$card_cvc
    );

    $ch = curl_init("http://amnesia.csisdmz.ul.ie/4014/cc.php?".http_build_query($data));
//    $ch = curl_init("http://amnesia.csisdmz.ul.ie/4014/cc.php?fullname=$cardholder_name&ccNumber=$card_number&month=$card_expiry_month&year=$card_expiry_year&security=$card_cvc");
    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // $output contains the output string
    $output = curl_exec($ch);

    // close curl resource to free up system resources
    curl_close($ch);
    return ($output); // 1 for accept or 0 for decline
}

?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <article>

            <h2>Upgrade</h2>

            <p><?=$msg?></p>

            <?php if (user_is_role(ROLE_FREE) && !$valid) { ?>

                <form role="" method="post" class="style-rounded-dark" action="">

    <!--                Customer Name-->
    <!--                16 digit card number-->
    <!--                A two digit expiration month-->
    <!--                A two sigit expiration year-->
    <!--                A three digit security code-->
    <!--                The expiration date cannot have already passed-->

                    <div class="group both-rounded">
                        <label for="cardholder_name" class="visible">Full Name</label>
                        <input type="text" id="cardholder_name" name="cardholder_name" size="30" placeholder=""/>
                    </div><br>

                    <div class="group both-rounded">
                        <label for="card_number" class="visible">Card number</label>
                        <input type="number" id="card_number" name="card_number" min="1000000000000000" max="9999999999999999" placeholder="" value="" title="">

                        <label for="card_cvc" class="visible">CVC</label>
                        <input type="number" id="card_cvc" name="card_cvc" min="100" max="999" placeholder="" value="" title="">
                    </div><br>

                    <div class="group both-rounded">
                        <label for="card_expiry_month card_expiry_year" class="visible">Expiry</label>
                        <label>
                            <select id="card_expiry_month" name="card_expiry_month" >
                                <?php
                                for($i = 1; $i <= 12; $i++) {
                                    echo "<option value=\"$i\">$i</option>";
                                }
                                ?>
                            </select>
                        </label>
                        <label>
                            <select id="card_expiry_year" name="card_expiry_year" >
                                <?php
                                $current_year = date("Y");
                                for($i = $current_year; $i <= $current_year + 5; $i++) {
                                    echo "<option value=\"$i\">$i</option>";
                                }
                                ?>
                            </select>
                        </label>
                    </div>

                    <input class="button" type="submit" name="action" value="Upgrade" />
                </form>

            <?php } ?>

        </article>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
