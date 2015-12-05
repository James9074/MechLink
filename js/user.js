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

function UserUploadAbout(first_load){
    var aboutTest = $("#about").val();
    if(aboutTest.length < 1){
        $("#edit_about_error").html("Please fill in all of the fields...");
    } else {
        $("#edit_about_post").hide();
        $("#edit_about_cancel").hide();
        $("#edit_about_loading").show();
        var ajax = ajaxObj("POST", "user.php");
        ajax.onreadystatechange = function() {
            if(ajaxReturn(ajax) == true) {
                if(ajax.responseText == "error"){
                    $("#edit_about_error").html("Something went wrong...");

                    $("#edit_about_post").show();
                    $("#edit_about_cancel").show();
                    $("#edit_about_loading").hide();
                } else {
                    console.log("RESPOSE: " +ajax.responseText);
                    //Close the window. If this is the first load type, restore it.
                    if(first_load == true) {
                        $("#light_select").show();
                        $("#edit_about_cancel").show();
                        $("#light_edit_about").hide();
                        $("#light_edit_about").html(initialAboutPostHTML);
                        $("#edit_about_error").hide();
                    }

                    //Undo loading effects
                    $("#edit_about_post").show();
                    $("#edit_about_cancel").show();
                    $("#edit_about_loading").hide();
                }
            }
        }
        //TODO: This is a bad way to do this. I really should get the username somewhere else.
        var u = window.location.search.split("&")[0].split("?")[1].split("=")[1];
        ajax.send("edit_about_data="+aboutTest+"&u="+u);
    }
}

$(document).ready(function() {
    if($("#user_about").attr("descriptionprovided") == 'false')
        UserConfigureInitialProfileSetup();
});


