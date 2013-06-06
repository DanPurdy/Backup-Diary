<?php

require_once 'includes/pdoconnection.php';

$dbh = dbConn::getConnection();

if(isset($_POST['submit'])){
    
    
    $cliID =    $_POST['clientID'];
    $cmpID =    $_POST['composerID'];
    
    try{
       $sth = $dbh->prepare('INSERT INTO cupboardDrive (cupbName) VALUES (:driveName)');
       
       $sth->bindParam(':driveName', $_POST['driveNameInput'] , PDO::PARAM_STR);
       
       $sth->execute();
        
       $driveID = $dbh->lastInsertId('cupbID');
       
    }
    catch (PDOException $e) {
        print $e->getMessage();
    }
    
    try{
       $notes = $dbh->prepare('INSERT INTO cupboardDriveNotes (cupbID, cupbNote) VALUES (:cupbID,:notes)');
       
       $notes->bindParam(':cupbID',$driveID, PDO::PARAM_INT);
       $notes->bindParam(':notes', $_POST['driveNotes'] , PDO::PARAM_STR);
       
       $notes->execute();
        
       
    }
    catch (PDOException $e) {
        print $e->getMessage();
    }
    
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
    $st1=$dbh->prepare('INSERT INTO driveOwnerCli (cliID, cupbID) VALUES (:clientID, :driveID)');
    
    $st1->bindParam(':clientID', $cliID, PDO::PARAM_INT);
    $st1->bindParam(':driveID', $driveID, PDO::PARAM_INT);
    
    $st1->execute();
}
    catch (PDOException $e) {
        print $e->getMessage();
    }
    
    try{
        $st1=$dbh->prepare('INSERT INTO driveOwnerCmp (cmpID, cupbID) VALUES (:composerID, :driveID)');
    
        $st1->bindParam(':composerID', $cmpID, PDO::PARAM_INT);
        $st1->bindParam(':driveID', $driveID, PDO::PARAM_INT);
    
        $st1->execute();
}
    catch (PDOException $e) {
        print $e->getMessage();
    }
}

try{
    $res=$dbh->prepare('SELECT cupboardDrive.*, client.cliName, composer.cmpName
                        FROM cupboardDrive
                        LEFT JOIN driveOwnerCli ON (cupboardDrive.cupbID = driveOwnerCli.cupbID)
                        LEFT JOIN driveOwnerCmp ON (cupboardDrive.cupbID = driveOwnerCmp.cupbID)
                        LEFT JOIN client ON (driveOwnerCli.cliID=client.cliID)
                        LEFT JOIN composer ON (driveOwnerCmp.cmpID=composer.cmpID)
                        GROUP BY cupboardDrive.cupbID;');
    
    $res->execute();
    
    $count=$res->rowCount();
}
catch (PDOException $e) {
        print $e->getMessage();
    }
    
    
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
        <?php while($row=$res->fetch(PDO::FETCH_ASSOC)){ ?>
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