<?php

require_once 'includes/pdoconnection.php';
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$drive = new cupboard($dbh);

$driveID=$_GET['driveID'];

if(isset($_POST['submit'])){

    $drive->updateDrive($_POST, $dbh);

    header('Location: /cupboard/new_drive.php');
}

    
$result=$drive->getDrive($driveID);

$driveName=$result['cupbName'];

$noteResult=$drive->getDriveNotes($driveID);

$driveIDText=  htmlentities($driveID); 


require_once ('header.php');
?>

<div class="returnLink">
    <a href="/cupboard/new_drive.php"> &laquo Back to Drives</a>
</div>
<div id="subHead">
    <h1><?php echo $driveName; ?></h1>
</div>
<div id="clientDriveDetails">
    <form id="newDrive" method="post" action="edit_drive.php?driveID=<?php echo $driveIDText;?>" enctype="multipart/form-data">
        <div id="driveName">
            <h3>
                <label for="driveNameInput">Drive Name</label>
            </h3>
            <input id="driveNameInput" name="driveNameInput" value="<?php echo $result['cupbName'];?>"type="text" size="75" required/>
            <input class="hidden" name="driveID" value="<?php echo $driveIDText; ?>"/>
        </div>
        
        <div id="ownerDetails">
            <div id="clientOwnerSelect">
                <h3>Select Client</h3>
                <input id="clisearch" name="cliN" type="text" value="<?php echo $result['cliName'];?>"/>
                <input id="clientID" name="clientID" value="<?php echo $result['cliID'];?>" class="hidden" />
            </div>
            
            <div id="cmpOwnerSelect">
                <h3>Select Composer</h3>
                <input id="composersearch" name="compN" type="text" value="<?php echo $result['cmpName'];?>" />
                <input id="composerID" name="composerID" value="<?php echo $result['cmpID'];?>" class="hidden" />
            </div>
        </div>
        <div class="driveNotes">
            <h3>Notes</h3>
            <textarea name="driveNotes" rows="5" cols="52"><?php echo $noteResult['cupbNote'];?></textarea>
            <input class="hidden" name="noteID" value="<?php echo $noteResult['cupbNoteID'];?>"/>
        </div>
        <div id="driveSubmit">
            <input type="submit" name="submit" value="Modify Drive" id="newDriveSubmit"/>
        </div>
        
        
    </form>
</div>

<?php
    require_once ('footer.php');
?>