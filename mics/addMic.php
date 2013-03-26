<?php
session_start();

require_once 'includes/pdoconnection.php';
require_once 'functions/functions.php';

$dbh = dbConn::getConnection();


    $link =$_SERVER['HTTP_REFERER'];
    $linkParts = explode('&', $link);
    $link = $linkParts[0];
    $message = $_GET['micNo'];
    
    
    
try{
    $st1 = $dbh->prepare('SELECT * FROM microphones WHERE micID = :mic;');
    
    $st1->bindParam(':mic', $_POST['micNo'], PDO::PARAM_INT);
    
    $st1->execute();
            
}catch(PDOException $e){
    print $e->getMessage();
    
}

    $row = $st1->fetch(PDO::FETCH_ASSOC);
    $resCount = $st1->rowCount();

if($row['micSession'] == 0 && $resCount !=0 && $row['micRepair'] ==0){
 try{
     $sth = $dbh->prepare('UPDATE microphones
                           SET micSession=:bakID, micCupboard=0, micTransfer=NULL, usrID=:user
                           WHERE micID = :mic;');
    
    $sth->bindParam(':mic', $_POST['micNo'], PDO::PARAM_INT);
    $sth->bindParam(':bakID', $_POST['bakID'], PDO::PARAM_INT);
    $sth->bindParam(':user', $_SESSION['user']['usrID'], PDO::PARAM_INT);
    $sth->execute();
   

    $st1 = $dbh->prepare('SELECT * FROM sessmics WHERE sessmicsID = :bakID');
    
    $st1->bindParam(':bakID', $_POST['bakID'], PDO::PARAM_INT);
    $st1->execute();
    
    
    if($st1->rowCount() == 0){
        
        $micArray = array();
        
        $micArray[] = $_POST['micNo'];
        
        $mics = serialize($micArray);
        
       
        $st2 = $dbh->prepare('INSERT INTO sessmics (sessmicsID, sessmicList) VALUES (:bakID, :mic);');
        
        $st2->bindParam(':bakID', $_POST['bakID'], PDO::PARAM_INT);
        $st2->bindParam(':mic', $mics, PDO::PARAM_STR); 
        
        $st2->execute();
        
        micLog($_POST['micNo'], $_SESSION['user']['usrID'], $_POST['sesID'], 'session'); 
        
    } else{
        
        $row = $st1->fetch(PDO::FETCH_ASSOC);
        
        $micArray = unserialize($row['sessmicList']);
        
        
        $nextMic = $_POST['micNo'];
        
        if(in_array($nextMic, $micArray)){
           
        }else{
        
        $micArray[]=$nextMic;
        sort($micArray);
        }
        
        
        $mics= serialize($micArray);
        
        
        $st2 = $dbh->prepare('UPDATE sessmics SET sessmicList=:mic WHERE sessmicsID = :bakID;');
        
        $st2->bindParam(':bakID', $_POST['bakID'], PDO::PARAM_INT);
        $st2->bindParam(':mic', $mics, PDO::PARAM_STR);
        
        $st2->execute();
        
        
        micLog($_POST['micNo'], $_SESSION['user']['usrID'], $_POST['sesID'], 'session');
    }
    
    
  
    
    
    
   
}
catch(PDOException $e){
    print $e->getMessage();
    
}

  
    header('Location: '.$link);     
}elseif($row['micRepair'] == 1){
    header('Location: '.$link.'&e=5&micNo='.$row['micID']);
}else{
    
    
    header('Location: '.$link.'&e=1&micNo='.$row['micID'].'&prevSes='.$row['micSession'] );
    
    
}     ?>
