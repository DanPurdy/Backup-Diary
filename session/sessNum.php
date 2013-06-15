<?php

require_once 'includes/pdoconnection.php';                              //autoload classes

function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();                                         //create a connection instance

$session=new session($dbh); 

$session->updateSessNo($_POST['ssNo'], $_POST['sesID']);

?>
