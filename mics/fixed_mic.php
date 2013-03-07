<?php

require('includes/pdoconnection.php');
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
        
}catch (PDOException $e){
    print $e ->getMessage();

 }
 header('Location: '.$_SERVER['HTTP_REFERER'] );
 }elseif($count > 0){
     header('Location: '.$link.'?e=1&micID='.   htmlentities($_GET['micID'])  );
 }
 }
?>
