<script>
  function UploadNewProject(){
    var formError = false;
    $("#project_error_message").html("");

    var type = $("#project_type").val();
    var projectLocation = $("#project_location").val();
    var details = $("#project_details").val();
    var skills = $("#project_skills").val();

    //region Skillset Checks
    if(type.length < 1) {
      formError = true;
      ApplyError($("#project_type"),true);
    }
    else ApplyError($("#project_type"),false);

    if(projectLocation.length < 1) {
      formError = true;
      ApplyError($("#project_location"),true);
    }
    else ApplyError($("#project_location"),false);

    if(details.length < 1) {
      formError = true;
      ApplyError($("#project_details"),true);
    }
    else ApplyError($("#project_details"),false);


    if(formError){
      ApplyError($("#project_title"),true);
      $("#project_error_message").html("Please fix all fields");
    }
    else{
      ApplyError($("#project_title"),false);
      $("#project_error_message").html("");

      $.ajax({
        url : "user.php",
        type: "POST",
        dataType: 'json',
        data : {"oper":"AddProject",automobiletype:type,location:projectLocation, details:details, skills:skills},
        success: function(data, textStatus, jqXHR){
          var newLoc = "http://www.mechlink.org/project.php?u="+data['newProject'].username+"&id="+data['newProject'].id;
          window.location.href = newLoc;
          return;
          //ModalOpen("None");
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
</script>

<div id="light_project" class="white_content_project">
  <div align="center">
    <div id="boxform">
      <div id="form"> <br />
        <span id="project_title"><b>Describe your project</b></span> <br />
        <br />
        <div>
          <input id="project_type" type="text" class="formfields" spellcheck="false" tabindex="1" onkeyup="restrict('')" maxlength="60" placeholder="Type of automobile (required)">
        </div>
        <div>
          <input id="project_location" type="text" class="formfields" spellcheck="false" tabindex="2" onkeyup="restrict('')" maxlength="60" placeholder="Location (required)">
        </div>
        <div>
          <textarea id="project_details" class="formfields" placeholder="Restoration details (required)" tabindex="3"></textarea>
        </div>
        <div>
          <textarea id="project_skills" class="formfields" placeholder="Required skills (optional)" tabindex="4"></textarea>
        </div>
        <br />
        <span id="project_error_message" class="status_error"></span>
        <br/>
        <br />
        <button onclick = "UploadNewProject();" tabindex="6">Post</button>
        <button onclick = "document.getElementById('light_project').style.display='none';document.getElementById('light_select').style.display='block'" tabindex="7">Cancel</button>
        <br />
        <br />
      </div>
    </div>
  </div>
</div>
<!--light_skills--> 

