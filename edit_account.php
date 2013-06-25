<?php 
 session_start();
require('includes/pdoconnection.php');

$dbh = dbConn::getConnection();



function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}
    
$users=new user($dbh);

    // At the top of the page we check to see whether the user is logged in or not 
    if(empty($_SESSION['user'])) 
    { 
        // If they are not, we redirect them to the login page. 
        header("Location: login.php"); 
         
        // Remember that this die statement is absolutely critical.  Without it, 
        // people can view your members-only content without logging in. 
        die("Redirecting to login.php"); 
    } 
     
    // This if statement checks to determine whether the edit form has been submitted 
    // If it has, then the account updating code is run, otherwise the form is displayed 
    if(!empty($_POST)) 
    { 
        // Make sure the user entered a valid E-Mail address 
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        { 
            die("Invalid E-Mail Address"); 
        } 
         
        // If the user is changing their E-Mail address, we need to make sure that 
        // the new value does not conflict with a value that is already in the system. 
        // If the user is not changing their E-Mail address this check is not needed. 
        if($_POST['email'] != $_SESSION['user']['email']) 
        { 
           $ok=$users->updateEmail($_POST['email'], $_SESSION['user']['usrID']);
           if($ok)
           {
                $_SESSION['user']['email'] = $_POST['email'];
           }
        } 
         
        // If the user entered a new password, we need to hash it and generate a fresh salt 
        // for good measure. 
        if(!empty($_POST['password'])) 
        { 
            $users->updatePassword($_POST['password'], $_SESSION['user']['usrID']);
        } 
         
        // Now that the user's E-Mail address has changed, the data stored in the $_SESSION 
        // array is stale; we need to update it so that it is accurate. 
         
         
        // This redirects the user back to the members-only page after they register 
        header("Location: /index.php"); 
         
        // Calling die or exit after performing a redirect using the header function 
        // is critical.  The rest of your PHP script will continue to execute and 
        // will be sent to the user if you do not die or exit. 
        die("Redirecting to /index.php"); 
    } 
     require("header.php"); 
?> 
<div id="subHead"><h1>Edit Account</h1></div>
<form action="edit_account.php" id="editaccount" method="post">
    <div id="editUser">
    <div class="backupDriveTitle"><h3>Username:</h3></div>
    <h3><?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?></h3> 
    <br /><br /> 
    <div class="backupDriveTitle"><h3>Email Address:</h3></div>
    <input type="text" name="email" class ="emalInput" value="<?php echo htmlentities($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8'); ?>" /> 
    <br /><br /> 
    <div class="backupDriveTitle"><h3>Password:</h3></div>
    <input type="password" name="password" class="paswInput" value="" /><br /> 
    <i>(leave blank if you're not changing your password)</i> 
    <br /><br /> 
    <input type="submit" class="submit" value="Update Account" /> 
    </div>
</form>

<?php require('footer.php'); ?>