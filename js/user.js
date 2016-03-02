/**
 * Created by james on 12/5/2015.
 */
function UserConfigureInitialProfileSetup(){
    $("#edit_about_post").attr("onclick",'UserUploadAbout(true);');
    $("#edit_about_cancel").hide();
    $("#edit_about_desc").html("Tell us about yourself or your business");
    $("#light_edit_about").show();
    $("#fade").show();
}

function ModalOpen(type){
    //Close all modals, prep for new modal
    $("#light_edit_about").hide();
    $("#fade").show();

    //Open desired Modal
    switch (type){
        case 'EditAbout':
            //Replace all line breaks, then replace all <br> tags with line breaks
            var text = $('#user_about_text').html();
            text = text.replace(/\n/g, '');
            text = text.replace(/<br>/g, '\n');

            //Set cancel button and fill the modal's textbox
            $('#edit_about_textarea').val(text);
            $("#edit_about_cancel").attr("onclick",'ModalOpen("None");');

            //Unlock the post button
            CheckAboutInput();

            //Reset errors and show the modal
            $("#light_edit_about").show();
            $("#edit_about_buttons").show();
            $("#edit_about_message").hide();

            break;
        default:
            $("#fade").hide();
            break;
    }
}
function CheckAboutInput(){
    //Replace all line breaks with <br> tags
    $('#user_about_text').html($('#edit_about_textarea').val().replace(/\n/g, '<br>'));
    $("#edit_about_message").html($("#edit_about_textarea").val().length + "/2000");
    if($("#edit_about_textarea").val().length > 100 && $("#edit_about_textarea").val().length <= 2000) {
        $("#edit_about_post").removeAttr('disabled');
        $("#edit_about_message").removeClass('status_error');
    }
    else{
        $("#edit_about_message").addClass('status_error');
    }
}

function UserUploadAbout(first_load){
    var aboutTest = $("#edit_about_textarea").val();
    if(aboutTest.length < 100){
        $("#edit_about_message").html("Please fill in at least 100 characters.");
        $("#edit_about_message").addClass('status_error');
    } else if(aboutTest.length > 2000){
        $("#edit_about_message").html("Please use no more than 2000 characters.");
        $("#edit_about_message").addClass('status_error');
    }
    else {
        $("#edit_about_message").removeClass('status_error');
        $("#edit_about_buttons").hide();
        $("#edit_about_loading").show();
        $.ajax({
            url : "user.php",
            type: "POST",
            dataType: 'json',
            data : {"oper":"EditAbout",about:aboutTest},
            success: function(data, textStatus, jqXHR)
            {
                //Close the window. If this is the first load type, restore it.
                if(first_load) {
                   // $("#light_select").show();

                }

                $("#edit_about_post").attr('disabled','true');

                //Undo loading effects
                $("#light_select").show();
                $("#edit_about_loading").hide();
                $("#fade").hide();
                $("#light_edit_about").hide();
                console.log("Success!");
                console.dir(data);

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $("#edit_about_message").html("Something went wrong...");

                $("#edit_about_post").show();
                $("#edit_about_cancel").show();
                $("#edit_about_loading").hide();
                console.log("Error: " + errorThrown);
                console.dir(JSON.parse(jqXHR.responseText).status);
            }
        });
    }
}

/**
 * Applies an error class to some text-holding ID
 * @param aID - The ID to toggle and error on.
 * @param aAdd - True for error, false to remove error
 */
function ApplyError(aID, aAdd){
    if(aAdd)
        $("#"+aID).addClass("status_error");
    else
        $("#"+aID).removeClass("status_error");
}

/**
 * Linked to the "Post" button on the Add a New Skillset Overlay
 */
function UserUploadNewSkillset(){
    var formError = false;
    $("#skillsetErrorMessage").html("");

    var type = $("#skillset_type").val();
    var skillLocation = $("#skillset_location").val();
    var dateStartMonth = $("#skillset_restored_from_month").val();
    var dateStartYear = $("#skillset_restored_from_year").val();
    var dateEndMonth = $("#skillset_restored_to_month").val();
    var dateEndYear = $("#skillset_restored_to_year").val();
    var dateStart = Date.parse(dateStartYear + "-" + dateStartMonth + "-01");
    var dateEnd = Date.parse(dateEndYear + "-" + dateEndMonth + "-01");
    var awards = [];
    var skills = $("#skillset_skills").val();
    var schools = [];


    //region Skillset Checks
    if(type.length < 1) {
        formError = true;
        ApplyError("skillsetProjectDetailsLabel",true);
    }
    else ApplyError("skillsetProjectDetailsLabel",false);

    if(location.length < 1) {
        formError = true;
        ApplyError("skillsetProjectDetailsLabel",true);
    }
    else ApplyError("skillsetProjectDetailsLabel",false);

    if(dateStart == null || dateEnd == null || dateStart > dateEnd) {
        formError = true;
        ApplyError("skillset_restored_date_label", true);
    }
    else {
        ApplyError("skillset_restored_date_label", false);
        dateStart = dateStart.toString('yyyy-MM-dd');
        dateEnd = dateEnd.toString('yyyy-MM-dd');
    }
    //endregion

    if(formError){
        ApplyError("skillset_project_details",true);
        $("#skillset_error_message").html("Please fix all fields");
    }
    else{
        ApplyError("skillset_project_details",false);
        $("#skillset_error_message").html("");

        //TODO: This is a bad way to do this. I really should get the username somewhere else.
        var u = window.location.search.split("&")[0].split("?")[1].split("=")[1];
        $.ajax({
            url : "user.php",
            type: "POST",
            dataType: 'json',
            data : {"oper":"AddSkillset",automobiletype:type,location:skillLocation,restoredfrom:dateStart,
                restoredto:dateEnd, awards: JSON.stringify(awards), skills:skills, username:u},
            success: function(data, textStatus, jqXHR){
                console.dir(data);
            },
            error: function (jqXHR, textStatus, errorThrown){
                if(DEBUG) {
                    console.log(errorThrown + ": ");
                    console.log(JSON.parse(jqXHR.responseText));
                }
            }
        });
    }
}

$(document).ready(function() {
    if($("#user_about").attr("descriptionprovided") == 'false') {
        UserConfigureInitialProfileSetup();
    }

    $("#edit_about_textarea").on('change keydown paste input', function(){
        CheckAboutInput();
    });

});


