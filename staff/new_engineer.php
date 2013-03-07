<?php
if (isset($_POST['insert'])) {
  require_once('../includes/connection.php');
  // initialize flag
  $OK = false;
  // create database connection
  $conn = dbConnect('write');
  // initialize prepared statement
  $stmt = $conn->stmt_init();
  // create SQL
  $sql = 'INSERT INTO engineer (engName) VALUES(?)';
  if ($stmt->prepare($sql)) {
	// bind parameters and execute statement
	$stmt->bind_param('s', $_POST['name']);
    // execute and get number of affected rows
	$stmt->execute();
	if ($stmt->affected_rows > 0) {
	  $OK = true;
	}
  }
  // redirect if successful or display error
  if ($OK) {
	header('Location: /staff/');
	exit;
  } else {
	$error = $stmt->error;
  }
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