<?php

require_once 'includes/pdoconnection.php';
$dbh = dbConn::getConnection();

try{
$sth = $dbh->prepare('UPDATE session SET ssNo=:ssNo
        WHERE sesID=:sesID;' );


$sth->bindParam(':ssNo', $_POST['ssNo'] , PDO::PARAM_INT);
$sth->bindParam(':sesID', $_POST['sesID'] , PDO::PARAM_INT);


$sth->execute();
}catch (PDOException $e) {
    print $e->getMessage();
}
?>
