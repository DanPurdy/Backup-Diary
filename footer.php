</div>
        </div> 
        <div id="footer">
            <div id="footerWrap">
              <?php  if(empty($_SESSION['user'])){
                  
              }else{?>
                <div id="footerlinks">
                <div class="footerlinks">
                    <div class="backupDriveTitle"><h3><a href="/session/">Sessions &raquo;</a></h3></div>
                    <ul>
                        <li><a href="/session/list_timetable.php">Timetable</a></li>
                        <li><a href="/session/new_session.php">New Session</a></li>
                        <li><a href="/session/search_session.php">Search Sessions</a></li>
                        <li><a href="/staff/">Manage Staff</a></li>
                        <li><a href="/screen/">Reception Screen</a></li>
                    </ul>
                </div>
                <div class="footerlinks">
                    <div class="backupDriveTitle"><h3><a href="/channels/">Faults &raquo;</a></h3></div>
                    <ul>
                        <li><a href="/channels/list_channels.php?studio=1">One</a></li>
                        <li><a href="/channels/list_channels.php?studio=3">Three</a></li>
                    </ul>
                </div>
                <div class="footerlinks">
                    <div class="backupDriveTitle"><h3><a href="/mics/">Microphones &raquo;</a></h3></div>
                    <ul>
                        <li><a href="/mics/list_session.php">In Session</a></li>
                        <li><a href="/mics/list.php">Microphone List</a></li>
                        <li><a href="/mics/list_repair.php">For repair</a></li>
                    </ul>
                </div>
                <div class="footerlinks">
                    <div class="backupDriveTitle"><h3><a href="/backup/">Backups &raquo;</a></h3></div>
                    <ul>
                        <li><a href="/backup/">Active Backups</a></li>
                        <li><a href="/backup/#stuLinkInnerWrap">Backup Drives</a></li>
                        <li><a href="/backup/selectstudio.php">Due for Deletion</a></li>
                        <li><a href="/backup/searchstudio.php">Search Backups</a></li>
                        </ul>
                </div>
                </div>
                <?php } ?>
            </div>
        </div>
        
        
    </body>
    <script>
$(document).ready(function(){

$('textarea.mic').each(function() {
    // Stores the default value for each textarea within each textarea
    $.data(this, 'default', this.value);
}).focus(function() {
    // If the user has NOT edited the text clear it when they gain focus
    if (!$.data(this, 'edited')) {
        this.value = "";
    }
}).change(function() {
    // Fires on blur if the content has been changed by the user
    $.data(this, 'edited', this.value != "");
}).blur(function() {
    // Put the default text back in the textarea if its not been edited
    if (!$.data(this, 'edited')) {
        this.value = $.data(this, 'default');
    }
});


$(function() {
  $("#micNo").focus();
});

$(function () {
    $('.checkall').click(function () {
        $(this).parents('#micForm').find(':checkbox').attr('checked', this.checked);
    });
});

$("#savedMics").hide();
        $("#showMics").show();
 
    $('#showMics').click(function(){
    $("#savedMics").slideToggle();
    });


$('#micNo').keyup(function(){
    if(this.value.length ==4){
    $('#submitMic').click();
    }
});

$('#sessCont option').each(function() {
    if ($(this).text() == 'Parent') {
        $('#sessCont').hide();
    }
});


$(".Eassigned").each( function() {
    alert("This mic is already assigned to a session Please follow the link to correct this.");
});
$(".REassigned").each( function() {
    alert("This mic must be returned to the correct session Please follow the link.");
}); 

$('#form1').submit(function() {
    if ($('input:radio[name=bakLoc]', this).is(':checked')) {
        // everything's fine...
    } else {
        alert('Please select a backup drive!');
        return false;
    }
});
});
</script>
</html>
