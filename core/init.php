<?php
global $pathToRoot;
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

date_default_timezone_set('GMT');

define("ROOT", '//'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/');

DEFINE('ERROR',		            "an error occurred");
DEFINE('NOT_FOUND',		        "not found");
DEFINE('ALREADY_EXISTS',		"already exists");
DEFINE('USER_BLOCKED',		    "user is blocked");
DEFINE('USER_BANNED',		    "user is banned");
DEFINE('USER_DELETED',		    "user is deleted");
DEFINE('INCORRECT_PASSWORD',	"incorrect password");
DEFINE('INCORRECT_USER_PASS',	"incorrect username or password");
DEFINE('MISSING_FIELDS',	    "missing fields");

DEFINE('IMG_LARGE',		500);
DEFINE('IMG_MEDIUM',	250);
DEFINE('IMG_SMALL',		150);
DEFINE('IMG_THUMB',		60);

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
require_once $initPathToRoot.'func/roles.php';
require_once $initPathToRoot.'func/functions.php';
require_once $initPathToRoot.'func/navigation.php';

?>