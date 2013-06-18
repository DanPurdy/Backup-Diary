<?php
session_start();

require('includes/pdoconnection.php');

function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$microphone=new mic($dbh);

$link =$_SERVER['HTTP_REFERER'];
$linkParts = explode('&', $link);
$link = $linkParts[0];
$message = $_GET['micNo'];   
    
$row=$microphone->getMicByID($_POST['micNo']);

$resCount = $microphone->count;

if($row['micSession'] == 0 && $resCount !=0 && $row['micRepair'] ==0){

    $row= $microphone->addMicSession($_POST['micNo'], $_SESSION['user']['usrID'], $_POST['bakID'], $_POST['sesID']);
    
    header('Location: '.$link);     
}elseif($row['micRepair'] == 1){
    
    header('Location: '.$link.'&e=5&micNo='.$row['micID']);
    
}else{
    
    
    header('Location: '.$link.'&e=1&micNo='.$row['micID'].'&prevSes='.$row['micSession'] );
    
    
}     ?>
