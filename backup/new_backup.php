<?php

require_once 'includes/pdoconnection.php';
require_once 'functions/function_mics.php';

$dbh = dbConn::getConnection();

try{
    $sth = $dbh->prepare("SELECT session.*, studio.stdName, engineer.engName, assistant.astName, client.cliName, composer.cmpName, fixer.fixName, project.prjName
                            FROM session
                            INNER JOIN studio ON session.stdID=studio.stdID
                            INNER JOIN engineer ON session.engID=engineer.engID
                            INNER JOIN assistant ON session.astID=assistant.astID
                            INNER JOIN client ON session.cliID=client.cliID
                            INNER JOIN project ON session.prjID=project.prjID
                            INNER JOIN composer ON session.cmpID=composer.cmpID
                            INNER JOIN fixer ON session.fixID=fixer.fixID
                            WHERE sesID = :sesID;" );
    
                            $sth->bindParam(':sesID', $_GET['sesID'] , PDO::PARAM_INT);
    
    $sth->execute();
    
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    
     $st1=$dbh->prepare('SELECT cupboardDrive.*, client.*, composer.*
                      FROM cupboardDrive
                      LEFT JOIN driveOwnerCli ON (cupboardDrive.cupbID = driveOwnerCli.cupbID)
                      LEFT JOIN driveOwnerCmp ON (cupboardDrive.cupbID = driveOwnerCmp.cupbID)
                      LEFT JOIN client ON (driveOwnerCli.cliID=client.cliID)
                      LEFT JOIN composer ON (driveOwnerCmp.cmpID=composer.cmpID)
                      WHERE (driveOwnerCli.cliID = :client AND driveOwnerCli.cliID > 1)  OR (driveOwnerCmp.cmpID = :composer AND driveOwnerCmp.cmpID >1);');
    
   $st1->bindParam(':client', $row['cliID'], PDO::PARAM_INT);
   $st1->bindParam(':composer', $row['cmpID'], PDO::PARAM_INT);
   
   $st1->execute();
    
       
}
catch (PDOException $e) {
    print $e->getMessage();
  }
 require_once('header.php');
?>

<div id="subHead"><h1>Add New Backup</h1></div>

    <?php  
        
        $stdID = $row['stdID'];
        $date = strtotime($row['sessDate']);
        $engIn = strpos($row['engName']," ",1);
        $bakID = $row['bakID'];
          ?>

    
<div class="session-backup-details">
    <div class="backupDriveTitle"><h3>Session Details</h3></div>
    <?php if($row['ssNo'] != 0){?>
        <div class="sessNum"><h3>#<?=$row['ssNo'];?></h3></div>
    <?php }else{?> 
        <form id="sheetNumber" method="post" action="session/sessNum.php" enctype="multipart/form-data">
            <input type="number" id="ssNo" name="ssNo" min="7000"/>
            <input type="text" name="sesID" value="<?= $_GET['sesID'];?>" class="hidden" />
            <input type="submit" id="ssNoSub" name="ssNoSub" value="Save"/>
        </form><?} ?>
    <div class="studio">Studio <?php echo $row['stdID'];?></div>
    <div class="sessdate"><?php echo date('d-m-Y', $date);?></div>
    <div class="sesstime"><?php echo substr($row['startTime'],0,5) . " - " . substr($row['endTime'],0,5); ?></div>
    <div class="backupDriveTitle"><h3>Engineer / Assistant</h3></div>
    <div class="engineer"><?php echo $row['engName'];?></div>
    <div class="assistant"><?php echo $row['astName']?></div>
    <div class="backupDriveTitle"><h3>Client Details</h3></div>
    <div class="client"><?php echo $row['cliName']?></div>
    <div class="composer"><?php echo $row['cmpName']?></div>
    <div class="fixer"><?php echo $row['fixName']?></div>
    <div class="project"><?php echo $row['prjName']?></div>
 
    <div id="newMicBak"><a href="#" id="showMics">Show Microphones &raquo;</a></div>
</div>

<div id="newbackup">
    <form id="form1" method="post" action="insert_backup.php" enctype="multipart/form-data">
        
        <div id="BackupName">
                Backup Name:
                <input id="backName" name="backName" type="text" size="75" required/>
                <input id="bakID" name="bakID" value="<?= $row['bakID']?>" class="hidden"/>
                <input id="sesID" name="sesID" value="<?php echo $_GET['sesID']; ?>" class="hidden"/>
                <input id="cliID" name="cliID" class="hidden" value="<?php echo $row['cliID']; ?>" />
                <input id="cmpID" name="cmpID" class="hidden" value="<?php echo $row['cmpID']; ?>" />
                <input id="editBool" name="editBool" value="0" class="hidden" />
            </div>
        
        <div id="driveLocation">
               
        <?php 
        if($stdID==1){
            ?>
        <div id="bakLocSelect">
                <h2>Backup Drive</h2>
                <input type="radio" value="1" name="bakLoc"/>Backup 1_1<br />
                <input type="radio" value="2" name="bakLoc"/>Backup 1_2<br />
                <input type="radio" value="3" name="bakLoc"/>Backup 1_3<br />
            </div>
        <?php
        }elseif($stdID==2){ ?>
            <div id="bakLocSelect">
                <h2>Backup Drive</h2>
                <input type="radio" value="4" name="bakLoc"/>Backup 2_1<br />
                <input type="radio" value="5" name="bakLoc"/>Backup 2_2<br />
                <input type="radio" value="6" name="bakLoc"/>Backup 2_3<br />
            </div>
          <?php  
        }else{?>
        
        <div id="bakLocSelect">
                <h2>Backup Drive</h2>
                <input type="radio" value="7" name="bakLoc"/>Backup 3_1<br />
                <input type="radio" value="8" name="bakLoc"/>Backup 3_2<br />
                <input type="radio" value="9" name="bakLoc"/>Backup 3_3<br />
            </div>
        <?php
        }
        ?>
        
        </div>
        <div id="backupType">
        <div id="backuptaken">
            <h3>Client taken full copy</h3>
            <input type="checkbox" name="fullcopy" />
        </div>
        
         <div id="backupcupboard">
             <h3>Copy in backup Cupboard</h3>
            <input type="checkbox" name="bakCupboard" />
        </div>
        
        <div id="backupkeep">
            <h3>Keep Longer</h3><p>Please add a reason for this option</p>
            <input type="checkbox" name="keep" />
        </div>
        </div>
        <div id="backupNotes">
            <div id="section-Notes">    
                <h3>Notes:</h3>
                <textarea name="bakNotes" rows="20" cols="30"></textarea>
            </div>
        </div>
        
        <?php 
        if($stdID==1){
            ?>
        <div id="roomMove">
                <h3>Moving to Studio:</h3>
                <input type="radio" value="0" name="bakMov" checked class="hidden"/>
                <input type="radio" value="2" name="bakMov"/>2<br />
                <input type="radio" value="3" name="bakMov"/>3<br />
            </div>
        
        <?php
        }elseif($stdID==2){ ?>
            <div id="roomMove">
                <h3>Moving to Studio:</h3>
                <input type="radio" value="1" name="bakMov"/>1<br />
                <input type="radio" value="0" name="bakMov" checked class="hidden"/>
                <input type="radio" value="3" name="bakMov"/>3<br />
            </div>
          <?php  
        }else{?>
        
        <div id="roomMove">
                <h3>Moving to Studio</h3>
                <input type="radio" value="1" name="bakMov"/>1<br />
                <input type="radio" value="2" name="bakMov"/>2<br />
                <input type="radio" value="0" name="bakMov" checked class="hidden"/>
            </div>
        <?php
        }
        ?>
        
        
        <div id="submit"><input type="submit" value="Save Backup Record"/></div><div id="cancel"><a href="/backup/">Cancel</a></div>
   
    <div id="cupboard-drive-panel">
            <h3>Tape Store Options</h3>
            <select name="cupbDrive" id="cupbDrive">
                <option value='' <?php if(!($backupDrive)){echo 'selected';}?>>Please Select A Drive</option>
                <?php 
                    while($driveList = $st1->fetch(PDO::FETCH_ASSOC)){
                        if($backupDrive['cupbID']==$driveList['cupbID']){
                            ?>
                            <option value="<?php echo $driveList['cupbID'];?> " selected><?php echo 'ATS-'.$driveList['cupbID'].' | '.$driveList['cupbName'].' | '.$driveList['cliName'].' | '.$driveList['cmpName'];?></option>
                        <?php }else{ ?>
                        ?>
                        <option value="<?php echo $driveList['cupbID'];?> "><?php echo 'ATS-'.$driveList['cupbID'].' | '.$driveList['cupbName'].' | '.$driveList['cliName'].' | '.$driveList['cmpName'];?></option>
                   <?php }
                    }
                    
                    
            
            ?>
                        <option value="new">Create New Backup Drive</option>
            </select>
            <input id="newDrive" name="newDrive"/>
        </div>
    </form>
    </div>
<div id="savedMics">
    <div class="backupDriveTitle">
         <h3>Microphones</h3>
    </div>
    <?php    
        getSessMic($bakID);
    ?>
</div>
<?php  require_once('footer.php'); ?>
