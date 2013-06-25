<?php 
require('includes/pdoconnection.php');
$dbh = dbConn::getConnection();
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}
    
$users=new user($dbh); 
    
     
    
    if(!empty($_POST)) 
    { 
        // Ensure that the user has entered a non-empty username 
        if(empty($_POST['username'])) 
        { 
            
            die("Please enter a username."); 
        } 
         
        // Ensure that the user has entered a non-empty password 
        if(empty($_POST['password'])) 
        { 
            die("Please enter a password."); 
        } 
         
        
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        { 
            die("Invalid E-Mail Address"); 
        } 
         
         
        $users->addUser($_POST['username'], $_POST['email'], $_POST['password'], $_POST['usrGroup']);
         
        // This redirects the user back to the login page after they register 
        header("Location: memberlist.php"); 
        
        die("Redirecting to memberlist.php"); 
    } 
    require_once('header-admin.php');
?> 
<div id="subHead"><h1>Register</h1></div>
<form id="register" action="register.php" method="post"> 
    <div id="manageUser">
    <div class="backupDriveTitle"><h3>Choose a Username:</h3></div>
    <input type="text" class="userInput" name="username" value="" /> 
    <br /><br /> 
    <div class="backupDriveTitle"><h3>Enter E-Mail:</h3></div>
    <input type="text" class ="emalInput" name="email" value="" /> 
    <br /><br /> 
    <div class="backupDriveTitle"><h3>Choose a Password:</h3></div>
    <input type="password" class="paswInput"name="password" value="" /> 
    <br /><br /> 
    <input type="submit" class="submit" value="Register User" />
    </div>
    <div class="usrGroups">
    <div class="backupDriveTitle"><h3>User Group Selection</h3></div>
        <input type="radio" class="roleSelect" value="default" name="usrGroup" checked/>Default<br /><!--<label for="default">Default (assigned by default - Cannot take/move mics)</label><br />-->
        <input type="radio" class="roleSelect" value="office" name="usrGroup"  />Office<br /><!--<label for="office">Office (Can update session sheets - Cannot take/move mics)</label><br />-->
        <input type="radio" class="roleSelect" value="studio" name="usrGroup" />Studio<br /><!--<label for="studio">Studio (All assistants / engineers and runners should be this)</label><br />-->
        <input type="radio" class="roleSelect" value="admin" name="usrGroup" />Admin<br /><br />
    </div>
    <div class="usrGroups">
        <div class="backupDriveTitle"><h3>User Group Descriptions</h3></div>
        <h3>Default</h3><p> Assigned by default - Cannot take/move mics.</p><br />
        <h3>Office</h3><p> Can update session sheets - Cannot take/move mics.</p><br />
        <h3>Studio</h3><p> All assistants / engineers and runners should be this.</p><br />
        <h3>Admin</h3><p> Only admins can create/manage users, delete sessions, fix faults etc.</p><br />
    </div>
    
</form>

<?require_once 'footer.php';