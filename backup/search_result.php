<?php

require_once 'includes/pdoconnection.php';

$dbh = dbConn::getConnection();


    
    $dateOne = date('Y-m-d',  strtotime($_POST['dateStart']));
    $dateTwo = date('Y-m-d',  strtotime($_POST['dateEnd']));
    
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
                        WHERE session.stdID = :stdID AND sessDate BETWEEN :date1 AND :date2
                        ORDER BY session.sessDate DESC, backup.bakDeleted; ");
    
    $sth->bindParam(':stdID', $_POST['studio'], PDO::PARAM_INT);
    $sth->bindParam(':date1', $dateOne, PDO::PARAM_INT);
    $sth->bindParam(':date2', $dateTwo, PDO::PARAM_INT);
  
    
     $sth->execute();
     
     
    
}
  catch(PDOException $e) {
    print $e->getMessage();
    
   
  }
  
   require_once('header.php');
   
   ?>

<div id="subHead"><h1>Backup Search Results</h1></div>
<div class="backupDriveTitle"><h3>Studio <?php echo $_POST['studio'];?> between <?php if(empty($_POST['dateStart'])){ echo "The beginning of time";} else{ echo $_POST['dateStart'];}?> and <?php echo $_POST['dateEnd']; ?></h3></div>

        <div id="Results">
            <table id="resultTable">
                <tr>
                    <th scope="col">Session Date</th>
                    <th scope="col">SS #</th>
                    <th scope="col">Session Folder Name</th>
                    <th scope="col">Client</th>
                    <th scope="col">Backup</th>
                    <th scope="col">Deleted</th>
                    <th scope="col"></th>
                </tr>
           <?php while($row=$sth->fetch(PDO::FETCH_ASSOC)){ ?>
                <tr>
                    <td><?=$row['sessDate'];?></td>
                    <td><?=$row['ssNo'];?></td>
                    <td><?=$row['bakName'];?></td>
                    <td><?=$row['cliName'];?></td>
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
                    <?php if(empty($row['bakID']) || $row['bakID']==0){?>
                        <td><a href="new_backup.php?sesID=<?php echo $row['sesID'];?>">Add New Backup</a></td>
                   <?php }else{?>
                    <td><a href="edit_backup.php?sesID=<?php echo $row['sesID'];?>">View/Edit Backup</a></td>
                    <?php } ?>
                    
               
               </tr>
        
        
      
      
 <?php  
 
 }
  
 ?>
            </table>
            <div class="returnLink"><h3><a href=""searchstudio.php">&laquo;Go Back</a></h3></div>
        </div>


        <?php  require_once('footer.php'); ?>

