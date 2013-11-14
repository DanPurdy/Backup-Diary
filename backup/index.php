<?php 
require('includes/pdoconnection.php');

function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$backup= new backup($dbh);

$studio=$_GET['studio'];


require_once('header.php');

?>            
            

            <?php
                
                $result=$backup->getNoRecord();

            if(!(empty($result))){ ?>
                <div class="ReqBackupRecords">
                    <div class="backupDriveTitle">
                        <h3>Please Complete These Missing Backup Records</h3>
                    </div>
                    <table>
                        <tr>
                            <th scope="col">Session Date</th>
                            <th scope="col">Client</th>
                            <th scope="col">Project</th>
                            <th scope="col">Assistant</th>
                            <th scope="col">Studio</th>
                            <th>&nbsp;</th>
                        </tr>
                        
            <?php

                    foreach($result as $row){ ?>
                        <tr>
                            <td><?echo date('d-m-Y',strtotime($row['sessDate']));?></td>
                            <td> <?= $row['cliName'];?></td>
                            <td><?= $row['prjName'];?></td>
                            <td><?= $row['astName'];?></td>
                            <td><?= $row['stdID'];?></td>

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
                <h1>Create / Edit Backups</h1>
            </div>
            <div class="backupTitle">
                
                <h3>Select Studio </h3>
            
            </div>
            <div class="stuLinkWrap">
                <div id="stuMainLinkInnerWrap" class="MOne">
                <div class="stuMainLinkPic" >
                    <a href="backup.php?studio=1"><img src="../img/pics/studio1_200.jpg" /></a>
                </div>
                <div class="studioMainLink">
                     <h3><a href="backup.php?studio=1">Studio One</a></h3>
                    
                </div>
                </div>
                <div id="stuMainLinkInnerWrap" class="MTwo">
                <div class="stuMainLinkPic" >
                    <a href="backup.php?studio=2"><img src="../img/pics/studio2_200.jpg" /></a>
                </div>
                <div class="studioMainLink">
                    
                    <h3><a href="backup.php?studio=2">Studio Two</a></h3>
                </div>
                
                </div>
                <div id="stuMainLinkInnerWrap" class="MThree">
                <div class="stuMainLinkPic" >
                    <a href="backup.php?studio=3"><img src="../img/pics/studio3_200.jpg" /></a>
                </div>
                <div class="studioMainLink">
                     <h3><a href="backup.php?studio=3">Studio Three</a></h3>
                </div>
                </div>
           </div>
         
            <div id="subHead">
                <h2>Search Backups</h2>
            </div>
                <div class="backupTitle">    
                    <h3>Backup Drives </h3>
            </div>
            <div class="stuLinkWrap">
                <div id="stuLinkInnerWrap" class="One">
                    <div class="stuLinkPic" >
                        <a href="backupdrive.php?studio=1"><img src="../img/icons/backupdrivecopy.png"/></a>
                    </div>
                    <div class="studioLink">
                         <h3><a href="backupdrive.php?studio=1">Studio One</a></h3>
                    </div>
                </div>
                <div id="stuLinkInnerWrap" class="Two">
                
                    
                <div class="stuLinkPic" >
                    <a href="backupdrive.php?studio=2"><img src="../img/icons/backupdrivecopy.png"/></a>
                </div>
                    <div class="studioLink">
                         <h3><a href="backupdrive.php?studio=2">Studio Two</a> </h3>
                    </div>
                </div>
                <div id="stuLinkInnerWrap" class="Three">
                    <div class="stuLinkPic" >
                        <a href="backupdrive.php?studio=3"><img src="../img/icons/backupdrivecopy.png"/></a>
                    </div>
                <div class="studioLink">
                    <h3><a href="backupdrive.php?studio=3">Studio Three</a></h3>
                    </div>
                </div>
                
            </div>
            
        
            
        
            <div class="backupSearchLink">
                <div class="backupTitle"> 
                <h3>Search &amp; Delete </h3>
            </div>
                <div id="backupSearchDate"><h3><a href="searchstudio.php">Search by Studio & Date </a></h3></div>
                <?php if($_SESSION['user']['usrGroup'] == 'admin'){ ?> <div id="backupSearchDel"><h3><a href="selectstudio.php">Due For Deletion </a></h3></div><?php }; ?>
            </div>

            <?php require_once('footer.php') ?>