<?php

require_once('../includes/connection.php'); // initialize flags
$OK = false;
$done = false;
// create database connection
$conn = dbConnect('write');
// initialize statement
$stmt = $conn->stmt_init();
// get details of selected record
if (isset($_GET['engID']) && !$_POST) {
// prepare SQL query
$sql = 'SELECT engID, engName FROM engineer WHERE engID = ?'; 
if ($stmt->prepare($sql)) {
// bind the query parameter
$stmt->bind_param('i', $_GET['engID']);
// bind the results to variables 
$stmt->bind_result($engID, $name); 
// execute the query, and fetch the result
$OK = $stmt->execute();
$stmt->fetch();
} 

}
// if form has been submitted, update record 
if (isset($_POST ['update'])) {
// prepare update query
$sql = 'UPDATE engineer SET engName = ? WHERE engID = ?'; 
if ($stmt->prepare($sql)) {
$stmt->bind_param('si', $_POST['name'], $_POST['engID']);
$done = $stmt->execute(); }
}
// redirect if $_GET['engID'] not defined 
// redirect page on success or if $_GET['engID']) not defined 
if ($done || !isset($_GET['engID'])) {
header('Location: /staff/');
exit; }
// display error message if query fails 
if (isset($stmt) && !$OK && !$done) {
$error = $stmt->error; }


require_once ('header.php');
?>

<div id="subHead"><h1>Update Engineer</h1></div>
<div class="staffLink"><h3><a href="/staff/">&laquo; Back To Staff Page</a></h3></div>
<?php if (isset($error)) {
    echo "<p class='warning'>Error: $error</p>";
    }
if($engID == 0) { ?>
<div class="warning">
<h1>Invalid request: record does not exist.</h1>
 </div>
    <?php } else { ?>

<form id="editEng" method="post" action="">
  <div class="staffEntry"><label for="name">Engineer:</label>
    <input name="name" type="text" id="name" value="<?php echo $name; ?>"></div>
  
    <input type="submit" name="update" value="Update Engineer" id="update">
  <input name="engID" class="hidden" value="<?php echo $engID; ?>">
</form>
<?php 

} 
require_once ('footer.php');
?>
