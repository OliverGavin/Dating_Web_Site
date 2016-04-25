<?php
/*
 * Facilitates ajax requests for validation of a specified form field
 * Errors are returned in JSON format
 * If no errors are returned, 'success' is returned
 */

$pathToRoot = '../';
require_once $pathToRoot.'core/init.php';
require_once $pathToRoot.'core/func/validation.php';

$msg = '';
$valid = false;

if (isset($_POST['action']) && $_POST['action'] == 'validate_field'
    && isset($_POST['field_name']) && !empty($_POST['field_name'])
    && isset($_POST['field_value']) && !empty($_POST['field_value'])
    && isset($_POST['validation_type']) && !empty($_POST['validation_type'])) {


    $field_name = $_POST['field_name'];
    $field_value = $_POST['field_value'];
    $validation_type = $_POST['validation_type'];

    switch ($validation_type) {
        case 'name':
            validate_name($field_value, $field_name);
            break;

        case 'text':
            validate_text($field_value, $field_name);
            break;

        case 'email':
            validate_email($field_value, $field_name);
            break;

        case 'card_number':
            validate_card_number($field_value, $field_name);
            break;

        case 'card_cvc':
            validate_card_cvc($field_value, $field_name);
            break;

        case 'password':
            validate_password($field_value, $field_name);
            break;

        case 'date_of_birth':
            $day = substr($field_value, 0, 2);
            $month = substr($field_value, 2, 2);
            $year = substr($field_value, 4);
            validate_date_of_birth($day, $month, $year, $field_name);
            break;

        case 'card_expiry_date':
            $month = substr($field_value, 0, 2);
            $year = substr($field_value, 2);
            validate_card_expiry_date($month, $year, $field_name);
            break;

        default:
            echo 'failed';
            exit();

    }

    if (empty($_SESSION['form_errors'])) {
        echo 'success';
        exit();
    } else {
        // send errors in json
        // tell javascript data is json
        header("Content-Type: application/json");
        echo json_encode($_SESSION['form_errors']);
    }

}