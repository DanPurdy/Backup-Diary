<?php

require_once '../includes/pdoconnection.php';

$dbh = dbConn::getConnection();

if (!isset($_REQUEST['term']))
{
    die('([])');
}


$st = $dbh->prepare(
            'SELECT engID,engName ' .
            'FROM engineer ' .
            'WHERE engName like :engName ' .
            'ORDER BY engID');

$searchName = $_REQUEST['term'] . '%';
$st->bindParam(':engName', $searchName, PDO::PARAM_STR);

$data = array();
if ($st->execute())
{
    while ($row = $st->fetch(PDO::FETCH_OBJ))
    {
        $data[] = array(
            'label' => $row->engName ,
            'value' => $row->engName,
            'post' => $row->engID
        );
    }
}
echo json_encode($data);
flush();

?>
