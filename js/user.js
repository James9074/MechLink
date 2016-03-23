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
    $("#light_select").hide();
    $("#light_skills").hide();
    $("#light_project").hide();
    $("#lightupload_pics").hide();
    $("#light_edit_name").hide();
    $("#light_edit_status").hide();
    $("#light_edit_location").hide();
    $("#lightshare").hide();
    $("#light_warning").hide();
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
        case "Skillset":
            $("#light_skills").show();
            break;
        case "Project":
            $("#light_project").show();
            break;
        case "Select":
            $("#light_select").show();
            break;
        case "Pics":
            $("#lightupload_pics").show();
            break;
        case "Share":
            $("#lightshare").show();
            break;
        case "Warning":
            $("#light_warning").show();
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
        ApplyError($("#edit_about_message"),false);
    }
    else
        ApplyError($("#edit_about_message",true));
}

/**
 * Linked to the post button on the edit about info modal.
 * @param first_load - True if we should fallback to initial modal after this one is closed
 */
function UserUploadAbout(first_load){
    var aboutTest = $("#edit_about_textarea").val();
    if(aboutTest.length < 100){
        $("#edit_about_message").html("Please fill in at least 100 characters.");
        ApplyError($("#edit_about_message"),false);
    } else if(aboutTest.length > 2000){
        $("#edit_about_message").html("Please use no more than 2000 characters.");
        ApplyError($("#edit_about_message"),true);
    }
    else {
        ApplyError($("#edit_about_message"),false);
        ShowLoading($("#edit_about_buttons"));
        $.ajax({
            url : "user.php",
            type: "POST",
            dataType: 'json',
            data : {"oper":"EditUser",description:aboutTest},
            success: function(data, textStatus, jqXHR)
            {
                //Close the window. If this is the first load time, restore it.
                if(first_load) ModalOpen("Select");

                HideLoading($("#edit_about_buttons"));
                ModalOpen("None");
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

function UserUploadRealName(){
    var rlname = $("#user_rl_name").val();
    $.ajax({
        url : "user.php",
        type: "POST",
        dataType: 'json',
        data : {"oper":"EditUser",rlname:rlname},
        success: function(data, textStatus, jqXHR)
        {
            ModalOpen("None");
            location.reload();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log("Error: " + errorThrown);
            console.dir(JSON.parse(jqXHR.responseText).status);
        }
    });
}

function UserUploadLocation(){
    var userLocation = $("#user_rl_location").val();
    $.ajax({
        url : "user.php",
        type: "POST",
        dataType: 'json',
        data : {"oper":"EditUser",location:userLocation},
        success: function(data, textStatus, jqXHR)
        {
            ModalOpen("None");
            location.reload();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log("Error: " + errorThrown);
            console.dir(JSON.parse(jqXHR.responseText).status);
        }
    });
}

$(document).ready(function() {


    $("#edit_about_textarea").on('change keydown paste input', function(){
        CheckAboutInput();
    });

});


