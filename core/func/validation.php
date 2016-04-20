<?php
// remove old errors
unset($_SESSION['form_errors']);

function validate_name($name, $field_name) {
    $name = trim($name);
    if (!empty($name)) {
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        if ($name == "") {
            $_SESSION['form_errors'][$field_name] = 'Please enter a valid name.';
            return $name;
        }
//        echo $name;
//        echo preg_match("/^[a-zA-Z '-]+$/", $name);
        if (!preg_match("/^[a-zA-Z '-]+$/", $name)) {   // TODO apostrophe
            $_SESSION['form_errors'][$field_name] = 'Please enter a valid name, using alphabetic characters.';
            return $name;
        }
    } else {
        $_SESSION['form_errors'][$field_name] = 'Please enter your name.';
        return false;
    }
    return $name;
}

function validate_text($text, $field_name) {
    $text = trim($text);
    if (!empty($text)) {
        $text = filter_var($text, FILTER_SANITIZE_STRING);
        if ($text == "") {
            $_SESSION['form_errors'][$field_name] = 'Please enter valid text.';
            return $text;
        }
    } else {
        $_SESSION['form_errors'][$field_name] = 'Please enter a description.';
        return false;
    }
    return $text;
}

function validate_email($email, $field_name) {
    if (!empty($email)) {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['form_errors'][$field_name] = "$email is not a valid email address.";
            return $email;
        }
    } else {
        $_SESSION['form_errors'][$field_name] = 'Please enter your email address.';
        return false;
    }
    return $email;
}

function validate_card_number($number, $field_name) {
    $number = trim($number);
    if (!empty($number)) {
        if (!is_numeric($number)) {
            $_SESSION['form_errors'][$field_name] = "$number is not a valid card number.";
            return $number;
        }
        if (strlen($number) != 16) {
            $_SESSION['form_errors'][$field_name] = "a valid card number must be 16 digits.";
            return $number;
        }
    } else {
        $_SESSION['form_errors'][$field_name] = 'Please enter your card number.';
        return false;
    }
    return $number;
}

function validate_card_cvc($cvc, $field_name) {
    $cvc = trim($cvc);
    if (!empty($cvc)) {
        if (!is_numeric($cvc)) {
            $_SESSION['form_errors'][$field_name] = "$cvc is not a valid security code.";
            return $cvc;
        }
        if (strlen($cvc) != 3) {
            $_SESSION['form_errors'][$field_name] = "a valid security code must be 3 digits.";
            return $cvc;
        }
    } else {
        $_SESSION['form_errors'][$field_name] = 'Please enter your card security code.';
        return false;
    }
    return $cvc;
}

function validate_password($password, $field_name) {
    if (!empty($password)) {
        if (preg_match("/ +/", $password)) {
            $_SESSION['form_errors'][$field_name] = "Password cannot have spaces.";
            return $password;
        }
        if (strlen($password) < 8) {
            $_SESSION['form_errors'][$field_name] = "Password must be at least 8 characters.";
            return $password;
        }
        if (!preg_match("/[0-9]+/", $password)) {
            $_SESSION['form_errors'][$field_name] = "Password must include at least one number.";
            return $password;
        }
        if (!preg_match("/[a-zA-Z]+/", $password)) {
            $_SESSION['form_errors'][$field_name] = "Password must include at least one letter.";
            return $password;
        }
    } else {
        $_SESSION['form_errors'][$field_name] = 'Please enter your password.';
        return false;
    }
    return $password;
}

function validate_date_of_birth($day, $month, $year, $field_name) {
    if (!empty($day) &&!empty($month) && !empty($year)) {
        $day = filter_var($day, FILTER_SANITIZE_NUMBER_INT);
        $month = filter_var($month, FILTER_SANITIZE_NUMBER_INT);
        $year = filter_var($year, FILTER_SANITIZE_NUMBER_INT);
        if (!filter_var($day, FILTER_VALIDATE_INT) &&!filter_var($month, FILTER_VALIDATE_INT) && !filter_var($year, FILTER_VALIDATE_INT)) {
            $_SESSION['form_errors'][$field_name] = "$day/$month/$year is not a valid birth date.";
            return $day.$month.$year;
        }
        if (!checkdate( $month, $day, $year)) {
            $_SESSION['form_errors'][$field_name] = "$day/$month/$year is not a valid birth date.";
            return $day.$month.$year;
        }

//        $now=date("d-m-Y");//today's date

        $DOB=new DateTime("$day-$month-$year");
        $now=new DateTime();

        $difference = $DOB->diff($now);
        $age= $difference->y;

        if ($age < 18){
            $_SESSION['form_errors'][$field_name] = "You must be at least 18 to use this service.";
            return $day.$month.$year;
        }
    } else {
        $_SESSION['form_errors'][$field_name] = 'Please enter a birth date.';
        return false;
    }
    return $DOB;
}

function validate_card_expiry_date($month, $year, $field_name) {
    if (!empty($month) && !empty($year)) {
        $month = filter_var($month, FILTER_SANITIZE_NUMBER_INT);
        $year = filter_var($year, FILTER_SANITIZE_NUMBER_INT);
        if (!filter_var($month, FILTER_VALIDATE_INT) && !filter_var($year, FILTER_VALIDATE_INT)) {
            $_SESSION['form_errors'][$field_name] = "$month/$year is not a valid expiry date.";
            return $month.$year;
        }
        $expiry = date_create_from_format('mY', $month.$year);  // mmyyyy
        $now = date_create();
        if ($expiry < $now) {
            $_SESSION['form_errors'][$field_name] = "Please enter an expiry date in the future.";
            return $expiry;
        }
    } else {
        $_SESSION['form_errors'][$field_name] = 'Please enter an expiry date.';
        return false;
    }
    return $expiry;
}

function verify_card($cardholder_name, $card_number, $card_cvc, $card_expiry_date) {

    $data = array(
        'fullname'  =>$cardholder_name,
        'ccNumber'  =>$card_number,
        'month'     =>$card_expiry_date->format('m'),
        'year'      =>$card_expiry_date->format('Y'),
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

function get_form_field_status($field_name) {
    if (isset($_SESSION['form_errors']) && !empty($_SESSION['form_errors'])) {
        $valid = !isset($_SESSION['form_errors'][$field_name]);
        return (!$valid) ? 'invalid':'valid';
    }
    return;
}

function get_form_field_message($field_name) {
    if (isset($_SESSION['form_errors']) && !empty($_SESSION['form_errors'])) {
        $valid = !isset($_SESSION['form_errors'][$field_name]);
        return '<p id="'. $field_name .'-field-message" class="field-message '. get_form_field_status($field_name) .'">'
                . '<i class="fa"></i> '. (!$valid ? $_SESSION['form_errors'][$field_name]:'')
            . '</p>';
    }
    return;
}

