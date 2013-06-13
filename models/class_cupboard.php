<?php

class cupboard{
    private $mydb; // database handler from pdoconnection.php
    private $result;
    public $count;
    private $cliID;
    private $cmpID;
    private $driveID;
    
    public function __construct($dbh) {
        $this->mydb = $dbh;
    }
    
    
    public function listDrives(){
        try{
            $res=$this->mydb->prepare('SELECT cupboardDrive.*, client.cliName, composer.cmpName
                                        FROM cupboardDrive
                                        LEFT JOIN driveOwnerCli ON (cupboardDrive.cupbID = driveOwnerCli.cupbID)
                                        LEFT JOIN driveOwnerCmp ON (cupboardDrive.cupbID = driveOwnerCmp.cupbID)
                                        LEFT JOIN client ON (driveOwnerCli.cliID=client.cliID)
                                        LEFT JOIN composer ON (driveOwnerCmp.cmpID=composer.cmpID)
                                        GROUP BY cupboardDrive.cupbID;');
    
            $res->execute();
            $this->result=$res->fetchALL(PDO::FETCH_ASSOC);
            $this->count=count($this->result);
            
            }
        catch (PDOException $e) {
            print $e->getMessage();
            }
        
            
            return $this->result;
    }
    
    public function getDrive($driveID){
        
        $this->driveID=$driveID;
        
        try{
            $d=$this->mydb->prepare('SELECT cupboardDrive.*, client.*, composer.*
                                    FROM cupboardDrive
                                    LEFT JOIN driveOwnerCli ON (cupboardDrive.cupbID = driveOwnerCli.cupbID)
                                    LEFT JOIN driveOwnerCmp ON (cupboardDrive.cupbID = driveOwnerCmp.cupbID)
                                    LEFT JOIN client ON (driveOwnerCli.cliID=client.cliID)
                                    LEFT JOIN composer ON (driveOwnerCmp.cmpID=composer.cmpID)
                                    WHERE cupboardDrive.cupbID = :cupbID');
    
            $d->bindParam(':cupbID', $this->driveID,PDO::PARAM_INT);
    
            $d->execute();
    
            $this->result = $d->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e){
            print $e->getMessage();
        }
        return $this->result;
    }
    
    public function getDriveNotes($driveID){
        
        $this->driveID=$driveID;
        
        try{
            $notes=$this->mydb->prepare('SELECT * FROM cupboardDriveNotes WHERE cupbID = :cupbID');
    
            $notes->bindParam(':cupbID', $this->driveID,PDO::PARAM_INT);
    
            $notes->execute();
    
            $noteResult = $notes->fetch(PDO::FETCH_ASSOC);
          
        }
        catch (PDOException $e) {
            print $e->getMessage();
        }  
        return $noteResult;
    }
    public function addDrive($postdata, $dbh){
        
        $this->mydb->beginTransaction();
        
        try{
            $sth = $this->mydb->prepare('INSERT INTO cupboardDrive (cupbName) VALUES (:driveName)');
       
            $sth->bindParam(':driveName', $postdata['driveNameInput'] , PDO::PARAM_STR);
       
            $sth->execute();
        
            $this->driveID = $this->mydb->lastInsertId('cupbID');
            
            $this->addDriveNotes($this->driveID, $postdata['driveNotes']);
            
            $this->cliID=client::checkClient($postdata, $dbh);
            
            $this->addDriveClient($this->driveID,$this->cliID);
            
            $this->cmpID=composer::checkComposer($postdata, $dbh);   
            
            $this->addDriveComposer($this->driveID,$this->cmpID);
            
            $this->mydb->commit();
            }
        catch (PDOException $e) {
        
            $this->mydb->rollback();
            print $e->getMessage();
            }
            
            return $driveID;
    }
    
    public function updateDrive($postdata, $dbh){
        
        $this->driveID=$postdata['driveID'];
        
        $this->mydb->beginTransaction();
        
        try{
            $st1=$this->mydb->prepare('UPDATE cupboardDrive SET cupbName=:name WHERE cupbID=:cupbID;');
    
            $st1->bindParam(':name', $postdata['driveNameInput'],PDO::PARAM_STR);
            $st1->bindParam(':cupbID',$this->driveID);
    
            $st1->execute();
            
            if(empty($postdata['noteID'])){
                $this->addDriveNotes($this->driveID, $postdata['driveNotes']);
            }else{
                $this->updateDriveNotes($this->driveID, $postdata['driveNotes']);
            }
            
            $this->cliID=client::checkClient($postdata, $dbh);
            
            $this->updateDriveClient($this->driveID,$this->cliID);
            
            $this->cmpID=composer::checkComposer($postdata, $dbh);   
            
            $this->updateDriveComposer($this->driveID,$this->cmpID);
            
            
            $this->mydb->commit();
        }
        catch (PDOException $e){
            $this->mydb->rollback();
            print $e->getMessage();
        }
        
        
        
    }
    
    public function addDriveNotes($driveID, $note){
        $notes = $this->mydb->prepare('INSERT INTO cupboardDriveNotes (cupbID, cupbNote) VALUES (:cupbID,:notes)');
       
        $notes->bindParam(':cupbID',$driveID, PDO::PARAM_INT);
        $notes->bindParam(':notes', $note, PDO::PARAM_STR);
       
        $notes->execute();
    }
   
    private function addDriveClient($driveID, $cliID){ 
            
       
        $st1=$this->mydb->prepare('INSERT INTO driveOwnerCli (cliID, cupbID) VALUES (:clientID, :driveID)');
    
        $st1->bindParam(':clientID', $cliID, PDO::PARAM_INT);
        $st1->bindParam(':driveID', $driveID, PDO::PARAM_INT);
    
        $st1->execute();
           
    
    }
    
    private function addDriveComposer($driveID, $cmpID){
        
    
        $st1=$this->mydb->prepare('INSERT INTO driveOwnerCmp (cmpID, cupbID) VALUES (:composerID, :driveID)');
    
        $st1->bindParam(':composerID', $cmpID, PDO::PARAM_INT);
        $st1->bindParam(':driveID', $driveID, PDO::PARAM_INT);
    
        $st1->execute();
        
    }
    
    public function updateDriveNotes($driveID, $note){
        $st1=$this->mydb->prepare('UPDATE cupboardDriveNotes SET cupbNote=:cupbNote WHERE cupbID=:cupbID;');
    
        $st1->bindParam(':cupbID',$driveID, PDO::PARAM_INT);
        $st1->bindParam(':cupbNote', $note ,PDO::PARAM_STR);
    
    
        $st1->execute();
    }
    
    private function updateDriveClient($driveID, $cliID){
        
        $st1=$this->mydb->prepare('UPDATE driveOwnerCli SET cliID=:clientID WHERE cupbID=:driveID;');
    
        $st1->bindParam(':clientID', $cliID, PDO::PARAM_INT);
        $st1->bindParam(':driveID', $driveID, PDO::PARAM_INT);
    
        $st1->execute();
        
        
    }
    
    private function updateDriveComposer($driveID, $cmpID){
        
        $st1=$this->mydb->prepare('UPDATE driveOwnerCmp SET cmpID=:composerID WHERE cupbID=:driveID;');
    
        $st1->bindParam(':composerID', $cmpID, PDO::PARAM_INT);
        $st1->bindParam(':driveID', $driveID, PDO::PARAM_INT);
    
        $st1->execute();
        
        
    }
    
    
    
}
?>
