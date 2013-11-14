<?php
require('includes/pdoconnection.php');

function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$backup= new backup($dbh);

$studio=$_GET['studio'];

require_once('header.php');

$result=$backup->getNoRecord();

if(!(empty($result))){ ?>
    <div class="ReqBackupRecords">
        <div class="backupDriveTitle"><h3>Please Complete These Missing Backup Records</h3></div>
        <table>
          <tr>
            <th scope="col">Session Date</th>
            <th scope="col">Client</th>
            <th scope="col">Project</th>
            <th scope="col">Assistant</th>
            <th scope="col">Sess Sheet</th>
            <th>&nbsp;</th>
          </tr>
            
<?php

    foreach($result as $row){ ?>
            <tr>
                <td><?echo date('d-m-Y',strtotime($row['sessDate']));?></td>
                <td> <?= $row['cliName'];?></td>
                <td><?= $row['prjName'];?></td>
                <td><?= $row['astName'];?></td>
                <td><?= $row['ssNo'];?></td>

                <td class="link">
                    <?php 
                        if(empty($row['bakDate'])){ ?>
                            <a href="new_backup.php?sesID=<?php echo $row['sesID'];?>">Add new backup entry</a>
                    <?php 
                        }else{?>
                            <a href="edit_backup.php?sesID=<?php echo $row['sesID'];?>">View/Edit Backup</a></td>
                
                    <?php 
                        } 
                    ?>
            </tr>
    <?php
    }
    ?>
        </table>
    </div>
    <? } ?>

    <div id="subHead">
        <h1>Studio <?=$studio?> Backup Drives</h1>
    </div>

    <div class="backupDrive">
        <div class="backupDriveTitle"><h3>Backup <?= $studio ?>_1</h3></div>
            <table>
                <tr>
                    <th scope="col">Session/Folder Name</th>
                    <th>&nbsp;</th>
                    
                </tr>
                <?php if($studio == 1){
                        $backup->getBakDrive(1);
                    }elseif($studio == 2){
                        $backup->getBakDrive(4);
                    }else{
                        $backup->getBakDrive(7);
                    }
                ?>
            </table>
        </div>

        <div class="backupDrive">
            <div class="backupDriveTitle">
                <h3>Backup <?= $studio ?>_2</h3>
            </div>
            <table>
                <tr>
                    <th scope="col">Session/Folder Name</th>
                    <th>&nbsp;</th>
                    
                </tr>
                
                <?php if($studio == 1){
                    $backup->getBakDrive(2);
                }elseif($studio == 2){
                    $backup->getBakDrive(5);
                }else{
                    $backup->getBakDrive(8);
                }
            ?>
            </table>
        </div>
    <div class="backupDrive">
        <div class="backupDriveTitle">
            <h3>Backup <?= $studio ?>_3</h3>
        </div>
        <table>
            <tr>
                <th scope="col">Session/Folder Name</th>
                <th>&nbsp;</th>
            
            </tr>
            <?php if($studio == 1){
                    $backup->getBakDrive(3);
                }elseif($studio == 2){
                    $backup->getBakDrive(6);
                }else{
                    $backup->getBakDrive(9);
                }
            ?>
        </table>
    </div>


    <?php require_once ('../footer.php');?>
