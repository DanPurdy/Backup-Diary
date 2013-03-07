<?php

require_once '../includes/pdoconnection.php';

$dbh = dbConn::getConnection();

if (!isset($_REQUEST['term']))
{
    die('([])');
}


$st = $dbh->prepare(
            'SELECT cliID,cliName ' .
            'FROM client ' .
            'WHERE cliName like :cliName ' .
            'ORDER BY cliID');

$searchName = $_REQUEST['term'] . '%';
$st->bindParam(':cliName', $searchName, PDO::PARAM_STR);

$data = array();
if ($st->execute())
{
    while ($row = $st->fetch(PDO::FETCH_OBJ))
    {
        $data[] = array(
            'label' => $row->cliName ,
            'value' => $row->cliName,
            'post' => $row->cliID
        );
    }
}
echo json_encode($data);
flush();

?>
