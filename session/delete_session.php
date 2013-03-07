<?php

require_once '../includes/pdoconnection.php';

$dbh = dbConn::getConnection();

if (isset($_GET['sesID']) && !$_POST) {
    
    $sesID = $_GET['sesID'];
try {
    
    
$sth=$dbh->prepare("SELECT bakID FROM session WHERE sesID=:sessID;" );
    
    $sth->bindParam(':sessID', $sesID);

  $sth->execute();
  
  $row=$sth->fetch(PDO::FETCH_ASSOC);
  
  $bakID = $row['bakID'];
    
    
$st1=$dbh->prepare("DELETE FROM session WHERE sesID=:sessID;" );
    
    $st1->bindParam(':sessID', $sesID);

    $st1->execute();
    
    
$st2=$dbh->prepare("SELECT sesID FROM session WHERE bakID=:bakID;");
    
    $st2->bindParam(':bakID', $bakID);
    
    $st2->execute();
    
    $count=$st2->rowCount();
    
}
catch (PDOException $e) {
    print $e->getMessage();
}

if($count == 0){
    try{
       $st2=$dbh->prepare("DELETE FROM backup WHERE bakID=:bakID;");
    
    $st2->bindParam(':bakID', $bakID);
    
    $st2->execute(); 
}
catch (PDOException $e) {
    print $e->getMessage();
}
}
header('Location: '.$_SERVER['HTTP_REFERER'] );
}
?>
