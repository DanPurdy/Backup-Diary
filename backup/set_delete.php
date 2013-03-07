<?php
session_start();
require_once '../includes/pdoconnection.php';

$dbh = dbConn::getConnection();

$OK = false;
$done = false;
if (isset($_GET['backupID']) && !$_POST) {
try {
$sth=$dbh->prepare("UPDATE backup SET bakDeleted=1 WHERE bakID = :bakID;" );
    
    $sth->bindParam(':bakID', $_GET['backupID']);

  $sth->execute();
  
  header('Location: '.$_SERVER['HTTP_REFERER']);
  
}
catch (PDOException $e) {
    print $e->getMessage();
}

}
?>
