<?php

session_start();

$referer = $_SERVER['HTTP_REFERER'];
if($referer != '/search/search_std.php'){                               //if linked from search session page then forward to session index
    $_SESSION['org_referer'] = htmlentities($_SERVER['HTTP_REFERER']);

}else{
    $_SESSION['org_referer'] = '/session/';
}
require_once 'includes/pdoconnection.php';                              //autoload classes
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();                                         //create a connection instance

$session=new session($dbh);                                             //new session object pass the connection object to it

if (isset($_GET['sesID']) && !$_POST) {                                 //if referrer isn't POST then get all the session details from the session ID in the URL

    $row=$session->getSessByID($_GET['sesID']);                         //get session details from session ID
    
    $studioSel = $row['stdID'];                                         //store which studio the session is in
    $sessDate = $row['sessDate'];                                       //store the session date
  
    $bakID = $row['bakID'];                                             //store the backup ID from this session
}   
                                                                        //find all the session details with the same backup ID (which means linked sessions as they are linked by backup ID)
$result=$session->getLinkedSess($bakID);
    
$count=$session->count;                                                 //get a count to see how many sessions are linked to this sessions backup ID

$initEng = explode(" ",$result['engName']);                             //split string into two seperate strings and seperate array values i.e Jeremy Murphy becomes [1,['Jeremy']][2,['Murphy']]
$initAst = explode(" ",$result['astName']);
  
$continue = $session->getContSessByStudio($studioSel, $sessDate);       //returns a list of all sessions in the same studio in the past 3 months (for the purpose of linking the session to a previous one)

$today=date("Y-m-d");                                                   //format todays date

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
               <?php switch($row['stdID'])                                                                  //switch to display the correct studio from the results 
{ 
                case "1": $checkone = "checked"; break; 
                case "2": $checktwo = "checked"; break; 
                case "3": $checkthree = "checked"; break; 
}  ?>
                
                <div class="sessionTitle"><h3>Studio</h3></div>
                <input type="radio" value="1" name="studio" class="radio" <?=$checkone?>/>Studio One<br />
                <input type="radio" value="2" name="studio" class="radio" <?=$checktwo?>/>Studio Two<br />
                <input type="radio" value="3" name="studio" class="radio" <?=$checkthree?>/>Studio Three<br />
            </div>
            <div id="dateSelect">
                <h3>Session Date</h3>
                <input id="sessdate" name="sessdate" type="date" value="<?php echo date('Y-m-d', $date); ?>"/>
            </div>
                
            <div id="timeSelect">
                <h3>Session Times</h3>
                <label for="starttime">Start Time: </label><input id="starttime" name="starttime" type="time" value="<?php echo substr($row['startTime'],0,5); ?>"/> -  <!-- strip the start and end times down to just hours and minutes -->
                <input id="endtime" name="endtime" type="time" value="<?php echo substr($row['endTime'],0,5); ?>"/><label for="endtime"> End Time</label>
            </div>
                <div id="ssSelect">
                <h3>Session Sheet Number</h3>
                <input type ="number" name="sessionNumber" min="7000" max="100000" value="<?php if($row['ssNo'] >=7000){echo $row['ssNo'];};  ?>" />
                
            </div>
                <div id="continueSelect">
                    <h3>Session Continuation</h3>
                    <input id="backupID" name="backupID" value="<?php echo $row['bakID'];?>" class="hidden" />
                    <select name="sessCont" id="sessCont">
                        <?php 
                        

                        
                        if($count ==1 && $result['sesID'] == $row['sesID']){                    // if theres only one session with the backup ID and that sessions ID == the session id of the record you are looking at then...
                            echo '<option value="'.$result['bakID'].'" selected>N/A</option>';  // there is no session linking going on so N/A value is selected and the records backup is assigned to the N/A option for logic purposes on updating the session
                            
                        }else{ 
                            if($result['sesID']!=$row['sesID']) {                               // if the session ID's dont match and the count is greater than 1 then a session is linked so output the first session and select it in the list
                                echo '<option value ="0">N/A</option>
                                      <option value="'.$result['bakID'].'" selected >'.$result['stdID'].' | '.date('d-m-Y', strtotime($result['sessDate']))." | ".substr($initEng[0],0,1).substr($initEng[1],0,1).substr($initAst[0],0,1).substr($initAst[1],0,1)." | ".$result['cliName'].' | '.$result['prjName'].'</option>'; 
                                    
                            }else{ 
                                echo '<option value="'.$result['bakID'].'" selected>Parent</option>'; //if the session is the parent session (first of the linked sessions to happen in time) then set it as the parent. jQuery will hide this select box from the edit page until all sessions have been unlinked. Preserves integrity.
                                        
                            }
                        }

                        
                        
                        
                        foreach($continue as $row2){
                            
                        $initEng = explode(" ",$row2['engName']); //split string into two seperate strings and seperate array values
                        $initAst = explode(" ",$row2['astName']);
                        
                        
                        if($result['bakID']==$row2['bakID']){ ?> <!-- hides this currently open record from the linking results -->
                        
                        <?php }else{ // list all other records. substr used to get First letter of each name from Engineer and assistants i.e. Jeremy Murphy becomes JM
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

