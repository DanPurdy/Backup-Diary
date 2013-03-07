<?php

require_once '../includes/pdoconnection.php';

$dbh = dbConn::getConnection();

try{
    $sth = $dbh->prepare("SELECT session.sesID, session.stdID, session.sessDate, session.startTime, session.endTime,session.ssNo, studio.stdName, engineer.engName, assistant.astName, client.cliName, composer.cmpName, fixer.fixName, project.prjName
                            FROM session
                            INNER JOIN studio ON session.stdID=studio.stdID
                            INNER JOIN engineer ON session.engID=engineer.engID
                            INNER JOIN assistant ON session.astID=assistant.astID
                            INNER JOIN client ON session.cliID=client.cliID
                            INNER JOIN project ON session.prjID=project.prjID
                            INNER JOIN composer ON session.cmpID=composer.cmpID
                            INNER JOIN fixer ON session.fixID=fixer.fixID
                            WHERE WEEK(sessDate,1)= WEEK(DATE_ADD(current_date, INTERVAL 1 WEEK),1)  AND YEAR(sessDate) = YEAR(current_date)
                            ORDER BY sessDate,session.stdID ASC,startTime;" );
    
    $sth->execute();
    
    
    
       
}
catch (PDOException $e) {
    print $e->getMessage();
  }
require_once ('header.php');
?>

<div id="subHead"><h1>Timetable</h1></div>
<div id="timetableCurr"><h3><a href="list_timetable.php">&laquo; This Week</a></h3></div>
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
    
    while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
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
                <?php if($_SESSION['user']['username'] == 'alex' || $_SESSION['user']['username'] == 'dan'){ ?> <div class="deleteLink"><a href="delete_session.php?sesID=<?=$row['sesID'];?>">Delete</a></div> <?php }; ?>
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
