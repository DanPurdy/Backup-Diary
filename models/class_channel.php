<?php

class channel{
    
    protected $mydb;
    
    public function __construct($dbh) {
        $this->mydb = $dbh;
    }
    
    public function listChannels($studio){
        try{
            $sth=$this->mydb->prepare('SELECT channels.*
                                        FROM channels
                                        WHERE stdID = :stdID AND ((channels.channelID)%100) !=0
                                        ORDER BY channels.currentPos ASC;');
            
            $sth->bindParam(':stdID', $studio, PDO::PARAM_INT);
            
            $sth->execute();
            
            $result=$sth->fetchAll(PDO::FETCH_ASSOC);
            
        }
        catch(PDOException $e){
            print $e->getMessage();
        }
    
        return $result;
    
    }
    
    public function listActiveChanFaults($studio){
        try{
            $sth = $this->mydb->prepare("SELECT channels.*, chanFault.*
                                    FROM channels
                                    INNER JOIN chanFault ON channels.channelID=chanFault.channelID
                                    WHERE stdID = :stdID AND ((channels.channelID)%100) !=0 AND ISNULL(chanFault.faultOutcome)
                                    ORDER BY channels.currentPos ASC;" );
    
            $sth->bindParam(':stdID', $_GET['studio']);

            $sth->execute();
            
            $result=$sth->fetchAll(PDO::FETCH_ASSOC);
            
            
        
        }
        catch (PDOException $e) {
            print $e->getMessage();
        }
        
        return $result;
    }
    
    public function listDetailChanFault($channel){
        try{
            $sth = $this->mydb->prepare("SELECT chanFault.*, users.username, channels.*
                                    FROM chanFault
                                    INNER JOIN users ON chanFault.userID = users.usrID
                                    INNER JOIN channels on chanFault.channelID = channels.channelID
                                    WHERE chanFault.channelID = :chID;
                                    ORDER BY chanFault.faultDate DESC" );
    
            $sth->bindParam(':chID', $_GET['chID'], PDO::PARAM_INT);
            
            $sth->execute();
            
            $result=$sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
            print $e->getMessage();
        }
        return $result;
    }
    
    public function addChanFault($channel, $studio, $fault, $user){
        try{
            $sth=$this->mydb->prepare('SELECT channelID FROM channels WHERE currentPos = :currentCh AND stdID = :stdID;');
            $sth->bindParam(':currentCh', $channel, PDO::PARAM_INT);
            $sth->bindParam(':stdID', $studio, PDO::PARAM_INT);
            $sth->execute();
            
            $result=$sth->fetchAll();
            
            $chanID = $result[0]['channelID'];
            
            $st1=$this->mydb->prepare('INSERT INTO chanFault (channelID, channelPos, faultDesc, userID) VALUES (:channelID, :channelPos, :faultDesc, :userID);');
            $st1->bindParam(':channelID', $chanID, PDO::PARAM_INT);
            $st1->bindParam(':channelPos', $channel, PDO::PARAM_INT);
            $st1->bindParam(':faultDesc', $fault, PDO::PARAM_STR);
            $st1->bindParam(':userID', $user);
            
            $st1->execute();
        }
        catch (PDOException $e){
            print $e->getMessage();
        }
    }
    
    public function updateChanFault($postdata){
        
        $fault=$postdata['faultID'];
     
     if (isset($postdata['faultID'])&& !empty($postdata['solution'])) {
     
     
     try{
    $sth=$this->mydb->prepare("UPDATE chanFault SET faultDesc = :fault, faultOutcome = :outcome WHERE faultID = :faultID;" );
    
    $sth->bindParam(':fault', $postdata['fault'], PDO::PARAM_STR);
    $sth->bindParam(':outcome', $postdata['solution'], PDO::PARAM_STR);
    $sth->bindParam(':faultID', $fault, PDO::PARAM_INT);
    
  $sth->execute();
        
}catch (PDOException $e){
    print $e ->getMessage();

 }
 }else{
    if (isset($postdata['faultID'])) {
     try{
    $sth=$this->mydb->prepare("UPDATE chanFault SET faultDesc = :fault WHERE faultID = :faultID;" );
    
    $sth->bindParam(':fault', $postdata['fault'], PDO::PARAM_STR);
    $sth->bindParam(':faultID', $fault, PDO::PARAM_INT);
    
  $sth->execute();
        
}catch (PDOException $e){
    print $e ->getMessage();

  
        }
    }
    
 }
    }
    
    public function swapChannels($chanOne, $chanTwo, $studio){
        
        $i=0;
        $array = array();
        $resultArray = array();
        
        $array[] = $chanOne;
        $array[] = $chanTwo;
        $this->mydb->beginTransaction();
        
        try{
            foreach($array as $value){
        
                $sth=$this->mydb->prepare('SELECT channelID FROM channels WHERE currentPos = :currentCh AND stdID = :stdID;');
                $sth->bindParam(':currentCh', $value, PDO::PARAM_INT);
                $sth->bindParam(':stdID', $studio, PDO::PARAM_INT);
                $sth->execute();

                $result=$sth->fetchAll();

                $resultArray[$i] = $result[0]['channelID'];

                $i++;
    
            }
            
            $j=1;
            
            foreach($resultArray as $id){
   
                $sth=$this->mydb->prepare('UPDATE channels SET currentPos = :pos WHERE channelID = :channelID');
                $sth->bindParam(':pos', $array[$j], PDO::PARAM_INT);
                $sth->bindParam(':channelID', $id, PDO::PARAM_INT);

                $sth->execute();

                $j--;
            
            }
            
            $this->mydb->commit();
        }
        catch(PDOException $e){
            $this->mydb->rollback();
            print $e->getMessage();
    
        }
    }
    
}
?>
