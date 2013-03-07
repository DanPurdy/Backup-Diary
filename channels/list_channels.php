<?php
require('includes/pdoconnection.php');
    $dbh = dbConn::getConnection();

try{
    $sth = $dbh->prepare("SELECT channels.*
                            FROM channels
                            WHERE stdID = :stdID AND ((channels.channelID)%100) !=0
                            ORDER BY channels.currentPos ASC;" );
    
    $sth->bindParam(':stdID', $_GET['studio']);
    
    $sth->execute();
    
    $st1 = $dbh->prepare("SELECT channels.*, chanFault.*
                            FROM channels
                            INNER JOIN chanFault ON channels.channelID=chanFault.channelID
                            WHERE stdID = :stdID AND ((channels.channelID)%100) !=0 AND ISNULL(chanFault.faultOutcome)
                            ORDER BY channels.currentPos ASC;" );
    
    $st1->bindParam(':stdID', $_GET['studio']);
    
    $st1->execute();
        
}
catch (PDOException $e) {
    print $e->getMessage();
  }

require('header.php');
?>
<div id="subHead"><h1>Channel Overview for Studio <?=htmlentities($_GET['studio']);?></h1></div>
<?php if($_SESSION['user']['username'] == 'alex' ||  $_SESSION['user']['username'] == 'dan'){ ?>
        <div id="channelPositions">
            <div class="backupDriveTitle"><h3>Current Positions</h3></div>
            <table id="resultTable">
                <tr>
                   
                    <th scope="col">Current Position</th>
                    <th scope="col">Channel ID</th>
                </tr>
           <?php while($row=$sth->fetch(PDO::FETCH_ASSOC)){ ?>
                
                
                <tr>
                    
                    <td><?=$row['currentPos'];?></td>
                    <td><?=$row['channelID'];?></td>
                    
                </tr>
                  <?php } ?>
            </table>
                    </div>
   <? } ?>
<div id ="faultForm">
    <div class="backupDriveTitle"><h3>Submit a new Fault</h3></div>
    <form id="channelFault" action="addChanFault.php" method="post">
        <div class="channelSelect"><h3>Channel</h3>
            <input type="text" name="stdID" value="<?= htmlentities($_GET['studio']);?>" hidden/>
        <select name="channel">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
            <option value="31">31</option>
            <option value="32">32</option>
            <option value="33">33</option>
            <option value="34">34</option>
            <option value="35">35</option>
            <option value="36">36</option>
            <option value="37">37</option>
            <option value="38">38</option>
            <option value="39">39</option>
            <option value="40">40</option>
            <option value="41">41</option>
            <option value="42">42</option>
            <option value="43">43</option>
            <option value="44">44</option>
            <option value="45">45</option>
            <option value="46">46</option>
            <option value="47">47</option>
            <option value="48">48</option>
            <option value="49">49</option>
            <option value="50">50</option>
            <option value="51">51</option>
            <option value="52">52</option>
            <option value="53">53</option>
            <option value="54">54</option>
            <option value="55">55</option>
            <option value="56">56</option>
            <option value="57">57</option>
            <option value="58">58</option>
            <option value="59">59</option>
            <option value="60">60</option>
        </select>
        </div>
        <div id="faultText">
            <textarea class="mic" id="faultDesc" name="faultDesc" rows="4" cols="35">Enter Fault Details...</textarea>
        </div>
        <div class="submitChanFault">
            <input type="submit" name="fault_button" class="faultButton" value="Submit Fault"/>
        </div> 
   </form>
</div>

<?php if($_SESSION['user']['username'] == 'alex' ||  $_SESSION['user']['username'] == 'dan'){ ?>
<div id ="swapChannel">
    <div class="backupDriveTitle"><h3>Swap Channels</h3></div>
    <form id ="moveChannels" action="addChanFault.php" method="post">
        <input type="text" name="stdID" value="<?= htmlentities($_GET['studio']);?>" hidden/>
        <div class="selectPush"></div>
        <div class="channelSelect"><h3>Channel One</h3>
        <select name="channelOne">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
            <option value="31">31</option>
            <option value="32">32</option>
            <option value="33">33</option>
            <option value="34">34</option>
            <option value="35">35</option>
            <option value="36">36</option>
            <option value="37">37</option>
            <option value="38">38</option>
            <option value="39">39</option>
            <option value="40">40</option>
            <option value="41">41</option>
            <option value="42">42</option>
            <option value="43">43</option>
            <option value="44">44</option>
            <option value="45">45</option>
            <option value="46">46</option>
            <option value="47">47</option>
            <option value="48">48</option>
            <option value="49">49</option>
            <option value="50">50</option>
            <option value="51">51</option>
            <option value="52">52</option>
            <option value="53">53</option>
            <option value="54">54</option>
            <option value="55">55</option>
            <option value="56">56</option>
            <option value="57">57</option>
            <option value="58">58</option>
            <option value="59">59</option>
            <option value="60">60</option>
        </select>
        </div>
        <div class="channelSelect "><h3>Channel Two</h3>
        <select name="channelTwo">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
            <option value="31">31</option>
            <option value="32">32</option>
            <option value="33">33</option>
            <option value="34">34</option>
            <option value="35">35</option>
            <option value="36">36</option>
            <option value="37">37</option>
            <option value="38">38</option>
            <option value="39">39</option>
            <option value="40">40</option>
            <option value="41">41</option>
            <option value="42">42</option>
            <option value="43">43</option>
            <option value="44">44</option>
            <option value="45">45</option>
            <option value="46">46</option>
            <option value="47">47</option>
            <option value="48">48</option>
            <option value="49">49</option>
            <option value="50">50</option>
            <option value="51">51</option>
            <option value="52">52</option>
            <option value="53">53</option>
            <option value="54">54</option>
            <option value="55">55</option>
            <option value="56">56</option>
            <option value="57">57</option>
            <option value="58">58</option>
            <option value="59">59</option>
            <option value="60">60</option>
        </select>
        </div>
        <div class="submitChanFault">
            <input type="submit" name="swap_button" class="swapButton" value="Swap Channels"/>
        </div> 
    </form>
    
</div> 
<? } ?>
   <div id="chanFaults">
       <div class="backupDriveTitle"><h3>Current Faults</h3></div>
       
           <table id="resultTable">
                <tr>
                   
                    <th scope="col">Channel</th>
                    <th scope="col">Fault</th>
                    <?php if($_SESSION['user']['username'] == 'alex' ||  $_SESSION['user']['username'] == 'dan'){?><th scope="col">View Faults</th> <? } ?>
                </tr>
           <?php while($row=$st1->fetch(PDO::FETCH_ASSOC)){ ?>
                
                
                <tr>
                    
                    <td><?=$row['currentPos'];?></td>
                    <td><?=$row['faultDesc'];?></td>
                    <?php if($_SESSION['user']['username'] == 'alex' ||  $_SESSION['user']['username'] == 'dan'){ ?><td><a href="list_channel_faults.php?chID=<?=$row['channelID']?>">View</a></td> <? } ?>
                    
                </tr>
                  <?php } ?>
            </table>
                   
       
   </div>



<?php require_once('footer.php'); ?>