<?php

require_once 'includes/pdoconnection.php';
require_once 'models/class_cupboard.php';
require_once 'models/class_client.php';

$dbh = dbConn::getConnection();
$drive = new cupboard($dbh);

if(isset($_POST['submit'])){
    
    $driveID=$drive->addDrive($_POST, $dbh);
}
    
    $driveList = $drive->listDrives();
    $count = $drive->count;
    
    

    
    
require_once ('header.php');
?>

<div id="subHead"><h1>Create New Tape Store Drive</h1></div>
    <div id="clientDriveDetails">
    <form id="newDrive" method="post" action="new_drive.php" enctype="multipart/form-data">
        <div id="driveName">
            <h3><label for="driveNameInput">Drive Name</label></h3>
                <input id="driveNameInput" name="driveNameInput" type="text" size="75" required/>
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
        <div class="driveNotes">
            <h3>Notes</h3>
                <textarea name="driveNotes" rows="5" cols="52"></textarea>
        </div>
        <div id="driveSubmit"><input type="submit" name="submit" value="Add New Drive" id="newDriveSubmit"/></div>
        
        
        </form>
    </div>


        <div class="backupDriveTitle"><h1>Drive Cupboard List</h1></div>
        <?php if($count){?>
        <table id="driveList" class="newDrive">
            <tr>
                <th>Drive ID</th>
                <th>Drive Name</th>
                <th>Drive Client</th>
                <th>Drive Composer</th>
                <th>On Site</th>
                <th> </th>
            </tr>
        <?php foreach($driveList as $row){ ?>
                <tr>
                    <td>ATS-<?=$row['cupbID']?></td>
                    <td><?=$row['cupbName']?></td>
                    <td><?=$row['cliName'];?></td>
                    <td><?=$row['cmpName'] ?></td>
                    <td><?php if($row['cupbStored']==1){echo '&#10004';}?></td>
                    <td><a href="view_drive.php?driveID=<?=$row['cupbID']?>">View /</a><a href="edit_drive.php?driveID=<?=$row['cupbID']?>"> Edit  &raquo;</a></td>
                    
                    
                    
                  </tr>
                  <?php } ?>
        </table>
    <?php } else {?>
        <h1 style="text-align: center">There are no drives</h1>
        <?php } 
    require_once ('footer.php');
?>