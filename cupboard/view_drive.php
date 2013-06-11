<?php

require_once 'includes/pdoconnection.php';

$dbh = dbConn::getConnection();


$driveID=$_GET['driveID'];

if(isset($_POST['modifyNotes'])){
    if(empty($_POST['noteID'])){
    try{
    $st1=$dbh->prepare('INSERT INTO cupboardDriveNotes (cupbID, cupbNote) VALUES (:cupbID,:notes);');
    
    $st1->bindParam(':notes', $_POST['driveNotes'],PDO::PARAM_STR);
    $st1->bindParam(':cupbID',$driveID,PDO::PARAM_INT);
    
    $st1->execute();
}
 catch (PDOException $e) {
        print $e->getMessage();
    }
}else{
        try{
    $st1=$dbh->prepare('UPDATE cupboardDriveNotes
                        SET cupbNote=:cupbNote
                        WHERE cupbID=:cupbID;');
    
    $st1->bindParam(':cupbID',$driveID);
    $st1->bindParam(':cupbNote', $_POST['driveNotes'],PDO::PARAM_STR);
    
    
    $st1->execute();
}
 catch (PDOException $e) {
        print $e->getMessage();
    }
}
}
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
    $st1=$dbh->prepare('INSERT INTO legacySess (legacyName,bakDate,cliID,cmpID) VALUES (:name, :date, :clientID, :composerID);');
    
    $st1->bindParam(':name', $_POST['driveNameInput'],PDO::PARAM_STR);
    $st1->bindParam(':date',$date);
    $st1->bindParam(':clientID', $cliID, PDO::PARAM_INT);
    $st1->bindParam(':composerID', $cmpID, PDO::PARAM_INT);
    
    $st1->execute();
    
    $legacyID=$dbh->lastInsertID('legacyID');
}
    catch (PDOException $e) {
        print $e->getMessage();
    }
    
    try{
    $st1=$dbh->prepare('INSERT INTO legacyDriveSess (legacyID,cupbID) VALUES (:legacyID, :cupbID);');
    
   
    $st1->bindParam(':legacyID',$legacyID, PDO::PARAM_INT);
    $st1->bindParam(':cupbID', $_POST['driveID'], PDO::PARAM_INT);
    
    $st1->execute();
}
    catch (PDOException $e) {
        print $e->getMessage();
    }
    
    header('Location: /cupboard/view_drive.php?driveID='.$_POST['driveID']);
}

try{
    $bak=$dbh->prepare('SELECT * FROM backup
                        LEFT JOIN driveContent ON driveContent.bakID = backup.bakID
                        LEFT JOIN session ON session.bakID = backup.bakID
                        LEFT JOIN client ON client.cliID = session.cliID
                        LEFT JOIN composer ON composer.cmpID = session.cmpID
                        WHERE driveContent.cupbID = :drive AND driveContent.deleted=0
                        GROUP BY backup.bakID;');
    
    $bak->bindParam(':drive',$driveID,PDO::PARAM_INT);
    
    $bak->execute();
    
    
    $res=$dbh->prepare('SELECT * FROM legacySess
                        LEFT JOIN client ON (legacySess.cliID=client.cliID)
                        LEFT JOIN composer ON (legacySess.cmpID=composer.cmpID)
                        INNER JOIN legacyDriveSess ON (legacySess.legacyID=legacyDriveSess.legacyID) 
                        WHERE cupbID = :drive AND legacySess.deleted=0;');
    
    $res->bindParam(':drive',$driveID,PDO::PARAM_INT);
    
    $res->execute();
}
catch (PDOException $e) {
        print $e->getMessage();
    }
    
try{
    $d=$dbh->prepare('SELECT cupbName FROM cupboardDrive WHERE cupbID = :cupbID');
    
    $d->bindParam(':cupbID', $driveID,PDO::PARAM_INT);
    
    $d->execute();
    
    $result = $d->fetch(PDO::FETCH_ASSOC);
    
    $driveName=$result['cupbName'];
    
}
catch (PDOException $e) {
    print $e->getMessage();
   }
   
   try{
    $notes=$dbh->prepare('SELECT * FROM cupboardDriveNotes WHERE cupbID = :cupbID');
    
    $notes->bindParam(':cupbID', $driveID,PDO::PARAM_INT);
    
    $notes->execute();
    
    $noteResult = $notes->fetch(PDO::FETCH_ASSOC);
    
    
    
}
catch (PDOException $e) {
    print $e->getMessage();
   }  
$driveIDText=  htmlentities($driveID); 


require_once ('header.php');
?>

<div class="returnLink">
    <a href="/cupboard/new_drive.php"> &laquo Back to Drives</a>
</div>
<div id="subHead"><h1><?=$driveName ?></h1></div>

    <div id="clientDriveDetails">
    <form id="newDriveSess" method="post" action="view_drive.php" enctype="multipart/form-data">
   
        <input id="driveID" name="driveID" value="<?=$driveIDText?>" class="hidden" />
        <div id="driveName">
            <h3><label for="driveNameInput">Session Name</label></h3>
                <input id="driveNameInput" name="driveNameInput" type="text" size="75" required/>
                
        </div>
        <div id="legacyDate">
            <h3><label for ="bakDate">Session Date</label></h3>
            <input id="legacyDateInput" type="date" name="bakDate" required>
        </div>
        
        <div id="ownerDetails">
            <div id="clientOwnerSelect">
                <h3>Select Client</h3>
                <input id="clisearch" name="cliN" type="text" />
                <input id="clientID" name="clientID" value="0" class="hidden" />
            </div>
            
            <div id="cmpOwnerSelect">
                <h3>Select Composer</h3>
                <input id="composersearch" name="compN" type="text" />
                <input id="composerID" name="composerID" value="0" class="hidden" />
            </div>
            </div>
       
        <div id="driveSubmit"><input type="submit" name="submit" value="Add Backup" id="newDriveSubmit"/></div>
        </div>
    </form>
        <div class="backupDriveTitle"><h1>Drive Notes</h1></div>
         <form id="newDriveSess" method="post" action="view_drive.php?driveID=<?=$driveIDText;?>" enctype="multipart/form-data">
             <div id="editDriveNotes">
        <div class="editDriveNotes">
            <h3>Notes</h3>
                <textarea name="driveNotes" rows="5" cols="82"><?=$noteResult['cupbNote'];?></textarea>
                <input class="hidden" name="noteID" value="<?=$noteResult['cupbNoteID'];?>"/>
        </div>
             <div id="modifyNotes"><input type="submit" name="modifyNotes" value="Update Notes" id="modifyNotesSubmit"/></div>
             </div>
    </form>
        
        <div class="backupDriveTitle"><h1>Drive Contents</h1></div>
        
        <?php if($res->rowCount()>0 || $bak->rowCount()>0){?>
        <table id="driveList">
            <tr>
                <th>Date</th>
                <th>Backup Name</th>
                <th>Client</th>
                <th>Composer</th>
                <th>Deleted</th>
                <th> </th>
            </tr>
        <?php while($row=$bak->fetch(PDO::FETCH_ASSOC)){ ?>
                <tr>
                    <td><?=date('d-m-y', strtotime($row['sessDate']))?></td>
                    <td><?=$row['bakName']?></td>
                    <td><?=$row['cliName'];?></td>
                    <td><?=$row['cmpName'] ?></td>
                    <td><?php if($row['deleted']==1){echo '&#10004';}?></td>
                    <td><a href="/backup/edit_backup.php?sesID=<?=$row['sesID']?>"> Edit  &raquo;</a></td>
                    
                    
                    
                  </tr>
                  <?php } ?>
        <?php while($row=$res->fetch(PDO::FETCH_ASSOC)){ ?>
                <tr>
                    <td><?=date('d-m-y', strtotime($row['bakDate']))?></td>
                    <td><?=$row['legacyName']?></td>
                    <td><?=$row['cliName'];?></td>
                    <td><?=$row['cmpName'] ?></td>
                    <td><?php if($row['deleted']==1){echo '&#10004';}?></td>
                    <td><a href="edit_drive_session.php?sesID=<?=$row['legacyID']?>&driveID=<?= htmlentities($_GET['driveID']); ?>"> Edit  &raquo;</a></td>
                    
                    
                    
                  </tr>
                  <?php } ?>
            </table>
    <?php } else {?>
        <h1 style="text-align: center">This Drive is empty</h1>
        <?php } 


    require_once ('footer.php');
?>