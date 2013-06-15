<?php

class checkRecord{
    
    public static function checkID($id, $name, $role, $conn){
        
        switch ($role)
        {
            case 'cli':
                $tbl='client';
                break;
            case 'ast':
                $tbl='assistant';
                break;
            case 'eng':
                $tbl='engineer';
                break;
            case 'prj':
                $tbl='project';
                break;
            case 'cmp':
                $tbl='composer';
                break;
            case 'fix':
                $tbl='fixer';
                break;
        }
        if(empty($name)){
        
            $id = 1; 
    
        
        }//if no ajax result found and user entered a name then insert name into relevant table
        
        elseif ($id==0 && !empty($name)) {
            
            $fh = $conn->prepare('SELECT '.$role.'ID FROM '.$tbl.' WHERE '.$role.'Name LIKE :name;');
            $fh->bindParam(':name', $name, PDO::PARAM_STR);
            
            $fh->execute();
            $result=$fh->fetch(PDO::FETCH_ASSOC);
            
            if (isset($result[$role.'ID'])){
                
                $id=$result[$role.'ID'];
                
            }else{
            
                $fh = $conn->prepare('INSERT INTO '.$tbl.' ('.$role.'Name) VALUES (:name)');
    
                $fh->bindParam(':name', $name, PDO::PARAM_STR);
                //Find the unique ID given to the record once inserted and set $..ID variable to insert into session
                $fh->execute();
            
                $id = $conn->lastInsertID($role.'ID');
            }
                
        }
        return $id;
    }  
}
?>
