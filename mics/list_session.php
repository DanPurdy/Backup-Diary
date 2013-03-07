<?php
require('includes/pdoconnection.php');
    $dbh = dbConn::getConnection();
    
    session_start();
    
    $studio = $_GET['studio'];
    
if(empty($studio)){
try{
    $sth = $dbh->prepare("SELECT backup.*, microphones.*, session.*, client.cliName, composer.cmpName, users.username 
                            FROM backup
                            INNER JOIN session ON backup.bakID = session.bakID
                            INNER JOIN microphones ON backup.bakID=microphones.micSession
                            INNER JOIN client ON session.cliID=client.cliID
                            INNER JOIN composer ON session.cmpID =composer.cmpID
                            INNER JOIN users ON microphones.usrID = users.usrID
                            WHERE micSession IS NOT NULL
                            GROUP BY microphones.micID
                            ORDER BY microphones.micID ASC;" );
    
    $sth->execute();
    
    
    
  $count=$sth->rowCount();     
}
catch (PDOException $e) {
    print $e->getMessage();
  }
}else{
   try{
    $sth = $dbh->prepare("SELECT backup.*, microphones.*, session.*, client.cliName, composer.cmpName, users.username 
                            FROM backup
                            INNER JOIN session ON backup.bakID = session.bakID
                            INNER JOIN microphones ON backup.bakID=microphones.micSession
                            INNER JOIN client ON session.cliID=client.cliID
                            INNER JOIN composer ON session.cmpID =composer.cmpID
                            INNER JOIN users ON microphones.usrID = users.usrID
                            WHERE micSession IS NOT NULL AND stdID=:stdID
                            GROUP BY microphones.micID
                            ORDER BY microphones.micID ASC;" );
    
    $sth->bindParam(':stdID',$studio,PDO::PARAM_INT);
    
    $sth->execute();
    
    
    
  $count=$sth->rowCount();     
}
catch (PDOException $e) {
    print $e->getMessage();
  } 
}
require('header.php');
?>
   <div id="subHead"><h1>Angel Microphones</h1></div>
   <div id="micFilterLinks"><h3>Filter by Studio <br></h3>
<div class="filterMic"><h3><a href="list_session.php?studio=1">One</a></h3></div><div class="filterMic"><h3><a href="list_session.php?studio=2">Two</a></h3></div><div class="filterMic"><h3><a href="list_session.php?studio=3">Three</a></h3></div><div class="filterMic"><h3><a href="list_session.php">Clear Filter</a></h3></div></div>
<div class="backupDriveTitle"><h3>Microphones In Studio<?php if(!empty($studio)){ echo " ".$studio; }else{ echo "s";}; ?></h3></div>

        <div id="micResultsSess">
            <table id="resultTable">
                <tr>
                    <th scope="col">Angel ID</th>
                    <th scope="col">Make</th>
                    <th scope="col">Model</th>
                    <th scope="col">In Studio</th>
                    <th scope="col">Session Date</th>
                    <th scope="col">In Session</th>
                    <th scope="col">Composer</th>
                    <th scope="col">Taken By</th>
                    <th scope="col">Return Mic</th>
                </tr>
           <?php while($row=$sth->fetch(PDO::FETCH_ASSOC)){ ?>
                <tr>
                    <td><?=$row['micID'];?></td>
                    <td><?=$row['micMake'];?></td>
                    <td><?=$row['micModel'];?></td>
                    <td><?=$row['stdID'] ?></td>
                    <td><?=date('D-d-M-Y', strtotime($row['sessDate'])) ?></td>
                    <td><?=$row['cliName'] ?></td>
                    <td><?=$row['cmpName']?></td>
                    <td><?=$row['username']." &nbsp;&nbsp;".date('h:i a  - d/m/y', strtotime($row['micTime']))?></td>
                    <td><a href="edit_microphone.php?sesID=<?=$row['sesID']?>&remove=1">&raquo;</a></td>
                    
                  </tr>
                  <?php } ?>
            </table>
                    </div>



<?php require_once('footer.php'); ?>