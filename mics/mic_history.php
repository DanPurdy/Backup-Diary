<?php

require_once 'includes/pdoconnection.php';
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$mics=new mic($dbh);

$micID = $_GET['micID'];
$history = $mics->getMicHistory($micID);
require_once ('header.php');
?>
<div class="returnLink">
    <a href="/mics/list.php"> &laquo Back to Mic List</a>
</div>
<div id="subHead">
    <h1>History for microphone <?php echo $micID; ?></h1>
</div>


<table class='historyTable'>
	<tr>
		<th scope="col">State</th>
		<th scope="col">Log Time</th>
		<th scope="col">Logged By</th>
		<th scope="col">Studio</th>
		<th scope="col">Session Date</th>
		<th scope="col">Client</th>
		<th scope="col">Composer</th>
		<th scope="col">Project</th>
		
	</tr>
<?php

foreach($history as $record=>$value){ ?>

	<tr>
		<td><?php echo $value->logState;?></td>
		<td>
			<?php echo date('d-m-y H:i:s', strtoTime($value->micLogTime));?>
		</td>
		<td><?php echo $value->username;?></td>
		<td><?php echo $value->stdID;?></td>
		<td><?php echo date('d-m-y', strtotime($value->sessDate));?></td>
		<td><?php echo $value->cliName;?></td>
		<td><?php echo $value->cmpName;?></td>
		<td><?php echo $value->prjName;?></td>
		
		
	</tr>
<?php
	}

?>
</table>
<?php
	require_once('footer.php');
?>