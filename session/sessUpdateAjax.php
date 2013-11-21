<?php

require_once 'includes/pdoconnection.php';                              //autoload classes

function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();                                         //create a connection instance

$session=new session($dbh); 
$cupboard = new cupboard($dbh);
if(!isset($_POST['tapeOwner'])){
	$session->updateDetailsBackup($_POST, $dbh);

	$result=$session->getSessByID($_POST['sessionID'], true);
}else{
	$result=$cupboard->getComposerDrive($_POST['tapeOwner']);
}

echo $result;

?>