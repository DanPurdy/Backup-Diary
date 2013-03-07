<?php

    require_once('includes/pdoconnection.php');
    
    $dbh = dbConn::getConnection();
    
    if(!empty($_POST['micNo']) && !empty($_POST['make']) && !empty($_POST['model'])){
        try{
            $sth=$dbh->prepare('INSERT INTO microphones (micID, micMake, micModel, micCupboard, micRepair) 
                VALUES (:micID, :micMake, :micModel, 1, 0);');
            
            $sth->bindParam(':micID', $_POST['micNo']);
            $sth->bindParam(':micMake', $_POST['make']);
            $sth->bindParam(':micModel', $_POST['model']);
            
            $sth->execute();
    } 
    catch (PDOException $e) {
    print $e->getMessage();
        }
    }




require_once ('header.php');
print_r($_POST);
?>

<form id="newMic" action="new_mic.php" method="post">
    <label for="micNumber">Angel ID: </label><input type="number" id="micNumber" max="3000" min="1000" name="micNo"/>
    <label for="make">Make: </label><input type="text" id="make" size="20" name="make" />
    <label for="model">Model: </label><input type="text" id="model" size="20" name="model" />
    <input type="submit" value="Add Mic">
    
    
</form>


<?php require_once ('footer.php');