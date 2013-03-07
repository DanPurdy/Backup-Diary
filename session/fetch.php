<?php
//require PDO connection class
require_once '../includes/pdoconnection.php';

//Create a connection
$dbh = dbConn::getConnection();

//PDO fetch function and html print function pass studio number variable to it
function getSessions($studio){
$i=0;
global $dbh; //make $dbh a global variable so function can access it
try{
$sth = $dbh->prepare("SELECT startTime, endTime, client.cliName, project.prjName, composer.cmpName, fixer.fixName, session.sessDate
    FROM session 
    INNER JOIN client ON session.cliID=client.cliID
    INNER JOIN project ON session.prjID=project.prjID
    INNER JOIN composer ON session.cmpID=composer.cmpID
    INNER JOIN fixer ON session.fixID=fixer.fixID
    WHERE date(sessDate) = date(NOW()) AND stdID = $studio ORDER BY startTime ASC");

//select all relevant information about a session record from record table for  the day.

//bind all values to variables
$sth->bindColumn('startTime', $startTime);  
$sth->bindColumn('endTime', $endTime);
$sth->bindColumn('cliName', $cliName);
$sth->bindColumn('prjName', $prjName);
$sth->bindColumn('cmpName', $cmpName);
$sth->bindColumn('fixName', $fixName);
$sth->bindColumn('sessDate', $sessDate);
//execute the fetch command
$sth->execute();

// assign each result to an array to allow multiple session results
while($row = $sth->fetch(PDO::FETCH_BOUND)){
    $sT[$i] = $startTime;
    $eT[$i] = $endTime;
    $cli[$i] = $cliName;
    $prj[$i] = $prjName;
    $cmp[$i] = $cmpName;
    $fix[$i] = $fixName;
    // calculate a valid timestamp with date and time fields
    $tSs[$i] = $sessDate . " " . $startTime;
    //iterate the array
    $i++;
}
} //catch any errors
  catch (PDOException $e) {
    print $e->getMessage();
  }

 //assign the current time in unix timestamp format to the now variable
  $now = time();
if(!empty($sT[0])){                                            //if studio numbers first array result is empty there are no sessions so do nothing
    if(!empty($sT[1])){                                         //if studio numbers first result is present but second isnt there is only one session
        if ($now < (strtotime($tSs[1])-(60*60*1.5))){           // if second session start time is 1.5 hours away then it should be displayed
            
            print '<div class ="s'.$studio.'time">'.substr($sT[0],0,5).' - '.substr($eT[0],0,5).'</div>'; // print session times and remove the last :00 with substr
            if(empty($cmp[0])){                                                 //if composer field is empty then print the client instead
                print '<div class ="s'.$studio.'client">'.$cli[0].'</div>';
                
                }
            else{
                print '<div class ="s'.$studio.'client">'.$cmp[0].'</div>';
                
                }
            
            if(empty($fix[0])){                                                 //if fixer field is empty then print project instead
                print '<div class ="s'.$studio.'project">'.$prj[0].'</div>';
                } 
            else {
                print '<div class ="s'.$studio.'project">'.$fix[0].'</div>';
                }
            
            }
        else
            {
            print '<div class ="s'.$studio.'time">'.substr($sT[1],0,5).' - '.substr($eT[1],0,5).'</div>';
            
            if(empty($cmp[1])){
                print '<div class ="s'.$studio.'client">'.$cli[1].'</div>';
                
                }
            else{
                print '<div class ="s'.$studio.'client">'.$cmp[1].'</div>';
                
                }
            
            if(empty($fix[1])){
                print '<div class ="s'.$studio.'project">'.$prj[1].'</div>';
                } 
            else {
                print '<div class ="s'.$studio.'project">'.$fix[1].'</div>';
                }
            }
     }
     else
        {
        print '<div class ="s'.$studio.'time">'.substr($sT[0],0,5).' - '.substr($eT[0],0,5).'</div>';
        if(empty($cmp[0])){
                print '<div class ="s'.$studio.'client">'.$cli[0].'</div>';
                
                }
            else{
                print '<div class ="s'.$studio.'client">'.$cmp[0].'</div>';
                
                }
            
            if(empty($fix[0])){
                print '<div class ="s'.$studio.'project">'.$prj[0].'</div>';
                } 
            else {
                print '<div class ="s'.$studio.'project">'.$fix[0].'</div>';
                }
        }
    }

else
    {


    }
}


?>
