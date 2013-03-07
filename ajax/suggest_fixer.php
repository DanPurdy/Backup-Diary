<?php

require_once '../includes/pdoconnection.php';

$dbh = dbConn::getConnection();

if (!isset($_REQUEST['term']))
{
    die('([])');
}


$st = $dbh->prepare(
            'SELECT fixID,fixName ' .
            'FROM fixer ' .
            'WHERE fixName like :fixName ' .
            'ORDER BY fixID');

$searchName = $_REQUEST['term'] . '%';
$st->bindParam(':fixName', $searchName, PDO::PARAM_STR);

$data = array();
if ($st->execute())
{
    while ($row = $st->fetch(PDO::FETCH_OBJ))
    {
        $data[] = array(
            'label' => $row->fixName ,
            'value' => $row->fixName,
            'post' => $row->fixID
        );
    }
}
echo json_encode($data);
flush();

?>
