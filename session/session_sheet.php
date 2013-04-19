<?php
require_once 'includes/pdoconnection.php';                              //autoload classes
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();                                         //create a connection instance

$session=new session($dbh);

if($_POST){
 
    $session->updateSessNo($_POST['sesNo'], $_POST['sesID']);
}
   
$result=$session->getEmptySessSheet();

require('header.php');
?>

<div id="subHead"><h1>Missing Session Sheet Numbers</h1></div>

<div id="Results">
    
    <? if(isset($result)){ ?>
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
           <?php foreach($result as $row){ 
               
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
                            <input type ="submit" value="Submit"/>
                 
                            </form>
                        </td>
                        <td>
                            <a href="edit_session.php?sesID=<?php echo $row['sesID'];?>">Edit &raquo;</a>
                        </td>
                    
                    
               
                    </tr>
               
              <?php } //end for loop 
               
        }else { ?>
            <h3>There are no Sessions without session sheet numbers</h3>
               <?php } ?>
        
        
            </table>
    <div class="returnLink"><h3><a href="/">&laquo;Go Back</a></h3></div>
</div>

<?php 
        
    require_once 'footer.php';
?>

