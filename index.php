<?php  require_once('header.php'); ?>
<div id="subHead"><h1>Main Menu</h1></div>
       <div id="nav">
    <ul id="navList">
      <li><a href="#">Sessions</a>
        <!-- sub nav -->
        <ul class="listTab">
          <li><a href="#">Timetable</a></li>
          <li><a href="#">Add A Session</a></li>
          <li><a href="#">Search Sessions</a></li>
          <li><a href="#">Reception Screen</a></li>
        </ul>
      </li>
      <li><a href="#">Microphones</a>
        <!-- sub nav -->
        <ul class="listTab">
          <li><a href="#">Mics In/Out</a></li>
          <li><a href="#">Mic In Session</a></li>
          <li><a href="#">Mic List</a></li>
          <li><a href="#">Mics In Workshop</a></li>
        </ul>
      </li>
      <li><a href="#">Faults</a></li>
      <li><a href="#">Backup</a>
        <!-- sub nav -->
        <ul class="listTab">
          <li><a href="#">Backup Overview</a></li>
          <li><a href="#">New Backups</a>
            <ul class="listTab">
                <li><a href="#">Studio One</a></li>
                <li><a href="#">Studio Two</a></li>
                <li><a href="#">Studio Three</a></li>
        </ul>
          </li>
          <li><a href="#">Backup Drives</a>
              <ul class="listTab">
                <li><a href="#">Studio One</a></li>
                <li><a href="#">Studio Two</a></li>
                <li><a href="#">Studio Three</a></li>
            </ul>
          </li>
          <?php if($_SESSION['user']['usrGroup'] == 'admin'){ ?><li><a href="#">Due For Deletion</a></li><?php }; ?>
          <li><a href="#">Search Backups</a></li>
          
        </ul>
      </li>
      <?php if($_SESSION['user']['usrGroup'] == 'admin'){ ?>
      <li><a href="#">Admin</a>
      <ul class="listTab">
                <li><a href="#">Users</a>
                    <ul class="listTab">
                        <li><a href="#">Member List</a></li>
                        <li><a href="#">Register New User</a></li>
                    </ul>
                </li>
                <li><a href="#">Due For Deletion</a>
                <ul class="listTab">
                <li><a href="#">Studio One</a></li>
                <li><a href="#">Studio Two</a></li>
                <li><a href="#">Studio Three</a></li>
            </ul>
                </li>
                <li><a href="#">Manage Backups</a></li>
        </ul>
      </li>
      <?php }; ?>
    </ul>
  </div>
        <div id="menu-info">
        
        </div>  
        
<?php  require_once('footer.php'); ?>