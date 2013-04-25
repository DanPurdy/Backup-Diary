
<?php
require('includes/pdoconnection.php');

$dbh = dbConn::getConnection();

try{
    $sth = $dbh->prepare("SELECT chanFault.*, users.username, channels.*
                            FROM chanFault
                            INNER JOIN users ON chanFault.userID = users.usrID
                            INNER JOIN channels on chanFault.channelID = channels.channelID
                            WHERE chanFault.channelID = :chID;
                            ORDER BY chanFault.faultDate DESC" );
    
    $sth->bindParam(':chID', $_GET['chID'], PDO::PARAM_INT);
    
    $sth->execute();
    
    
    
  $count=$sth->rowCount();
  $result=$sth->fetchAll();
}
catch (PDOException $e) {
    print $e->getMessage();
  }

require('header.php');

?>
<div id="subHead"><h1>Faults For Channel ARS<?php echo $result[0]['channelID'];?></h1></div>
<div class="backupDriveTitle"><h3>Current Faults</h3></div>

    <?php
    $i=0;
    foreach($result as $fault){
        if(empty($fault['faultOutcome'])){?>
<form id="micFault-<?= $i ?>" action="updateChannelFault.php" method="post">
            <div class="faultDetails">
                <input type="text" name="chID" value="<?=htmlentities($_GET['chID']);?>" class="hidden"/>
                <input type="text" name="faultID" value="<?=$fault['faultID']; ?>" class="hidden"/>
                <div class="faultDesc"><label for="fault">Fault Description</label>
                <textarea id="fault" name="fault" rows="3" cols="30"><?=$fault['faultDesc'];?></textarea>
                </div>
                <?php if($_SESSION['user']['username'] == 'alex' ||  $_SESSION['user']['username'] == 'dan'){?>
                <div class="faultSolu">
                <label for="solution">Fault Outcome</label>
                <textarea id="solution" name="solution" rows="3" cols="30"><?=$fault['faultOutcome'];?></textarea>
                </div>
                <? } ?>
                <div class="faultUser">Submitted by: <br /><?=$fault['username']." <br /> ".date('G:i',strtotime($fault['faultDate']))." |  ".date('d-M-y',strtotime($fault['faultDate']))?></div>
                <div class="submitFault"><input type ="submit" class="submit" name="updateFault" value ="Save"/></div>
            </div>
        </form>
    
   <?php
   $i++;
        }
    }?>

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
<div class="returnLink"><a href="<?=$_SERVER['HTTP_REFERER'];?>">&laquo; Back</a></div>
<?php
require_once ('footer.php');

?>
