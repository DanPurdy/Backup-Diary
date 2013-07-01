<?php

require_once 'includes/pdoconnection.php';
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}


$dbh = dbConn::getConnection();

$session=new session($dbh);

$result=$session->getContSessAjax($_REQUEST['term']);
        
echo $result;

?>
