<?php

require_once 'includes/pdoconnection.php';                              //autoload classes
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();                                         //create a connection instance

$session=new session($dbh);

if (isset($_GET['sesID']) && !$_POST) {
    
    $sesID = $_GET['sesID'];
    
    $session->deleteSession($sesID);
    
    header('Location: '.$_SERVER['HTTP_REFERER']);
}
?>
