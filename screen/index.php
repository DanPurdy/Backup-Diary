<?php

require_once 'includes/pdoconnection.php';
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$session=new session($dbh);

?>
<!doctype>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Refresh" content="900"/>
    <title>Reception Screen - V2</title>
    <LINK REL=StyleSheet HREF="/screen/assets/Screen.css" TYPE="text/css"/>
    <script src="/includes/jquery/js/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="assets/scripts.js" type="text/javascript"></script>
</head>


<body>
    <div class="wrapper">
        <div class="title-area">
            <h3>Welcome To </h3><h2>Angel Studios</h2>
            
        </div>

        <?php 
            $q=1;
            while($q<4){
                ?>
                <div class="sess-details">
                    <?php
                        print('<div class="studio-title-'.$q.'">Studio<h2>'.$q.'</h2></div>');                
                        $session->getScreen($q);
                        $q++;
        ?>
                </div>
        <?php
            }
        ?>

        <ul id="clock"> 
              <li id="sec"></li>
              <li id="hour"></li>
              <li id="min"></li>
            </ul>
        <div class ="DateTime">
            <div class="day">
                <?php
                    print date('l');
                ?>
            </div>

            <div class="date">
                <?php
                    print date('jS F Y');
                ?>
            </div>
        </div>
    </div>
</body>
</html>