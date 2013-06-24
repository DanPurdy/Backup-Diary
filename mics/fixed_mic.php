<?php
session_start();

require('includes/pdoconnection.php');

function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$microphone=new mic($dbh);


    
    $link =$_SERVER['HTTP_REFERER'];
    $linkParts = explode('?', $link);
    $link = $linkParts[0];
    
    
if (isset($_GET['micID']) && !$_POST) {
     
    $mic =  htmlentities($_GET['micID']);
      
    $count=$microphone->checkMicFault($mic);
 
    if($count == 0){
     
        $microphone->returnFaultMic($mic, $_SESSION['user']['usrID'], 'cupboard');
    
        header('Location: '.$_SERVER['HTTP_REFERER'] );
    
    }elseif($count > 0){
     
        header('Location: '.$link.'?e=1&micID='.   htmlentities($_GET['micID'])  );
     
    }
 }
?>
