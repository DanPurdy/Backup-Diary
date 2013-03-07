<?php


require_once 'functions/function_list.php';

$studio=$_GET['studio'];

require_once('header.php');
?>
<div class="ReqBackupRecords">
<div class="backupDriveTitle"><h3>Backup Records Missing For Studio <?=$studio;?> - Please Complete</h3></div>
<table>
  <tr>
    <th scope="col">Session Date</th>
    <th scope="col">Client</th>
    <th scope="col">Project</th>
    <th scope="col">Assistant</th>
    <th scope="col">Sess Sheet</th>
    <th>&nbsp;</th>
  </tr>
    <?php getNoRecord(); ?>
</table>
</div>

<div id="subHead"><h1>Studio <?=$studio?> Backup Drives</h1></div>

<div class="backupDrive">
<div class="backupDriveTitle"><h3>Backup <?= $studio ?>_1</h3></div>
<table>
  <tr>
    <th scope="col">Session/Folder Name</th>
    <th>&nbsp;</th>
    
  </tr>
    <?php if($studio == 1){
        getBakDrive(1);
    }elseif($studio == 2){
        getBakDrive(4);
    }else{
        getBakDrive(7);
    }
?>
</table>
</div>

<div class="backupDrive">
    <div class="backupDriveTitle"><h3>Backup <?= $studio ?>_2</h3></div>
<table>
  <tr>
    <th scope="col">Session/Folder Name</th>
    <th>&nbsp;</th>
    
  </tr>
    <?php if($studio == 1){
        getBakDrive(2);
    }elseif($studio == 2){
        getBakDrive(5);
    }else{
        getBakDrive(8);
    }
?>
</table>
</div>
<div class="backupDrive">
<div class="backupDriveTitle"><h3>Backup <?= $studio ?>_3</h3></div>
<table>
  <tr>
    <th scope="col">Session/Folder Name</th>
    <th>&nbsp;</th>
    
  </tr>
    <?php if($studio == 1){
        getBakDrive(3);
    }elseif($studio == 2){
        getBakDrive(6);
    }else{
        getBakDrive(9);
    }
?>
</table>
</div>


<?php require_once ('../footer.php');?>
