<?php
require('includes/pdoconnection.php');

function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$microphone=new mic($dbh);

$result = $microphone->listMic();


require('header.php');
?>
    <div id="subHead">
        <h1>Angel Microphones</h1>
    </div>
    <div class="backupDriveTitle">
        <h3><?php echo $_POST['clientName'];?>All Microphones</h3>
    </div>

    <div id="micResults">
        <table id="resultTable">
            <tr>
                <th scope="col">Angel ID</th>
                <th scope="col">Make</th>
                <th scope="col">Model</th>
                <th scope="col">In Cupboard</th>
                <th scope="col">In Session</th>
                <th scope="col">Out For Repair</th>
                <th scope="col">Last Activity</th>
            </tr>
    <?php
        foreach($result as $row){ ?>
            <tr>
                <td>
                    <a href="mic_history.php?micID=<?php echo $row['micID'];?>"><?php echo $row['micID'];?></a>
                </td>
                <td><?php echo $row['micMake'];?></td>
                <td><?php echo $row['micModel'];?></td>
                <td><?php if($row['micCupboard'] == 1){echo "&gt;&lt;";} ?></td>
                <td><?php echo $row['stdID']; ?></td>
                <td><?php if($row['micRepair'] == 1){echo "&gt;&lt;";} ?></td>
                <td><?php if(!empty($row['username'])){ echo $row['username']." ".date('h:i a  - d/m/y', strtotime($row['micTime'])); } ?></td>

              </tr>
    <?php 
        }//foreach (line 36) 

    ?>
        </table>
    </div>
<div class="returnLink">
    <a href="<?php echo $_SERVER['HTTP_REFERER'];?>">&laquo; Back</a>
</div>


<?php 
    require_once('footer.php'); 
?>