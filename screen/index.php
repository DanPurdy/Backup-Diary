<?php

require_once 'session/fetch.php';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Refresh" content="900">
<title>Reception Screen - V2</title>
<LINK REL=StyleSheet HREF="/screen/assets/Screen.css" TYPE="text/css">
</head>


<body>

<div class="wrapper">


<?php $q=1;
while($q<4){
getSessions($q);
$q++;
}
?>

<div class ="DateTime">
<?php
    print date('l d F Y');
?>
</div>
</div>
</body>
</html>