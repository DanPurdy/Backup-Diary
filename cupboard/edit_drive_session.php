<?php

require_once 'includes/pdoconnection.php';
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$drive=new driveSess($dbh);

$driveID= htmlentities($_GET['driveID']);
$sesID= htmlentities($_GET['sesID']);

if(isset($_POST['submit'])){
    

    $drive->updateLegacySess($_POST, $dbh);

    
    header('Location: /cupboard/view_drive.php?driveID='.$_POST['driveID']);
}

$result=$drive->getLegacySess($sesID);

$date = strtotime($result['bakDate']);

require_once ('header.php');
?>

<div class="returnLink">
    <a href="/cupboard/edit_drive.php?driveID=<?=$driveID?>"> &laquo Back to Drive</a>
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