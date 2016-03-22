<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

date_default_timezone_set('GMT');


define("ROOT", '//'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/');

// reinitialise error/success messages
$message = array();
$message['error'] = array();
$message['success'] = array();

require_once 'db/connect.php';
require_once 'func/session.php';
require_once 'func/functions.php';
require_once 'func/navigation.php';

?>