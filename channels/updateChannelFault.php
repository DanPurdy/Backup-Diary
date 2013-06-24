<?php
session_start();
require('includes/pdoconnection.php');
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();
$channel = new channel($dbh);

$channel->updateChanFault($_POST);

header('Location: '.$_SERVER['HTTP_REFERER'] );
?>

