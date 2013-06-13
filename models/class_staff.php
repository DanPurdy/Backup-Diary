<?php

class staff{
    
    private $mydb; // database handler from pdoconnection.php
    private $role; // selector from user - also used to order by ID in select statements
    private $tbl;  // table name from switch whitelist in __construct (determined by $role)
    private $result;
    
    private function __construct($dbh, $role) {
          $this->mydb = $dbh;                   //assign database handler to private variable mydb
          $this->role = $role;                  //assign user selected role to private variable role
          
          switch($this->role)                   //switch through role to get full table name
        {
            case 'ast':
                $this->tbl='assistant';
                break;
            case 'eng':
                $this->tbl='engineer';
                break;
            default:
                $this->tbl='engineer';
        }
          
        }
        
    public function listStaff(){  //method to do a raw pull of all entries in a table ordered by ID
        
        
            try{
            
               $stmt = $this->mydb->prepare('SELECT * FROM '.$this->tbl.' ORDER BY '.$this->role.'ID');
               $stmt->execute();
               
               $this->result=$stmt->fetchALL(PDO::FETCH_ASSOC);
              
            }
            catch (PDOException $e) {
        
            }
    
         return $this->result;
    }
    
    public function staffDetail($id){
        try{
            
               $stmt = $this->mydb->prepare('SELECT * FROM '.$this->tbl.' WHERE '.$this->role.'ID = :id');
               $stmt->bindParam(':id',$id, PDO::PARAM_INT);
               $stmt->execute();
               
               $this->result=$stmt->fetchALL(PDO::FETCH_ASSOC);
               $this->result=$this->result['0'];
              
            }
            catch (PDOException $e) {
        
            }
            
         return $this->result;
    }
    
    public function addStaff($postData){
        try{
            $stmt = $this->mydb->prepare('INSERT INTO '.$this->tbl.' ('.$this->role.'Name) VALUES (:name);');
            $stmt->bindParam(':name', $postData['name'], PDO::PARAM_STR);
            $stmt->execute();
        }
        catch (PDOException $e){
        
        }
    }
    public function updateStaff($postData){
        
        try{
            $stmt= $this->mydb->prepare('UPDATE '.$this->tbl.' SET '.$this->role.'Name = :name WHERE '.$this->role.'ID = :id');
            $stmt->bindParam(':name', $postData['name'],PDO::PARAM_STR);
            $stmt->bindParam(':id', $postData['id'],PDO::PARAM_INT);
            $stmt->execute();
            
            
        }
        catch (PDOException $e) {
        
        
        }
           
    }
  
}
?>
