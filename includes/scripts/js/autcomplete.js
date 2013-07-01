$(document).ready(function () {
    //Prevent Enter key presses submitting the form (useful for my inability to remember not to!)
    // Allow them in textarea's though
    $(window).keydown(function (event) {
        if (event.keyCode == 13 && event.target.nodeName != 'TEXTAREA') {
            event.preventDefault();
            return false;
        }
    });

    function engineer(message) {
        $("#engineerID").val(message);
    };

    function assistant(message) {
        $("#assistantID").val(message);
    };

    function client(message) {
        $("#clientID").val(message);
    };

    function composer(message) {
        $("#composerID").val(message);
    };

    function project(message) {
        $("#projectID").val(message);
    };

    function fixer(message) {
        $("#fixerID").val(message);
    };


    // Autcomplete functions for each search box
    $('#engsearch').autocomplete({
        source: '../ajax/suggest_engineer.php',
        minLength: 2,
        //What to do when search result selected
        select: function (event, ui) {
            engineer(ui.item.post);
        },
        //What to do once search response is received(inserts value 0 if no result
        response: function (event, ui) {
            // ui.content is the array that's about to be sent to the response callback.
            if (ui.content.length === 0) {
                $("#engineerID").val("0");
            }
        }

    });

    $('#astsearch').autocomplete({
        source: '../ajax/suggest_assistant.php',
        minLength: 2,
        select: function (event, ui) {
            assistant(ui.item.post);
        },
        response: function (event, ui) {
            // ui.content is the array that's about to be sent to the response callback.
            if (ui.content.length === 0) {
                $("#assistantID").val("0");
            }
        }

    });

    $('#clisearch').autocomplete({
        source: '../ajax/suggest_client.php',
        minLength: 2,
        select: function (event, ui) {
            client(ui.item.post);
        },
        response: function (event, ui) {
            // ui.content is the array that's about to be sent to the response callback.
            if (ui.content.length === 0) {
                $("#clientID").val("0");
            }
        }

    });

    $('#composersearch').autocomplete({
        source: '../ajax/suggest_composer.php',
        minLength: 2,
        select: function (event, ui) {
            composer(ui.item.post);
        },
        response: function (event, ui) {
            // ui.content is the array that's about to be sent to the response callback.
            if (ui.content.length === 0) {
                $("#composerID").val("0");
            }
        }

    });

    $('#projsearch').autocomplete({
        source: '../ajax/suggest_project.php',
        minLength: 2,
        select: function (event, ui) {
            project(ui.item.post);
        },
        response: function (event, ui) {
            // ui.content is the array that's about to be sent to the response callback.
            if (ui.content.length === 0) {
                $("#projectID").val("0");
            }
        }

    });

    $('#fixsearch').autocomplete({
        source: '../ajax/suggest_fixer.php',
        minLength: 2,
        select: function (event, ui) {
            fixer(ui.item.post);
        },
        response: function (event, ui) {
            // ui.content is the array that's about to be sent to the response callback.
            if (ui.content.length === 0) {
                $("#fixerID").val("0");
            }
        }
    });




});