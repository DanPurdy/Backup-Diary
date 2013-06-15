</div>
        </div> 
        <div id="footer">
            <div id="footerWrap">
              
            </div>
        </div>
        
        
    </body>
    <script>
$(document).ready(function(){
    
    function evaluate(){                //evaluate checkbox for display panel
    
        var item = $('#backupcupboard :input');
        var relatedItem = $('#cupboard-drive-panel');

        if(item.is(":checked")){
            relatedItem.slideDown();
        }else{
            relatedItem.slideUp();
            $('#cupbDrive').val('');
        }
    }
    

$('#backupcupboard :input').click(evaluate).each(evaluate);
//end

    $(function() {
  $('#cupbDrive').change(function() {
    if ($(this).val() == 'new')
      $('#addDrive').show();
    else
      $('#addDrive').hide();
  });
});


   
    $('li.main').hover(
			function() {
                            $('ul', this).stop().animate({'opacity':'1'},300); 
                        
                        },
			function() { $('ul', this).stop().animate({'opacity': '0'},300); 
                        });


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

$('#sheetNumber').submit(function(){
    var url="/session/sessNum.php";
    var text=$('#ssNo').val();

    $.ajax({
           type: "POST",
           url: url,
           data: $("#sheetNumber").serialize(), // serializes the form's elements.
           success: function(data)
           {
               $('<div>')
                .attr('class', 'sessNum')
                .html('<h3>#'+text+'<h3>')
                .fadeIn('fast')
                .insertBefore($('.studio'))
                .animate({opacity: 1.0}, 1000);

               $('#sheetNumber').hide();
           }
         });


    return false;
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
        $('#continueSelect').hide();
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
