<?php

require('includes/pdoconnection.php');

function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$microphone=new mic($dbh);

if($_POST){
    $microphone->createMic($_POST['micNo'], $_POST['make'], $_POST['model']);
}
require_once ('header.php');
print_r($_POST);
?>

<form id="newMic" action="new_mic.php" method="post">
    <label for="micNumber">Angel ID: </label><input type="number" id="micNumber" max="3000" min="1000" name="micNo" required/>
    <label for="make">Make: </label><input type="text" id="make" size="20" name="make" required />
    <label for="model">Model: </label><input type="text" id="model" size="20" name="model" required />
    <input type="submit" value="Add Mic">
    
    
</form>


<?php require_once ('footer.php');