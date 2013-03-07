<?php
session_start();

require_once 'includes/pdoconnection.php';

$dbh = dbConn::getConnection();

$sesID =    $_POST['sessionID'];
$stdID =    $_POST['studio'];
$cliID =    $_POST['clientID'];
$prjID =    $_POST['projectID'];
$fixID =    $_POST['fixerID'];
$engID =    $_POST['engineerID'];
$astID =    $_POST['assistantID'];
$ssNo =     $_POST['sessionNumber'];
$sessDate = $_POST['sessdate'];
$startTime= $_POST['starttime'];
$endTime =  $_POST['endtime'];
$cmpID =    $_POST['composerID'];
$bakID =    $_POST['sessCont'];
$prevBak =  $_POST['backupID'];

$sessDate = date('Y-m-d',  strtotime($sessDate));

//Detect user input and either insert new record and then add to session or just add to session

//if no ajax result selected then check to see if a value has been entered.
//if it's blank or been deleted then insert blank record to session
if($cliID==0 && empty($_POST['cliN'])){
    $cliID = 1; 
}//if no ajax result found and user entered a name then insert name into relevant table
elseif ($cliID==0 && !empty($_POST['cliN'])) {
    $fh = $dbh->prepare('INSERT INTO client (cliName) VALUES (:name)');
    
    $fh->bindParam(':name', $_POST['cliN'], PDO::PARAM_STR);
    $fh->execute();
    //Find the unique ID given to the record once inserted and set $..ID variable to insert into session
    $cliID = $dbh->lastInsertID('cliID');
}
else{
    //Do nothing if a search result has been selected
}//end if

if($prjID==0 && empty($_POST['projN'])){
    $prjID = 1; 
}elseif ($prjID==0 && !empty($_POST['projN'])) {
    $fh = $dbh->prepare('INSERT INTO project (prjName) VALUES (:name)');
    
    $fh->bindParam(':name', $_POST['projN'], PDO::PARAM_STR);
    $fh->execute();
    $prjID = $dbh->lastInsertID('prjID');
}else{
    //Do nothing
}

if($fixID==0 && empty($_POST['fixN'])){
    $fixID = 1; 
}elseif ($fixID==0 && !empty($_POST['fixN'])) {
    $fh = $dbh->prepare('INSERT INTO fixer (fixName) VALUES (:name)');
    
    $fh->bindParam(':name', $_POST['fixN'], PDO::PARAM_STR);
    $fh->execute();
    $fixID = $dbh->lastInsertID('fixID');
}else{
    //Do nothing
}

if($engID==0 && empty($_POST['engN'])){
    $engID = 1; 
}elseif ($engID==0 && !empty($_POST['engN'])) {
    $fh = $dbh->prepare('INSERT INTO engineer (engName) VALUES (:name)');
    
    $fh->bindParam(':name', $_POST['engN'], PDO::PARAM_STR);
    $fh->execute();
    $engID = $dbh->lastInsertID('engID');
}else{
    //Do nothing
}

if($astID==0 && empty($_POST['astN'])){
    $astID = 1; 
}elseif ($astID==0 && !empty($_POST['astN'])) {
    $fh = $dbh->prepare('INSERT INTO assistant (astName) VALUES (:name)');
    
    $fh->bindParam(':name', $_POST['astN'], PDO::PARAM_STR);
    $fh->execute();
    $astID = $dbh->lastInsertID('astID');
}else{
    //Do nothing
}

if($cmpID==0 && empty($_POST['compN'])){
    $cmpID = 1; 
}elseif ($cmpID==0 && !empty($_POST['compN'])) {
    $fh = $dbh->prepare('INSERT INTO composer (cmpName) VALUES (:name)');
    
    $fh->bindParam(':name', $_POST['compN'], PDO::PARAM_STR);
    $fh->execute();
    $cmpID = $dbh->lastInsertID('cmpID');
}else{
    //Do nothing
}

if($bakID != $prevBak && $bakID !=0){

try{
$sth = $dbh->prepare('UPDATE session SET stdID=:stdID, cliID=:cliID, prjID=:prjID, fixID=:fixID, engID=:engID, astID=:astID, ssNo=:ssNo, sessDate=:sessDate, startTime=:startTime, endTime=:endTime, cmpID=:cmpID, bakID =:bakID
        WHERE sesID=:sesID;' );

$sth->bindParam(':stdID', $stdID , PDO::PARAM_INT);
$sth->bindParam(':cliID', $cliID , PDO::PARAM_INT);
$sth->bindParam(':prjID', $prjID , PDO::PARAM_INT);
$sth->bindParam(':fixID', $fixID , PDO::PARAM_INT);
$sth->bindParam(':engID', $engID , PDO::PARAM_INT);
$sth->bindParam(':astID', $astID , PDO::PARAM_INT);
$sth->bindParam(':ssNo', $ssNo , PDO::PARAM_INT);
$sth->bindParam(':bakID', $bakID, PDO::PARAM_INT);
$sth->bindParam(':sessDate', $sessDate , PDO::PARAM_STR);
$sth->bindParam(':startTime', $startTime , PDO::PARAM_STR);
$sth->bindParam(':endTime', $endTime , PDO::PARAM_STR);
$sth->bindParam(':cmpID', $cmpID , PDO::PARAM_INT);
$sth->bindParam(':sesID', $sesID, PDO::PARAM_INT);

$sth->execute();

$st1 = $dbh->prepare('SELECT sesID FROM session WHERE bakID =:bakID;');

$st1->bindParam(':bakID', $prevBak, PDO::PARAM_INT);

$st1->execute();

$count = $st1->rowCount();

if($count == 0){
    $sth= $dbh->prepare('DELETE FROM backup WHERE bakID=:bakID;');
    
    $sth->bindParam(':bakID', $prevBak, PDO::PARAM_INT);
    
    $sth->execute();
    
}

header('Location: /session/');
}
catch (PDOException $e) {
    print $e->getMessage();
}

}elseif($bakID ==0 && $prevBak !=0){
    try{
$sth = $dbh->prepare('INSERT INTO backup (bakName) 
    VALUES (NULL);' );


$sth->execute();
$id = $dbh->lastInsertId('bakID');

$st1 = $dbh->prepare('UPDATE session SET stdID=:stdID, cliID=:cliID, prjID=:prjID, fixID=:fixID, engID=:engID, astID=:astID, ssNo=:ssNo, sessDate=:sessDate, startTime=:startTime, endTime=:endTime, cmpID=:cmpID, bakID =:bakID
        WHERE sesID=:sesID;' );

$st1->bindParam(':stdID', $stdID , PDO::PARAM_INT);
$st1->bindParam(':cliID', $cliID , PDO::PARAM_INT);
$st1->bindParam(':prjID', $prjID , PDO::PARAM_INT);
$st1->bindParam(':fixID', $fixID , PDO::PARAM_INT);
$st1->bindParam(':engID', $engID , PDO::PARAM_INT);
$st1->bindParam(':astID', $astID , PDO::PARAM_INT);
$st1->bindParam(':ssNo', $ssNo , PDO::PARAM_INT);
$st1->bindParam(':bakID', $id, PDO::PARAM_INT);
$st1->bindParam(':sessDate', $sessDate , PDO::PARAM_STR);
$st1->bindParam(':startTime', $startTime , PDO::PARAM_STR);
$st1->bindParam(':endTime', $endTime , PDO::PARAM_STR);
$st1->bindParam(':cmpID', $cmpID , PDO::PARAM_INT);
$st1->bindParam(':sesID', $sesID, PDO::PARAM_INT);


$st1->execute();



}
catch (PDOException $e) {
    print $e->getMessage();
}
header('Location: /session/');
}else{
    try{
$sth = $dbh->prepare('UPDATE session SET stdID=:stdID, cliID=:cliID, prjID=:prjID, fixID=:fixID, engID=:engID, astID=:astID, ssNo=:ssNo, sessDate=:sessDate, startTime=:startTime, endTime=:endTime, cmpID=:cmpID
        WHERE sesID=:sesID;' );

$sth->bindParam(':stdID', $stdID , PDO::PARAM_INT);
$sth->bindParam(':cliID', $cliID , PDO::PARAM_INT);
$sth->bindParam(':prjID', $prjID , PDO::PARAM_INT);
$sth->bindParam(':fixID', $fixID , PDO::PARAM_INT);
$sth->bindParam(':engID', $engID , PDO::PARAM_INT);
$sth->bindParam(':astID', $astID , PDO::PARAM_INT);
$sth->bindParam(':ssNo', $ssNo , PDO::PARAM_INT);
$sth->bindParam(':sessDate', $sessDate , PDO::PARAM_STR);
$sth->bindParam(':startTime', $startTime , PDO::PARAM_STR);
$sth->bindParam(':endTime', $endTime , PDO::PARAM_STR);
$sth->bindParam(':cmpID', $cmpID , PDO::PARAM_INT);
$sth->bindParam(':sesID', $sesID, PDO::PARAM_INT);

$sth->execute();
    

}
catch (PDOException $e) {
    print $e->getMessage();
}
header('Location: /session/');
}
?>
