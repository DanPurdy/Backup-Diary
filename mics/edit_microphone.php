<?php

	require('includes/pdoconnection.php');

	function __autoload($class_name) {
			include 'models/class_'.$class_name . '.php';
	}

	$dbh = dbConn::getConnection();

	$microphone=new mic($dbh);
	$session=new session($dbh);


	$row=$session->getSessByID($_GET['sesID']);
					
	$stdID = $row['stdID'];
	$sesID = $row['sesID'];
	$date = strtotime($row['sessDate']);
	$engIn = strpos($row['engName']," ",1);
	$bakMicID = $row['bakID'];
					
	$micList=$microphone->getSessionMics($bakMicID);
		
	$sesList=$session->getUpcomingSessions();
		
	if(isset($_GET['e'])){
		$e = htmlentities($_GET['e']);
		
		if(isset($_GET['prevSes'])){
			$prevSes = htmlentities($_GET['prevSes']);

		}
	}

	$errorSess=$session->getPrevSess($prevSes);

	require_once('header.php');

?>

<?php
// if $_GET['remove'] is present then we are taking mics from the cupboard if not present then we are returning mics to the cupboard.


	if($_GET['remove'] !=1){
?>
		<div id="subHead">
			<h1>Add Microphones</h1>
		</div><?php 
	}else{ ?>
		<div id="subHead">
			<h1>Return Microphones</h1>
		</div> 
<?php 
	}; 

?>
<?php 
	if($e==1 && !empty($_GET['micNo'])){ ?>

		<div id="micWarning" class="Eassigned">
			<h3>Sorry Microphone 
				<?php echo htmlentities($_GET['micNo']);?> is already signed out on <a href="edit_microphone.php?sesID=<?php echo $errorSess;?>&remove=1">Session # <?php echo $errorSess;?></a>
			</h3>
		</div>
		
<?php 
	}elseif($e == 2){ ?>
		<div id="micWarning">
			<h3>
				Sorry! Microphone <?php echo htmlentities($_GET['micNo']);?> is already signed into the cupboard. Was it signed out properly?
			</h3>
		</div>

<?php 
	}elseif ($e==3) {?>
		 <div id="micWarning" class="REassigned">
			<h3>
				Sorry! Microphone <?php echo htmlentities($_GET['micNo']);?> is assigned to <a href="edit_microphone.php?sesID=<?php echo $errorSess;?>&remove=1">Session # <?php echo $errorSess;?> You must return it there.</a>
			</h3>
		</div>

							 
<?php  
	}elseif($e==4) { ?>
		<div id="micWarning">
			<h3>
				You must choose a session to transfer mics to from the dropdown menu.
			</h3>
		</div>

<?php   
	}elseif($e==5){ ?>
		<div id="micWarning">
			<h3>
				Sorry! Microphone <?php echo htmlentities($_GET['micNo']); ?> is currently out for repair.
			</h3>
		</div>
<?php   
	} 
?>
		
		
		
		
		

		
	<div id="session-mic-details">
		<div class="micSessDet">
			<div class="backupDriveTitle"><h3>Session Details</h3></div>
			<div class="studio">Studio <?php echo $row['stdID'];?></div>
			<div class="sessdate"><?php echo date('d-m-Y', $date);?></div>
			<div class="sesstime">
				<?php echo substr($row['startTime'],0,5) . " - " . substr($row['endTime'],0,5);?>
			</div>
		</div>
		<div class="micSessStaff">
			<div class="backupDriveTitle"><h3>Engineer / Assistant</h3></div>
			<div class="engineer"><?php echo $row['engName'];?></div>
			<div class="assistant"><?php echo $row['astName']?></div>
		</div>
		<div class="micSessClient">
			<div class="backupDriveTitle"><h3>Client Details</h3></div>
			<div class="client"><?php echo $row['cliName']?></div>
			<div class="composer"><?php echo $row['cmpName']?></div>
			<div class="fixer"><?php echo $row['fixName']?></div>
			<div class="project"><?php echo $row['prjName']?></div>
		</div>
		<?php

			
			?>
	</div>

<?php
				
	if($_GET['remove'] !=1){ ?>
				
			
	<form id="micForm" action="addMic.php" method="post">
			
		<div class="microphoneAssigned">
	 		<div class="backupDriveTitle">
	 			<h3>Microphones</h3>
	 		</div> 
	<?php
	
		foreach($micList as $row2) {?>

			<div class="micNo"><?php echo $row2['micID'] . " " .$row2['micMake'] . " " .$row2['micModel'] ?>
			</div>
		
	<?php
		} //end foreach
	?>
		</div>
	 	<div id="micFormContainer">
		 	<div class="backupDriveTitle"><h1>Assign Microphones</h1></div>
			<input type="number" name="bakID" class="hidden" value="<?php echo $bakMicID ?>"  />
			<input type="number" name="sesID" class="hidden" value="<?php echo$sesID ?>"  />
			<input type="number" id="micNo" class="micEntryNo" name="micNo" min="1000" max="1350" />
			<input type="submit" id="submitMic" class="hidden"/>
	

		</div>
	</form>

<?php 
	}else{ //if ?>

						 
	<form id="micForm" action="returnMic.php" method="post"> 
				
		<div class="microphoneAssigned">
			<div class="backupDriveTitle">
				<h3>Microphones</h3>
			</div>
					
	<?php 
		
		if($microphone->count >=1){?>
			<div class="micNo">
				<h3>
					<input type="checkbox" class="checkall" /> Check All 
				</h3>
			</div>
			<br> 
	<?php 
		} //if 
		
		foreach($micList as $row2) {  
				
	?>
			<div class="micNo">
				<input type="checkbox" name="micNo_check[]" value="<?php echo$row2['micID'];?>"/> <?php echo $row2['micID'] . " &nbsp;|&nbsp; " .$row2['micMake'] . " " .$row2['micModel'] ?>
			</div>
		
	<?php
	
		} //foreach
	?>
		
		</div>
		<div id="micFormContainer">  
			
			<div class="backupDriveTitle">
				<h1>Return Microphones</h1>
			</div>
			<input type="number" name="bakID" class="hidden" value="<?php echo $bakMicID ?>" />
			<input type="number" name="sesID" class="hidden" value="<?php echo$sesID ?>" />
			<input type="number" id="micNo" class="micEntryNo" name="micNo" min="1000" max="1350" />
			<input type="submit" id="submitMic" name="returnMic_button" value="Return" class="hidden" />
			<div class="backupDriveTitle">
				<h1>Transfer Mics</h1>
			</div>
			
			<select name="transferSession" id="transferSession">
				<option value="0">Choose Session To Transfer To</option>
		
		<?php 
			foreach($sesList as $row3){?>

				<option value="<?php echo$row3['bakID'];?>"><?php echo' Studio: '.$row3['stdID']. ' | '.date('d-M-Y', strtotime($row3['sessDate'])).' | '.$row3['cliName']?></option>
		<?php 

			} //foreach 
		?>
			</select>
			<input type="submit" id="transferMic" name="transferMic_button" value="Transfer Microphones" >

			<div class="backupDriveTitle">
				<h1>Repair</h1>
			</div>

			<textarea class="mic" name="fault" rows="3" cols="40">Select one mic and describe the fault here..</textarea>

			<input type="submit" id="repairMic" name="repairMic_button" value="Send To Workshop" >
		</div>  

		
		
		
	</form>
					 
<?php 
} // else (171)
?>
<?php 
	require_once('footer.php'); 
?>