<?php

session_start();

require('includes/pdoconnection.php');
    $dbh = dbConn::getConnection();
    
     $fault =$_POST['faultID'];
     
     if (isset($_POST['faultID'])&& !empty($_POST['solution'])) {
     
     
     try{
    $sth=$dbh->prepare("UPDATE chanFault SET faultDesc = :fault, faultOutcome = :outcome WHERE faultID = :faultID;" );
    
    $sth->bindParam(':fault', $_POST['fault'], PDO::PARAM_STR);
    $sth->bindParam(':outcome', $_POST['solution'], PDO::PARAM_STR);
    $sth->bindParam(':faultID', $fault, PDO::PARAM_INT);
    
  $sth->execute();
        
}catch (PDOException $e){
    print $e ->getMessage();

 }
 }else{
    if (isset($_POST['faultID'])) {
     
     $fault =$_POST['faultID'];
     try{
    $sth=$dbh->prepare("UPDATE chanFault SET faultDesc = :fault WHERE faultID = :faultID;" );
    
    $sth->bindParam(':fault', $_POST['fault'], PDO::PARAM_STR);
    $sth->bindParam(':faultID', $fault, PDO::PARAM_INT);
    
  $sth->execute();
        
}catch (PDOException $e){
    print $e ->getMessage();

  
        }
    }
 }
 header('Location: '.$_SERVER['HTTP_REFERER'] );
?>

