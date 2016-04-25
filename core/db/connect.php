<?php
/*
 * Connects to the database server
 */

//$dbhost="127.0.0.1";
//$dbport=3306;
//$dbsocket="";
//$dbuser="root";
//$dbpassword="";
//$dbname="cs4014populatedtest";

//ul
$dbhost="p:193.1.101.7";
$dbport=3307;
$dbsocket="";
$dbuser="group22";
$dbpassword="LdoOq0a0P";
$dbname="group22DB";

$db = new mysqli($dbhost, $dbuser, $dbpassword, $dbname, $dbport, $dbsocket)
or die ('Could not connect to the database server' . mysqli_connect_error());


?>