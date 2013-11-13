<?php

require_once 'includes/pdoconnection.php';
function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}


$dbh = dbConn::getConnection();

$session=new session($dbh);

	$q=1;
	$result='';
	while($q<4){
		$result.='<div class="sess-details"><div class="studio-title-'.$q.'">Studio<h2>'.$q.'</h2></div>'.$session->getScreen($q).'</div>';
		$q++;
   	}
echo $result;

?>