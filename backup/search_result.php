<?php

require_once 'includes/pdoconnection.php';

function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$session=new session($dbh);

$dateOne = date('Y-m-d',  strtotime($_POST['dateStart']));
$dateTwo = date('Y-m-d',  strtotime($_POST['dateEnd']));

$result = $session->sessSearchDate('session', 'stdID', $_POST['studio'], $dateOne, $dateTwo);

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
           <?php foreach($result as $row){ ?>
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

