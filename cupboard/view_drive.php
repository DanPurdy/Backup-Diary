<?php

require_once 'includes/pdoconnection.php';
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();
$drive=new cupboard($dbh);
$session =new driveSess($dbh);



$driveID=$_GET['driveID'];

if(isset($_POST['modifyNotes'])){
    if(empty($_POST['noteID'])){
        $drive->addDriveNotes($driveID, $_POST['driveNotes']);
}else{
        $drive->updateDriveNotes($driveID, $_POST['driveNotes']);
    }
}
if(isset($_POST['submit'])){
    
    $session->addLegacySess($_POST, $dbh);
    
   
    header('Location: /cupboard/view_drive.php?driveID='.$_POST['driveID']);
}


$bak=$session->listBackupSess($driveID);

$res=$session->listLegacySess($driveID);
    
$result=$drive->getDrive($driveID);

$driveName=$result['cupbName'];

$noteResult=$drive->getDriveNotes($driveID);

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

        <?php if(count($res)>0 || count($bak)>0){?>

        <table id="driveList">
            <tr>
                <th>Date</th>
                <th>Backup Name</th>
                <th>Client</th>
                <th>Composer</th>
                <th>Deleted</th>
                <th> </th>
            </tr>

        <?php foreach($bak as $row){ ?>
                <tr>
                    <td><?=date('d-m-y', strtotime($row['sessDate']))?></td>
                    <td><?=$row['bakName']?></td>
                    <td><?=$row['cliName'];?></td>
                    <td><?=$row['cmpName'] ?></td>
                    <td><?php if($row['deleted']==1){echo '&#10004';}?></td>
                    <td><a href="/backup/edit_backup.php?sesID=<?=$row['sesID']?>"> Edit  &raquo;</a></td>
                    
                    
                    
                  </tr>
                  <?php } ?>

        <?php foreach($res as $row){ ?>
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