<?php
session_start();

require('includes/pdoconnection.php');

    $dbh = dbConn::getConnection();
    
    if($_POST['fault_button']){
        try{
            $sth=$dbh->prepare('SELECT channelID FROM channels WHERE currentPos = :currentCh AND stdID = :stdID;');
            $sth->bindParam(':currentCh', $_POST['channel'], PDO::PARAM_INT);
            $sth->bindParam(':stdID', $_POST['stdID'], PDO::PARAM_INT);
            $sth->execute();
            
            $result=$sth->fetchAll();
            
            $chanID = $result[0]['channelID'];
            
            $st1=$dbh->prepare('INSERT INTO chanFault (channelID, channelPos, faultDesc, userID) VALUES (:channelID, :channelPos, :faultDesc, :userID);');
            $st1->bindParam(':channelID', $chanID, PDO::PARAM_INT);
            $st1->bindParam(':channelPos', $_POST['channel'], PDO::PARAM_INT);
            $st1->bindParam(':faultDesc', $_POST['faultDesc'], PDO::PARAM_STR);
            $st1->bindParam(':userID', $_SESSION['user']['usrID']);
            
            $st1->execute();
        }
    catch (PDOException $e){
        print $e->getMessage();
    }
    }elseif($_POST['swap_button']){
        
        $i=0;
        $array = array();
        $resultArray = array();
        
        $array[] = $_POST['channelOne'];
        $array[] = $_POST['channelTwo'];
        
        foreach($array as $value){
        try{
            $sth=$dbh->prepare('SELECT channelID FROM channels WHERE currentPos = :currentCh AND stdID = :stdID;');
            $sth->bindParam(':currentCh', $value, PDO::PARAM_INT);
            $sth->bindParam(':stdID', $_POST['stdID'], PDO::PARAM_INT);
            $sth->execute();
            
            $result=$sth->fetchAll();
            
            $resultArray[$i] = $result[0]['channelID'];
            
            $i++;
    }
    catch (PDOException $e){
        print $e->getMessage();
    }
    }
    $j=1;
    foreach($resultArray as $id){
        try{
            $sth=$dbh->prepare('UPDATE channels SET currentPos = :pos WHERE channelID = :channelID');
            $sth->bindParam(':pos', $array[$j], PDO::PARAM_INT);
            $sth->bindParam(':channelID', $id, PDO::PARAM_INT);
            
            $sth->execute();
            
            $j--;
            
            
    }
    catch(PDOException $e){
        print $e->getMessage();
    }
    }
    }
    header('Location: '.$_SERVER['HTTP_REFERER'] );
    
?>
