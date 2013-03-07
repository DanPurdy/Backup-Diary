<?php
session_start();

require('includes/pdoconnection.php');
$dbh = dbConn::getConnection();

try{
    $ast = $dbh->prepare("SELECT assistant.* FROM assistant ORDER BY astID" );
    $eng = $dbh->prepare("SELECT engineer.* FROM engineer ORDER BY engID" );
    
    $ast->execute();
    $eng->execute();
    
    
    
       
}
catch (PDOException $e) {
    print $e->getMessage();
  }
require_once ('header.php');
?>

<div id="subHead"><h1>Manage Staff</h1></div>
<div id="engTable">
    <h3>Engineers</h3>
    <table>
        <tr>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
<?php 
    while($row = $eng->fetch(PDO::FETCH_ASSOC)){
        if($row['engID']==1){
          
        }else{
?>
        <tr>
            <td><?php echo $row['engName']; ?></td>
            <td><a href="update_engineer.php?engID=<?php echo $row['engID']; ?>">Edit</a></td>
        </tr>
<?php
    }
}
?>
        
           
      
    </table>
    <div class="newLink"><a href="new_engineer.php">Add New Engineer &rarr;</a></div>
</div>

<div id="astTable">
    <h3>Assistants</h3>
    <table>
        <tr>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
<?php
    while($row = $ast->fetch(PDO::FETCH_ASSOC)){
        if($row['astID']==1){
          
        }else{
?>
        <tr>
            <td><?php echo $row['astName']; ?></td>
            <td><a href="update_assistant.php?astID=<?php echo $row['astID']; ?>">Edit</a></td>
        </tr>
<?php

    }
}
?>
    </table>
    <div class="newLink"><a href="new_assistant.php">Add New Assistant &rarr; </a></div>
</div>

<?php  require_once ('footer.php');