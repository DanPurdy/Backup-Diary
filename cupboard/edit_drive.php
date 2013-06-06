<?php

require_once 'includes/pdoconnection.php';

$dbh = dbConn::getConnection();


$driveID=$_GET['driveID'];

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
try{
    $st1=$dbh->prepare('UPDATE driveOwnerCli SET cliID=:clientID WHERE cupbID=:driveID;');
    
    $st1->bindParam(':clientID', $cliID, PDO::PARAM_INT);
    $st1->bindParam(':driveID', $driveID, PDO::PARAM_INT);
    
    $st1->execute();
}
    catch (PDOException $e) {
        print $e->getMessage();
    }
    
    try{
        $st1=$dbh->prepare('UPDATE driveOwnerCmp SET cmpID=:composerID WHERE cupbID=:driveID;');
    
        $st1->bindParam(':composerID', $cmpID, PDO::PARAM_INT);
        $st1->bindParam(':driveID', $driveID, PDO::PARAM_INT);
    
        $st1->execute();
}
    catch (PDOException $e) {
        print $e->getMessage();
    }

try{
    $st1=$dbh->prepare('UPDATE cupboardDrive
                        SET cupbName=:name
                        WHERE cupbID=:cupbID;');
    
    $st1->bindParam(':name', $_POST['driveNameInput'],PDO::PARAM_STR);
    $st1->bindParam(':cupbID',$driveID);
    
    $st1->execute();
}
    catch (PDOException $e) {
        print $e->getMessage();
    }

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
    
    header('Location: /cupboard/new_drive.php');
}
    
try{
    $d=$dbh->prepare('SELECT cupboardDrive.*, client.*, composer.*
                      FROM cupboardDrive
                      LEFT JOIN driveOwnerCli ON (cupboardDrive.cupbID = driveOwnerCli.cupbID)
                      LEFT JOIN driveOwnerCmp ON (cupboardDrive.cupbID = driveOwnerCmp.cupbID)
                      LEFT JOIN client ON (driveOwnerCli.cliID=client.cliID)
                      LEFT JOIN composer ON (driveOwnerCmp.cmpID=composer.cmpID)
                      WHERE cupboardDrive.cupbID = :cupbID');
    
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
    <a href="http://localhost/cupboard/new_drive.php"> &laquo Back to Drives</a>
</div>
<div id="subHead"><h1><?=$driveName ?></h1></div>
<div id="clientDriveDetails">
    <form id="newDrive" method="post" action="edit_drive.php?driveID=<?=$driveIDText;?>" enctype="multipart/form-data">
        <div id="driveName">
            <h3><label for="driveNameInput">Drive Name</label></h3>
                <input id="driveNameInput" name="driveNameInput" value="<?=$result['cupbName'];?>"type="text" size="75" required/>
                <input class="hidden" name="driveID" value="<?=$driveIDText?>"/>
        </div>
        
        <div id="ownerDetails">
            <div id="clientOwnerSelect">
                <h3>Select Client</h3>
                <input id="clisearch" name="cliN" type="text" value="<?=$result['cliName'];?>"/>
                <input id="clientID" name="clientID" value="<?=$result['cliID'];?>" class="hidden" />
            </div>
            
            <div id="cmpOwnerSelect">
                <h3>Select Composer</h3>
                <input id="composersearch" name="compN" type="text" value="<?=$result['cmpName'];?>" />
                <input id="composerID" name="composerID" value="<?=$result['cmpID'];?>" class="hidden" />
            </div>
            </div>
        <div class="driveNotes">
            <h3>Notes</h3>
                <textarea name="driveNotes" rows="5" cols="52"><?=$noteResult['cupbNote'];?></textarea>
                <input class="hidden" name="noteID" value="<?=$noteResult['cupbNoteID'];?>"/>
        </div>
        <div id="driveSubmit"><input type="submit" name="submit" value="Modify Drive" id="newDriveSubmit"/></div>
        
        
        </form>
    </div>

<?php
    require_once ('footer.php');
?>