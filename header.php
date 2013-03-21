<?php
     session_start();
         // This tells the web browser that your content is encoded using UTF-8 
    // and that it should submit content back to you using UTF-8 
    header('Content-Type: text/html; charset=utf-8');
    // check to see whether the user is logged in or not 
    if(empty($_SESSION['user'])) 
    { 
        // If they are not, we redirect them to the login page. 
        header("Location: /login.php"); 
         
        die("Redirecting to /login.php"); 
    } 
    
    
?>


<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <LINK REL=StyleSheet HREF="/css/layout.css" TYPE="text/css" media="all">
        <LINK REL=StyleSheet HREF="/css/print.css" TYPE="text/css" media="print">
        <script type="text/javascript" src="/includes/jquery/js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="/includes/jquery/js/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript" src="/includes/scripts/js/autcomplete.js"></script>
	<link rel="stylesheet" href="/includes/jquery/css/smoothness/jquery-ui-1.9.2.custom.min.css" TYPE="text/css"/>
	<style type="text/css"><!--
	
	        /* style the auto-complete response */
	        li.ui-menu-item { font-size:12px !important; }
	
	--></style>
        <title>Angel Recording Studios</title>
        
    </head>
    <body>
        <div id="wrapper">
            <div id="header"><h1><a href="/">Angel Backup Diary</a></h1>
                <div id="accCont">
                    <div class="backupDriveTitle"><h3>Logged in as:  <?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?></h3></div>
                    <div id="loginEdit"><a href="/edit_account.php">Edit Account</a></div>
                    <div id="logout"><a href="/logout.php">Logout</a></div>
                </div>
                
                    <div id="headMenu">
                        
                        <a style="display:block" href="/session/list_timetable.php"><div class="menuItemFirst"><h3>Timetable</h3></div></a>
                        <a style="display:block" href="/channels/"><div class="menuItem"><h3>Faults</h3></div></a>
                        <a style="display:block" href="/mics/"><div class="menuItem"><h3>Mics</h3></div></a>
                        <a style="display:block" href="/backup/"><div class="menuItem"><h3>Backups</h3></div></a>
                   </div>  
            
            
            </div>
            <div id="main">