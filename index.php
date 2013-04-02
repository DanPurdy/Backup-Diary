<?php  require_once('header.php'); ?>
<div id="subHead"><h1>Main Menu</h1></div>
        <ul id="menu-screen">
                
            <li data-menu="sessMenu"><a style="display:block" href="#" >Manage Sessions</a></li>
            
                
        
        
            <li data-menu="micMenu"><a style="display:block" href ="#" >Microphones</a></li> 
                
            <li data-menu="bakMenu"><a style="display:block" href ="#" >Backup Diary</a></li>
            <li></li>
        </ul>
<div id="menu">
        
        <ul class="sessMenu">
            	<li><a href="/session/list_timetable.php">Timetable</a></li>
                <li><a href="/session/new_session.php">Add Session</a></li>
                <li><a href="/session/search_session.php">Search Sessions</a></li>
                <li><a href="/screen/">Reception Screen</a></li>
            </ul>
            <ul class="micMenu">
            	<li><a href="/mics/">Mics In/Out</a></li>
                <li><a href="/mics/list_session.php">Mics in Session</a></li>
                <li><a href="/session/list_timetable.php">Mic List</a></li>
                <li><a href="/session/list_timetable.php">Mic Faults</a></li>
                
            </ul>
            <ul class="bakMenu">
                <li><a href="/backup/">Backup Overview</a></li>
                <?php if($_SESSION['user']['usrGroup'] == 'admin'){ ?><li><a href="selectstudio.php">Due For Deletion </a></li><?php }; ?>
                <li><a href="/backup/searchstudio.php">Search Backups</a></li>
                
                
                
            </ul>
</div>
        <div id="menu-info">
        
        </div>  
        
<?php  require_once('footer.php'); ?>