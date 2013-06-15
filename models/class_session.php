<?php

class session{
    
    private $mydb;
    private $result;
    private $cliID;
    private $cmpID;
    private $engID;
    private $astID;
    private $prjID;
    private $fixID;
    public $count;
    
    
    public function __construct($dbh) {
        $this->mydb=$dbh;
    }
    
    public function listWeekSession($week){
        try{
            $sth = $this->mydb->prepare("SELECT session.sesID, session.stdID, session.sessDate, session.startTime, session.endTime,session.ssNo, studio.stdName, engineer.engName, assistant.astName, client.cliName, composer.cmpName, fixer.fixName, project.prjName
                                    FROM session
                                    INNER JOIN studio ON session.stdID=studio.stdID
                                    INNER JOIN engineer ON session.engID=engineer.engID
                                    INNER JOIN assistant ON session.astID=assistant.astID
                                    INNER JOIN client ON session.cliID=client.cliID
                                    INNER JOIN project ON session.prjID=project.prjID
                                    INNER JOIN composer ON session.cmpID=composer.cmpID
                                    INNER JOIN fixer ON session.fixID=fixer.fixID
                                    WHERE WEEK(sessDate,1)= WEEK(DATE_ADD(current_date, INTERVAL :week WEEK),1) AND YEAR(sessDate) = YEAR(current_date)
                                    ORDER BY sessDate,session.stdID ASC,startTime;" );
            
            $sth->bindParam(':week',$week,PDO::PARAM_INT);
    
            $sth->execute();
            $this->result=$sth->fetchAll(PDO::FETCH_ASSOC);
      
        }
        catch (PDOException $e) {
            print $e->getMessage();
        }
        
        return $this->result;
    }
    
    public function getSessByID($id){
        try { //get all session details from the session ID
            $sth=$this->mydb->prepare("SELECT session.*, studio.stdName, engineer.engName, assistant.astName, client.cliName, composer.cmpName, fixer.fixName, project.prjName
                            FROM session
                            INNER JOIN studio ON session.stdID=studio.stdID
                            INNER JOIN engineer ON session.engID=engineer.engID
                            INNER JOIN assistant ON session.astID=assistant.astID
                            INNER JOIN client ON session.cliID=client.cliID
                            INNER JOIN project ON session.prjID=project.prjID
                            INNER JOIN composer ON session.cmpID=composer.cmpID
                            INNER JOIN fixer ON session.fixID=fixer.fixID
                            WHERE session.sesID = :sessID;" );
    
            $sth->bindParam(':sessID', $id, PDO::PARAM_INT);

            $sth->execute();
            
            $result=$sth->fetch(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
            print $e->getMessage();
        }
        return $result;
    }
    
    public function getLinkedSess($bakID){
        try{
            $sth=$this->mydb->prepare("SELECT session.*, studio.stdName, engineer.engName, assistant.astName, client.cliName, composer.cmpName, fixer.fixName, project.prjName
                                    FROM session
                                    INNER JOIN studio ON session.stdID=studio.stdID
                                    INNER JOIN engineer ON session.engID=engineer.engID
                                    INNER JOIN assistant ON session.astID=assistant.astID
                                    INNER JOIN client ON session.cliID=client.cliID
                                    INNER JOIN project ON session.prjID=project.prjID
                                    INNER JOIN composer ON session.cmpID=composer.cmpID
                                    INNER JOIN fixer ON session.fixID=fixer.fixID
                                    WHERE session.bakID = :bakID
                                    ORDER BY stdID ASC,sessDate ASC;" );
    
            $sth->bindParam(':bakID', $bakID, PDO::PARAM_INT);

            $sth->execute();
            
            $this->count=$sth->rowCount(); 
            
            $result=$sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e){
            print $e->getMessage();
        }
        
        return $result;
    }
    
    public function getEmptySessSheet(){
        try{
            $sth = $this->mydb->prepare("SELECT session.*, engineer.engName, assistant.astName, client.cliName, composer.cmpName, project.prjName
                                    FROM session
                                    INNER JOIN engineer ON session.engID=engineer.engID
                                    INNER JOIN assistant ON session.astID=assistant.astID
                                    INNER JOIN client ON session.cliID=client.cliID
                                    INNER JOIN project ON session.prjID=project.prjID
                                    INNER JOIN composer ON session.cmpID=composer.cmpID
                                    WHERE session.ssNo = '0' OR ISNULL(session.ssNo)
                                    ORDER BY sessDate DESC;" );
    
            $sth->execute();
            
            $result=$sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e){
            print $e->getMessage();
        }
        return $result;
    }
    
    
    public function addNewSession($postdata, $bakID, $dbh){
        
        $this->mydb->beginTransaction();
        
        $stdID =    $postdata['studio'];
        $ssNo =     $postdata['sessionNumber'];
        $sessDate = $postdata['sessdate'];
        $startTime =$postdata['starttime'];
        $endTime =  $postdata['endtime'];


        $sessDate = date('Y-m-d',  strtotime($sessDate));
        
        try{
            
            $this->cliID=checkRecord::checkID($postdata['clientID'],$postdata['cliN'],'cli', $dbh);
            $this->prjID=checkRecord::checkID($postdata['projectID'],$postdata['projN'],'prj', $dbh);
            $this->fixID=checkRecord::checkID($postdata['fixerID'],$postdata['fixN'],'fix', $dbh);
            $this->engID=checkRecord::checkID($postdata['engineerID'],$postdata['engN'],'eng', $dbh);
            $this->astID=checkRecord::checkID($postdata['assistantID'],$postdata['astN'],'ast', $dbh);
            $this->cmpID=checkRecord::checkID($postdata['composerID'],$postdata['compN'],'cmp', $dbh);
            
            $sth = $this->mydb->prepare('INSERT INTO session (stdID, cliID, prjID, fixID, engID, astID, ssNo, sessDate, startTime, endTime, cmpID, bakID) 
                                            VALUES (:stdID, :cliID, :prjID, :fixID, :engID, :astID, :ssNo, :sessDate, :startTime, :endTime, :cmpID, :bakID)' );

            $sth->bindParam(':stdID', $stdID , PDO::PARAM_INT);
            $sth->bindParam(':cliID', $this->cliID , PDO::PARAM_INT);
            $sth->bindParam(':prjID', $this->prjID , PDO::PARAM_INT);
            $sth->bindParam(':fixID', $this->fixID , PDO::PARAM_INT);
            $sth->bindParam(':engID', $this->engID , PDO::PARAM_INT);
            $sth->bindParam(':astID', $this->astID , PDO::PARAM_INT);
            $sth->bindParam(':ssNo', $ssNo , PDO::PARAM_INT);
            $sth->bindParam(':sessDate', $sessDate , PDO::PARAM_STR);
            $sth->bindParam(':startTime', $startTime , PDO::PARAM_STR);
            $sth->bindParam(':endTime', $endTime , PDO::PARAM_STR);
            $sth->bindParam(':cmpID', $this->cmpID , PDO::PARAM_INT);
            $sth->bindParam(':bakID', $bakID, PDO::PARAM_INT);

            $sth->execute();
            
            
            $this->mydb->commit();
        }
        catch(PDOException $e){
            $this->mydb->rollback();
            print $e->getMessage();
        } 
    
    }
    
    public function updateSession($postdata, $bakID, $dbh){
        
        $this->mydb->beginTransaction();
        
        $sesID =    $postdata['sessionID'];
        $stdID =    $postdata['studio'];
        $ssNo =     $postdata['sessionNumber'];
        $sessDate = $postdata['sessdate'];
        $startTime =$postdata['starttime'];
        $endTime =  $postdata['endtime'];
        
        

        $sessDate = date('Y-m-d',  strtotime($sessDate));
        
        try{
            
            
            if($bakID ==0 && $prevBak !=0){
                
            }
            
            $this->cliID=checkRecord::checkID($postdata['clientID'],$postdata['cliN'],'cli', $dbh);
            $this->prjID=checkRecord::checkID($postdata['projectID'],$postdata['projN'],'prj', $dbh);
            $this->fixID=checkRecord::checkID($postdata['fixerID'],$postdata['fixN'],'fix', $dbh);
            $this->engID=checkRecord::checkID($postdata['engineerID'],$postdata['engN'],'eng', $dbh);
            $this->astID=checkRecord::checkID($postdata['assistantID'],$postdata['astN'],'ast', $dbh);
            $this->cmpID=checkRecord::checkID($postdata['composerID'],$postdata['compN'],'cmp', $dbh);
            
            $sth = $this->mydb->prepare('UPDATE session SET stdID=:stdID, cliID=:cliID, prjID=:prjID, fixID=:fixID, engID=:engID, astID=:astID, ssNo=:ssNo, sessDate=:sessDate, startTime=:startTime, endTime=:endTime, cmpID=:cmpID, bakID =:bakID
                                         WHERE sesID=:sesID;' );

            $sth->bindParam(':stdID', $stdID , PDO::PARAM_INT);
            $sth->bindParam(':cliID', $this->cliID , PDO::PARAM_INT);
            $sth->bindParam(':prjID', $this->prjID , PDO::PARAM_INT);
            $sth->bindParam(':fixID', $this->fixID , PDO::PARAM_INT);
            $sth->bindParam(':engID', $this->engID , PDO::PARAM_INT);
            $sth->bindParam(':astID', $this->astID , PDO::PARAM_INT);
            $sth->bindParam(':ssNo', $ssNo , PDO::PARAM_INT);
            $sth->bindParam(':sessDate', $sessDate , PDO::PARAM_STR);
            $sth->bindParam(':startTime', $startTime , PDO::PARAM_STR);
            $sth->bindParam(':endTime', $endTime , PDO::PARAM_STR);
            $sth->bindParam(':cmpID', $this->cmpID , PDO::PARAM_INT);
            $sth->bindParam(':bakID', $bakID, PDO::PARAM_INT);
            $sth->bindParam(':sesID', $sesID, PDO::PARAM_INT);

            $sth->execute();
            
            
            $this->mydb->commit();
        }
        catch(PDOException $e){
            $this->mydb->rollback();
            print $e->getMessage();
        } 
    
    }
    
    public function deleteSession($sesID){
        $this->mydb->beginTransaction();
        
        try{
            $sth=$this->mydb->prepare("SELECT bakID FROM session WHERE sesID=:sessID;" );
    
            $sth->bindParam(':sessID', $sesID);
            
            $sth->bindColumn('bakID', $bakID);

            $sth->execute();
            
            $sth->fetch(PDO::FETCH_BOUND);
  
            $sth=$this->mydb->prepare("DELETE FROM session WHERE sesID=:sessID;" );
    
            $sth->bindParam(':sessID', $sesID);

            $sth->execute();
            
            $backup = new backup($this->mydb);
            
            $backup->cleanBackup($bakID);
            
            $this->mydb->commit();
        }
        catch(PDOException $e){
            $this->mydb->rollback();
            print $e->getMessage();
        }
        
    }
    
    public function updateSessNo($sessNo, $sessID){
        try{
            $sth = $this->mydb->prepare("UPDATE session SET ssNo = :ssNo WHERE sesID = :sesID;");
    
            $sth->bindParam(":ssNo", $sessNo, PDO::PARAM_INT);
            $sth->bindParam(":sesID", $sessID, PDO::PARAM_INT);
    
            $sth->execute();
        }
        catch (PDOException $e) {
            print $e->getMessage();
        } 
    }
    
    public function getContSess(){
         try{
            $sth = $this->mydb->prepare('SELECT session.*, engineer.engName,assistant.astName, client.cliName, project.prjName
                          FROM session
                          INNER JOIN engineer ON session.engID=engineer.engID
                          INNER JOIN assistant ON session.astID=assistant.astID
                          INNER JOIN client ON session.cliID=client.cliID
                          INNER JOIN project ON session.prjID=project.prjID
                          WHERE sessDate >= DATE_ADD(current_date, INTERVAL -3 MONTH) AND YEAR(sessdate) = YEAR(CURRENT_DATE)
                            GROUP BY session.bakID                          
                            ORDER BY stdID ASC,sessDate DESC;');
    
            $sth->execute();
            
            $result=$sth->fetchAll(PDO::FETCH_ASSOC);
    
    
        }catch(PDOException $e){
            print $e->getMessage();
    
        }
        return $result;
    }
    public function getContSessByStudio($studio, $sessDate){
         try{
            $sth = $this->mydb->prepare('SELECT session.*, engineer.engName,assistant.astName, client.cliName, project.prjName
                                        FROM session
                                        INNER JOIN engineer ON session.engID=engineer.engID
                                        INNER JOIN assistant ON session.astID=assistant.astID
                                        INNER JOIN client ON session.cliID=client.cliID
                                        INNER JOIN project ON session.prjID=project.prjID
                                        WHERE sessDate >= DATE_ADD(:sessDate, INTERVAL - 3 MONTH) AND YEAR(:sessDate) = YEAR(CURRENT_DATE) AND stdID=:stdID
                                        GROUP BY session.bakID
                                        ORDER BY stdID ASC,sessDate ASC;');
     
     
            $sth->bindParam(':stdID',  $studio, PDO::PARAM_INT);
            $sth->bindParam(':sessDate',  $sessDate, PDO::PARAM_INT);
    
            $sth->execute();
    
    

            
            $result=$sth->fetchAll(PDO::FETCH_ASSOC);
    
    
        }catch(PDOException $e){
            print $e->getMessage();
    
        }
        return $result;
    }
    
    public function getScreen($studio){
        $i=0;

        try{
            $sth = $this->mydb->prepare("SELECT startTime, endTime, client.cliName, project.prjName, composer.cmpName, fixer.fixName, session.sessDate
                                            FROM session 
                                            INNER JOIN client ON session.cliID=client.cliID
                                            INNER JOIN project ON session.prjID=project.prjID
                                            INNER JOIN composer ON session.cmpID=composer.cmpID
                                            INNER JOIN fixer ON session.fixID=fixer.fixID
                                            WHERE date(sessDate) = date(NOW()) AND stdID = $studio ORDER BY startTime ASC");

            //select all relevant information about a session record from record table for  the day.

            $sth->bindColumn('startTime', $startTime);  
            $sth->bindColumn('endTime', $endTime);
            $sth->bindColumn('cliName', $cliName);
            $sth->bindColumn('prjName', $prjName);
            $sth->bindColumn('cmpName', $cmpName);
            $sth->bindColumn('fixName', $fixName);
            $sth->bindColumn('sessDate', $sessDate);
            
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

        if(date('I') == 1){     // check if daylight savings is active
            $dst=3600;          // if it is active then set dst to 1 hour 
        }else{
            $dst=0;             //if not set time offset to 0
        }
        //assign the current time in unix timestamp format to the now variable
        $now = time() + $dst; //add the time offset if daylight savings is active to allow the start and end times to be calculated correctly.
  
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

      
    }
}
?>
