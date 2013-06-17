<?php
    session_start();
    $_SESSION['org_referer'] = htmlentities($_SERVER['HTTP_REFERER']);
    
    $today=date("Y-m-d");
    
    require_once ('header.php');
?>
<div id="subHead"><h1>Search For Sessions</h1></div>
<div class="searchGroup">
    <div class="backupTitle"><h3>Session Sheet Number / Studio</h3></div>
    <div id="searchSS">
        <h3>Search Session Sheet Number</h3>
        <form id="sessionSheet" method="post" action="search_result.php" enctype="multipart/form-data">
            <input type="number" name="ssNoSearch" id="searchSSNo" value="7000"/>
            <input type="submit" class="submit" name="ssNo">
        </form>
    </div>

    <div id="searchStd">
        <h3>Search By Studio</h3>
        <form id="studioSearch" method="post" action="search_result.php" enctype="multipart/form-data">
            <input type="radio" name="studio" value="1" class="radio">Studio One<br />
            <input type="radio" name="studio" value="2" class="radio" >Studio Two<br />
            <input type="radio" name="studio" value="3" class="radio" >Studio Three<br />
            <h4>Between Dates (optional)</h4>
            <input type="date" name="dateStart" id="dateStart" />
            <input type="date" name="dateEnd" value="<?php echo $today;?>" id="dateEnd" /><br />
            <input type="submit" class="submit" name="std">
        </form> 
    </div>
</div>
<div class="searchGroup">
<div class="backupTitle"><h3>Client / Composer</h3></div>
<div id="searchCli">
        <h3>Search By Client</h3>
        <form id="clientSearch" method="post" action="search_result.php" enctype="multipart/form-data">
            <input type="text" name="cliSearch" id="clisearch" required />
            <h4>Between Dates (optional)</h4>
            <input type="date" name="dateStart" id="dateStart" />
            <input type="date" name="dateEnd" value="<?php echo $today;?>" id="dateEnd" /><br />
            <input type="submit" class="submit" name="cli" >
        </form>
</div>
<div id ="searchCmp">
        <h3>Search By Composer</h3>
        <form id="composerSearch" method="post" action="search_result.php" enctype="multipart/form-data">
            <input type="text" name="cmpSearch" id="composersearch" required />
            <h4>Between Dates (optional)</h4>
            <input type="date" name="dateStart" id="dateStart" />
            <input type="date" name="dateEnd" value="<?php echo $today;?>" id="dateEnd" /><br />
            <input type="submit" class="submit" name="cmp" >
        </form>
</div>
</div>

<div class="searchGroup">
    <div class="backupTitle"><h3>Engineer / Assistant</h3></div>
<div id="searchEng">
        <h3>Search By Engineer</h3>
        <form id="engineerSearch" method="post" action="search_result.php" enctype="multipart/form-data">
            <input type="text" name="engSearch" id="engsearch" required/><br />
            <h4>Between Dates (optional)</h4>
            <input type="date" name="dateStart" id="dateStart"/>
            <input type="date" name="dateEnd" value="<?php echo $today;?>" id="dateEnd"/><br />
            <input type="submit" class="submit" name="eng">
        </form>
</div>

<div id ="searchAst">
        <h3>Search Assistant</h3>
        <form id="assistantSearch" method="post" action="search_result.php" enctype="multipart/form-data">
            <input type="text" name="astSearch" id="astsearch" required /><br />
            <h4>Between Dates (optional)</h4>
            <input type="date" name="dateStart" id="dateStart"/>
            <input type="date" name="dateEnd" value="<?php echo $today;?>" id="dateEnd" /><br />
            <input type="submit"class="submit" name='ast'/>
        </form>
</div>
    </div><br /><br />

<?php require_once ('footer.php'); ?>
