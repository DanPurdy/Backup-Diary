<?php
require('includes/pdoconnection.php');
    $dbh = dbConn::getConnection();
    
    session_start();

try{
    $sth = $dbh->prepare("SELECT microphones.*, users.username FROM microphones
                          LEFT JOIN users ON microphones.usrID=users.usrID
                          ORDER BY 'micID';" );
    
    $sth->execute();
    
    
    
       
}
catch (PDOException $e) {
    print $e->getMessage();
  }
require('header.php');
?>
   <div id="subHead"><h1>Angel Microphones</h1></div>
<div class="backupDriveTitle"><h3><?=$_POST['clientName'];?>All Microphones</h3></div>

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
           <?php while($row=$sth->fetch(PDO::FETCH_ASSOC)){ ?>
                <tr>
                    <td><?=$row['micID'];?></td>
                    <td><?=$row['micMake'];?></td>
                    <td><?=$row['micModel'];?></td>
                    <td><?php if($row['micCupboard'] == 1){echo "&gt;&lt;";} ?></td>
                    <td><?php if($row['micSession'] !=0){echo "<a href='list_session.php'>&gt;&lt;</a>";} ?></td>
                    <td><?php if($row['micRepair'] == 1){echo "&gt;&lt;";} ?></td>
                    <td><?php if(!empty($row['username'])){ echo $row['username']." ".date('h:i a  - d/m/y', strtotime($row['micTime'])); } ?></td>
                    
                  </tr>
                  <?php } ?>
            </table>
                    </div>
<div class="returnLink"><a href="<?=$_SERVER['HTTP_REFERER'];?>">&laquo; Back</a></div>


<?php require_once('footer.php'); ?>