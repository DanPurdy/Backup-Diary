<?php
require('includes/pdoconnection.php');

function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$microphone=new mic($dbh);

$result = $microphone->listMicRepair();

require('header.php');
?>
   <div id="subHead"><h1>Microphones In Workshop</h1></div>
<?php if($_GET['e']==1 && !empty($_GET['micID'])){ ?>

<div id="micWarning"><h3>Sorry you cannot return microphone #<?= htmlentities($_GET['micID']);?> While there is an active fault</h3></div>
    
    <?php }
    
    ?>

        <div id="micResultsSess">
            <table id="resultTable">
                <tr>
                    <th scope="col">Angel ID</th>
                    <th scope="col">Make</th>
                    <th scope="col">Model</th>
                    <th scope="col">Submitted By</th>
                    <?php if($_SESSION['user']['usrGroup'] == 'admin'){ ?><th scope="col">Mic Faults</th>
                    <th scope="col">Return to Cupboard</th><?php } ?>
                </tr>
           <?php foreach($result as $row){ ?>
                <tr>
                    <td><?=$row['micID'];?></td>
                    <td><?=$row['micMake'];?></td>
                    <td><?=$row['micModel'];?></td>
                    <td><?=$row['username']." &nbsp;&nbsp;".date('h:i a  - d/m/y', strtotime($row['micTime']))?></td>
                    <?php if($_SESSION['user']['usrGroup'] == 'admin'){ ?><td><a href="list_micfaults.php?micID=<?=$row['micID'];?>">View Faults</a>
                    <td><a href="fixed_Mic.php?micID=<?=$row['micID'];?>">Return</a><?php } ?>
                   
                    
                  </tr>
                  <?php } ?>
            </table>
                    </div>



<?php require_once('footer.php'); ?>