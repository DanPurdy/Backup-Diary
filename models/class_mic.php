<?php

class mic{
    protected $mydb;
    public $count;
    private $micID;
    private $user;
    private $logstate;
    public function __construct($dbh) {
        $this->mydb = $dbh;
    }
    
    
    
    public function listMic(){
        try{
            $sth = $this->mydb->prepare("SELECT microphones.*, users.username FROM microphones
                                LEFT JOIN users ON microphones.usrID=users.usrID
                                ORDER BY 'micID';" );
    
            $sth->execute();
            
            $result=$sth->fetchAll(PDO::FETCH_ASSOC);
    
    
       
        }
        catch (PDOException $e) {
            print $e->getMessage();
        }
        return $result;
    }
    
    public function listMicSession(){
        try{
            $sth = $this->mydb->prepare("SELECT backup.*, microphones.*, session.*, client.cliName, composer.cmpName, users.username 
                                    FROM backup
                                    INNER JOIN session ON backup.bakID = session.bakID
                                    INNER JOIN microphones ON backup.bakID=microphones.micSession
                                    INNER JOIN client ON session.cliID=client.cliID
                                    INNER JOIN composer ON session.cmpID =composer.cmpID
                                    INNER JOIN users ON microphones.usrID = users.usrID
                                    WHERE micSession IS NOT NULL
                                    GROUP BY microphones.micID
                                    ORDER BY microphones.micID ASC;" );
    
            $sth->execute();
            
            $result=$sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            print $e->getMessage();
        }
        return $result;
    }
    
    public function listMicStudio($studio){
        try{
            $sth = $this->mydb->prepare("SELECT backup.*, microphones.*, session.*, client.cliName, composer.cmpName, users.username 
                                    FROM backup
                                    INNER JOIN session ON backup.bakID = session.bakID
                                    INNER JOIN microphones ON backup.bakID=microphones.micSession
                                    INNER JOIN client ON session.cliID=client.cliID
                                    INNER JOIN composer ON session.cmpID =composer.cmpID
                                    INNER JOIN users ON microphones.usrID = users.usrID
                                    WHERE micSession IS NOT NULL AND stdID=:stdID
                                    GROUP BY microphones.micID
                                    ORDER BY microphones.micID ASC;" );
            
            $sth->bindParam(':stdID',$studio,PDO::PARAM_INT);
    
            $sth->execute();
            
            $result=$sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            print $e->getMessage();
        }
        return $result;
    }
    
    public function listMicRepair(){
        try{
            $sth = $this->mydb->prepare("SELECT microphones.*, users.username 
                                    FROM microphones
                                    INNER JOIN users ON microphones.usrID = users.usrID
                                    WHERE micRepair > 0
                                    GROUP BY microphones.micID
                                    ORDER BY microphones.micID ASC;" );
    
            $sth->execute();
            
            $result=$sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            print $e->getMessage();
        }
        return $result;
    }
    
    public function listMicFault($mic){
        try{
            $sth = $this->mydb->prepare("SELECT micFault.*, users.username, microphones.* 
                                    FROM micFault
                                    INNER JOIN users ON micFault.userID = users.usrID
                                    INNER JOIN microphones on micFault.micID = microphones.micID
                                    WHERE micFault.micID = :micID;
                                    ORDER BY micFault.faultDate DESC" );
    
            $sth->bindParam(':micID', $mic, PDO::PARAM_INT);
    
            $sth->execute();
    
            $result=$sth->fetchAll();
            
            $this->count=$sth->rowCount();
            
        }
        catch (PDOException $e) {
            print $e->getMessage();
        }
        return $result;
    }
    
    public function createMic($num, $make, $model){
        try{
            $sth=$this->mydb->prepare('INSERT INTO microphones (micID, micMake, micModel, micCupboard, micRepair) 
                                        VALUES (:micID, :micMake, :micModel, 1, 0);');
            
            $sth->bindParam(':micID', $num);
            $sth->bindParam(':micMake', $make);
            $sth->bindParam(':micModel', $model);
            
            $sth->execute();
        } 
        catch (PDOException $e) {
            print $e->getMessage();
        }
    }
    
    public function getMicByID($micID){
        try{
            $sth=$this->mydb->prepare('SELECT * FROM microphones WHERE micID=:mic;');
            
            $sth->bindParam(':mic', $micID, PDO::PARAM_INT);
            
            $sth->execute();
            
            $result=$sth->fetch(PDO::FETCH_ASSOC);
            
            $this->count=$sth->rowCount();
        }
        catch(PDOException $e){
            print $e->getMessage();
        }
        
        return $result;
    }
    
    
    
    public function getSessionMics($bakID){
        try{
            $sth =$this->mydb->prepare("SELECT * FROM microphones
                                        WHERE micSession = :bakID");
                           
            $sth->bindParam(':bakID', $bakID , PDO::PARAM_INT);
                           
            $sth->execute();
            
            $result=$sth->fetchAll(PDO::FETCH_ASSOC);
            
            $this->count=$sth->rowCount();
        }
        catch(PDOException $e){
            print $e->getMessage();
        }
        
        return $result;
    }
    
    public function updateFault($fault, $outcome, $faultID){
        try{
            $sth=$this->mydb->prepare("UPDATE micFault SET faultDesc = :fault, faultOutcome = :outcome WHERE faultID = :faultID;" );
    
            $sth->bindParam(':fault', $fault, PDO::PARAM_STR);
            $sth->bindParam(':outcome', $outcome, PDO::PARAM_STR);
            $sth->bindParam(':faultID', $faultID, PDO::PARAM_INT);
    
            $sth->execute();
        
        }
        catch (PDOException $e){
            print $e ->getMessage();

        }
    }
    
    public function checkMicFault($micID){
        try{
            $sth=$this->mydb->prepare("SELECT * FROM micFault WHERE micID = :micID AND faultOutcome IS NULL;" );
    
            $sth->bindParam(':micID', $micID, PDO::PARAM_INT);

            $sth->execute();
  
            $count=$sth->rowCount();
        
        }
        catch (PDOException $e){
            print $e ->getMessage();
        }
        
        return $count;
    }
    
    public function returnFaultMic($micID, $user, $logstate){
        
        $this->micID=$micID;
        $this->user=$user;
        $this->logstate=$logstate;
        
        $this->mydb->beginTransaction();
        
        try{
        
            $sth=$this->mydb->prepare("UPDATE microphones SET micRepair = 0, micCupboard =1 WHERE micID = :micID;" );

            $sth->bindParam(':micID', $micID, PDO::PARAM_INT);

            $sth->execute();
            
            $result=$this->getTransferSesID($micID);
            
            $this->logMic($this->micID, $this->user, $result['sesID'], $this->logstate);
            
            $this->mydb->commit();
        }
        catch(PDOException $e){
            $this->mydb->rollback();
            print $e->getMessage();   
        }
    }
    
    
    
    public function addMicSession($micID, $user, $bakID, $sesID){
        
        $this->mydb->beginTransaction();
        
        try{
            $sth = $this->mydb->prepare('UPDATE microphones
                                        SET micSession=:bakID, micCupboard=0, micTransfer=NULL, usrID=:user
                                        WHERE micID = :mic;');
    
            $sth->bindParam(':mic', $micID, PDO::PARAM_INT);
            $sth->bindParam(':bakID', $bakID, PDO::PARAM_INT);
            $sth->bindParam(':user', $user, PDO::PARAM_INT);
            $sth->execute();


            $row=$this->updateSessMic($micID, $bakID);
            
            $this->logMic($micID, $user, $sesID, 'session'); 
    
            $this->mydb->commit();
        }
        catch(PDOException $e){
            
            $this->mydb->rollback();
            print $e->getMessage();

        }
        return $row;
    }
    
    public function returnMicSession($micID, $user, $bakID, $sesID){
        $this->mydb->beginTransaction();
        
        try{
            $sth = $this->mydb->prepare('UPDATE microphones
                                        SET micSession=null, micCupboard=1, micTransfer=:bakID, usrID=:user
                                        WHERE micID = :mic;');
    
            $sth->bindParam(':mic', $micID, PDO::PARAM_INT);
            $sth->bindParam(':bakID', $bakID, PDO::PARAM_INT);
            $sth->bindParam(':user', $user, PDO::PARAM_INT);
            $sth->execute();
            
            $this->logMic($micID, $user, $sesID, 'cupboard');
        
            $this->mydb->commit();
        }
        catch(PDOException $e){
            $this->mydb->rollback();
            print $e->getMessage();
        }
    }
    
    public function transferMicSession($micID, $user, $bakID, $newBakID, $sesID){
        foreach($micID as $transferMic) { //for each of the checkbox values selected
        
            $this->mydb->beginTransaction();
        
            try{ //update the microphone table with the correct location details for each mic being transferred including setting transferid to previous backup id
                $sth = $this->mydb->prepare('UPDATE microphones 
                                            SET micSession=:newSes, micTransfer=:bakID, usrID=:user
                                            WHERE micID= :mic;');
                $sth->bindParam(':mic', $transferMic, PDO::PARAM_INT);
                $sth->bindParam(':newSes', $newBakID, PDO::PARAM_INT);
                $sth->bindParam(':bakID', $bakID, PDO::PARAM_INT);
                $sth->bindParam(':user', $user, PDO::PARAM_INT);
                $sth->execute();

                $this->updateSessMic($transferMic, $newBakID);
                
                $this->logMic($transferMic, $user, $sesID, 'transfer');


                $this->mydb->commit();
            }
            catch(PDOException $e){
                
                $this->mydb->rollback();
                print $e->getMessage();
            }
        }
        
    }
    
    public function transferMicWorkshop($micID, $user, $bakID, $fault, $sesID){
        foreach($micID as $repairMic) {
            
            $this->mydb->beginTransaction();
            
            try{                                                //update the microphone table with the correct location details for each mic being sent for repair including setting transferid to previous session id
                $sth = $this->mydb->prepare('UPDATE microphones 
                                            SET micSession=NULL, micRepair=1, micTransfer=:bakID, usrID=:user
                                            WHERE micID= :mic;');
                $sth->bindParam(':mic', $repairMic, PDO::PARAM_INT);
                $sth->bindParam(':bakID', $bakID, PDO::PARAM_INT);
                $sth->bindParam(':user', $user, PDO::PARAM_INT);
                $sth->execute();
        
        
                $sth = $this->mydb->prepare('INSERT INTO micFault (micID, userID, faultDesc)
                                    VALUES (:micID, :userID, :faultDesc);');
        
                $sth->bindParam(':micID', $repairMic, PDO::PARAM_INT);
                $sth->bindParam(':userID', $user, PDO::PARAM_INT);
                $sth->bindParam(':faultDesc', $fault, PDO::PARAM_INT);
                $sth->execute();

                $this->logMic($repairMic, $user, $sesID, 'workshop');
                
                $this->mydb->commit();
            }
            catch (PDOException $e){
                $this->mydb->rollback();
                print $e->getMessage();
            }
        }
    }
    
    private function updateSessMic($micID, $bakID ){
        $sth=$this->mydb->prepare('SELECT * FROM sessmics WHERE sessmicsID = :bakID;');
        
        $sth->bindParam(':bakID', $bakID, PDO::PARAM_INT);
        
        $sth->execute();
        
        $row=$sth->fetch(PDO::FETCH_ASSOC);
        
        $count=$sth->rowCount();
        
        if($count == 0){

            $micArray = array();

            $micArray[] = $micID;

            $mics = serialize($micArray);


            $sth = $this->mydb->prepare('INSERT INTO sessmics (sessmicsID, sessmicList) VALUES (:bakID, :mic);');

            $sth->bindParam(':bakID', $bakID, PDO::PARAM_INT);
            $sth->bindParam(':mic', $mics, PDO::PARAM_STR); 

            $sth->execute();

                

        } else{

            $micArray = unserialize($row['sessmicList']);

            $nextMic = $micID;

            if(in_array($nextMic, $micArray)){

            }else{

            $micArray[]=$nextMic;
            sort($micArray);
            }


            $mics= serialize($micArray);


            $sth = $this->mydb->prepare('UPDATE sessmics SET sessmicList=:mic WHERE sessmicsID = :bakID;');

            $sth->bindParam(':bakID', $bakID, PDO::PARAM_INT);
            $sth->bindParam(':mic', $mics, PDO::PARAM_STR);

            $sth->execute();
            
            

            }
            
        return $row;
    }
    
    private function getTransferSesID($micID){
        $st1=$this->mydb->prepare("SELECT sesID FROM session WHERE bakID = (SELECT micTransfer FROM microphones WHERE micID = :micID) ORDER BY sessDate ASC LIMIT 1;");
        $st1->bindParam(':micID',$micID, PDO::PARAM_INT);
        $st1->execute();
  
        $result = $st1->fetch(PDO::FETCH_ASSOC);
        
        return $result;
    }
    
    
    
    private function logMic($micID, $user, $sesID, $logState){
       

            $sth = $this->mydb->prepare('INSERT INTO micLog (micID, sesID, usrID, logState) VALUES(:micID, :sesID, :usrID, :logState);');

            $sth->bindParam(':micID', $micID, PDO::PARAM_INT);
            $sth->bindParam(':sesID', $sesID, PDO::PARAM_INT);
            $sth->bindParam(':usrID', $user, PDO::PARAM_INT);
            $sth->bindParam(':logState', $logState, PDO::PARAM_STR);

            $sth->execute();

    }
    
    public function getSessMic($bakID){
        try{
            $st1 = $this->mydb->prepare("SELECT * FROM sessmics
                          WHERE sessmicsID = :bakID;");
    
            $st1->bindParam(':bakID', $bakID, PDO::PARAM_INT);

            $st1->execute();


            $count1 = $st1->rowCount();

            $row1 = $st1->fetch(PDO::FETCH_ASSOC);
            if($count1 > 0){

                $micArray = array();

                $micArray = unserialize($row1['sessmicList']);

            }

        }catch(PDOException $e){
            print $e->getMessage();
        }
        if($count1 > 0){ ?> 
            
            <?php
                foreach($micArray as $micID){?>
                    <tr>
                        <?php    
                         $row=$this->getMicByID($micID);
                         
                         ?>
                        <td><?= $row['micID']?></td>
                        <td><?= $row['micMake']?></td>
                        <td><?= $row['micModel']?></td>
                    </tr>
      <?php
            }
        }
    }

    public function getMicHistory($micID){
        try{
            $sth = $this->mydb->prepare("SELECT micLog.*, session.*,engineer.engName, assistant.astName, client.cliName, composer.cmpName, project.prjName, users.username 
                FROM micLog
                INNER JOIN session ON micLog.sesID = session.sesID
                INNER JOIN users ON micLog.usrID = users.usrID
                INNER JOIN engineer ON session.engID = engineer.engID
                INNER JOIN assistant ON session.astID = assistant.astID
                INNER JOIN client ON session.cliID = client.cliID
                LEFT JOIN composer ON session.cmpID = composer.cmpID
                LEFT JOIN project ON session.prjID = project.prjID
                WHERE micID= :micID
                ORDER BY micLog.micLogTime DESC;");

            $sth->bindParam(':micID', $micID, PDO::PARAM_INT);

            $sth->execute();

            $result = $sth->fetchAll(PDO::FETCH_OBJ);

        }
        catch(PDOException $e){
            print $e->getMessage();
        }

        return $result;
        
    }
}

?>
