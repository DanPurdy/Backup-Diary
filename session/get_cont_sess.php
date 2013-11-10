<?php

require_once 'includes/pdoconnection.php';
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}


$dbh = dbConn::getConnection();

$session=new session($dbh);

if($_REQUEST['term']){
	$result=$session->getContSessAjax($_REQUEST['term']);
}elseif($_REQUEST['Id']){
	$result=$session->getSessByBakID($_REQUEST['Id']);
}    
echo $result;

?>
