<?php 
require('includes/pdoconnection.php');

$dbh = dbConn::getConnection();

require_once 'header-admin.php';

function __autoload($class_name) {
    include 'models/class_'.$class_name . '.php';
}
    
$users=new user($dbh);

$result=$users->memberList();
    
    
?> 
<div id="subHead"><h1>Memberlist</h1></div>
<div id="memberList">
<table> 
    <tr> 
        <th>ID</th> 
        <th>Username</th> 
        <th>E-Mail Address</th>
        <th>User Group</th>
        <th>Manage</th>
    </tr> 
    <?php foreach($result as $row): ?> 
        <tr> 
            <td><?php echo $row['usrID']; ?></td> <!-- htmlentities is not needed here because $row['id'] is always an integer --> 
            <td><?php echo htmlentities($row['username'], ENT_QUOTES, 'UTF-8'); ?></td> 
            <td><?php echo htmlentities($row['email'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo $row['usrGroup'];?></td>
            <td><a href="manage_user.php?id=<?php echo $row['usrID'];?>">Edit &raquo;</a></td>
        </tr> 
    <?php endforeach; ?> 
</table>
</div>


<?php require_once 'footer.php'; ?>