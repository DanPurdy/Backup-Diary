<?php

class backup{
    
    private $mydb;
    
    public function __construct($dbh) {
        $this->mydb = $dbh;
    }
    
    public function initBackup(){
        try{
            $st1 = $this->mydb->prepare('INSERT INTO backup (bakName) 
                                    VALUES (NULL);' );
    
            $st1->execute();
        
            $bakID = $this->mydb->lastInsertID('bakID');
            
        }
        catch (PDOException $e){
            print $e->getMessage();
        }
        
        return $bakID;
    }
    
    public function cleanBackup($id){
        
            $st1 = $this->mydb->prepare('SELECT sesID FROM session WHERE bakID =:bakID;');

            $st1->bindParam(':bakID', $id, PDO::PARAM_INT);

            $st1->execute();

            $count = $st1->rowCount();

            if($count == 0){
                $sth= $this->mydb->prepare('DELETE FROM backup WHERE bakID=:bakID;');
    
                $sth->bindParam(':bakID', $id, PDO::PARAM_INT);
    
                $sth->execute();
            }
            
             
    } 
}
?>
