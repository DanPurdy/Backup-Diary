<?php

require('includes/pdoconnection.php');
require('class_staff.php');
$dbh = dbConn::getConnection();

if(isset($_GET['engID'])){
    $staff= new staff($dbh, 'eng');
    $id = htmlentities($_GET['engID']);
    $tbl='eng';
}else{
    $staff= new staff($dbh, 'ast');
    $id = htmlentities($_GET['astID']);
    $tbl='ast';
}

if($tbl=='eng'){ 
    $title='Engineer';
                    
}else{
     $title= 'Assistant';
     
     }
     
// if form has been submitted, update record     
if (isset($_POST['update'])) {
    $staff->updateStaff($_POST);
    header('Location: /staff/');
}

$row=$staff->staffDetail($id);





require_once ('header.php');
?>

<div id="subHead"><h1>Update Engineer</h1></div>
    <div class="staffLink">
        <h3>
            <a href="/staff/">&laquo; Back To Staff Page</a>
        </h3>
    </div>

        <?php 
            if(!($row) || $id==='1') { ?>
    <div class="warning">
        <h1>Invalid request: record does not exist.</h1>
    </div>
    <?php } else { ?>

    <form id="editEng" method="post" action="<?php echo 'update_staff.php?'.$tbl.'ID='.$id;?>">
        <div class="staffEntry">
            <label for="name">
                <?php   echo $title?>
            </label>
            <input name="name" type="text" id="name" value="<?php echo $row[$tbl.'Name']; ?>">
        </div>
  
        <input type="submit" name="update" value="Update <?php echo $title;?>" id="update">
        <input name="id" class="hidden" value="<?php echo $row[$tbl.'ID']; ?>">
    </form>
<?php 

} 
require_once ('footer.php');
?>
