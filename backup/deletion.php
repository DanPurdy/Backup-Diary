<?php

session_start();
$_SESSION['org_referer'] = htmlentities($_SERVER['HTTP_REFERER']);
require_once 'functions/function_list.php';

$studio=$_GET['studio'];

require_once('../header.php');
?>

<div id="subHead"><h1>Backups Older than 3 Months</h1></div>
<div class="backupDrive">
<div class="backupDriveTitle"><h3>Backup <?=$studio ?>_1</h3></div>
<table>
  <tr>
    <th scope="col">Session/Folder Name</th>
    <th>&nbsp;</th>
    
  </tr>
    <?php if($studio == 1){
        getDelDrive(1);
    }elseif($studio == 2){
        getDelDrive(4);
    }else{
        getDelDrive(7);
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
        getDelDrive(2);
    }elseif($studio == 2){
        getDelDrive(5);
    }else{
        getDelDrive(8);
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
        getDelDrive(3);
    }elseif($studio == 2){
        getDelDrive(6);
    }else{
        getDelDrive(9);
    }
?>
    </table>
</div>
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
<?php  require_once('../footer.php'); ?>