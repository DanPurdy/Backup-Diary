<?php
session_start();

require_once 'includes/pdoconnection.php';
require_once 'functions/functions.php';

$dbh = dbConn::getConnection();

    // Remove the parameters from the referal link
    $link = $_SERVER['HTTP_REFERER'];
    $linkParts = explode('&', $link);
    $link = $linkParts[0].'&remove=1'; // add the remove parameter onto the referer again

    
    
if($_POST['returnMic_button']){  // Checks to see if mics are being returned or transferred, Returned in this case
    try{
        $st1 = $dbh->prepare('SELECT * FROM microphones WHERE micID= :mic'); //Get all current location details for the mic being returned
        $st1->bindParam(':mic', $_POST['micNo'], PDO::PARAM_INT);
    
        $st1->execute();
        }
    catch(PDOException $e){
        print $e->getMessage();
    }
    $row = $st1->fetch(PDO::FETCH_ASSOC); //set $row with the results of query
    if($_POST['bakID'] === $row['micSession']){ // checks to see if you are returning a mic from this session by checking backup number with saved backup number
        try{
            $sth = $dbh->prepare('UPDATE microphones
                                SET micSession=null, micCupboard=1, micTransfer=:bakID, usrID=:user
                                WHERE micID = :mic;');
    
            $sth->bindParam(':mic', $_POST['micNo'], PDO::PARAM_INT);
            $sth->bindParam(':bakID', $_POST['bakID'], PDO::PARAM_INT);
            $sth->bindParam(':user', $_SESSION['user']['usrID'], PDO::PARAM_INT);
            $sth->execute();
    
            header('Location: '.$link ); //update the microphone table to reflect mic going into cupboard
            
            micLog($_POST['micNo'], $_SESSION['user']['usrID'], $_POST['sesID'], 'cupboard');
            
            }
        catch(PDOException $e){
            print $e->getMessage();
    
        }
        
        
        
    }elseif ($_POST['bakID'] != $row['micSession'] && $row['micCupboard'] == 1){ // if a mic is returned that isnt assigned to the current session but is already in the cupboard
    
        header('Location: '.$link.'&e=2&micNo='.$row['micID'].'&micCupb=1'); //return to edit_mic page and set error parameter to 2
    
    
    }elseif ($_POST['bakID'] != $row['micSession'] && $row['micCupboard'] == 0 && $row['micRepair'] == 1){ // if a mic is returned that isnt assigned to the current session but is already in the cupboard
    
        header('Location: '.$link.'&e=5&micNo='.$row['micID']); //return to edit_mic page and set error parameter to 5 (mic out for repair)
    
    
    }else {
    
        header('Location: '.$link.'&e=3&micNo='.$row['micID'].'&prevSes='.$row['micSession']); // return to edit page and set error to 3 (mic not assigned to this session)
    }  
}



elseif($_POST['transferMic_button'] && !empty($_POST['micNo_check'])){ //Triggers if mics are being transferred via transfer button
    if( $_POST['transferSession'] >=1){
    foreach($_POST['micNo_check'] as $transferMic) { //for each of the checkbox values selected
            

    try{                                                //update the microphone table with the correct location details for each mic being transferred including setting transferid to previous session id
        $st1 = $dbh->prepare('UPDATE microphones 
                          SET micSession=:newSes, micTransfer=:bakID, usrID=:user
                          WHERE micID= :mic;');
        $st1->bindParam(':mic', $transferMic, PDO::PARAM_INT);
        $st1->bindParam(':newSes', $_POST['transferSession'], PDO::PARAM_INT);
        $st1->bindParam(':bakID', $_POST['bakID'], PDO::PARAM_INT);
        $st1->bindParam(':user', $_SESSION['user']['usrID'], PDO::PARAM_INT);
        $st1->execute();
    
        $st2 = $dbh->prepare('SELECT * FROM sessmics WHERE sessmicsID = :bakID;');   //query the sessmics table to see if a record exists for that the session the mics are transferring to
        $st2->bindParam(':bakID', $_POST['transferSession'], PDO::PARAM_INT);
        $st2->execute();
    
    
        if($st2->rowCount() == 0){ //if no results ie there is no record for the session in the sessmics table then
        
            $micArray = array(); //initialize array for the miclist
        
            $micArray[] = $transferMic; //set the value of the current checkbox/microphone to the next value of the mic Array
        
            $mics = serialize($micArray); //serialize the array for storage in the database
        
       
            $st3 = $dbh->prepare('INSERT INTO sessmics (sessmicsID, sessmicList) VALUES (:bakID, :mic);'); //make a new record for the 
        
            $st3->bindParam(':bakID', $_POST['transferSession'], PDO::PARAM_INT);
            $st3->bindParam(':mic', $mics, PDO::PARAM_STR); 
        
            $st3->execute();
            
            micLog($_POST['micNo'], $_SESSION['user']['usrID'], $_POST['sesID'], 'transfer');
        
        
        }else{
        
            $row = $st2->fetch(PDO::FETCH_ASSOC); // if a record is already present then take the current mic list unserialize add the new mic to the list and then serialize again to store
        
            $micArray = unserialize($row['sessmicList']);
        
        
            $nextMic = $transferMic;
        
            if(in_array($nextMic, $micArray)){ //check to see the mic is already in the miclist for this session
           
            }else{
        
                $micArray[]=$nextMic; //if it isnt add it to the mic list
            }
        
            sort($micArray); // sort the array numerically before storage
            $mics= serialize($micArray); //serialize the array including the new value
       
        
            $st3 = $dbh->prepare('UPDATE sessmics SET sessmicList=:mic WHERE sessmicsID = :bakID;'); //update the sessmics table
            
            $st3->bindParam(':bakID', $_POST['transferSession'], PDO::PARAM_INT);
            $st3->bindParam(':mic', $mics, PDO::PARAM_STR);
        
            $st3->execute();
            
            $st4 = $dbh->prepare('SELECT sesID FROM session WHERE bakID = :bakID ORDER BY sessDate ASC LIMIT 1');
            
            $st4->bindParam(':bakID', $_POST['transferSession'], PDO::PARAM_INT);
            $st4->execute();
            
            $result = $st4->fetch(PDO::FETCH_ASSOC);
            
            
            
            micLog($transferMic, $_SESSION['user']['usrID'], $result['sesID'], 'transfer');
            }
    
    }catch(PDOException $e){
        print $e->getMessage();
    }
    } // End of Foreach

    header('location: '.$link); //return to the return mics page
   }
    elseif ($_POST['transferSession'] == 0) { //if a session to transfer to hasnt been selected return an error (4 - set a session to transfer to)
    header('location: '.$link.'&e=4');
}
}

elseif ($_POST['repairMic_button']){
    if(!empty($_POST['micNo_check'])){
    foreach($_POST['micNo_check'] as $repairMic) {
        try{                                                //update the microphone table with the correct location details for each mic being sent for repair including setting transferid to previous session id
        $st1 = $dbh->prepare('UPDATE microphones 
                          SET micSession=NULL, micRepair=1, micTransfer=:bakID, usrID=:user
                          WHERE micID= :mic;');
        $st1->bindParam(':mic', $repairMic, PDO::PARAM_INT);
        $st1->bindParam(':bakID', $_POST['bakID'], PDO::PARAM_INT);
        $st1->bindParam(':user', $_SESSION['user']['usrID'], PDO::PARAM_INT);
        $st1->execute();
        
        
        $st2 = $dbh->prepare('INSERT INTO micFault (micID, userID, faultDesc)
                              VALUES (:micID, :userID, :faultDesc);');
        
        $st2->bindParam(':micID', $repairMic, PDO::PARAM_INT);
        $st2->bindParam(':userID', $_SESSION['user']['usrID'], PDO::PARAM_INT);
        $st2->bindParam(':faultDesc', $_POST['fault'], PDO::PARAM_INT);
        $st2->execute();
        
        micLog($repairMic, $_SESSION['user']['usrID'], $_POST['sesID'], 'workshop');
        }
        catch (PDOException $e){
            print $e->getMessage();
        }
    }
    }
    header('location: '.$link); //return to the return mics page
}
?>
