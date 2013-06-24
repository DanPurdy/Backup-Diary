<?php

require_once 'includes/pdoconnection.php';                              //autoload classes
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}

$dbh = dbConn::getConnection();                                         //create a connection instance

$session=new session($dbh); 
    
//if(!empty($_POST['cmpSearch'])){
//    $session = "%".$_POST['cmpSearch']."%";
//}else{
//    $session= "%"."'%'"."%";
//}

if($_POST){
    
    $dateStart=$_POST['dateStart'];
    $dateEnd=$_POST['dateEnd'];
        
    if(isset($_POST['ssNo'])){
        $table='session';
        $field='ssNo';
        $term=$_POST['ssNoSearch'];
        $title='Session Sheet Number: ';
    }
        
    if(isset($_POST['std'])){
        $table='session';
        $field='stdID';
        $term=$_POST['studio'];
        $title='Studio: ';
    }
        
    if(isset($_POST['cli'])){
        $table='client';
        $field='cliName';
        $term=$_POST['cliSearch'];
        $title='Client: ';
    }
        
    if(isset($_POST['cmp'])){
        $table='composer';
        $field='cmpName';
        $term=$_POST['cmpSearch'];
        $title='Composer: ';
    }
     
    if(isset($_POST['eng'])){
        $table='engineer';
        $field='engName';
        $term=$_POST['engSearch'];
        $title='Engineer: ';
    }
        
    if(isset($_POST['ast'])){
        $table='assistant';
        $field='astName';
        $term=$_POST['astSearch'];
        $title='Assistant: ';
    }
    if(empty($dateStart)){
        $result=$session->sessSearch($table, $field, $term);
    }else{
        $result=$session->sessSearchDate($table, $field, $term, $dateStart, $dateEnd);
    }
        
    
}
  require_once 'header.php';
  
  ?>


        <div id="Results">
            <div id="subHead"><h3>Results for <? echo $title.$term; ?></h3></div>
            
                <?php
                
     
     if($session->count !=0){
         
                foreach($result as $row){?>
                <div class="session">
                    <div class="resDetails"><div class="backupDriveTitle"><h3>Session Details</h3></div>
                        <div class="resLink"><div class="resEditLink"><a href="/session/edit_session.php?sesID=<?php echo $row['sesID'];?>">Edit</a></div></div>    
                <div class="resDate"><?= date('d-m-y    ', strtotime($row['sessDate']))?></div>
                <div class="resSesstime"><?php echo substr($row['startTime'],0,5) . " - " . substr($row['endTime'],0,5); ?></div>
                </div>
                    <div class="resClientDet"><div class="backupDriveTitle"><h3>Client Details</h3></div>
                <div class="resClient"><?php echo $row['cliName']?></div>
                <div class="resComposer"><?php echo $row['cmpName']?></div>
                <div class="resProject"><?php echo $row['prjName']?></div>
                    </div>
                    
                </div>
                <?php 
            
                }
 }else{
     ?>
            <div class="searchError"><h3>Sorry there are no results.</h3></div>
            <?php
 }
  
 ?>
            
        </div>
        
<div class="returnLink"><a href="<?=$_SESSION['org_referer'];?>">&laquo; Back</a></div>
<?php  require_once ('footer.php'); ?>