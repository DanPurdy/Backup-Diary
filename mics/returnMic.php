<?php
session_start();

require('includes/pdoconnection.php');

function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$session=new session($dbh);
$microphone=new mic($dbh);

    // Remove the parameters from the referal link
    $link = $_SERVER['HTTP_REFERER'];
    $linkParts = explode('&', $link);
    $link = $linkParts[0].'&remove=1'; // add the remove parameter onto the referer again

    
    
if($_POST['returnMic_button']){  // Checks to see if mics are being returned or transferred, Returned in this case

   $row = $microphone->getMicByID($_POST['micNo']);
   
    if($_POST['bakID'] === $row['micSession']){ // checks to see if you are returning a mic from this session by checking backup number with saved backup number
       
        $microphone->returnMicSession($_POST['micNo'], $_SESSION['user']['usrID'], $_POST['bakID'], $_POST['sesID']);
        
        header('Location: '.$link ); //update the microphone table to reflect mic going into cupboard
        
        
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
    
        
        
        $sesID=$session->getTransferSessID($_POST['transferSession']);
            
        $microphone->transferMicSession($_POST['micNo_check'], $_SESSION['user']['usrID'], $_POST['bakID'], $_POST['transferSession'], $sesID);

        header('location: '.$link); //return to the return mics page
    }
    elseif ($_POST['transferSession'] == 0) { //if a session to transfer to hasnt been selected return an error (4 - set a session to transfer to)
        
        header('location: '.$link.'&e=4');
        
    }
}

elseif ($_POST['repairMic_button']){
    if(!empty($_POST['micNo_check'])){
        $microphone->transferMicWorkshop($_POST['micNo_check'], $_SESSION['user']['usrID'], $_POST['bakID'], $_POST['fault'], $_POST['sesID']); 
    }
    header('location: '.$link); //return to the return mics page
}
?>
