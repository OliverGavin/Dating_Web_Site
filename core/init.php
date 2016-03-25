<?php
global $pathToRoot;
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

date_default_timezone_set('GMT');

define("ROOT", '//'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/');

// reinitialise error/success messages
$message = array();
$message['error'] = array();
$message['success'] = array();

$initPathToRoot = "";
if (isset($pathToRoot)) {
    // Alternative directory for including from sub folders such as ajax
    $initPathToRoot = $pathToRoot . 'core/';
}
require_once $initPathToRoot.'db/connect.php';
require_once $initPathToRoot.'func/session.php';
require_once $initPathToRoot.'func/functions.php';
require_once $initPathToRoot.'func/navigation.php';

?>