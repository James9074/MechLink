/**
 * Created by james on 12/5/2015.
 */
var initialAboutPostHTML;
function UserConfigureInitialProfileSetup(){
    initialAboutPostHTML = $("#light_edit_about").html();
    $("#edit_about_post").attr("onclick",'UserUploadAbout(true);');
    $("#edit_about_cancel").hide();
    $("#edit_about_desc").html("Tell us about yourself or your business");
    $("#light_edit_about").show();
    $("#fade").show();
}

var originalEditText;
function ModalOpen(type){
    switch (type){
        case 'EditAbout':
            var text = $('#user_about_text').html();
            originalEditText = text;

            //Replace all line breaks, then replace all <br> tags with line breaks
            text = text.replace(/\n/g, '');
            text = text.replace(/<br>/g, '\n');
            $('#about').val(text);
            $("#edit_about_cancel").attr("onclick",'UserEditRestoreText();');
            $("#light_edit_about").show();
            $("#fade").show();
            CheckAboutInput();
            break;

    }
}
function UserEditRestoreText(){
    $('#user_about_text').html(originalEditText);
    $("#light_edit_about").hide();
    $("#fade").hide();
}
function CheckAboutInput(){
    //Replace all line breaks with <br> tags
    $('#user_about_text').html($('#about').val().replace(/\n/g, '<br>'));
    $("#edit_about_message").html($("#about").val().length + "/2000");
    if($("#about").val().length > 100 && $("#about").val().length <= 2000) {
        $("#edit_about_post").removeAttr('disabled');
        $("#edit_about_message").removeClass('status_error');
    }
    else{
        $("#edit_about_message").addClass('status_error');
    }
}

function UserUploadAbout(first_load){
    var aboutTest = $("#about").val();
    if(aboutTest.length < 100){
        $("#edit_about_message").html("Please fill in at least 100 characters.");
        $("#edit_about_message").addClass('status_error');
    } else if(aboutTest.length > 2000){
        $("#edit_about_message").html("Please use no more than 2000 characters.");
        $("#edit_about_message").addClass('status_error');
    }
    else {
        $("#edit_about_message").removeClass('status_error');
        $("#edit_about_post").hide();
        $("#edit_about_cancel").hide();
        $("#edit_about_loading").show();
        var ajax = ajaxObj("POST", "user.php");
        ajax.onreadystatechange = function() {
            if(ajaxReturn(ajax) == true) {
                if(ajax.responseText == "error"){
                    $("#edit_about_message").html("Something went wrong...");

                    $("#edit_about_post").show();
                    $("#edit_about_cancel").show();
                    $("#edit_about_loading").hide();
                } else {
                    //Close the window. If this is the first load type, restore it.
                    if(first_load == true) {
                        $("#light_select").show();
                        $("#edit_about_cancel").show();
                        $("#light_edit_about").hide();
                        $("#light_edit_about").html(initialAboutPostHTML);
                        $("#edit_about_message").hide();
                    }

                    $("#edit_about_post").attr('disabled','true');

                    //Undo loading effects
                    $("#edit_about_post").show();
                    $("#edit_about_cancel").show();
                    $("#edit_about_loading").hide();
                    $("#fade").hide();
                    $("#light_edit_about").hide();
                }
            }
        }
        //TODO: This is a bad way to do this. I really should get the username somewhere else.
        var u = window.location.search.split("&")[0].split("?")[1].split("=")[1];
        ajax.send("edit_about_data="+aboutTest+"&u="+u);
    }
}

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
    var failure = false;
    $("#skillsetErrorMessage").html("");

    var type = $("#skillset_type").val();
    var skillLocation = $("#skillset_location").val();
    var dateStartMonth = $("#skillset_restored_from_month").val();
    var dateStartYear = $("#skillset_restored_from_year").val();
    var dateEndMonth = $("#skillset_restored_to_month").val();
    var dateEndYear = $("#skillset_restored_to_year").val();
    var dateFrom;
    var dateTo;
    var awards = [];
    var skills = $("#skillset_skills").val();
    var schools = [];

    if(type.length < 1) {
        failure = true;
        ApplyError("skillsetProjectDetailsLabel",true);
    }
    else ApplyError("skillsetProjectDetailsLabel",false);

    if(location.length < 1) {
        failure = true;
        ApplyError("skillsetProjectDetailsLabel",true);
    }
    else ApplyError("skillsetProjectDetailsLabel",false);

    if(dateStartMonth.length < 1 || dateStartYear.length != 4 ||
        dateEndMonth.length < 1 || dateEndYear.length != 4){
        failure = true;
        ApplyError("skillset_restored_date_label",true);
    }
    else {
        ApplyError("skillset_restored_date_label", false);
        //date =
        //TODO: How does format date.
    }

    if(failure == false) {
        ApplyError("skillset_project_details",false);

        //TODO: This is a bad way to do this. I really should get the username somewhere else.
        var u = window.location.search.split("&")[0].split("?")[1].split("=")[1];
        $.ajax({
            url : "user.php",
            type: "POST",
            dataType: 'json',
            data : {oper:"AddSkillset",automobiletype:type,location:skillLocation,restoredfrom:dateFrom,
                retoredto:dateTo, awards: JSON.stringify(awards), skills:skills, username:u},
            success: function(data, textStatus, jqXHR)
            {
                if (first_load == true) {
                    /*$("#light_select").show();
                    $("#edit_about_cancel").show();
                    $("#light_edit_about").hide();
                    $("#light_edit_about").html(initialAboutPostHTML);
                    $(
                    "#edit_about_message").hide();*/
                }
                console.log("Success!");
                console.dir(data);

                //Undo loading effects
               // $("#fade").hide();
                //$("#light_edit_about").hide();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                console.log("Error: " + errorThrown);
            }
        });
    }
    else{
        ApplyError("skillset_project_details",true);
        $("#skillset_error_message").html("Please enter all required fields");
    }

    //document.getElementById('light_skills').style.display='none';document.getElementById('fade').style.display='none';

}

$(document).ready(function() {
    if($("#user_about").attr("descriptionprovided") == 'false') {
        UserConfigureInitialProfileSetup();
    }

    $("#about").on('change keydown paste input', function(){
        CheckAboutInput();
    });

});


