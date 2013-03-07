<?php

require('includes/pdoconnection.php');
    $dbh = dbConn::getConnection();
    
     if (isset($_POST['faultID'])) {
     
     $fault =$_POST['faultID'];
     try{
    $sth=$dbh->prepare("UPDATE micFault SET faultDesc = :fault, faultOutcome = :outcome WHERE faultID = :faultID;" );
    
    $sth->bindParam(':fault', $_POST['fault'], PDO::PARAM_STR);
    $sth->bindParam(':outcome', $_POST['solution'], PDO::PARAM_STR);
    $sth->bindParam(':faultID', $fault, PDO::PARAM_INT);
    
  $sth->execute();
        
}catch (PDOException $e){
    print $e ->getMessage();

 }
 }
 header('Location: '.$_SERVER['HTTP_REFERER'] );
?>
