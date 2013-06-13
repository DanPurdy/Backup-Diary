<?php

class composer{
    public static function checkComposer($postdata, $conn){
         
        $cmpID =    $postdata['composerID'];
        
        if(empty($postdata['compN'])){
        
            $cmpID = 1; 
    
        }
        elseif ($cmpID==0 && !empty($postdata['compN'])) {
        
       
            $fh = $conn->prepare('INSERT INTO composer (cmpName) VALUES (:name)');
    
            $fh->bindParam(':name', $postdata['compN'], PDO::PARAM_STR);
            $fh->execute();
            $cmpID = $conn->lastInsertID('cmpID');
        }
        return $cmpID;
    }
}
?>
