<?php

class backup{
    
    private $mydb;
    
    public function __construct($dbh) {
        $this->mydb = $dbh;
    }
    
    public function initBackup(){
        try{
            $sth = $this->mydb->prepare('INSERT INTO backup (bakName) 
                                    VALUES (NULL);' );
    
            $sth->execute();
        
            $bakID = $this->mydb->lastInsertID('bakID');
            
        }
        catch (PDOException $e){
            print $e->getMessage();
        }
        
        return $bakID;
    }
    
    public function cleanBackup($id){
        
            $sth = $this->mydb->prepare('SELECT sesID FROM session WHERE bakID =:bakID;');

            $sth->bindParam(':bakID', $id, PDO::PARAM_INT);

            $sth->execute();

            $count = $sth->rowCount();

            if($count == 0){
                $sth= $this->mydb->prepare('DELETE FROM backup WHERE bakID=:bakID;');
    
                $sth->bindParam(':bakID', $id, PDO::PARAM_INT);
    
                $sth->execute();
            }
            
             
    }
    
    public function setBackupDeleted($bakID){
        try {
            $sth=$this->mydb->prepare("UPDATE backup SET bakDeleted=1 WHERE bakID = :bakID;" );
    
            $sth->bindParam(':bakID', $bakID);

            $sth->execute();
  
  
  
        }
        catch (PDOException $e) {
            print $e->getMessage();
        }
    }
    
    public function getCurrentBackup($stdID){
        try{
            $sth = $this->mydb->prepare("SELECT session.sesID, session.stdID, session.sessDate, session.startTime, session.endTime,session.ssNo, studio.stdName, engineer.engName, assistant.astName, client.cliName, composer.cmpName, fixer.fixName, project.prjName, session.bakID,backup.*
                            FROM session
                            INNER JOIN studio ON session.stdID=studio.stdID
                            INNER JOIN engineer ON session.engID=engineer.engID
                            INNER JOIN assistant ON session.astID=assistant.astID
                            INNER JOIN client ON session.cliID=client.cliID
                            INNER JOIN project ON session.prjID=project.prjID
                            INNER JOIN composer ON session.cmpID=composer.cmpID
                            INNER JOIN fixer ON session.fixID=fixer.fixID
                            INNER JOIN backup ON session.bakID=backup.bakID
                            WHERE (sessDate = CURRENT_DATE OR DATE_ADD(sessDate, INTERVAL 1 DAY)=CURRENT_DATE OR DATE_ADD(sessDate, INTERVAL -1 DAY)=CURRENT_DATE) AND session.stdID=:stdID
                            ORDER BY session.sessDate, session.startTime;" );
            $sth->bindParam(':stdID', $stdID);
    
            $sth->execute();
            
            $result=$sth->fetchAll(PDO::FETCH_ASSOC);
    
        }
        catch (PDOException $e) {
            print $e->getMessage();
        }
        return $result;
    }
    
    public function insertBackup($postdata){
        
        $name=$postdata['backName'];
        $loc=$postdata['bakLoc'];
        $date = date("Y-m-d H:i:s", time());
        $fullcopy = (isset($postdata['fullcopy'])) ? 1 : 0;
        $bakCupboard = (isset($postdata['bakCupboard'])) ? 1 : 0;
        $bakKeep =(isset($postdata['keep'])) ? 1 : 0;
        $notes=$postdata['bakNotes'];
        $bakMovOpt = $postdata['bakMov'];
        $bakID = $postdata['bakID'];
        $deleted = (isset($_POST['deleted'])) ? 1 : 0;

        if($bakCupboard == 1){
            $cupbID = $postdata['cupbDrive'];
        }

        if($bakMovOpt != 0){
            $bakMov = $bakMovOpt;
        }
        
        $this->mydb->beginTransaction();

        try{

            if((!empty($postdata['bakID'])) && ($postdata['editBool'] == 0)){
                $sth = $this->mydb->prepare('UPDATE backup SET bakName=:bakName, bakLoc=:bakLoc, fullCopy=:fullCopy, bakCupboard=:bakCupboard, bakKeep=:bakKeep, bakNotes=:bakNotes, bakMov=:bakMov, bakDate=:bakDate, bakDeleted=:bakDeleted
                                  WHERE bakID=:bakID;' );


                $sth->bindParam(':bakName', $name);
                $sth->bindParam(':bakLoc', $loc , PDO::PARAM_INT);
                $sth->bindParam(':bakDate', $date);
                $sth->bindParam(':fullCopy', $fullcopy , PDO::PARAM_INT);
                $sth->bindParam(':bakCupboard', $bakCupboard , PDO::PARAM_INT);
                $sth->bindParam(':bakKeep', $bakKeep , PDO::PARAM_INT);
                $sth->bindParam(':bakNotes', $notes , PDO::PARAM_STR);
                $sth->bindParam(':bakMov', $bakMov , PDO::PARAM_INT);
                $sth->bindParam(':bakID', $bakID , PDO::PARAM_INT);
                $sth->bindParam(':bakDeleted', $deleted , PDO::PARAM_INT);

                $sth->execute();
            }
            elseif((!empty($postdata['bakID'])) && ($postdata['editBool'] == 1)){

                $sth = $this->mydb->prepare('UPDATE backup SET bakName=:bakName, bakLoc=:bakLoc, fullCopy=:fullCopy, bakCupboard=:bakCupboard, bakKeep=:bakKeep, bakNotes=:bakNotes, bakMov=:bakMov, bakDeleted=:bakDeleted
                                    WHERE bakID=:bakID;' );


                $sth->bindParam(':bakName', $_POST['backName']);
                $sth->bindParam(':bakLoc', $_POST['bakLoc'] , PDO::PARAM_INT);
                $sth->bindParam(':fullCopy', $fullcopy , PDO::PARAM_INT);
                $sth->bindParam(':bakCupboard', $bakCupboard , PDO::PARAM_INT);
                $sth->bindParam(':bakKeep', $bakKeep , PDO::PARAM_INT);
                $sth->bindParam(':bakNotes', $_POST['bakNotes'] , PDO::PARAM_STR);
                $sth->bindParam(':bakMov', $bakMov , PDO::PARAM_INT);
                $sth->bindParam(':bakID', $bakID , PDO::PARAM_INT);
                $sth->bindParam(':bakDeleted', $deleted , PDO::PARAM_INT);

                $sth->execute();    

            }


            $this->manageDriveBackup($postdata['newDrive'], $postdata['cliID'], $postdata['cmpID'], $bakID, $cupbID, $bakCupboard);

            $this->mydb->commit();
        }
        catch(PDOException $e){

            $this->mydb->rollback();

            print $e->getMessage();
        }
    }
    
    private function manageDriveBackup($driveName, $cliID, $cmpID, $bakID, $cupbID, $bakCupboard){
        if($bakCupboard == 1){
            
            $sth=$this->mydb->prepare('SELECT cupbID FROM driveContent WHERE bakID=:bakID;');
            $sth->bindParam(':bakID',$bakID, PDO::PARAM_INT);
            $sth->execute();
    
            $testCount = $sth->rowCount();
            
            if($cupbID>=1 || $cupbID ==='new'){
                if($cupbID==='new'){
                    $sth=$this->mydb->prepare('INSERT INTO cupboardDrive (cupbName) VALUES (:name);');
                    $sth->bindParam(':name',$driveName, PDO::PARAM_STR);
                    $sth->execute();

                    $cupbID = $this->mydb->lastInsertID('cupbID');

                    $sth=$this->mydb->prepare('INSERT INTO driveOwnerCli (cliID,cupbID) VALUES (:clientID,:driveID);');
                    $sth->bindParam(':clientID',$cliID, PDO::PARAM_INT);
                    $sth->bindParam(':driveID',$cupbID, PDO::PARAM_INT);
                    $sth->execute();

                    $sth=$this->mydb->prepare('INSERT INTO driveOwnerCmp (cmpID,cupbID) VALUES (:composerID,:driveID);');
                    $sth->bindParam(':composerID',$cmpID, PDO::PARAM_INT);
                    $sth->bindParam(':driveID',$cupbID, PDO::PARAM_INT);
                    $sth->execute();

                }
                if($testCount){

                    $sth = $this->mydb->prepare('UPDATE driveContent SET bakID = :bakID, cupbID=:cupbID WHERE bakID=:bakID;');
                    $sth->bindParam(':bakID', $bakID, PDO::PARAM_INT);
                    $sth->bindParam(':cupbID', $cupbID, PDO::PARAM_INT);

                    $sth->execute();

                }else{
                    $sth = $this->mydb->prepare('INSERT INTO driveContent (bakID, cupbID) VALUES (:bakID, :cupbID);');
                    $sth->bindParam(':bakID', $bakID, PDO::PARAM_INT);
                    $sth->bindParam(':cupbID', $cupbID, PDO::PARAM_INT);

                    $sth->execute();
                }
            }
        }
    }
    
    public function getDelDrive($bakLoc){
    
        try{
            $sth=$this->mydb->prepare("SELECT backup.bakLoc, client.cliName, project.prjName, assistant.astName, session.sesID, session.sessDate, session.stdID, backup.bakID, backup.bakName, backup.bakDate, backup.bakKeep, backup.bakDeleted
                                FROM session
                                INNER JOIN studio ON session.stdID=studio.stdID
                                INNER JOIN engineer ON session.engID=engineer.engID
                                INNER JOIN assistant ON session.astID=assistant.astID
                                INNER JOIN client ON session.cliID=client.cliID
                                INNER JOIN project ON session.prjID=project.prjID
                                INNER JOIN composer ON session.cmpID=composer.cmpID
                                INNER JOIN fixer ON session.fixID=fixer.fixID
                                LEFT JOIN backup ON session.bakID=backup.bakID
                                WHERE session.stdID = :stdID AND sessDate < DATE_SUB(CURDATE(), INTERVAL 3 MONTH) AND (ISNULL(backup.bakDeleted) OR backup.bakDeleted =0) AND bakLoc= :bakLoc 
                                ORDER BY session.stdID,backup.bakLoc,sessDate; ");

            $sth->bindParam(':stdID', $_GET['studio'], PDO::PARAM_INT);
            $sth->bindParam(':bakLoc', $bakLoc, PDO::PARAM_INT);
            $sth->execute();
        
            
        }
        catch(PDOException $e) {
            print $e->getMessage();
        }

        while($row=$sth->fetch(PDO::FETCH_ASSOC)){?>

            <tr <?php if($row['bakKeep'] == 1){ echo 'class="bakKeep"';} ?>>
                <td><?=$row['bakName'];?></td>
                <td class="link"><a href="edit_backup.php?sesID=<?php echo $row['sesID'];?>">View/Edit Backup</a></td>
                <td class="link"><a href="set_delete.php?backupID=<?php echo $row['bakID'];?>">Set Deleted</a></td>
            </tr>

        <?php  }
    }
    
    public function getNoRecord(){
        try{
            $sth=$this->mydb->prepare("SELECT client.cliName, project.prjName, assistant.astName,session.sesID, session.ssNo, session.sessDate, session.stdID, backup.*
                                FROM session
                                INNER JOIN studio ON session.stdID=studio.stdID
                                INNER JOIN engineer ON session.engID=engineer.engID
                                INNER JOIN assistant ON session.astID=assistant.astID
                                INNER JOIN client ON session.cliID=client.cliID
                                INNER JOIN project ON session.prjID=project.prjID
                                INNER JOIN composer ON session.cmpID=composer.cmpID
                                INNER JOIN fixer ON session.fixID=fixer.fixID
                                LEFT JOIN backup ON session.bakID=backup.bakID
                                WHERE session.stdID = :stdID AND (backup.bakLastDate <= sessDate OR ISNULL(backup.bakName)) AND sessDate < CURRENT_DATE()
                                ORDER BY session.stdID,sessDate DESC; ");

            $sth->bindParam(':stdID', $_GET['studio'], PDO::PARAM_INT);

            $sth->execute();
     
     
    
        }
        catch(PDOException $e) {
            print $e->getMessage();
        }

  
        while($row=$sth->fetch(PDO::FETCH_ASSOC)){?>
            <tr>
              <td><?echo date('d-m-Y',strtotime($row['sessDate']));?></td>
                     <td> <?= $row['cliName'];?></td>
                      <td><?= $row['prjName'];?></td>
                      <td><?= $row['astName'];?></td>
                      <td><?= $row['ssNo'];?></td>

            <td class="link"><?php if(empty($row['bakDate'])){ ?><a href="new_backup.php?sesID=<?php echo $row['sesID'];?>">Add new backup entry</a><?php }else{?><a href="edit_backup.php?sesID=<?php echo $row['sesID'];?>">View/Edit Backup</a></td><?php } ?>
            </tr>
          <?php 
        }
    
    }
    
    public function getBakDrive($bakLoc){
   
        try{
            $sth=$this->mydb->prepare("SELECT backup.bakLoc, client.cliName, project.prjName, assistant.astName, session.sesID, session.sessDate, session.stdID, backup.bakID, backup.bakName, backup.bakDate, backup.bakKeep, backup.bakDeleted
                                FROM session
                                INNER JOIN studio ON session.stdID=studio.stdID
                                INNER JOIN engineer ON session.engID=engineer.engID
                                INNER JOIN assistant ON session.astID=assistant.astID
                                INNER JOIN client ON session.cliID=client.cliID
                                INNER JOIN project ON session.prjID=project.prjID
                                INNER JOIN composer ON session.cmpID=composer.cmpID
                                INNER JOIN fixer ON session.fixID=fixer.fixID
                                LEFT JOIN backup ON session.bakID=backup.bakID
                                WHERE session.stdID = :stdID AND (ISNULL(backup.bakDeleted) OR backup.bakDeleted =0) AND bakLoc= :bakLoc
                                GROUP BY backup.bakID
                                ORDER BY backup.bakLoc,sessDate DESC; ");

            $sth->bindParam(':stdID', $_GET['studio'], PDO::PARAM_INT);
            $sth->bindParam(':bakLoc', $bakLoc, PDO::PARAM_INT);

            $sth->execute();



        }
        catch(PDOException $e) {
            print $e->getMessage();
        }

        while($row=$sth->fetch(PDO::FETCH_ASSOC)){?>
            <tr>
                <td><?=$row['bakName'];?></td>
                <td class="link"><a href="edit_backup.php?sesID=<?php echo $row['sesID'];?>">View/Edit Backup</a></td>
            </tr>
<?php  }
    }
    
    public function backupSearch($name){
        try{
            $sth=$this->mydb->prepare("SELECT client.cliName,composer.cmpName, project.prjName, assistant.astName,engineer.engName, session.sesID, session.ssNo, session.sessDate, session.stdID,bakdrive.bkdName, backup.*
                                FROM session
                                INNER JOIN studio ON session.stdID=studio.stdID
                                INNER JOIN engineer ON session.engID=engineer.engID
                                INNER JOIN assistant ON session.astID=assistant.astID
                                INNER JOIN client ON session.cliID=client.cliID
                                INNER JOIN project ON session.prjID=project.prjID
                                INNER JOIN composer ON session.cmpID=composer.cmpID
                                INNER JOIN fixer ON session.fixID=fixer.fixID
                                LEFT JOIN backup ON session.bakID=backup.bakID
                                LEFT JOIN bakdrive ON backup.bakLoc=bakdrive.bkdID
                                WHERE client.cliName LIKE :cliName OR composer.cmpName LIKE :cliName
                                ORDER BY session.sessDate DESC, backup.bakDeleted; ");

            $sth->bindParam(':cliName', $name, PDO::PARAM_STR);
            
            $sth->execute();
            
            $result=$sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) {
            print $e->getMessage();
        }
        
        return $result;
    
    }
}
?>
