<?php

session_start();    
require_once 'includes/pdoconnection.php';

$dbh = dbConn::getConnection();
    
if(!empty($_POST['studio'])){
    $session = $_POST['studio'];
}else{
    $session= 4;
};



if(empty($_POST['dateStart'])){
try{
    $sth=$dbh->prepare("SELECT session.*, client.cliName, composer.cmpName, fixer.fixName, project.prjName, assistant.astName,engineer.engName, studio.stdName
                        FROM session
                        INNER JOIN studio ON session.stdID=studio.stdID
                        INNER JOIN engineer ON session.engID=engineer.engID
                        INNER JOIN assistant ON session.astID=assistant.astID
                        INNER JOIN client ON session.cliID=client.cliID
                        INNER JOIN project ON session.prjID=project.prjID
                        INNER JOIN composer ON session.cmpID=composer.cmpID
                        INNER JOIN fixer ON session.fixID=fixer.fixID
                        WHERE session.stdID = :stdID
                        ORDER BY sessDate DESC;");
    
    $sth->bindParam(':stdID', $session, PDO::PARAM_INT);

    $sth->execute();
    
     
     
     
    
}
  catch(PDOException $e) {
    print $e->getMessage();
  }
  
  
}else{
    try{
    $sth=$dbh->prepare("SELECT session.*, client.cliName, composer.cmpName, fixer.fixName, project.prjName, assistant.astName,engineer.engName
                        FROM session
                        INNER JOIN studio ON session.stdID=studio.stdID
                        INNER JOIN engineer ON session.engID=engineer.engID
                        INNER JOIN assistant ON session.astID=assistant.astID
                        INNER JOIN client ON session.cliID=client.cliID
                        INNER JOIN project ON session.prjID=project.prjID
                        INNER JOIN composer ON session.cmpID=composer.cmpID
                        INNER JOIN fixer ON session.fixID=fixer.fixID
                        WHERE session.stdID = :stdID and (sessDate BETWEEN :dateS AND :dateE)
                        ORDER BY sessDate DESC;");
    
    $sth->bindParam(':stdID', $session, PDO::PARAM_INT);
    $sth->bindParam(':dateS', $_POST['dateStart'], PDO::PARAM_STR);
    $sth->bindParam(':dateE', $_POST['dateEnd'], PDO::PARAM_STR);

    $sth->execute();
    
     
     
     
    
}
  catch(PDOException $e) {
    print $e->getMessage();
  }
}

 require_once ('header.php');

?>

        <div id="Results">
            <div id="subHead"><h3>Search By Studio: <?=htmlentities($_POST['studio']);?></h3></div>
            
                <?php
                
     
     if($sth->rowCount() !=0){
            
     
                while($row=$sth->fetch(PDO::FETCH_ASSOC)){?>
                <div class="session">
                    <div class="resDetails"><div class="backupDriveTitle"><h3>Session Details</h3></div>
                        <div class="resLink"><div class="resEditLink"><a href="/session/edit_session.php?sesID=<?php echo $row['sesID'];?>">Edit</a></div></div>    
                <div class="resDate"><?= date('d-m-y    ', strtotime($row['sessDate']))?></div>
                <div class="resSesstime"><?php echo substr($row['startTime'],0,5) . " - " . substr($row['endTime'],0,5); ?></div>
                </div>
                    <div class="resClientDet"><div class="backupDriveTitle"><h3>Client Details</h3></div>
                <div class="resClient"><?php echo $row['cliName']?></div>
                <div class="resComposer"><?php echo $row['cmpName']?></div>
                <div class="resProject"><?php echo $row['prjName']?></div>
                    </div>
                    
                </div>
                <?php 
            
                }
 }else{
     ?>
            <div class="searchError"><h3>Sorry there are no results.</h3></div>
            <?php
 }
  
 ?>
            
        </div>
        
<div class="returnLink"><a href="<?=$_SESSION['org_referer'];?>">&laquo; Back</a></div>
<?php  require_once ('footer.php'); ?>