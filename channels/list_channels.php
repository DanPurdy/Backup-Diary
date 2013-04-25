<?php
require('includes/pdoconnection.php');
    $dbh = dbConn::getConnection();

try{
    $sth = $dbh->prepare("SELECT channels.*
                            FROM channels
                            WHERE stdID = :stdID AND ((channels.channelID)%100) !=0
                            ORDER BY channels.currentPos ASC;" );
    
    $sth->bindParam(':stdID', $_GET['studio']);
    
    $sth->execute();
    
    $st1 = $dbh->prepare("SELECT channels.*, chanFault.*
                            FROM channels
                            INNER JOIN chanFault ON channels.channelID=chanFault.channelID
                            WHERE stdID = :stdID AND ((channels.channelID)%100) !=0 AND ISNULL(chanFault.faultOutcome)
                            ORDER BY channels.currentPos ASC;" );
    
    $st1->bindParam(':stdID', $_GET['studio']);
    
    $st1->execute();
        
}
catch (PDOException $e) {
    print $e->getMessage();
  }

require('header.php');
?>
<div id="subHead"><h1>Channel Overview for Studio <?=htmlentities($_GET['studio']);?></h1></div>
<?php if($_SESSION['user']['usrGroup'] == 'admin'){ ?>
        <div id="channelPositions">
            <div class="backupDriveTitle"><h3>Current Positions</h3></div>
            <table id="resultTable">
                <tr>
                   
                    <th scope="col">Current Position</th>
                    <th scope="col">Channel ID</th>
                </tr>
           <?php while($row=$sth->fetch(PDO::FETCH_ASSOC)){ ?>
                
                
                <tr>
                    
                    <td><?=$row['currentPos'];?></td>
                    <td><?=$row['channelID'];?></td>
                    
                </tr>
                  <?php } ?>
            </table>
                    </div>
   <? } ?>
<div id ="faultForm">
    <div class="backupDriveTitle"><h3>Submit a new Fault</h3></div>
    <form id="channelFault" action="addChanFault.php" method="post">
        <div class="channelSelect"><h3>Channel</h3>
            <input type="text" name="stdID" value="<?= htmlentities($_GET['studio']);?>" class="hidden"/>
        <select name="channel">
            <?php for($i=1; $i<=60; $i++){
                ?>
            <option value="<?=$i ?>"><?=$i ?></option>
            <?php 
                } 
            ?>
        </select>
        </div>
        <div id="faultText">
            <textarea class="mic" id="faultDesc" name="faultDesc" rows="4" cols="35">Enter Fault Details...</textarea>
        </div>
        <div class="submitChanFault">
            <input type="submit" name="fault_button" class="faultButton" value="Submit Fault"/>
        </div> 
   </form>
</div>

<?php if($_SESSION['user']['usrGroup'] == 'admin'){ ?>
<div id ="swapChannel">
    <div class="backupDriveTitle"><h3>Swap Channels</h3></div>
    <form id ="moveChannels" action="addChanFault.php" method="post">
        <input type="text" name="stdID" value="<?= htmlentities($_GET['studio']);?>" class="hidden"/>
        <div class="selectPush"></div>
        <div class="channelSelect"><h3>Channel One</h3>
        <select name="channelOne">
            <?php for($i=1; $i<=60; $i++){
                ?>
            <option value="<?=$i ?>"><?=$i ?></option>
            <?php 
                } 
            ?>
        </select>
        </div>
        <div class="channelSelect "><h3>Channel Two</h3>
        <select name="channelTwo">
            <?php for($i=1; $i<=60; $i++){
                ?>
            <option value="<?=$i ?>"><?=$i ?></option>
            <?php 
                } 
            ?>
        </select>
        </div>
        <div class="submitChanFault">
            <input type="submit" name="swap_button" class="swapButton" value="Swap Channels"/>
        </div> 
    </form>
    
</div> 
<? } ?>
   <div id="chanFaults">
       <div class="backupDriveTitle"><h3>Current Faults</h3></div>
       
           <table id="resultTable">
                <tr>
                   
                    <th scope="col">Channel</th>
                    <th scope="col">Fault</th>
                    <?php if($_SESSION['user']['usrGroup'] == 'admin'){?><th scope="col">View Faults</th> <? } ?>
                </tr>
           <?php while($row=$st1->fetch(PDO::FETCH_ASSOC)){ ?>
                
                
                <tr>
                    
                    <td><?=$row['currentPos'];?></td>
                    <td><?=$row['faultDesc'];?></td>
                    <?php if($_SESSION['user']['usrGroup'] == 'admin'){ ?><td><a href="list_channel_faults.php?chID=<?=$row['channelID']?>">View</a></td> <? } ?>
                    
                </tr>
                  <?php } ?>
            </table>
                   
       
   </div>



<?php require_once('footer.php'); ?>