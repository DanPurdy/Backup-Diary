<?php

require_once('../includes/connection.php'); // initialize flags
$OK = false;
$done = false;
// create database connection
$conn = dbConnect('write');
// initialize statement
$stmt = $conn->stmt_init();
// get details of selected record
if (isset($_GET['astID']) && !$_POST) {
// prepare SQL query
$sql = 'SELECT astID, astName FROM assistant WHERE astID = ?'; 
if ($stmt->prepare($sql)) {
// bind the query parameter
$stmt->bind_param('i', $_GET['astID']);
// bind the results to variables 
$stmt->bind_result($astID, $name); 
// execute the query, and fetch the result
$OK = $stmt->execute();
$stmt->fetch();
} 

}
// if form has been submitted, update record 
if (isset($_POST ['update'])) {
// prepare update query
$sql = 'UPDATE assistant SET astName = ? WHERE astID = ?'; 
if ($stmt->prepare($sql)) {
$stmt->bind_param('si', $_POST['name'], $_POST['astID']);
$done = $stmt->execute(); }
}
// redirect if $_GET['astID'] not defined 
// redirect page on success or if $_GET['astID']) not defined 
if ($done || !isset($_GET['astID'])) {
header('Location: /staff/');
exit; }
// display error message if query fails 
if (isset($stmt) && !$OK && !$done) {
$error = $stmt->error; }


require_once ('header.php');
?>

<div id="subHead"><h1>Update Assistant</h1></div>
<div class="staffLink"><h3><a href="/staff/">&laquo; Back To Staff Page</a></h3></div>
<?php if (isset($error)) {
    echo "<p class='warning'>Error: $error</p>";
    }
if($astID == 0) { ?>
<div class="warning">
    Invalid request: record does not exist.
</div> 
    <?php } else { ?>

<form id="editAst" method="post" action="">

    <div class="staffEntry"><label for="name">Assistant:</label>
    <input name="name" type="text" id="name" value="<?php echo $name; ?>"></div>
 

    <input type="submit" name="update" value="Update Entry" id="update" />
  <input name="astID" class="hidden" value="<?php echo $astID; ?>">
</form>
<?php 

} 

require_once ('footer.php');

?>
