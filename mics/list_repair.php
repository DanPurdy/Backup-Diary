<?php
require('includes/pdoconnection.php');
    $dbh = dbConn::getConnection();

try{
    $sth = $dbh->prepare("SELECT microphones.*, users.username 
                            FROM microphones
                            INNER JOIN users ON microphones.usrID = users.usrID
                            WHERE micRepair > 0
                            GROUP BY microphones.micID
                            ORDER BY microphones.micID ASC;" );
    
    $sth->execute();
    
    
    
  $count=$sth->rowCount();     
}
catch (PDOException $e) {
    print $e->getMessage();
  }

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
                    <?php if($_SESSION['user']['username'] == 'alex' || $_SESSION['user']['username'] == 'dan'){ ?><th scope="col">Mic Faults</th>
                    <th scope="col">Return to Cupboard</th><?php } ?>
                </tr>
           <?php while($row=$sth->fetch(PDO::FETCH_ASSOC)){ ?>
                <tr>
                    <td><?=$row['micID'];?></td>
                    <td><?=$row['micMake'];?></td>
                    <td><?=$row['micModel'];?></td>
                    <td><?=$row['username']." &nbsp;&nbsp;".date('h:i a  - d/m/y', strtotime($row['micTime']))?></td>
                    <?php if($_SESSION['user']['username'] == 'alex' || $_SESSION['user']['username'] == 'dan'){ ?><td><a href="list_micfaults.php?micID=<?=$row['micID'];?>">View Faults</a>
                    <td><a href="fixed_Mic.php?micID=<?=$row['micID'];?>">Return</a><?php } ?>
                   
                    
                  </tr>
                  <?php } ?>
            </table>
                    </div>



<?php require_once('footer.php'); ?>