<?php

session_start();
require_once '../includes/pdoconnection.php';

$dbh = dbConn::getConnection();

$bakMovOpt = $_POST['bakMov'];
$bakID = $_POST['bakID'];
$bakDate = date("Y-m-d H:i:s", time());
$fullcopy = (isset($_POST['fullcopy'])) ? 1 : 0;
$bakCupboard = (isset($_POST['bakCupboard'])) ? 1 : 0;
$bakKeep =(isset($_POST['keep'])) ? 1 : 0;
$deleted = (isset($_POST['deleted'])) ? 1 : 0;
    
if($bakMovOpt != 0){
    $bakMov = $bakMovOpt;
}else{
    
}

if(empty($_POST['bakID']) || $_POST['bakID'] == 0){
    
    
try{
$sth = $dbh->prepare('INSERT INTO backup (bakName, bakLoc, bakDate, fullCopy, bakCupboard, bakKeep, bakNotes, bakMov) 
    VALUES (:bakName, :bakLoc, :bakDate, :fullCopy, :bakCupboard, :bakKeep, :bakNotes, :bakMov)' );


$sth->bindParam(':bakName', $_POST['backName']);
$sth->bindParam(':bakLoc', $_POST['bakLoc'] , PDO::PARAM_INT);
$sth->bindParam(':bakDate', $bakDate);
$sth->bindParam(':fullCopy', $fullcopy , PDO::PARAM_INT);
$sth->bindParam(':bakCupboard', $bakCupboard , PDO::PARAM_INT);
$sth->bindParam(':bakKeep', $bakKeep , PDO::PARAM_INT);
$sth->bindParam(':bakNotes', $_POST['bakNotes'] , PDO::PARAM_STR);
$sth->bindParam(':bakMov', $bakMov , PDO::PARAM_INT);


$sth->execute();
$id = $dbh->lastInsertId('bakID');

$st1=$dbh->prepare('UPDATE session SET bakID=:bakID WHERE sesID=:sesID;');

$st1->bindParam(':bakID', $id);
$st1->bindParam(':sesID', $_POST['sesID']);

$st1->execute();



}
catch (PDOException $e) {
    print $e->getMessage();
}
header('Location: /backup/');

}elseif((!empty($_POST['bakID'])) && ($_POST['editBool'] == 0)){
    
   
try{
$sth = $dbh->prepare('UPDATE backup SET bakName=:bakName, bakLoc=:bakLoc, fullCopy=:fullCopy, bakCupboard=:bakCupboard, bakKeep=:bakKeep, bakNotes=:bakNotes, bakMov=:bakMov, bakDate=:bakDate, bakDeleted=:bakDeleted
    WHERE bakID=:bakID;' );


$sth->bindParam(':bakName', $_POST['backName']);
$sth->bindParam(':bakLoc', $_POST['bakLoc'] , PDO::PARAM_INT);
$sth->bindParam(':bakDate', $bakDate);
$sth->bindParam(':fullCopy', $fullcopy , PDO::PARAM_INT);
$sth->bindParam(':bakCupboard', $bakCupboard , PDO::PARAM_INT);
$sth->bindParam(':bakKeep', $bakKeep , PDO::PARAM_INT);
$sth->bindParam(':bakNotes', $_POST['bakNotes'] , PDO::PARAM_STR);
$sth->bindParam(':bakMov', $bakMov , PDO::PARAM_INT);
$sth->bindParam(':bakID', $bakID , PDO::PARAM_INT);
$sth->bindParam(':bakDeleted', $deleted , PDO::PARAM_INT);


$sth->execute();



}
catch (PDOException $e) {
    print $e->getMessage();
}

}elseif((!empty($_POST['bakID'])) && ($_POST['editBool'] == 1)){
    
    
try{
$sth = $dbh->prepare('UPDATE backup SET bakName=:bakName, bakLoc=:bakLoc, fullCopy=:fullCopy, bakCupboard=:bakCupboard, bakKeep=:bakKeep, bakNotes=:bakNotes, bakMov=:bakMov, bakDeleted=:bakDeleted
    WHERE bakID=:bakID;' );


$sth->bindParam(':bakName', $_POST['backName']);
$sth->bindParam(':bakLoc', $_POST['bakLoc'] , PDO::PARAM_INT);
$sth->bindParam(':fullCopy', $fullcopy , PDO::PARAM_INT);
$sth->bindParam(':bakCupboard', $bakCupboard , PDO::PARAM_INT);
$sth->bindParam(':bakKeep', $bakKeep , PDO::PARAM_INT);
$sth->bindParam(':bakNotes', $_POST['bakNotes'] , PDO::PARAM_STR);
$sth->bindParam(':bakMov', $bakMov , PDO::PARAM_INT);
$sth->bindParam(':bakID', $bakID , PDO::PARAM_INT);
$sth->bindParam(':bakDeleted', $deleted , PDO::PARAM_INT);


$sth->execute();



}
catch (PDOException $e) {
    print $e->getMessage();
}
}

header('Location: /backup/');

?>