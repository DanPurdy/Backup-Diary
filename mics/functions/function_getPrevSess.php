<?php

require_once '../includes/pdoconnection.php';

$dbh = dbConn::getConnection();

function getPrevSess($prevSes){
    global $dbh;
    try{
        $sth = $dbh->prepare('SELECT sesID, sessDate FROM session WHERE bakID = :bakID ORDER BY sessDate ASC;');
        
        $sth->bindParam(':bakID', $prevSes, PDO::PARAM_INT);
        
        $sth->execute();
        
        $row=$sth->fetch(PDO::FETCH_ASSOC);
        
        $prevSes = $row['sesID'];
}catch(PDOException $e){
   print $e->getMessage();
}
    
    return $prevSes;
}
?>
