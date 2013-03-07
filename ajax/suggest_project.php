<?php

require_once '../includes/pdoconnection.php';

$dbh = dbConn::getConnection();

if (!isset($_REQUEST['term']))
{
    die('([])');
}


$st = $dbh->prepare(
            'SELECT prjID,prjName ' .
            'FROM project ' .
            'WHERE prjName like :prjName ' .
            'ORDER BY prjID');

$searchName = $_REQUEST['term'] . '%';
$st->bindParam(':prjName', $searchName, PDO::PARAM_STR);

$data = array();
if ($st->execute())
{
    while ($row = $st->fetch(PDO::FETCH_OBJ))
    {
        $data[] = array(
            'label' => $row->prjName ,
            'value' => $row->prjName,
            'post' => $row->prjID
        );
    }
}
echo json_encode($data);
flush();

?>
