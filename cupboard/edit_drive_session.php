<?php

require_once 'includes/pdoconnection.php';

$dbh = dbConn::getConnection();

$driveID= htmlentities($_GET['driveID']);
$sesID= htmlentities($_GET['sesID']);

if(isset($_POST['submit'])){
    
    
    $cliID =    $_POST['clientID'];
    $cmpID =    $_POST['composerID'];
    
    if(empty($_POST['cliN'])){
        
        $cliID = 1; 
    
        
    }//if no ajax result found and user entered a name then insert name into relevant table
        
    elseif ($cliID==0 && !empty($_POST['cliN'])) {
        try{
            $fh = $dbh->prepare('INSERT INTO client (cliName) VALUES (:name)');
    
            $fh->bindParam(':name', $_POST['cliN'], PDO::PARAM_STR);
            $fh->execute();
            //Find the unique ID given to the record once inserted and set $..ID variable to insert into session
            $cliID = $dbh->lastInsertID('cliID');
        
         }
    catch (PDOException $e) {
        print $e->getMessage();
    }
    
    }
    else{
    //Do nothing if a search result has been selected
    }//end if
    
    if(empty($_POST['compN'])){
        
        $cmpID = 1; 
    
    }
    elseif ($cmpID==0 && !empty($_POST['compN'])) {
        
        try{
            $fh = $dbh->prepare('INSERT INTO composer (cmpName) VALUES (:name)');
    
            $fh->bindParam(':name', $_POST['compN'], PDO::PARAM_STR);
            $fh->execute();
            $cmpID = $dbh->lastInsertID('cmpID');
        
         }
    catch (PDOException $e) {
        print $e->getMessage();
    }
    
}else{
    //Do nothing
}

$date=date("Y-m-d", strtotime($_POST['bakDate']));
try{
    $st1=$dbh->prepare('UPDATE legacySess 
        SET legacyName=:name, bakDate=:date, cliID=:clientID, cmpID=:composerID
        WHERE legacyID=:legacyID;');
    
    $st1->bindParam(':name', $_POST['driveNameInput'],PDO::PARAM_STR);
    $st1->bindParam(':date',$date);
    $st1->bindParam(':clientID', $cliID, PDO::PARAM_INT);
    $st1->bindParam(':composerID', $cmpID, PDO::PARAM_INT);
    $st1->bindParam(':legacyID', $_POST['sessID'], PDO::PARAM_INT);
    
    $st1->execute();
}
    catch (PDOException $e) {
        print $e->getMessage();
    }
    
    header('Location: /cupboard/edit_drive.php?driveID='.$_POST['driveID']);
}



try{
    
    $res=$dbh->prepare('SELECT * FROM legacySess
                        LEFT JOIN client ON (legacySess.cliID=client.cliID)
                        LEFT JOIN composer ON (legacySess.cmpID=composer.cmpID)
                        INNER JOIN legacyDriveSess ON (legacySess.legacyID=legacyDriveSess.legacyID) 
                        WHERE legacySess.legacyID=:sessID');
    
    $res->bindParam(':sessID',$sesID,PDO::PARAM_INT);
    
    $res->execute();
    
    $result=$res->fetch(PDO::FETCH_BOTH);
}
catch (PDOException $e) {
        print $e->getMessage();
    }
    

catch (PDOException $e) {
    print $e->getMessage();
   }  

$date = strtotime($result['bakDate']);

require_once ('header.php');
?>

<div class="returnLink">
    <a href="http://localhost/cupboard/edit_drive.php?driveID=<?=$driveID?>"> &laquo Back to Drive</a>
</div>
<div id="subHead"><h1>Edit Tape Store Backup</h1></div>
    <div id="clientDriveDetails">
    <form id="newDriveSess" method="post" action="edit_drive_session.php" enctype="multipart/form-data">
        
        <input class="driveID hidden" name="driveID" value="<?=$result['cupbID'];?>"/>
        <input class="driveID hidden" name="sessID" value="<?=$sesID?>" class="hidden" />
        <div id="driveName">
            <h3><label for="driveNameInput">Session Name</label></h3>
                <input id="driveNameInput" name="driveNameInput" type="text" size="75" value="<?=$result['legacyName'] ?>" required/>
                
        </div>
        <div id="legacyDate">
            <h3><label for ="bakDate">Session Date</label></h3>
            <input id="legacyDateInput" type="date" name="bakDate" value="<?=date('Y-m-d',$date); ?>" required>
        </div>
        
        <div id="ownerDetails">
            <div id="clientOwnerSelect">
                <h3>Select Client</h3>
                <input id="clisearch" name="cliN" type="text" value="<?=$result['cliName'] ?>" />
                <input id="clientID" name="clientID" value="<?=$result['cliID'] ?>" class="hidden" />
            </div>
            
            <div id="cmpOwnerSelect">
                <h3>Select Composer</h3>
                <input id="composersearch" name="compN" type="text" value="<?=$result['cmpName'] ?>" />
                <input id="composerID" name="composerID" value="<?=$result['cmpID'] ?>" class="hidden" />
            </div>
            </div>
       
        <div id="driveSubmit"><input type="submit" name="submit" value="Modify Backup" id="newDriveSubmit"/></div>
        </div>
    </form>

        
<?php

    require_once ('footer.php');
?>