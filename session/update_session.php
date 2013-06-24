<?php
session_start();

require_once 'includes/pdoconnection.php';
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$session= new session($dbh);
$backup=new backup($dbh);

$bakID =    $_POST['sessCont'];
$prevBak =  $_POST['backupID'];

if($bakID != $prevBak && $bakID !=0){

    $session->updateSession($_POST, $bakID, $prevBak, $dbh);

    $backup->cleanBackup($prevBak);

    header('Location: /session/');

}elseif($bakID ==0 && $prevBak !=0){
    $id=$backup->initBackup();

    $session->updateSession($_POST, $id, $dbh);

    header('Location: /session/');
}else{
    $session->updateSession($_POST, $prevBak, $dbh);

    header('Location: /session/');
}
?>
