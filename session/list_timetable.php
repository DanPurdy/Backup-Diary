<?php
require('includes/pdoconnection.php');
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();

$session= new session($dbh);

$result=$session->listWeekSession(0);

require('header.php');
?>

<div id="subHead"><h1>Timetable</h1></div>
<div id="timetableNext"><h3><a href="list_timetable_next.php">Next Week &raquo;</a></h3></div>
<div class="newSessLink"><h3><a href="new_session.php">Add New Session </a></h3></div>

<div id="timetable">
    <div id="timetableWrap">
    <div class="studioHead">&nbsp;</div>
    <div class="studioHead"><h3>Studio One</h3></div>
    <div class="studioHead"><h3>Studio Two</h3></div>
    <div class="studioHead"><h3>Studio Three</h3></div>
    
    <div>
    <?php 
    $week = date('w');
    $year = date('Y');
    $day[1] = date('l',strtotime('This Monday',time()));
    $day[2] = date('l',strtotime('This Tuesday',time()));
    $day[3] = date('l',strtotime('This Wednesday',time()));
    $day[4] = date('l',strtotime('This Thursday',time()));
    $day[5] = date('l',strtotime('This Friday',time()));
    $day[6] = date('l',strtotime('This Saturday',time()));
    $day[7] = date('l',strtotime('This Sunday',time()));
   
    $i=0;
    $j=0;
    $q=0;
    
    foreach($result as $row) {
        $date = strtotime($row['sessDate']);
        $initEng = explode(" ",$row['engName']); //split string into two seperate strings and seperate array values
        $initAst = explode(" ",$row['astName']);
        
        if(date('l',$date)!= $day[$i]){
            $i++;
            $q=0;
            $j=1;
            $prevStd = 0;
        }
        
        
        
        while(date('l',$date)!= $day[$i]){
            ?> </div><div class ="<?=$day[$i];?>"><div class="dayTitle"><h2><?=$day[$i] ;?></h2></div>
            <?php
            $i++;
            
            
                } 
        
                
                if(date('l',$date)== $day[$i] && $q==0){ ?>
                    
                </div><div class ="<?=$day[$i];?>"><div class="dayTitle"><h2><?=$day[$i] ;?></h2></div>
                <?php
                 $q++;
                
                } 
                
                if($row['stdID'] == $prevStd && date('l',$date)== $day[$i]){
                    $j=2;
                } ?>
                <div class="studio<?=$row['stdID'].$j?>">
                <div class="sesstime"><?php echo substr($row['startTime'],0,5) . " - " . substr($row['endTime'],0,5); ?></div>
                <div class="engineer"><?php echo substr($initEng[0],0,1).substr($initEng[1],0,1)?><br /><?php echo substr($initAst[0],0,1).substr($initAst[1],0,1)?></div> <?php //take the first letter of engineer/assistant first and surname. ?>
                <div class="client"><?php echo $row['cliName']?></div>
                <div class="composer"><?php echo $row['cmpName']?></div>
                <div class="fixer"><?php echo $row['fixName']?></div>
                <div class="project"><?php echo $row['prjName']?></div>
                <div class="editLink"><a href="edit_session.php?sesID=<?php echo $row['sesID'];?>">Edit</a></div>
                <?php if($_SESSION['user']['usrGroup'] == 'admin'){ ?> <div class="deleteLink"><a href="delete_session.php?sesID=<?=$row['sesID'];?>">Delete</a></div> <?php }; ?>
                </div>
                
                    
                    
                    <?php 
                    $prevStd = $row['stdID'];
                    $j=1;
                    }
                    while($i<8){ 
                    $i++;
                    if($i<8){?>
                    </div><div class ="<?=$day[$i];?>"><div class="dayTitle"><h2><?=$day[$i] ;?></h2></div>
                        
                     <?php
                    }
                    }
        
        
        ?>
           
                </div>
</div>
</div>
<?php require_once ('footer.php'); ?>
