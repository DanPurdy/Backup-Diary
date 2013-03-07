<?php

require_once '../includes/pdoconnection.php';
require_once 'functions/function_getPrevSess.php';

$dbh = dbConn::getConnection();


try{
    $sth = $dbh->prepare("SELECT session.*, studio.stdName, engineer.engName, assistant.astName, client.cliName, composer.cmpName, fixer.fixName, project.prjName, session.bakID
                            FROM session
                            INNER JOIN studio ON session.stdID=studio.stdID
                            INNER JOIN engineer ON session.engID=engineer.engID
                            INNER JOIN assistant ON session.astID=assistant.astID
                            INNER JOIN client ON session.cliID=client.cliID
                            INNER JOIN project ON session.prjID=project.prjID
                            INNER JOIN composer ON session.cmpID=composer.cmpID
                            INNER JOIN fixer ON session.fixID=fixer.fixID
                            WHERE sesID = :sesID;" );
    
                            $sth->bindParam(':sesID', $_GET['sesID'] , PDO::PARAM_INT);
    
    $sth->execute();
    
    $row = $sth->fetch(PDO::FETCH_ASSOC); 
        
        $stdID = $row['stdID'];
        $date = strtotime($row['sessDate']);
        $engIn = strpos($row['engName']," ",1);
        $bakMicID = $row['bakID'];
          
    
       
}
catch (PDOException $e) {
    print $e->getMessage();
  }
  
  
  try{
      $st1 =$dbh->prepare("SELECT * FROM microphones
                           WHERE micSession = :bakID");
                           
                           $st1->bindParam(':bakID', $bakMicID , PDO::PARAM_INT);
                           
                           $st1->execute();
  }
  catch(PDOException $e){
      print $e->getMessage();
  }
  
 try{
    $st2 = $dbh->prepare("SELECT session.*, studio.stdName, engineer.engName, assistant.astName, client.cliName, composer.cmpName, fixer.fixName, project.prjName
                            FROM session
                            INNER JOIN studio ON session.stdID=studio.stdID
                            INNER JOIN engineer ON session.engID=engineer.engID
                            INNER JOIN assistant ON session.astID=assistant.astID
                            INNER JOIN client ON session.cliID=client.cliID
                            INNER JOIN project ON session.prjID=project.prjID
                            INNER JOIN composer ON session.cmpID=composer.cmpID
                            INNER JOIN fixer ON session.fixID=fixer.fixID
                            WHERE sessDate >= CURRENT_DATE() AND YEAR(sessDate)= YEAR(CURRENT_DATE())
                            ORDER BY sessDate,stdID;");
    
    $st2->execute();
    
    
    
       
}
catch (PDOException $e) {
    print $e->getMessage();
  }
  
  if(isset($_GET['e'])){
  $e = htmlentities($_GET['e']);
  if(isset($_GET['prevSes'])){
  $prevSes = htmlentities($_GET['prevSes']);
    
  }
  }
 require_once('header.php');
 
?>

<?php if($_GET['remove'] !=1){?><div id="subHead"><h1>Add Microphones</h1></div><?php }else{ ?><div id="subHead"><h1>Return Microphones</h1></div> <?php }; ?>
    <?php if($e==1 && !empty($_GET['micNo'])){ ?>

<div id="micWarning" class="Eassigned"><h3>Sorry Microphone <?= htmlentities($_GET['micNo']);?> is already signed out on <a href="edit_microphone.php?sesID=<?=getPrevSess($prevSes);?>&remove=1">Session # <?=getPrevSess($prevSes);?></a></h3></div>
    
    <?php }elseif($e == 2){ ?>
       <div id="micWarning"><h3>Sorry! Microphone <?= htmlentities($_GET['micNo']);?> is already signed into the cupboard. Was it signed out properly?</h3></div>
       <?php }elseif ($e==3) { ?>
           <div id="micWarning" class="REassigned"><h3>Sorry! Microphone <?= htmlentities($_GET['micNo']);?> is assigned to <a href="edit_microphone.php?sesID=<?=getPrevSess($prevSes);?>&remove=1">Session # <?=getPrevSess($prevSes);?> You must return it there.</a></h3></div>

               
         <?php  }elseif($e==4) { ?>
             <div id="micWarning"><h3>You must choose a session to transfer mics to from the dropdown menu.</h3></div>
      <?php   }elseif($e==5){ ?>
             <div id="micWarning"><h3>Sorry! Microphone <?= htmlentities($_GET['micNo']); ?> is currently out for repair.</h3></div>
   <?php   } ?>
    
    
    
    
    

    
<div id="session-mic-details">
    <div class="micSessDet">
    <div class="backupDriveTitle"><h3>Session Details</h3></div>
    <div class="studio">Studio <?php echo $row['stdID'];?></div>
    <div class="sessdate"><?php echo date('d-m-Y', $date);?></div>
    <div class="sesstime"><?php echo substr($row['startTime'],0,5) . " - " . substr($row['endTime'],0,5); ?></div>
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
   <div class="backupDriveTitle"><h3>Microphones</h3></div> 
    <?php
    
    while($row2 = $st1->fetch(PDO::FETCH_ASSOC)) { 
        
          ?>

    <div class="micNo"><?= $row2['micID'] . " " .$row2['micMake'] . " " .$row2['micModel'] ?></div>
    
  <?php
  
    }
    ?>
    </div>
     <div id="micFormContainer">
         <div class="backupDriveTitle"><h1>Assign Microphones</h1></div>
    <input type="number" name="bakID" value="<?= $bakMicID ?>" hidden/>
    <input type="number" id="micNo" name="micNo" min="1000" max="1350"/>
    <input type="submit" id="submitMic" hidden/>
</form>
 
</div>


<?php }else{ ?>

             
    <form id="micForm" action="returnMic.php" method="post"> 
        
    <div class="microphoneAssigned">
        <div class="backupDriveTitle"><h3>Microphones</h3></div>
          
        <?php 
        $count = $st1->rowCount();
        
        if($count >=1){?><div class="micNo"><h3><input type="checkbox" class="checkall"> Check All</div></h3><br> <?php } ?>
            <?php
     while($row2 = $st1->fetch(PDO::FETCH_ASSOC)) {  
        
          ?>

    <div class="micNo"><input type="checkbox" name="micNo_check[]" value="<?=$row2['micID'];?>"/> <?= $row2['micID'] . " &nbsp;|&nbsp; " .$row2['micMake'] . " " .$row2['micModel'] ?></div>
    
  <?php
  
    }
    ?>
    
</div>
  <div id="micFormContainer">  
                 <div class="backupDriveTitle"><h1>Return Microphones</h1></div>
        <input type="number" name="bakID" value="<?= $bakMicID ?>" hidden/>
    <input type="number" id="micNo" name="micNo" min="1000" max="1350" />
    <input type="submit" id="submitMic" name="returnMic_button" value="Return"hidden/>
    <div class="backupDriveTitle"><h1>Transfer Mics</h1></div>
    <select name="transferSession" id="transferSession">
        <option value="0">Choose Session To Transfer To</option>
        <?php 
        
                while($row3 = $st2->fetch(PDO::FETCH_ASSOC)){?>
    <option value="<?=$row3['bakID'];?>"><?=' Studio: '.$row3['stdID']. ' | '.date('d-M-Y', strtotime($row3['sessDate'])).' | '.$row3['cliName']?></option>
    <?php } 
    ?>
    </select>
    <input type="submit" id="transferMic" name="transferMic_button" value="Transfer Microphones" >
    <div class="backupDriveTitle"><h1>Repair</h1></div>
    <textarea class="mic" name="fault" rows="3" cols="40">Select one mic and describe the fault here..</textarea>
    <input type="submit" id="repairMic" name="repairMic_button" value="Send To Workshop" >
            </div>  

    
    
    
</form>
           
<?php } ?>
<?php require_once('footer.php'); ?>