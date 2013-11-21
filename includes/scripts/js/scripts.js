$(document).ready(function () {

        function evaluate() { //evaluate checkbox for display panel
            var item = $('#backupcupboard :input');
            var relatedItem = $('#cupboard-drive-panel');

            if (item.is(":checked")) {
                relatedItem.slideDown();
            } else {
                relatedItem.slideUp();
                $('#cupbDrive').val('');
            }
        }


        $('#backupcupboard :input').click(evaluate).each(evaluate);
        //end
        $(function () {
            $('#cupbDrive').change(function () {
                if ($(this).val() == 'new') $('#addDrive').show();
                else $('#addDrive').hide();
            });
        });



        $('li.main').hover(

        function () {
            $('ul', this).stop().animate({
                'opacity': '1'
            }, 300);

        }, function () {
            $('ul', this).stop().animate({
                'opacity': '0'
            }, 300);
        });


        $('textarea.mic').each(function () {
            // Stores the default value for each textarea within each textarea
            $.data(this, 'default', this.value);
        }).focus(function () {
            // If the user has NOT edited the text clear it when they gain focus
            if (!$.data(this, 'edited')) {
                this.value = "";
            }
        }).change(function () {
            // Fires on blur if the content has been changed by the user
            $.data(this, 'edited', this.value != "");
        }).blur(function () {
            // Put the default text back in the textarea if its not been edited
            if (!$.data(this, 'edited')) {
                this.value = $.data(this, 'default');
            }
        });


        $(function () {
            $("#micNo").focus();
        });

        $(function () {
            $('.checkall').click(function () {
                $(this).parents('#micForm').find(':checkbox').attr('checked', this.checked);
            });
        });

        $('#sheetNumber').submit(function () {
            var url = "/session/sessNum.php";
            var text = $('#ssNo').val();

            $.ajax({
                type: "POST",
                url: url,
                data: $("#sheetNumber").serialize(),
                // serializes the form's elements.
                success: function(){
                    $('<div>').attr('class', 'sessNum').html('<h3>#' + text + '<h3>').fadeIn('fast').insertBefore($('.studio')).animate({
                        opacity: 1.0
                    }, 1000);

                    $('#sheetNumber').hide();
                }
            });


            return false;
        });

        var updateTapeList = function(selComp){
            $.ajax({
                type: "POST",
                url: "/session/sessUpdateAjax.php",
                data: 'tapeOwner='+selComp,

                success: function(data){

                    var jsonObj = $.parseJSON(data);

                    $.each(jsonObj, function(index, value){
                        var seperator = ' | ';
                        var text = '<option value="'+value.cupbID+'">';
                            text += 'ATS-'+value.cupbID+seperator;
                            text += value.cupbName+seperator;
                            text += value.cliName+seperator;
                            text += value.cmpName;
                            text += '</option>';

                        $('#cupbDrive').prepend(text);
                    });
                }
            });
        };

        $('#sessionEdit').submit(function(){
            var url = "/session/sessUpdateAjax.php",
            that = this,
            formDetails = $("#sessionEdit").serialize(),
            fixInput = $('#fixsearch'),
            cmpInput = $('#composersearch'),
            prjInput = $('#projsearch'),
            frmActive = fixInput.length+cmpInput.length+prjInput.length;

            
            $.ajax({
                type: "POST",
                url: url,
                data: formDetails,
                // serializes the form's elements.
                success: function(data){

                    var result = $.parseJSON(data);
                    if(fixInput.length>0 && result.fixName !== null){
                        fixInput.remove();
                        frmActive--;
                        $('.fixer').prepend(result.fixName);
                    }

                    if(cmpInput.length>0 && result.cmpName !== null){
                        cmpInput.remove();
                        frmActive--;

                        $('.composer').prepend(result.cmpName);
                        
                        if(result.cliName !== result.cmpName){
                            updateTapeList(result.cmpID);
                        }
                    }

                    if(prjInput.length>0 && result.prjName !== null){
                        prjInput.remove();
                        frmActive--;
                        $('.project').prepend(result.prjName);
                    }

                    if(frmActive === 0){
                        $('#bakSessEdit').remove();
                        
                    }


                }
            });
            return false;
        });

        $("#savedMics").hide();
        $("#showMics").show();

        $('#showMics').click(function () {              // toggle hide and show on the saved mics list for a backup entry
            $("#savedMics").slideToggle();
        });


        $('#micNo').keyup(function () {                 //when 4 digits are entered into the mic form submit the form (each microphone is referenced by a 4 digit number so this allows quick entry into the system
            if (this.value.length == 4) {
                $('#submitMic').click();
            }
        });

        $('#sessCont option').each(function () {       // if editing a session that is the parent session for a continuation (first session of multiple sessions) then dont allow the user to link it to another session
            if ($(this).text() == 'Parent') {
                $('#continueSelect').hide();
            }
        });

        $('.deleteLink').click(function(e){
            $this=$(this);
            $this.parent().css('background', 'red');
            if (!confirm('Are you sure you want to delete this session? It cannot be undone.')){
                e.preventDefault();
                $this.parent().css('background','none');
            }
        });

        $(".Eassigned").each(function () {              // annoying alert box to stop people submitting microphones when an error has flagged (it would clear the error otherwise and people wouldn't necessarily pay attention to it
            alert("This mic is already assigned to a session Please follow the link to correct this.");
        });
        $(".REassigned").each(function () {             //same as above
            alert("This mic must be returned to the correct session Please follow the link.");
        });

        $('#form1').submit(function () {                        //ensure that when submitting a new backup that a backup drive has been checked, normal 'required' values in html wont work on radio boxes
            if ($('input:radio[name=bakLoc]', this).is(':checked')) {
                // everything's fine...
            } else {
                alert('Please select a backup drive!');         //if a backup drive hasn't been selected put an annoying alert on the screen and return false to stop the form submitting
                return false;
            }
        });
        
        $('#newSession').submit(function () {                   //ensure that when submitting a new session that a studio number has been checked, normal 'required' values in html wont work on radio boxes
            if ($('input:radio[name=studio]', this).is(':checked')) {
                // everything's fine...
            } else {
                alert('You must choose a Studio!');         //if a studio hasn't been selected put an annoying alert on the screen and return false to stop the form submitting
                return false;
            }
        });
        
        var jsonObj;
        $(".radio").click(function(){        // Whenever a different studio is selected in new session page, use Ajax call to populate session continuation drop down box
            
            var url="/session/get_cont_sess.php";
            var val=$(".radio:checked").val();  // Get the value of the currently selected radio box (studio number)
            
            $.ajax({                            //Start and Ajax call
                type:"POST",
                url: url,
                data: { term: val },            //send the studio value as a parameter to the php page, can be accessed with $_REQUEST or $_GET
                datatype: "json",
                
                success: function(data){        //If the query is succesful then pass the data received to the function
                    var select = $("#sessCont").empty();        //empty the contents of the select box/initialise it
                    jsonObj = $.parseJSON(data);            //parse the received JSON to give you a a javascript object for each result
                    select.append('<option value="0">N/A</option>');     //append the default value to the top of the select box
                    $.each(jsonObj, function(){                         //for each javascript objec (each result from the ajax call)
                        
                        var project=this.project;                       //if project returns empty(null) then set project to be an 'empty' string
                        var composer = this.composer;
                        if(project === null){
                            project=' ';
                        }
                        if(this.composer === null){
                            composer=' ';
                        }else{
                            composer+=' | ';
                        }
                    
                        select.append('<option value='+ this.bakID +'>'+ this.date + ' | ' + this.staff + ' | ' + this.client +' | '+ composer + project +'</option>'); // append the values correctly to a option for each result
                    });
                 }
            });
        });

        var fillSessForm=function(selected){
                    $('#engsearch').val(selected.engineer);
                    $('#engineerID').val(selected.engineerID);
                    $('#astsearch').val(selected.assistant);
                    $('#assistantID').val(selected.assistantID);
                    $('#clisearch').val(selected.client);
                    $('#clientID').val(selected.clientID);
                    $('#composersearch').val(selected.composer);
                    $('#composerID').val(selected.composerID);
                    $('#fixsearch').val(selected.fixer);
                    $('#fixerID').val(selected.fixerID);
                    $('#projsearch').val(selected.project);
                    $('#projectID').val(selected.projectID);
                };

        $("#sessCont").change(function(event) {
            
            var that = this,
                result,
                selected;

            if(jsonObj){
                result = $.grep(jsonObj, function(session) {
                    return session.bakID == $(that).val();
                });

                selected = result[0];

                fillSessForm(selected);
            }else{

                var url ="/session/get_cont_sess.php";
                var bakID=$(that).val();



                $.ajax({                            //Start and Ajax call
                type:"POST",
                url: url,
                data: { Id: bakID },            //send the studio value as a parameter to the php page, can be accessed with $_REQUEST or $_GET
                datatype: "json",

                    success: function(data){
                        result = $.parseJSON(data);
                        if(result !== null){
                            selected = result[0];

                            fillSessForm(selected);
                        }



                    }

                });

                
            }

        });

    }); // end of document ready function