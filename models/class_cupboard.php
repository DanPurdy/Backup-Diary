<?php

class cupboard{
    public $mydb; // database handler from pdoconnection.php
    private $result;
    public $count;
    public $cliID;
    
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
    
    
    public function addDrive($postdata, $dbh){
        
        $this->mydb->beginTransaction();
        
        try{
            $sth = $this->mydb->prepare('INSERT INTO cupboardDrive (cupbName) VALUES (:driveName)');
       
            $sth->bindParam(':driveName', $postdata['driveNameInput'] , PDO::PARAM_STR);
       
            $sth->execute();
        
            $driveID = $this->mydb->lastInsertId('cupbID');
       
            $notes = $this->mydb->prepare('INSERT INTO cupboardDriveNotes (cupbID, cupbNote) VALUES (:cupbID,:notes)');
       
            $notes->bindParam(':cupbID',$driveID, PDO::PARAM_INT);
            $notes->bindParam(':notes', $postdata['driveNotes'] , PDO::PARAM_STR);
       
            $notes->execute();
            
            
            
            $cliID=client::checkClient($postdata, $dbh);
            
            $this->addDriveClient($driveID,$cliID);
            
            $cmpID=$this->checkComposer($postdata, $dbh);   
            
            $this->addDriveComposer($driveID,$cmpID);
            
            $this->mydb->commit();
            }
        catch (PDOException $e) {
        
            $this->mydb->rollback();
            print $e->getMessage();
            }
            
            return $driveID;
    }    
                
    
    public function addDriveClient($driveID, $cliID){ 
            
       
            $st1=$this->mydb->prepare('INSERT INTO driveOwnerCli (cliID, cupbID) VALUES (:clientID, :driveID)');
    
            $st1->bindParam(':clientID', $cliID, PDO::PARAM_INT);
            $st1->bindParam(':driveID', $driveID, PDO::PARAM_INT);
    
            $st1->execute();
           
    
    }
    
    public function addDriveComposer($driveID, $cmpID){
        
    
        $st1=$this->mydb->prepare('INSERT INTO driveOwnerCmp (cmpID, cupbID) VALUES (:composerID, :driveID)');
    
        $st1->bindParam(':composerID', $cmpID, PDO::PARAM_INT);
        $st1->bindParam(':driveID', $driveID, PDO::PARAM_INT);
    
        $st1->execute();
        
    }
    
    
    
    
}
?>
