<?php

require('includes/pdoconnection.php');
require('class_staff.php');
$dbh = dbConn::getConnection();

$engineer = new staff($dbh, 'eng');
if(isset($_POST['insert'])){
    
    $engineer->addStaff($_POST);
    
    header('Location: /staff/');
}
require_once ('header.php');
?>
<div id="subHead"><h1>Add New Engineer</h1></div>
<?php if (isset($error)) {
  echo "<p>Error: $error</p>";
} ?>
       <form id="newEng" method="post" action="">
  <div class="staffEntry"><label for="name">Engineer:</label>
    <input name="name" type="text" id="name"></div>
  
    <input type="submit" name="insert" value="Add Engineer" id="insert">
</form>
<div class="staffLink"><h3><a href="/staff/">&laquo; Back To Staff Page</a></h3></div>
        
    
    <?php require_once ('footer.php'); ?>