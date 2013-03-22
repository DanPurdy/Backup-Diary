<?php 

    
    require('includes/pdoconnection.php');
    $dbh = dbConn::getConnection();
    
    session_start();
     
    
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
         
         
        $query = " 
            SELECT 
                1 
            FROM users 
            WHERE 
                username = :username 
        "; 
         
        
        $query_params = array( 
            ':username' => $_POST['username'] 
        ); 
         
        try 
        { 
             
            $stmt = $dbh->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
             
            die("Failed to run query: " . $ex->getMessage()); 
        } 
         
        
        $row = $stmt->fetch(); 
         
        
        if($row) 
        { 
            die("This username is already in use"); 
        } 
        
        $query = " 
            SELECT 
                1 
            FROM users 
            WHERE 
                email = :email 
        "; 
         
        $query_params = array( 
            ':email' => $_POST['email'] 
        ); 
         
        try 
        { 
            $stmt = $dbh->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            die("Failed to run query: " . $ex->getMessage()); 
        } 
         
        $row = $stmt->fetch(); 
         
        if($row) 
        { 
            die("This email address is already registered"); 
        } 
         
        
        $query = " 
            INSERT INTO users ( 
                username, 
                password, 
                salt, 
                email,
                usrGroup
            ) VALUES ( 
                :username, 
                :password, 
                :salt, 
                :email,
                'studio'
            ) 
        "; 
         
         
        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
        
        $password = hash('sha256', $_POST['password'] . $salt); 
        
        for($round = 0; $round < 65536; $round++) 
        { 
            $password = hash('sha256', $password . $salt); 
        } 
         
        
        $query_params = array( 
            ':username' => $_POST['username'], 
            ':password' => $password, 
            ':salt' => $salt, 
            ':email' => $_POST['email'] 
        ); 
         
        try 
        { 
            // Execute the query to create the user 
            $stmt = $dbh->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            
            die("Failed to run query: " . $ex->getMessage()); 
        } 
         
        // This redirects the user back to the login page after they register 
        header("Location: /login.php"); 
        
        die("Redirecting to login.php"); 
    } 
    require_once('header.php');
?> 
<div id="subHead"><h1>Register</h1></div>
<form id="register" action="register.php" method="post"> 
    <div class="backupDriveTitle"><h3>Choose a Username:</h3></div>
    <input type="text" class="userInput" name="username" value="" /> 
    <br /><br /> 
    <div class="backupDriveTitle"><h3>Enter your E-Mail:</h3></div>
    <input type="text" class ="emalInput" name="email" value="" /> 
    <br /><br /> 
    <div class="backupDriveTitle"><h3>Choose a Password:</h3></div>
    <input type="password" class="paswInput"name="password" value="" /> 
    <br /><br /> 
    <input type="submit" class="submit" value="Register" /> 
</form>

<?require_once 'footer.php';