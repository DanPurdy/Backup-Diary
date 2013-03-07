<?php
session_start();
    $_SESSION['org_referer'] = htmlentities($_SERVER['HTTP_REFERER']);
    
require_once '../includes/pdoconnection.php';

$today=date("Y-m-d");

require_once ('../header.php');
?>



<h1>Search by Studio</h1>
    
    <div id="searchStd">
        <h3>Search By Studio</h3>
        <form id="studioSearch" method="post" action="search_result.php" enctype="multipart/form-data">
            <input type="radio" name="studio" value="1" class="radio">Studio One<br />
            <input type="radio" name="studio" value="2" class="radio" >Studio Two<br />
            <input type="radio" name="studio" value="3" class="radio" >Studio Three<br />
            <h4>Between Dates</h4>
            <input type="date" name="dateStart" id="dateStart" />
            <input type="date" name="dateEnd" value="<?=$today;?>" id="dateEnd" /><br />
            <input type="submit" class="submit">
        </form> 
    </div>

<div id="searchCli">
        <h3>Search By Client</h3>
        <form id="clientSearch" method="post" action="search_client.php" enctype="multipart/form-data">
            <input type="text" name="clientName" id="clisearch" required />
            <input type="submit" class="submit" >
        </form>
</div>
<div id="searchBakName">
        <h3>Search By Folder Name</h3>
        <form id="backupSearch" method="post" action="search_backup.php" enctype="multipart/form-data">
            <input type="text" name="backupName" id="baksearch" required />
            <input type="submit" class="submit" >
        </form>
</div>

<?php require_once ('../footer.php'); ?>

