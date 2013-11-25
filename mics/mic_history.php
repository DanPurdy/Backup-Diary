<?php

require_once 'includes/pdoconnection.php';
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$mics=new mic($dbh);

$history = $mics->getMicHistory(1009);
require_once ('header.php');
?>

<table>
	<tr>
		<th>State</th>
		<th>Session Date</th>
		<th>Session Times</th>
		<th>Engineer</th>
		<th>Assistant</th>
		<th>Client</th>
		<th>Composer</th>
		<th>Project</th>
		<th>Logged By</th>
		<th>Log Time</th>
	</tr>
<?php

foreach($history as $record=>$value){ ?>

	<tr>
		<td><?php echo $value->logState;?></td>
		<td><?php echo date('d-m-y', strtotime($value->sessDate));?></td>
		<td><?php echo $value->startTime.' - '.$value->endTime;?></td>
		<td><?php echo $value->engName;?></td>
		<td><?php echo $value->astName;?></td>
		<td><?php echo $value->cliName;?></td>
		<td><?php echo $value->cmpName;?></td>
		<td><?php echo $value->prjName;?></td>
		<td><?php echo $value->username;?></td>
		<td><?php echo $value->micLogTime;?></td>
	</tr>
<?php
	}

?>
</table>
<?php
	require_once('footer.php');
?>