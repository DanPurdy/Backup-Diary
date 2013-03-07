<?php

require_once '../includes/pdoconnection.php';

$dbh = dbConn::getConnection();

function getMicDet($micID){

    global $dbh;
    
    try{
        $sth = $dbh->prepare('SELECT * FROM microphones
                              WHERE micID = :micID;');
        
        $sth->bindParam(':micID', $micID, PDO::PARAM_INT);
        
        $sth->execute();
        
        $row=$sth->fetch(PDO::FETCH_ASSOC);
        
}catch (PDOException $e){
    print $e->getMessage();
}
    ?>
<td><?= $row['micID']?></td>
<td><?= $row['micMake']?></td>
<td><?= $row['micModel']?></td>

    <?php
    
}



function getSessMic($bakID){
    
    global $dbh;
    
    
    try{
        $st1 = $dbh->prepare("SELECT * FROM sessmics
                          WHERE sessmicsID = :bakID;");
    
    $st1->bindParam(':bakID', $bakID, PDO::PARAM_INT);
    
    $st1->execute();
    
    
    $count1 = $st1->rowCount();
    
    $row1 = $st1->fetch(PDO::FETCH_ASSOC);
    if($count1 > 0){
        
        $micArray = array();
        
        $micArray = unserialize($row1['sessmicList']);
    
    }
    
    }catch(PDOException $e){
        print $e->getMessage();
        
}
if($count1 > 0){ ?>
    
    <table id="micList">
        <tr>
            <th scope="col">Mic #</th>
            <th scope="col">Make</th>
            <th scope="col">Model</th>
            
  </tr>
<?php
    foreach($micArray as $micID){?>
  <tr>
    <?php    
     getMicDet($micID);
     ?>
  </tr>
  <?php
    }
 ?>
    </table>

<?php

}
}
?>
