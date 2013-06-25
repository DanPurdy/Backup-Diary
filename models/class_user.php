<?php

class user{
    
    var $mydb;
    
    public function __construct($dbh) {
        $this->mydb=$dbh;
    }
    
    public function memberList(){
        try{
            $sth=$this->mydb->prepare('SELECT usrID, username, email, usrGroup FROM users');
            
            $sth->execute();
            
            $result=$sth->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e){
        
        die("Failed to run query: " . $e->getMessage());
    }
    return $result;
    }
    
    public function getUserByID($id){
        try{
            $sth=$this->mydb->prepare('SELECT * FROM users WHERE usrID=:id');
            
            $sth->bindParam(':id',$id,PDO::PARAM_INT);
            $sth->execute();
            
            $result=$sth->fetch(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e){
        die("Incorrect ID:" .$e->getMessage());
    }
    return $result;
    }
    
    public function updateEmail($email, $id){
        try{
            
    
            $unique=$this->checkEmail($email);
            
            if($unique == 0){
                $sth=$this->mydb->prepare('UPDATE users SET email=:email WHERE usrID=:usrID');
                $sth->bindParam(':email',$email,PDO::PARAM_STR);
                $sth->bindParam(':usrID',$id, PDO::PARAM_INT);
                
                $sth->execute();
                
                $ok=1;
            }else{
               
                die('Email Address already in Use!');
            }
            
            
        }
        catch(PDOException $e){
            print $e->getMessage();
        }
        
        return $ok;
    }
    
    private function checkEmail($email){
       
            $sth=$this->mydb->prepare('SELECT 1 FROM users WHERE email = :email');
            
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            
            $sth->execute();
            
            $count=$sth->rowCount();
            
            return $count;
            
             
    }
    
    public function updatePassword($pass, $id){
        
        if(!empty($pass)) 
        { 
            $salt = $this->generateSalt(); 
           
            $password = $this->hashPassword($salt, $pass);
        }
        else
        {
            $password=null;
            $salt=null;
        }
        
        
        if($password !=null)
        {
            try{  

                $sth=$this->mydb->prepare('UPDATE users SET password=:pass, salt=:salt WHERE usrID=:usrID');
                $sth->bindParam(':pass',$password);
                $sth->bindParam(':salt',$salt);
                $sth->bindParam(':usrID',$id, PDO::PARAM_INT);

                $sth->execute();

            }
            catch(PDOException $e){
                die('failed:'.$e->getMessage());
            }
        }
        
        
    }
    
    private function generateSalt(){
        
        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
        
        return $salt;
    }
    
    private function hashPassword($salt, $pass){
        
        $password = hash('sha256', $pass . $salt);
        
        for($round = 0; $round < 65536; $round++) 
            { 
                $password = hash('sha256', $password . $salt); 
            }
        
        return $password;
    }
    
    public function updateUsername($name, $id){
        
        
        $unique=$this->checkUserName($name);
        
        if($unique == 0)
        {
            try{  

                    $sth=$this->mydb->prepare('UPDATE users SET username=:name WHERE usrID=:usrID');
                    $sth->bindParam(':name',$name);
                    $sth->bindParam(':usrID',$id, PDO::PARAM_INT);

                    $sth->execute();

                }
                catch(PDOException $e){
                    die('failed:'.$e->getMessage());
                    
                    
                }
        }
        else
        {
            return false;
        }
    }
    private function checkUserName($name){
       
            $sth=$this->mydb->prepare('SELECT 1 FROM users WHERE username = :name');
            
            $sth->bindParam(':name', $name, PDO::PARAM_STR);
            
            $sth->execute();
            
            $count=$sth->rowCount();
            
            return $count;
            
             
    }
    
    public function updateUserGroup($grp, $id){
        try{  

            $sth=$this->mydb->prepare('UPDATE users SET usrGroup=:grp WHERE usrID=:usrID');
            $sth->bindParam(':grp',$grp);
            $sth->bindParam(':usrID',$id, PDO::PARAM_INT);

            $sth->execute();

        }
        catch(PDOException $e){
            print $e->getMessage();
        }
    }
    
    public function addUser($name, $email, $pass, $role){
        
        $uniqueName=$this->checkUserName($name);
        $uniqueMail=$this->checkEmail($email);
        
        $salt=$this->generateSalt();
        $password=$this->hashPassword($salt, $pass);
        
        if($uniqueName == 0 && $uniqueMail==0)
        {
            try{
                $sth=$this->mydb->prepare('INSERT INTO users (userName, password, salt, email, usrGroup) VALUES (:name, :pw, :salt, :mail, :role);');
                $sth->bindParam(':name',$name);
                $sth->bindParam(':pw',$password);
                $sth->bindParam(':salt',$salt);
                $sth->bindParam(':mail',$email);
                $sth->bindParam(':role',$role);
                
                $sth->execute();
            }
            catch(PDOException $e){
                print $e->getMessage();
            }
        }
        else{
            die('Username or Email is already in Use'); //temporary error handling
        }
        
    }
}
?>
