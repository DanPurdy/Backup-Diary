<?php
session_start();
require('includes/pdoconnection.php');

function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$backup= new backup($dbh);

$backup->insertBackup($_POST);

header('Location: /backup/');

?>