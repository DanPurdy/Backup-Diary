<?php
session_start();
require('includes/pdoconnection.php');

function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$backup= new backup($dbh);

if (isset($_GET['backupID']) && !$_POST) {
    
    $backup->setBackupDeleted($_GET['backupID']);

header('Location: '.$_SERVER['HTTP_REFERER']);
}
?>
