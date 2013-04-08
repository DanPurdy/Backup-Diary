<?php
require('includes/pdoconnection.php');
    $dbh = dbConn::getConnection();

if($_POST){
  try{
    $sth = $dbh->prepare("UPDATE session SET ssNo = :ssNo WHERE sesID = :sesID;");
    
    $sth->bindParam(":ssNo", $_POST['sesNo']);
    $sth->bindParam(":sesID", $_POST['sesID']);
    
    $sth->execute();
    
    
    
       
}
catch (PDOException $e) {
    print $e->getMessage();
  }  
    
    
    
    
}
   

try{
    $sth = $dbh->prepare("SELECT session.*, engineer.engName, assistant.astName, client.cliName, composer.cmpName, project.prjName
                            FROM session
                            INNER JOIN engineer ON session.engID=engineer.engID
                            INNER JOIN assistant ON session.astID=assistant.astID
                            INNER JOIN client ON session.cliID=client.cliID
                            INNER JOIN project ON session.prjID=project.prjID
                            INNER JOIN composer ON session.cmpID=composer.cmpID
                            WHERE session.ssNo = '0' OR ISNULL(session.ssNo)
                            ORDER BY sessDate DESC;" );
    
    $sth->execute();
    
    $count = $sth->rowCount();
    
       
}
catch (PDOException $e) {
    print $e->getMessage();
  }
require('header.php');
?>

<div id="subHead"><h1>Missing Session Sheet Numbers</h1></div>

<div id="Results">
    
    <? if($count > 0){ ?>
            <table id="sessResultTable">
                <tr>
                    <th scope="col">Session Date</th>
                    <th scope="col">Studio</th>
                    <th scope="col">Client</th>
                    <th scope="col">Composer</th>
                    <th scope="col">Engineer</th>
                    <th scope="col">Assistant</th>
                    <th scope="col">Session Sheet #</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
           <?php while($row=$sth->fetch(PDO::FETCH_ASSOC)){ 
               
               $date = strtotime($row['sessDate']);
               $initEng = explode(" ",$row['engName']); //split string into two seperate strings and seperate array values
               $initAst = explode(" ",$row['astName']);
               
               ?>
                
                
                <tr>
                    <td><?=date('d-m-Y', $date);?></td>
                    <td><?=$row['stdID'];?></td>
                    <td><?=$row['cliName'];?></td>
                    <td><?=$row['cmpName'];?></td>
                    <td><?=substr($initEng[0],0,1).substr($initEng[1],0,1)?></td>
                    <td><?=substr($initAst[0],0,1).substr($initAst[1],0,1)?></td>
                    
                    <td>
                        <form id="<?= $row['sesID'];?>" method="post" action="session_sheet.php" enctype="multipart/form-data">
                            <input type ="number" name="sesNo" min="7000"/>
                            <input type="input" name="sesID" value="<?=$row['sesID'];?>" hidden/>
                        </td>
                        <td>    
                            <input type ="submit" />
                 
                        </form>
                    </td>
                    <td><a href="edit_session.php?sesID=<?php echo $row['sesID'];?>">Edit &raquo;</a></td>
                    
                    
               
               </tr>
               
               <? } 
               
               }else { ?>
                <h3>There are no Sessions without session sheet numbers</h3>
               <?php } ?>
        
        
            </table>
            <div class="returnLink"><h3><a href="/">&laquo;Go Back</a></h3></div>
        </div>







<?php 
        
    require_once 'footer.php';
?>

