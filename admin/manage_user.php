<?php 
session_start();
require('includes/pdoconnection.php');

$dbh = dbConn::getConnection();



function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}
    
$users=new user($dbh);
     
$result=$users->getUserByID($_GET['id']);
     
    // This if statement checks to determine whether the edit form has been submitted 
    // If it has, then the account updating code is run, otherwise the form is displayed 
    if(!empty($_POST)) 
    { 
        
        if($_POST['usrname'] != $result['username'])
        {
            $users->updateUsername($_POST['usrname'], $_POST['usrID']);
        }
        // Make sure the user entered a valid E-Mail address 
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        { 
            die("Invalid E-Mail Address"); 
        } 
         
        // If the user is changing their E-Mail address, we need to make sure that 
        // the new value does not conflict with a value that is already in the system. 
        // If the user is not changing their E-Mail address this check is not needed. 
        if($_POST['email'] !=$result['email'])
        {
            $users->updateEmail($_POST['email'], $_POST['usrID']);
            
        }
        // If the user entered a new password, we need to hash it and generate a fresh salt 
        // for good measure. 
        if(!empty($_POST['password'])) 
        { 
            $users->updatePassword($_POST['password'], $_POST['usrID']);
        }
        if($result['usrGroup'] != $_POST['usrGroup']){
            $users->updateUserGroup($_POST['usrGroup'], $_POST['usrID']);
        }
        
        
        
        header("Location: memberlist.php"); 
         
        // Calling die or exit after performing a redirect using the header function 
        // is critical.  The rest of your PHP script will continue to execute and 
        // will be sent to the user if you do not die or exit. 
        die("Redirecting to memberlist.php"); 
    } 
    
    switch($result['usrGroup']) 
                { 
                    case "default": $default = "checked"; break; 
                    case "office": $office = "checked"; break; 
                    case "studio": $studio = "checked"; break;
                    case "admin": $admin = "checked"; break;
                
                } 
                
    require_once 'header-admin.php';
    
?> 
<div id="subHead"><h1>Manage Account for user - <?php echo $result['username'];?></h1></div>
<form action="manage_user.php?id=<?php echo $result['usrID'];?>" id="editaccount" method="post"> 
    <div id="manageUser">
    <div class="backupDriveTitle"><h3>Username:</h3></div>
    <input type="text" value="<?php echo htmlentities($result['username']); ?>" name="usrname" class="emalInput"></input>
    <input type="number" value="<?php echo $result['usrID'];?>" name="usrID" class="hidden"></input>
    <br /><br /> 
    <div class="backupDriveTitle"><h3>Email Address:</h3></div>
    <input type="text" name="email" class ="emalInput" value="<?php echo $result['email']; ?>" /> 
    <br /><br /> 
    <div class="backupDriveTitle"><h3>Password:</h3></div>
    <input type="password" name="password" class="paswInput" value="" /><br /> 
    <i>(leave blank if you're not changing the password)</i> 
    <br /><br /> 
    
    <input type="submit" value="Update Account" />
    </div>
    <div class="usrGroups">
    <div class="backupDriveTitle"><h3>User Group Selection</h3></div>
        <input type="radio" class="roleSelect" value="default" name="usrGroup" <?php echo $default;?>/>Default<br /><!--<label for="default">Default (assigned by default - Cannot take/move mics)</label><br />-->
        <input type="radio" class="roleSelect" value="office" name="usrGroup" <?php echo $office;?> />Office<br /><!--<label for="office">Office (Can update session sheets - Cannot take/move mics)</label><br />-->
        <input type="radio" class="roleSelect" value="studio" name="usrGroup" <?php echo $studio;?> />Studio<br /><!--<label for="studio">Studio (All assistants / engineers and runners should be this)</label><br />-->
        <input type="radio" class="roleSelect" value="admin" name="usrGroup" <?php echo $admin;?> />Admin<br /><br />
    </div>
    
    <div class="usrGroups">
        <div class="backupDriveTitle"><h3>User Group Descriptions</h3></div>
        <h3>Default</h3><p> Assigned by default - Cannot take/move mics</p><br />
        <h3>Office</h3><p> Can update session sheets - Cannot take/move mics</p><br />
        <h3>Studio</h3><p> All assistants / engineers and runners should be this</p><br />
        <h3>Admin</h3><p> Only admins can create/manage users, delete sessions, fix faults etc</p><br />
    </div>
    
    
</form>

<?php require('footer.php'); ?>