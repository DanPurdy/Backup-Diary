
<?php
require('includes/pdoconnection.php');

$dbh = dbConn::getConnection();

try{
    $sth = $dbh->prepare("SELECT micFault.*, users.username, microphones.* 
                            FROM micFault
                            INNER JOIN users ON micFault.userID = users.usrID
                            INNER JOIN microphones on micFault.micID = microphones.micID
                            WHERE micFault.micID = :micID;
                            ORDER BY micFault.faultDate DESC" );
    
    $sth->bindParam(':micID', $_GET['micID'], PDO::PARAM_INT);
    
    $sth->execute();
    
    
    
  $count=$sth->rowCount();
  $result=$sth->fetchAll();
}
catch (PDOException $e) {
    print $e->getMessage();
  }

require('header.php');

?>
<div id="subHead"><h1>Faults For Microphone #<?php echo htmlentities($_GET['micID'])." | ".$result[0]['micMake']." ".$result[0]['micModel'];?></h1></div>
<div class="backupDriveTitle"><h3>Current Faults</h3></div>
<form id="micFault" action="updateFault.php" method="post">
    <?php
    
    foreach($result as $fault){
        if(empty($fault['faultOutcome'])){?>
    
            <div class="faultDetails">
                <input type="text" class="hidden" name="micID" value="<?=htmlentities($_GET['micID']);?>" />
                <input type="text" class="hidden" name="faultID" value="<?=$fault['faultID']; ?>"/>
                <div class="faultDesc"><label for="fault">Fault Description</label>
                <textarea id="fault" name="fault" rows="3" cols="30"><?=$fault['faultDesc'];?></textarea>
                </div>
                <?php if($_SESSION['user']['username'] == 'alex' ||  $_SESSION['user']['username'] == 'dan'){?>
                <div class="faultSolu">
                <label for="solution">Fault Outcome</label>
                <textarea id="solution" name="solution" rows="3" cols="30"><?=$fault['faultOutcome'];?></textarea>
                </div>
                <? }?>
                <div class="faultUser">Submitted by: <br /><?=$fault['username']." <br /> ".date('G:i',strtotime($fault['faultDate']))." |  ".date('d-M-y',strtotime($fault['faultDate']))?></div>
                <div class="submitFault"><input type ="submit" class="submit" name="updateFault" value ="Save"/></div>
            </div>
        
    
   <?php
        }
    }?>
</form>
<div class="backupDriveTitle"><h3>Previous Faults</h3></div>
<div class="faultTable">
    
    <table>
        <tr>
            <th scope="col"><h4>Fault</h4></th>
            <th scope="col"><h4>Fault Solution</h4></th>
            <th scope="col"><h4>Reported by</h4></th>
        </tr>
    <?php
    foreach($result as $fault){
        if(!empty($fault['faultOutcome'])){?>
            <tr>
                <td><?=$fault['faultDesc'];?></td>
                
                
                <td><?=$fault['faultOutcome'];?></td>
              
                <td><?=$fault['username']." | ".date('G:i',strtotime($fault['faultDate']))." | ".date('d-M-y',strtotime($fault['faultDate']))?></td>
            
            </tr>
    
   <?php
        }
    }
    ?>
    </table>
</div>
<?php
require_once ('footer.php');

?>
