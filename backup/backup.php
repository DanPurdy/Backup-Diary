<?php
session_start();
$_SESSION['org_referer'] = htmlentities($_SERVER['HTTP_REFERER']);
require('includes/pdoconnection.php');

function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$backup= new backup($dbh);


$result=$backup->getCurrentBackup($_GET['studio']);

 require_once('header.php'); 
?>

<h1>Current Backups in Studio <?php echo $_GET['studio'];?></h1>
<div id="backupSess">
    <?php foreach($result as $row) { 
        
        $date = strtotime($row['sessDate']);
        $initEng = explode(" ",$row['engName']); //split string into two seperate strings and seperate array values
        $initAst = explode(" ",$row['astName']);
          ?>

    
<div class="session">
    <!--<div class="sessdate"></div> -->
    
    <h1><?=date('l',$date);?></h1>
    <?php if(empty($row['bakDate']) || $row['bakDate'] ==0){ ?>
    <div class="newBk"><a href="new_backup.php?sesID=<?php echo $row['sesID'];?>">New Backup</a><br /></div><br /><br />
  <?php 
  }else{ 
      ?>
    <div class="newBk"><a href="edit_backup.php?sesID=<?php echo $row['sesID'];?>">View/Edit Backup</a><br /></div><br /><br />
    
        <?php }?> 
    <div class="sessDetails">
    <div class="sesstime"><?php echo substr($row['startTime'],0,5) . " - " . substr($row['endTime'],0,5); ?></div>
    <div class="engineer"><?php echo substr($initEng[0],0,1).substr($initEng[1],0,1)?><br /><?php echo substr($initAst[0],0,1).substr($initAst[1],0,1)?></div> <?php //take the first letter of engineer/assistant first and surname. ?>
    <div class="projectDetails">
    <div class="client"><?php echo $row['cliName']?></div>
    <div class="composer"><?php echo $row['cmpName']?></div>
    <div class="fixer"><?php echo $row['fixName']?></div>
    <div class="project"><?php echo $row['prjName']?></div>
    </div>
    </div>
  
  
  </div> <?php }?>
</div>

<?php require_once('footer.php'); ?>
