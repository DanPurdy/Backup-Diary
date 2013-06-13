<?php

class client{
    
    
    public static function checkClient($postdata, $conn){
        
        $cliID = $postdata['clientID'];
        
        if(empty($postdata['cliN'])){
        
            $cliID = 1; 
    
        
        }//if no ajax result found and user entered a name then insert name into relevant table
        
        elseif ($cliID==0 && !empty($postdata['cliN'])) {
            
                $fh = $conn->prepare('INSERT INTO client (cliName) VALUES (:name)');
    
                $fh->bindParam(':name', $postdata['cliN'], PDO::PARAM_STR);
                //Find the unique ID given to the record once inserted and set $..ID variable to insert into session
                $fh->execute();
            
                $cliID = $conn->lastInsertID('cliID');
                
        }
        return $cliID;
    }  
}
?>
