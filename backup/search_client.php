<?php

require_once 'includes/pdoconnection.php';

$dbh = dbConn::getConnection();
    
$client = "%".$_POST['clientName']."%";

try{
    $sth=$dbh->prepare("SELECT client.cliName, project.prjName, assistant.astName,engineer.engName, session.sesID, session.ssNo, session.sessDate, session.stdID,bakdrive.bkdName, backup.*
                        FROM session
                        INNER JOIN studio ON session.stdID=studio.stdID
                        INNER JOIN engineer ON session.engID=engineer.engID
                        INNER JOIN assistant ON session.astID=assistant.astID
                        INNER JOIN client ON session.cliID=client.cliID
                        INNER JOIN project ON session.prjID=project.prjID
                        INNER JOIN composer ON session.cmpID=composer.cmpID
                        INNER JOIN fixer ON session.fixID=fixer.fixID
                        LEFT JOIN backup ON session.bakID=backup.bakID
                        LEFT JOIN bakdrive ON backup.bakLoc=bakdrive.bkdID
                        WHERE client.cliName LIKE :cliName OR composer.cmpName LIKE :cliName
                        ORDER BY session.sessDate DESC, backup.bakDeleted; ");
    
    $sth->bindParam(':cliName', $client, PDO::PARAM_STR);

  
    
     $sth->execute();
     
     
    
}
  catch(PDOException $e) {
    print $e->getMessage();
  }
  
  
  require_once ('header.php');
  
  ?>
<div id="subHead"><h1>Backup Search Results</h1></div>
<div class="backupDriveTitle"><h3><?=$_POST['clientName'];?> Backup Records</h3></div>

        <div id="Results">
            <table id="resultTable">
                <tr>
                    <th scope="col">Session Date</th>
                    <th scope="col">SS #</th>
                    <th scope="col">Session Folder</th>
                    <th scope="col">Composer</th>
                    <th scope="col">Bak Drive   </th>
                    <th scope="col">Is Deleted</th>
                    <th scope="col">Continued</th>
                    <th scope="col"></th>
                </tr>
           <?php while($row=$sth->fetch(PDO::FETCH_ASSOC)){ ?>
                <tr>
                    <td><?=$row['sessDate'];?></td>
                    <td><?=$row['ssNo'];?></td>
                    <td><?=$row['bakName'];?></td>
                    <td><?=$row['cmpName'];?></td>
                    <td><?=$row['bkdName'];?></td>
                    <?php
                        if($row['bakDeleted'] ==1){
                            ?>
                            <td>Yes</td>
                        <?php
                        }else{ ?>
                            <td>No</td>
                        <?php
                        } ?>
                    <td><?=$row['bakMov'];?></td>
                    <?php if(empty($row['bakDate']) || $row['bakDate']==0){?>
                        <td><a href="new_backup.php?sesID=<?php echo $row['sesID'];?>">Add New Backup</a></td>
                   <?php }else{?>
                    <td><a href="edit_backup.php?sesID=<?php echo $row['sesID'];?>">View/Edit Backup</a></td>
                    <?php } ?>
                    
               
               </tr>
        
        
      
      
 <?php  
 
 }
  
 ?>
            </table>
            <div class="returnLink"><h3><a href="searchstudio.php">&laquo;Go Back</a></h3></div>
        </div>


        <?php  require_once('footer.php'); ?>