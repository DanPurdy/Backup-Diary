<?php

require_once '../includes/pdoconnection.php';

$dbh = dbConn::getConnection();

if (!isset($_REQUEST['term']))
{
    die('([])');
}


$st = $dbh->prepare(
            'SELECT cmpID,cmpName ' .
            'FROM composer ' .
            'WHERE cmpName like :cmpName ' .
            'ORDER BY cmpID');

$searchName = $_REQUEST['term'] . '%';
$st->bindParam(':cmpName', $searchName, PDO::PARAM_STR);

$data = array();
if ($st->execute())
{
    while ($row = $st->fetch(PDO::FETCH_OBJ))
    {
        $data[] = array(
            'label' => $row->cmpName ,
            'value' => $row->cmpName,
            'post' => $row->cmpID
        );
    }
}
echo json_encode($data);
flush();

?>
