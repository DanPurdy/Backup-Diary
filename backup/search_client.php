<?php

require_once 'includes/pdoconnection.php';

function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$backup= new backup($dbh);
    
$client = "%".$_POST['clientName']."%";

$result = $backup->backupSearch($client);

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
           <?php foreach($result as $row){ ?>
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