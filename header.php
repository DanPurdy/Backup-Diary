<?php
    if(!isset($_SESSION)){session_start();
         
    header('Content-Type: text/html; charset=utf-8');
    // check to see whether the user is logged in or not
    }
    if(empty($_SESSION['user'])) 
    { 
        // If they are not,redirect them to the login page. 
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
            <div id="header">
                <div id="mainHead"><h1><a href="/">Angel Backup Diary</a></h1></div>
                <div id="accCont">
                    <div class="backupDriveTitle"><h3>Logged in as:  <?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?></h3></div>
                    <div id="loginEdit"><a href="/edit_account.php">Edit Account</a></div>
                    <div id="logout"><a href="/logout.php">Logout</a></div>
                </div>
                
                    <nav>
                        <ul>
                            <li class="main">
                                <a href=""><h3>Sessions</h3></a>
                                <ul>
                                   
                                    <li><a href="/session/list_timetable.php">Timetable</a></li>
                                    <li><a href="/session/new_session.php">Add a Session</a></li>
                                    <li><a href="/session/search_session.php">Search Sessions</a></li>
                                    <?php if ($_SESSION['user']['usrGroup']=='admin' || $_SESSION['user']['usrGroup']=='default'){?><li><a href="/screen/">Reception Screen</a></li><?}?>
                                    <?php if ($_SESSION['user']['usrGroup']=='admin' || $_SESSION['user']['usrGroup']=='office'){?><li><a href="/session/session_sheet.php">Session Sheets</a></li><?}?>
                                    
                                </ul>
                            </li>
                            <li class="main">
                                <a href="/channels/"><h3>Faults</h3></a>
                            <ul>
                                   
                                    <li><a href="/channels/list_channels.php?studio=1">Studio One</a></li>
                                    <li><a href="/channels/list_channels.php?studio=3">Studio Three</a></li>
                                
                                </ul>
                            </li>
                            <li class="main">
                                <?php if ($_SESSION['user']['usrGroup']!='office' && $_SESSION['user']['usrGroup']!='default'){?><a href="/mics/"><?}else{?><a href=""><?}?><h3>Microphones</h3></a>
                                <ul>
                                   
                                    <?php if ($_SESSION['user']['usrGroup']!='office' && $_SESSION['user']['usrGroup']!='default'){?><li><a href="/mics/">In / Out</a></li><?}?>
                                    <li><a href="/mics/list.php">Mic List</a></li>
                                    <li><a href="/mics/list_session.php">Mics In Session</a></li>
                                    <li><a href="/mics/list_repair.php">Repair List</a></li>
                                    
                                
                                </ul>
                            
                            </li>
                            <li class="main">
                                <a href="/backup/"><h3>Backups</h3></a>
                                <ul>
                                   
                                    <li><a href="/backup/">Backup Menu</a></li>
                                    <li><a href="/backup/searchstudio.php">Search Backups</a></li>
                                    <?php if ($_SESSION['user']['usrGroup']=='admin'){?> <li><a href="/backup/selectstudio.php">Due For Deletion</a></li><?}?>
                                    <li><a href="/cupboard/new_drive.php">Tape Store</a></li>
                                    
                                
                                </ul>
                            </li>
                            
                                <?php if ($_SESSION['user']['usrGroup']=='admin'){?>
                                <li class="main">
                                    <a href="/admin/"><h3>Admin</h3></a>
                                <ul>
                                   
                                    
                                    <li><a href="/admin/memberlist.php">Manage Users</a></li>
                                    <li><a href="/admin/register.php">Register User</a></li>
                                    
                                    
                                
                                </ul>
                            </li>
                            
                            <?php } ?>
                                
                        </ul>
                   </div>  
            
            
            </nav>
            <div id="main">