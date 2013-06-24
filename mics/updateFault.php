<?php

require('includes/pdoconnection.php');

function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$microphone=new mic($dbh);
    
if (isset($_POST['faultID'])) {

   $fault =$_POST['faultID'];

   $microphone->updateFault($_POST['fault'], $_POST['solution'], $fault);
}
 header('Location: '.$_SERVER['HTTP_REFERER'] );
?>
