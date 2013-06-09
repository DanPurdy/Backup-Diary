<?php

require('includes/pdoconnection.php');
require('class_staff.php');
$dbh = dbConn::getConnection();

$assistant = new staff($dbh, 'ast');
if(isset($_POST['insert'])){
    
    $assistant->addStaff($_POST);
    
    header('Location: /staff/');
}
require_once ('header.php');
?>
<div id="subHead"><h1>Add New Assistant</h1></div>
<?php if (isset($error)) {
  echo "<p>Error: $error</p>";
} ?>
       <form id="newAst" method="post" action=""> 
          
            <div class="staffEntry"><label for="name">Assistant: </label>
            <input name="name" type="text" id="name"></div>
          
               <input type="submit" name="insert" value="New Assistant" id="insert">
         
       </form>
<div class="staffLink"><h3><a href="/staff/">&laquo; Back To Staff Page</a></h3></div>
<?php require_once ('footer.php'); ?>
