<?php

require_once '../includes/pdoconnection.php';

$dbh = dbConn::getConnection();

if (!isset($_REQUEST['term']))
{
    die('([])');
}


$st = $dbh->prepare(
            'SELECT astID,astName ' .
            'FROM assistant ' .
            'WHERE astName like :astName ' .
            'ORDER BY astID');

$searchName = $_REQUEST['term'] . '%';
$st->bindParam(':astName', $searchName, PDO::PARAM_STR);

$data = array();
if ($st->execute())
{
    while ($row = $st->fetch(PDO::FETCH_OBJ))
    {
        $data[] = array(
            'label' => $row->astName ,
            'value' => $row->astName,
            'post' => $row->astID
        );
    }
}
echo json_encode($data);
flush();

?>
