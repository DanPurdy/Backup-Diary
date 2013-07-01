<?php


require_once 'includes/pdoconnection.php';
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$session=new session($dbh);
$backup=new backup($dbh);

if($_POST){
    if($_POST['sessCont']!=0 OR !empty($_POST['sessCont'])){
        $bakID = $_POST['sessCont'];
    }else{
        
        $bakID = $backup->initBackup(); 
    }
    
    $session->addNewSession($_POST,$bakID, $dbh);
    
}

$today=date("Y-m-d");

$result=$session->getContSess();
require_once ('header.php');
?>

        
        
<div id="subHead"><h1>Add New Session</h1></div>
        
        
        <form id="newSession" method="post" action="new_session.php" enctype="multipart/form-data">
            <div id="sessDet">
                <div class="backupTitle"><h3>Session Details</h3></div>
            <div id="studioSelect">
                <div class="sessionTitle"><h3>Select Studio</h3></div>
                <input type="radio" value="1" name="studio" class="radio"/>Studio One<br />
                <input type="radio" value="2" name="studio" class="radio"/>Studio Two<br />
                <input type="radio" value="3" name="studio" class="radio"/>Studio Three<br />
            </div>
               
             
            <div id="dateSelect">
                <h3>Choose Date</h3>
                <label for="sessdate">Session Date: </label>
                <input id="sessdate" name="sessdate" type="date" value="<?php echo $today; ?>"/>
            </div>
                
            <div id="timeSelect">
                <h3>Choose Session Times</h3>
                <label for="starttime">Start Time: </label><input id="starttime" name="starttime" type="time" value="10:00"/> -
                <input id="endtime" name="endtime" type="time" value="00:00"/><label for="endtime"> End Time</label>
            </div>
                 <div id="ssSelect">
                <h3>Session Sheet Number</h3>
                <input type ="number" name="sessionNumber" min="7000" max="100000" /><br />
                
            </div>
                
                <div id="continueSelect">
                    <h3>Session Continuation</h3>
                    <select name="sessCont" id="sessCont">
                        <option value="0">N/A</option>
                        
                        
                    </select>
                </div>
                 </div>
                <div id="staffSel">
                    <div class="backupTitle"><h3>Staff Details</h3></div>
            <div id="engineerSelect">
                <h3>Select Engineer</h3>
                Select Engineer:
                <input id="engsearch" name="engN" type="text" />
                <input id="engineerID" name="engineerID" value="0" class="hidden" />
            </div>
            <div id ="assistantSelect">
                <h3>Select Assistant</h3>
                Select Assistant:
                <input id="astsearch" name="astN" type="text" />
                <input id="assistantID" name="assistantID" value="0" class="hidden" />
            </div>
                </div>  
            <div id="clientDetails">
                <div class="backupTitle"><h3>Client Details</h3></div>
            <div id="clientSelect">
                <h3>Select Client</h3>
                Enter Client Name:
                <input id="clisearch" name="cliN" type="text" required />
                <input id="clientID" name="clientID" value="0" class="hidden" />
            </div>
            
            <div id="cmpSelect">
                <h3>Select Composer</h3>
                Enter Composer:
                <input id="composersearch" name="compN" type="text" />
                <input id="composerID" name="composerID" value="0" class="hidden" />
            </div>
            
            <div id="fixSelect"> 
                <h3>Select Fixer</h3>
                Enter Fixer Name:
                <input id="fixsearch" name="fixN" type="text" />
                <input id="fixerID" name="fixerID" value="0" class="hidden" />
            </div>
            
            <div id="projSelect">
                <h3>Select Project</h3>
                Enter Project Name:
                <input id="projsearch" name="projN" type="text" />
                <input id="projectID" name="projectID" value="0" class="hidden" />
            </div>
                </div>
            <div id="sessSubmit"><input type="submit" name="submit" value="Add New Session" id="newSessSubmit"/></div>
           
        </form>
<?php require_once ('footer.php'); ?>
