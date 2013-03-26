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

function micLog($micID, $user, $sesID, $logState){
    global $dbh;
    try{
        
        $sth = $dbh->prepare('INSERT INTO micLog (micID, sesID, usrID, logState) VALUES(:micID, :sesID, :usrID, :logState);');
        
        $sth->bindParam(':micID', $micID, PDO::PARAM_INT);
        $sth->bindParam(':sesID', $sesID, PDO::PARAM_INT);
        $sth->bindParam(':usrID', $user, PDO::PARAM_INT);
        $sth->bindParam(':logState', $logState, PDO::PARAM_STR);
        
        $sth->execute();
        
        
        }catch(PDOException $e){
    print $e->getMessage();
}
    
    
    
}
?>
