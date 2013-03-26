<?php
session_start();

require_once 'includes/pdoconnection.php';
require_once 'functions/functions.php';

    $dbh = dbConn::getConnection();
    
    $link =$_SERVER['HTTP_REFERER'];
    $linkParts = explode('?', $link);
    $link = $linkParts[0];
    
    
 if (isset($_GET['micID']) && !$_POST) {
     
     $mic =$_GET['micID'];
      try{
    $sth=$dbh->prepare("SELECT * FROM micFault WHERE micID = :micID AND faultOutcome IS NULL;" );
    
    $sth->bindParam(':micID', $mic, PDO::PARAM_INT);

  $sth->execute();
  
  $count=$sth->rowCount();
        
}catch (PDOException $e){
    print $e ->getMessage();

 }
 
 if($count == 0){
     
     try{
    $sth=$dbh->prepare("UPDATE microphones SET micRepair = 0, micCupboard =1 WHERE micID = :micID;" );
    
    $sth->bindParam(':micID', $mic, PDO::PARAM_INT);

  $sth->execute();
  
  $st1=$dbh->prepare("SELECT sesID FROM session WHERE bakID = (SELECT micTransfer FROM microphones WHERE micID = :micID) ORDER BY sessDate ASC LIMIT 1;");
  $st1->bindParam(':micID',$mic, PDO::PARAM_INT);
  $st1->execute();
  
  $result = $st1->fetch(PDO::FETCH_ASSOC);
  
  micLog($mic, $_SESSION['user']['usrID'], $result['sesID'] ,'cupboard');
        
}catch (PDOException $e){
    print $e ->getMessage();

 }
 header('Location: '.$_SERVER['HTTP_REFERER'] );
 }elseif($count > 0){
     header('Location: '.$link.'?e=1&micID='.   htmlentities($_GET['micID'])  );
 }
 }
?>
