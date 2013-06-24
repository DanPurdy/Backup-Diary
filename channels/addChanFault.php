<?php
session_start();
require('includes/pdoconnection.php');
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}
$dbh = dbConn::getConnection();
$channel = new channel($dbh);
    
    if($_POST['fault_button']){
        
        $channel->addChanFault($_POST['channel'], $_POST['stdID'], $_POST['faultDesc'], $_SESSION['user']['usrID']);
        
    }elseif($_POST['swap_button']){
        
        $channel->swapChannels($_POST['channelOne'], $_POST['channelTwo'], $_POST['stdID']);
        
    }
    header('Location: '.$_SERVER['HTTP_REFERER'] );
    
?>
