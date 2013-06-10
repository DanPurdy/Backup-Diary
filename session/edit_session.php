<?php

session_start();

$referer = $_SERVER['HTTP_REFERER'];
if($referer != '/search/search_std.php'){
    $_SESSION['org_referer'] = htmlentities($_SERVER['HTTP_REFERER']);

}else{
    $_SESSION['org_referer'] = '/session/';
}
require_once 'includes/pdoconnection.php';

$dbh = dbConn::getConnection();

$OK = false;
$done = false;



if (isset($_GET['sesID']) && !$_POST) {
try {
$sth=$dbh->prepare("SELECT session.*, studio.stdName, engineer.engName, assistant.astName, client.cliName, composer.cmpName, fixer.fixName, project.prjName
                            FROM session
                            INNER JOIN studio ON session.stdID=studio.stdID
                            INNER JOIN engineer ON session.engID=engineer.engID
                            INNER JOIN assistant ON session.astID=assistant.astID
                            INNER JOIN client ON session.cliID=client.cliID
                            INNER JOIN project ON session.prjID=project.prjID
                            INNER JOIN composer ON session.cmpID=composer.cmpID
                            INNER JOIN fixer ON session.fixID=fixer.fixID
                            WHERE session.sesID = :sessID;" );
    
    $sth->bindParam(':sessID', $_GET['sesID']);

  $sth->execute();
  
  $row = $sth->fetch(PDO::FETCH_ASSOC);
  $studioSel = $row['stdID'];
  
  $bakID = $row['bakID'];
}
catch (PDOException $e) {
    print $e->getMessage();
}
}

try {
    $st1=$dbh->prepare("SELECT session.*, studio.stdName, engineer.engName, assistant.astName, client.cliName, composer.cmpName, fixer.fixName, project.prjName
                            FROM session
                            INNER JOIN studio ON session.stdID=studio.stdID
                            INNER JOIN engineer ON session.engID=engineer.engID
                            INNER JOIN assistant ON session.astID=assistant.astID
                            INNER JOIN client ON session.cliID=client.cliID
                            INNER JOIN project ON session.prjID=project.prjID
                            INNER JOIN composer ON session.cmpID=composer.cmpID
                            INNER JOIN fixer ON session.fixID=fixer.fixID
                            WHERE session.bakID = :bakID
                            ORDER BY sessDate ASC;" );
    
    $st1->bindParam(':bakID', $bakID, PDO::PARAM_INT);

  $st1->execute();
    $count=$st1->rowCount();
    $result=$st1->fetch(PDO::FETCH_ASSOC);
  
  


}
catch (PDOException $e) {
    print $e->getMessage();
}

  try{
     $st2 = $dbh->prepare('SELECT session.*, engineer.engName,assistant.astName, client.cliName, project.prjName
                          FROM session
                          INNER JOIN engineer ON session.engID=engineer.engID
                          INNER JOIN assistant ON session.astID=assistant.astID
                          INNER JOIN client ON session.cliID=client.cliID
                          INNER JOIN project ON session.prjID=project.prjID
                          WHERE sessDate >= DATE_ADD(sessDate, INTERVAL - 14 DAY) AND YEAR(sessdate) = YEAR(CURRENT_DATE) AND stdID=:stdID
                          ORDER BY stdID ASC,sessDate ASC;');
     
     
     $st2->bindParam(':stdID',  $studioSel, PDO::PARAM_INT);
    
    $st2->execute();
    
    
}catch(PDOException $e){
    print $e->getMessage();
    
}

$today=date("Y-m-d");

require_once 'header.php'
?>

        
        
        <div id="subHead"><h1>Update Session</h1></div>
        <?php if (isset($error)) {echo "<p>Error: $error</p>";} ?>
        
        <form id="updateSession" method="post" action="update_session.php" enctype="multipart/form-data">
            
             
            <?php
            $date = strtotime($row['sessDate']);
          
            

            ?>
            
            <input id="sessionID" name="sessionID" value="<?php echo $row['sesID'];?>" class="hidden" />
            <div id="sessDet">
                <div class="backupTitle"><h3>Session Details</h3></div>
            <div id="studioSelect">
               <?php switch($row['stdID']) 
{ 
                case "1": $checkone = "checked"; break; 
                case "2": $checktwo = "checked"; break; 
                case "3": $checkthree = "checked"; break; 
}  ?>
                
                <div class="sessionTitle"><h3>Studio</h3></div>
                <input type="radio" value="1" name="studio" <?=$checkone?>/>Studio One<br />
                <input type="radio" value="2" name="studio" <?=$checktwo?>/>Studio Two<br />
                <input type="radio" value="3" name="studio" <?=$checkthree?>/>Studio Three<br />
            </div>
            <div id="dateSelect">
                <h3>Session Date</h3>
                <input id="sessdate" name="sessdate" type="date" value="<?php echo date('Y-m-d', $date); ?>"/>
            </div>
                
            <div id="timeSelect">
                <h3>Session Times</h3>
                <label for="starttime">Start Time: </label><input id="starttime" name="starttime" type="time" value="<?php echo substr($row['startTime'],0,5); ?>"/> -
                <input id="endtime" name="endtime" type="time" value="<?php echo substr($row['endTime'],0,5); ?>"/><label for="endtime"> End Time</label>
            </div>
                <div id="ssSelect">
                <h3>Session Sheet Number</h3>
                <input type ="number" name="sessionNumber" min="7000" max="100000" value="<?php if($row['ssNo'] >=7000){echo $row['ssNo'];}else{};  ?>" />
                
            </div>
                <div id="continueSelect">
                    <h3>Session Continuation</h3>
                    <input id="backupID" name="backupID" value="<?php echo $row['bakID'];?>" class="hidden" />
                    <select name="sessCont" id="sessCont">
                        <?php 
                        $initEng = explode(" ",$result['engName']); //split string into two seperate strings and seperate array values
                        $initAst = explode(" ",$result['astName']);
                        
                        if($count ==1 && $result['sesID'] == $row['sesID']){ 
                            echo '<option value="'.$result['bakID'].'" selected>N/A</option>'; 
                            
                            }else{ 
                                if($result['sesID']!=$row['sesID']) {
                                    echo '<option value ="0">N/A</option><option value="'.$result['bakID'].'" selected >'.$result['stdID'].' | '.date('d-m-Y', strtotime($result['sessDate']))." | ".substr($initEng[0],0,1).substr($initEng[1],0,1).substr($initAst[0],0,1).substr($initAst[1],0,1)." | ".$result['cliName'].' | '.$result['prjName'].'</option>'; 
                                    
                                    }else{ 
                                        echo '<option value="'.$result['bakID'].'" selected>Parent</option>'; 
                                        
                                        }
                                  }
                        
                        
                        
                        while ($row2 = $st2->fetch(PDO::FETCH_ASSOC)){
                            
                        $initEng = explode(" ",$row2['engName']); //split string into two seperate strings and seperate array values
                        $initAst = explode(" ",$row2['astName']);
                        
                        
                        if($result['bakID']==$row2['bakID']){ ?>
                        
                        <?php }else{
                        ?>
                        <option value="<?= $row2['bakID']?>"><?=$row2['stdID']." | ".date('d-m-Y', strtotime($row2['sessDate']))." | ".substr($initEng[0],0,1).substr($initEng[1],0,1).substr($initAst[0],0,1).substr($initAst[1],0,1)." | ".$row2['cliName']." | ".$row2['prjName']?></option>
                        
                            
                            <?php   
                        }
                        
                        
                            }
                        
                        
                        ?>
                        
                    </select>
                </div>
            </div>
                <div id="staffSel">
                    <div class="backupTitle"><h3>Staff Details</h3></div>
            <div id="engineerSelect">
                <h2>Engineer</h2>
                Select Engineer:
                <input id="engsearch" name="engN" type="text" value="<?php echo $row['engName'];?>" />
                <input id="engineerID" name="engineerID" value="<?php echo $row['engID'];?>"  class="hidden" />
            </div>
            <div id ="assistantSelect">
                <h2>Assistant</h2>
                Select Assistant:
                <input id="astsearch" name="astN" type="text" value="<?php echo $row['astName'];?>" />
                <input id="assistantID" name="assistantID" value="<?php echo $row['astID'];?>" class="hidden" />
            </div>
                </div>
            <div id="clientDetails">
                <div class="backupTitle"><h3>Client Details</h3></div>
            <div id="clientSelect">
                <h2>Client</h2>
                Enter Client Name:
                <input id="clisearch" name="cliN" type="text" value="<?php echo $row['cliName'];?>" />
                <input id="clientID" name="clientID" value="<?php echo $row['cliID'];?>" class="hidden" />
            </div>
            
            <div id="cmpSelect">
                <h2>Composer</h2>
                Enter Composer:
                <input id="composersearch" name="compN" type="text" value="<?php echo $row['cmpName'];?>"/>
                <input id="composerID" name="composerID" value="<?php echo $row['cmpID'];?>" class="hidden" />
            </div>
            
            <div id="fixSelect">
                <h2>Fixer</h2>
                Enter Fixer Name:
                <input id="fixsearch" name="fixN" type="text" value="<?php echo $row['fixName'];?>" />
                <input id="fixerID" name="fixerID" value="<?php echo $row['fixID'];?>" class="hidden" />
            </div>
            
            <div id="projSelect">
                <h2>Project</h2>
                Enter Project Name:
                <input id="projsearch" name="projN" type="text" value="<?php echo $row['prjName'];?>" />
                <input id="projectID" name="projectID" value="<?php echo $row['prjID'];?>" class="hidden" />
            </div>
            </div>
            <div id="sessSubmit"><input type="submit" name="submit" value="Update Session" id="newSessSubmit"/></div>
            
        </form>
        <?php 
        
         
        require_once 'footer.php';
        ?>

