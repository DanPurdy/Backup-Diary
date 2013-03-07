<?php


session_start();
$_SESSION['org_referer'] = htmlentities($_SERVER['HTTP_REFERER']);
require_once 'includes/pdoconnection.php';

$dbh = dbConn::getConnection();


try{
    $sth = $dbh->prepare("SELECT session.sesID, session.stdID, session.sessDate, session.startTime, session.endTime,session.ssNo, studio.stdName, engineer.engName, assistant.astName, client.cliName, composer.cmpName, fixer.fixName, project.prjName, session.bakID,backup.*
                            FROM session
                            INNER JOIN studio ON session.stdID=studio.stdID
                            INNER JOIN engineer ON session.engID=engineer.engID
                            INNER JOIN assistant ON session.astID=assistant.astID
                            INNER JOIN client ON session.cliID=client.cliID
                            INNER JOIN project ON session.prjID=project.prjID
                            INNER JOIN composer ON session.cmpID=composer.cmpID
                            INNER JOIN fixer ON session.fixID=fixer.fixID
                            INNER JOIN backup ON session.bakID=backup.bakID
                            WHERE (sessDate = CURRENT_DATE OR DATE_ADD(sessDate, INTERVAL 1 DAY)=CURRENT_DATE OR DATE_ADD(sessDate, INTERVAL -1 DAY)=CURRENT_DATE) AND session.stdID=:stdID
                            ORDER BY session.sessDate, session.endTime;" );
    $sth->bindParam(':stdID', $_GET['studio']);
    
    $sth->execute();
    
    
    
       
}
catch (PDOException $e) {
    print $e->getMessage();
  }

 require_once('header.php'); 
?>

<h1>Current Backups in Studio <?php echo $_GET['studio'];?></h1>
<div id="backupSess">
    <?php while($row = $sth->fetch(PDO::FETCH_ASSOC)) { 
        
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
