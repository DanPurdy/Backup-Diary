<?php

class driveSess{
    
    private $mydb;
    private $cliID;
    private $cmpID;
    private $legacyID;
    
    public function __construct($dbh) {
        $this->mydb = $dbh;
    }
    
    public function listLegacySess($driveID){
        try{
            $sth=$this->mydb->prepare('SELECT * FROM legacySess
                                        LEFT JOIN client ON (legacySess.cliID=client.cliID)
                                        LEFT JOIN composer ON (legacySess.cmpID=composer.cmpID)
                                        INNER JOIN legacyDriveSess ON (legacySess.legacyID=legacyDriveSess.legacyID) 
                                        WHERE cupbID = :drive AND legacySess.deleted=0;');
    
            $sth->bindParam(':drive',$driveID,PDO::PARAM_INT);
    
            $sth->execute();
            
            $res=$sth->fetchALL(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e){
            print $e->getMessage();
        }
        return $res;
    }
    
    public function listBackupSess($driveID){
        try{
            $sth=$this->mydb->prepare('SELECT * FROM backup
                                        LEFT JOIN driveContent ON driveContent.bakID = backup.bakID
                                        LEFT JOIN session ON session.bakID = backup.bakID
                                        LEFT JOIN client ON client.cliID = session.cliID
                                        LEFT JOIN composer ON composer.cmpID = session.cmpID
                                        WHERE driveContent.cupbID = :drive AND driveContent.deleted=0');
    
            $sth->bindParam(':drive',$driveID,PDO::PARAM_INT);
    
            $sth->execute();
            
            $bak=$sth->fetchALL(PDO::FETCH_ASSOC);
    
            
        }
        catch (PDOException $e) {
            print $e->getMessage();
        }
        return $bak;
    }
    
    public function getLegacySess($sessID){
        try{
            $sth=$this->mydb->prepare('SELECT * FROM legacySess
                                        LEFT JOIN client ON (legacySess.cliID=client.cliID)
                                        LEFT JOIN composer ON (legacySess.cmpID=composer.cmpID)
                                        INNER JOIN legacyDriveSess ON (legacySess.legacyID=legacyDriveSess.legacyID) 
                                        WHERE legacySess.legacyID=:sessID');
    
            $sth->bindParam(':sessID',$sessID,PDO::PARAM_INT);
    
            $sth->execute();
    
            $result=$sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            print $e->getMessage();
        } 
       return $result; 
    }
    
    
    public function addLegacySess($postdata, $dbh){
        
        $this->mydb->beginTransaction();
        try{
            
            $date=date("Y-m-d", strtotime($postdata['bakDate']));
        
            $this->cliID=client::checkClient($postdata, $dbh);
        
            $this->cmpID=composer::checkComposer($postdata, $dbh);  
        
        
            $st1=$this->mydb->prepare('INSERT INTO legacySess (legacyName,bakDate,cliID,cmpID) VALUES (:name, :date, :clientID, :composerID);');
    
            $st1->bindParam(':name', $postdata['driveNameInput'],PDO::PARAM_STR);
            $st1->bindParam(':date',$date);
            $st1->bindParam(':clientID', $this->cliID, PDO::PARAM_INT);
            $st1->bindParam(':composerID', $this->cmpID, PDO::PARAM_INT);
    
            $st1->execute();
    
            $this->legacyID=$this->mydb->lastInsertID('legacyID');
            
            $st1=$this->mydb->prepare('INSERT INTO legacyDriveSess (legacyID,cupbID) VALUES (:legacyID, :cupbID);');
    
            $st1->bindParam(':legacyID',$this->legacyID, PDO::PARAM_INT);
            $st1->bindParam(':cupbID', $postdata['driveID'], PDO::PARAM_INT);
    
            $st1->execute();
            
            $this->mydb->commit();
        }
        catch (PDOException $e) {
        
            $this->mydb->rollback();
            print $e->getMessage();
        }
    }
    
    public function updateLegacySess($postdata, $dbh){
        
        $this->mydb->beginTransaction();
        
        try{
            
            $date=date("Y-m-d", strtotime($postdata['bakDate']));
        
            $this->cliID=client::checkClient($postdata, $dbh);
        
            $this->cmpID=composer::checkComposer($postdata, $dbh); 
            
            $st1=$this->mydb->prepare('UPDATE legacySess 
                                        SET legacyName=:name, bakDate=:date, cliID=:clientID, cmpID=:composerID
                                        WHERE legacyID=:legacyID;');
    
            $st1->bindParam(':name', $postdata['driveNameInput'],PDO::PARAM_STR);
            $st1->bindParam(':date',$date);
            $st1->bindParam(':clientID', $this->cliID, PDO::PARAM_INT);
            $st1->bindParam(':composerID', $this->cmpID, PDO::PARAM_INT);
            $st1->bindParam(':legacyID', $postdata['sessID'], PDO::PARAM_INT);
    
            $st1->execute();
            
            $this->mydb->commit();
        }
        catch (PDOException $e) {
            $this->mydb->rollback();
            print $e->getMessage();
        }
    }
    
}
?>
