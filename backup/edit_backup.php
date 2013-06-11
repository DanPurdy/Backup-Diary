<?php

  session_start();
  
  $referer = $_SERVER['HTTP_REFERER'];
if($referer != '/backup/search_result.php'){
    $_SESSION['org_referer'] = htmlentities($_SERVER['HTTP_REFERER']);

}
require_once 'includes/pdoconnection.php';
require_once 'functions/function_mics.php';

$dbh = dbConn::getConnection();

try{
    $sth = $dbh->prepare("SELECT session.*, engineer.engName, assistant.astName, client.cliName, composer.cmpName, fixer.fixName, project.prjName, session.bakID,
                            backup.*
                            FROM session
                            INNER JOIN studio ON session.stdID=studio.stdID
                            INNER JOIN engineer ON session.engID=engineer.engID
                            INNER JOIN assistant ON session.astID=assistant.astID
                            INNER JOIN client ON session.cliID=client.cliID
                            INNER JOIN project ON session.prjID=project.prjID
                            INNER JOIN composer ON session.cmpID=composer.cmpID
                            INNER JOIN fixer ON session.fixID=fixer.fixID
                            INNER JOIN backup ON session.bakID=backup.bakID
                            WHERE sesID = :sesID;" );
    
                            $sth->bindParam(':sesID', $_GET['sesID'] , PDO::PARAM_INT);
    
    $sth->execute();
    
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    
    $bakID = $row['bakID'];
    
    if($row['bakCupboard'] == 1){
        $st1 = $dbh->prepare("SELECT * FROM sessions.cupboardDrive
                                LEFT JOIN driveContent ON cupboardDrive.cupbID = driveContent.cupbID
                                WHERE driveContent.bakID=:bakID;");
        $st1->bindParam(':bakID', $bakID, PDO::PARAM_INT);
        $st1->execute();
        
        $backupDrive=$st1->fetch(PDO::FETCH_ASSOC);
    }else{ 
        $backupDrive=0;
    }
    
    $sth=$dbh->prepare('SELECT cupboardDrive.*, client.*, composer.*
                      FROM cupboardDrive
                      LEFT JOIN driveOwnerCli ON (cupboardDrive.cupbID = driveOwnerCli.cupbID)
                      LEFT JOIN driveOwnerCmp ON (cupboardDrive.cupbID = driveOwnerCmp.cupbID)
                      LEFT JOIN client ON (driveOwnerCli.cliID=client.cliID)
                      LEFT JOIN composer ON (driveOwnerCmp.cmpID=composer.cmpID)
                      WHERE (driveOwnerCli.cliID = :client AND driveOwnerCli.cliID > 1)  OR (driveOwnerCmp.cmpID = :composer AND driveOwnerCmp.cmpID >1);');
    
   $sth->bindParam(':client', $row['cliID'], PDO::PARAM_INT);
   $sth->bindParam(':composer', $row['cmpID'], PDO::PARAM_INT);
   
   $sth->execute();
   
   
       
}
catch (PDOException $e) {
    print $e->getMessage();
  }
  

  
 require_once('header.php');
 
?>

<div id="subHead"><h1>Backups</h1></div>

    <?php  
        
        $stdID = $row['stdID'];
        $date = strtotime($row['sessDate']);
        $engIn = strpos($row['engName']," ",1);
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
  


<div class="backupDriveTitle"><h3>Backup created: </h3></div><div id="timeCreated"> <?php echo date('D d F Y H:i:s', strtotime($row['bakDate']));?></div><br />
<div class="backupDriveTitle"><h3>Last Updated: </h3></div><div id="timeUpdated"><?php 
if(!empty($row['bakLastDate'])){echo date('D d F Y H:i:s', strtotime($row['bakLastDate']));}else{echo date('D d F Y H:i:s', strtotime($row['bakDate']));}?></div>

<div id="newMicBak"><a href="#" id="showMics">Show Microphones &raquo;</a></div>
</div>
<div id="newbackup">
    <form id="form1" method="post" action="insert_backup.php" enctype="multipart/form-data">
        
        <div id="BackupName">
                Backup Name:
                <input id="backName" name="backName" type="text" value="<?php echo $row['bakName']; ?>" size="75" required/>
                <input id="bakID" name="bakID" class="hidden" value="<?php echo $row['bakID']; ?>"  />
                <input id="sesID" name="sesID" class="hidden" value="<?php echo $_GET['sesID']; ?>"  />
                <input id="cliID" name="cliID" class="hidden" value="<?php echo $row['cliID']; ?>" />
                <input id="cmpID" name="cmpID" class="hidden" value="<?php echo $row['cmpID']; ?>" />
                <input id="editBool" name="editBool" value="1" class="hidden" />
            </div>
        <div id="driveLocation">
        
        <?php 
        
        switch($row['bakLoc']) 
                { 
                    case "1": $bcheckone = "checked"; break; 
                    case "2": $bchecktwo = "checked"; break; 
                    case "3": $bcheckthree = "checked"; break;
                    case "4": $bcheckfour = "checked"; break;
                    case "5": $bcheckfive = "checked"; break;
                    case "6": $bchecksix = "checked"; break;
                    case "7": $bcheckseven = "checked"; break;
                    case "8": $bcheckeight = "checked"; break;
                    case "9": $bchecknine = "checked"; break;
                            
                            
                       
                } 
                
        if($stdID==1){
            ?>
        <div id="bakLocSelect">
                <h2>Backup Drive</h2>
                <input type="radio" value="1" name="bakLoc" <?=$bcheckone ?>/>Backup 1_1<br />
                <input type="radio" value="2" name="bakLoc" <?=$bchecktwo ?>/>Backup 1_2<br />
                <input type="radio" value="3" name="bakLoc" <?=$bcheckthree ?>/>Backup 1_3<br />
            </div>
        <?php
        }elseif($stdID==2){ ?>
            <div id="bakLocSelect">
                <h2>Backup Drive</h2>
                <input type="radio" value="4" name="bakLoc" <?=$bcheckfour ?>/>Backup 2_1<br />
                <input type="radio" value="5" name="bakLoc" <?=$bcheckfive ?>/>Backup 2_2<br />
                <input type="radio" value="6" name="bakLoc" <?=$bchecksix ?>/>Backup 2_3<br />
            </div>
          <?php  
        }else{?>
        
        <div id="bakLocSelect">
                <h2>Backup Drive</h2>
                <input type="radio" value="7" name="bakLoc" <?=$bcheckseven ?>/>Backup 3_1<br />
                <input type="radio" value="8" name="bakLoc" <?=$bcheckeight ?>/>Backup 3_2<br />
                <input type="radio" value="9" name="bakLoc" <?=$bchecknine ?>/>Backup 3_3<br />
            </div>
        <?php
        }
        ?>
        </div>
        
     <div id="backupType">
        <div id="backuptaken">
            <h3>Client taken full copy?</h3>
            <input type="checkbox" name="fullcopy" <?php if($row['fullCopy']==1){echo "checked";} ?> />
        </div>
        
         <div id="backupcupboard">
             <h3>Copy in backup Cupboard?</h3>
            <input type="checkbox" name="bakCupboard" <?php if($row['bakCupboard']==1){echo "checked";} ?> />
        </div>
        
        <div id="backupkeep">
            <h3>Keep Longer</h3><p>Please add a reason to the text box</p>
            <input type="checkbox" name="keep" <?php if($row['bakKeep']==1){echo "checked";} ?> />
        </div>
     </div>
        <div id="backupNotes">
            <div id="section-Notes"> 
            <h3>Notes:</h3>
            <textarea name="bakNotes" rows="20" cols="30"><?php echo $row['bakNotes']; ?></textarea>
            </div>
        </div>
        
        <?php 
        
        
        switch($row['bakMov']) 
                { 
                    case "" : $mchecknone = "checked"; break; 
                    case "1": $mcheckone = "checked"; break; 
                    case "2": $mchecktwo = "checked"; break; 
                    case "3": $mcheckthree = "checked"; break;
                           
                } 
                
        
        if($stdID==1){
            ?>
        <div id="roomMove">
                <h3>Moving to Studio</h3>
                <input type="radio" value="0" name="bakMov" <?=$mchecknone?>/>N/A<br />
                <input type="radio" value="2" name="bakMov" <?=$mchecktwo?>/>2<br />
                <input type="radio" value="3" name="bakMov" <?=$mcheckthree?>/>3<br />
            </div>
        <?php
        }elseif($stdID==2){ ?>
            <div id="roomMove">
                <h3>Moving to Studio:</h3>
                <input type="radio" value="0" name="bakMov" <?=$mchecknone?>/>N/A<br />
                <input type="radio" value="1" name="bakMov" <?=$mcheckone?>/>1<br />
                <input type="radio" value="3" name="bakMov" <?=$mcheckthree?>/>3<br />
            </div>
          <?php  
        }else{?>
        
        <div id="roomMove">
                <h3>Moving to Studio:</h3>
                <input type="radio" value="0" name="bakMov" <?=$mchecknone?>/>N/A<br />
                <input type="radio" value="1" name="bakMov" <?=$mcheckone?>/>1<br />
                <input type="radio" value="2" name="bakMov" <?=$mchecktwo?>/>2<br />
                
            </div>
        <?php
        }
        
        ?>
        
        <div id="deleted">
             <h3>Deleted?</h3>
            <input type="checkbox" name="deleted" <?php if($row['bakDeleted']==1){echo "checked";} ?> />
        </div>
      
        
        <div id="submit"><input type="submit" value="Save Backup Record"/></div><div id="cancel"><a href="/backup/">Cancel</a></div>
        
                
        <div id="cupboard-drive-panel">
            <h3>Tape Store Options</h3>
            <select name="cupbDrive" id="cupbDrive">
                <option value='' <?php if(!($backupDrive)){echo 'selected';}?>>Please Select A Drive</option>
                <?php 
                    while($driveList = $sth->fetch(PDO::FETCH_ASSOC)){
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
