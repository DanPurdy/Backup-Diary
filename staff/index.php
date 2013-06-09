<?php
session_start();

require('includes/pdoconnection.php');
require('class_staff.php');
$dbh = dbConn::getConnection();

$engineer = new staff($dbh,'eng'); //create new instance of staff class for engineers
$assistant = new staff($dbh, 'ast');//create new instance of staff class for assistant





require_once ('header.php');
?>

<div id="subHead"><h1>Manage Staff</h1></div>
    <div id="engTable">
        <h3>Engineers</h3>
        <table>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
<?php 
    foreach($engineer->listStaff() as $row){ //iterate through the result of the listStaff method from class_staff
        if($row['engID']==1){                //ignore record 1 as its NULL in db for sessions with no engineer purposes
          
        }else{
?>
            <tr>
                <td><?php echo $row['engName']; ?></td>
                <td><a href="update_staff.php?engID=<?php echo $row['engID']; ?>">Edit</a></td>
            </tr>
<?php
        }                                       //end if
    }                                           //end foreach loop
?>
        
           
      
        </table>
        <div class="newLink"><a href="new_engineer.php">Add New Engineer &rarr;</a></div>
    </div>

    <div id="astTable">
        <h3>Assistants</h3>
        <table>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
<?php
    foreach($assistant->listStaff() as $row){   //iterate through the results of the listStaff metho from class_staff
        if($row['astID']==1){                   //ignore record 1 as it's NULL in db for sessions with no assistant purposes
          
        }else{
?>
            <tr>
                <td><?php echo $row['astName']; ?></td>
                <td><a href="update_staff.php?astID=<?php echo $row['astID']; ?>">Edit</a></td>
            </tr>
<?php

        }                                       //end if
    }                                           //end foreach loop
?>
        </table>
        <div class="newLink"><a href="new_assistant.php">Add New Assistant &rarr; </a></div>
    </div>

<?php  require_once ('footer.php'); 