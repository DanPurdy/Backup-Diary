<?php

require_once '../includes/pdoconnection.php';

$dbh = dbConn::getConnection();




function getNoRecord(){
    
    global $dbh;
    
try{
    $sth=$dbh->prepare("SELECT client.cliName, project.prjName, assistant.astName,session.sesID, session.ssNo, session.sessDate, session.stdID, backup.*
                        FROM session
                        INNER JOIN studio ON session.stdID=studio.stdID
                        INNER JOIN engineer ON session.engID=engineer.engID
                        INNER JOIN assistant ON session.astID=assistant.astID
                        INNER JOIN client ON session.cliID=client.cliID
                        INNER JOIN project ON session.prjID=project.prjID
                        INNER JOIN composer ON session.cmpID=composer.cmpID
                        INNER JOIN fixer ON session.fixID=fixer.fixID
                        LEFT JOIN backup ON session.bakID=backup.bakID
                        WHERE session.stdID = :stdID AND backup.bakLastDate <= sessDate AND sessDate < CURRENT_DATE()
                        ORDER BY session.stdID,sessDate DESC; ");
    
    $sth->bindParam(':stdID', $_GET['studio'], PDO::PARAM_INT);
    
     $sth->execute();
     
     
    
}
  catch(PDOException $e) {
    print $e->getMessage();
  }

  
  while($row=$sth->fetch(PDO::FETCH_ASSOC)){?>
    <tr>
      <td><?= date('d-m-Y',strtotime($row['sessDate']));?></td>
             <td> <?= $row['cliName'];?></td>
              <td><?= $row['prjName'];?></td>
              <td><?= $row['astName'];?></td>
              <td><?= $row['ssNo'];?></td>
    
    <td class="link"><?php if(empty($row['bakDate'])){ ?><a href="new_backup.php?sesID=<?php echo $row['sesID'];?>">Add new backup entry</a><?php }else{?><a href="edit_backup.php?sesID=<?php echo $row['sesID'];?>">View/Edit Backup</a></td><?php } ?>
    </tr>
  <?php 
    }
  }
  
  
function getDelDrive($bakLoc){
    
    global $dbh;
    
try{
    $sth=$dbh->prepare("SELECT backup.bakLoc, client.cliName, project.prjName, assistant.astName, session.sesID, session.sessDate, session.stdID, backup.bakID, backup.bakName, backup.bakDate, backup.bakKeep, backup.bakDeleted
                        FROM session
                        INNER JOIN studio ON session.stdID=studio.stdID
                        INNER JOIN engineer ON session.engID=engineer.engID
                        INNER JOIN assistant ON session.astID=assistant.astID
                        INNER JOIN client ON session.cliID=client.cliID
                        INNER JOIN project ON session.prjID=project.prjID
                        INNER JOIN composer ON session.cmpID=composer.cmpID
                        INNER JOIN fixer ON session.fixID=fixer.fixID
                        LEFT JOIN backup ON session.bakID=backup.bakID
                        WHERE session.stdID = :stdID AND sessDate < DATE_SUB(CURDATE(), INTERVAL 3 MONTH) AND (ISNULL(backup.bakDeleted) OR backup.bakDeleted =0) AND bakLoc= :bakLoc 
                        ORDER BY session.stdID,backup.bakLoc,sessDate; ");
    
    $sth->bindParam(':stdID', $_GET['studio'], PDO::PARAM_INT);
    $sth->bindParam(':bakLoc', $bakLoc, PDO::PARAM_INT);
     $sth->execute();
     
     
    
}
 catch(PDOException $e) {
    print $e->getMessage();
  }
  
  while($row=$sth->fetch(PDO::FETCH_ASSOC)){?>
    
    <tr <?php if($row['bakKeep'] == 1){ echo 'class="bakKeep"';} ?>>
      <td><?=$row['bakName'];?></td>
    <td class="link"><a href="edit_backup.php?sesID=<?php echo $row['sesID'];?>">View/Edit Backup</a></td>
    <td class="link"><a href="set_delete.php?backupID=<?php echo $row['bakID'];?>">Set Deleted</a></td>
    </tr>
    
    <?php  }
}

function getBakDrive($bakLoc){
    
    global $dbh;
    
try{
    $sth=$dbh->prepare("SELECT backup.bakLoc, client.cliName, project.prjName, assistant.astName, session.sesID, session.sessDate, session.stdID, backup.bakID, backup.bakName, backup.bakDate, backup.bakKeep, backup.bakDeleted
                        FROM session
                        INNER JOIN studio ON session.stdID=studio.stdID
                        INNER JOIN engineer ON session.engID=engineer.engID
                        INNER JOIN assistant ON session.astID=assistant.astID
                        INNER JOIN client ON session.cliID=client.cliID
                        INNER JOIN project ON session.prjID=project.prjID
                        INNER JOIN composer ON session.cmpID=composer.cmpID
                        INNER JOIN fixer ON session.fixID=fixer.fixID
                        LEFT JOIN backup ON session.bakID=backup.bakID
                        WHERE session.stdID = :stdID AND (ISNULL(backup.bakDeleted) OR backup.bakDeleted =0) AND bakLoc= :bakLoc
                        ORDER BY backup.bakLoc,sessDate DESC; ");
    
    $sth->bindParam(':stdID', $_GET['studio'], PDO::PARAM_INT);
     $sth->bindParam(':bakLoc', $bakLoc, PDO::PARAM_INT);
    
     $sth->execute();
     
     
    
}
 catch(PDOException $e) {
    print $e->getMessage();
  }
  
  while($row=$sth->fetch(PDO::FETCH_ASSOC)){?>
    <tr>
      <td><?=$row['bakName'];?></td>
    <td class="link"><a href="edit_backup.php?sesID=<?php echo $row['sesID'];?>">View/Edit Backup</a></td>
    </tr>
    <?php  }
  }
  
  ?>