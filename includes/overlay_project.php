<script>

  function EditProject(){
    var id = "<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>";
    if(id != "") {
      PrepProjectEdit(id);
      ModalOpen("Project");
    }
  }

  function PrepProjectEdit(aID){
    if(typeof aID == "undefined") aID = -1;
    $.ajax({
      url : "user.php",
      type: "POST",
      dataType: 'json',
      data : {"oper":"GetProject",id:aID},
      success: function(data, textStatus, jqXHR){
        console.dir(data);
        if(data['project'].id == null){
          $("#project_id").val("");
          return;
        }

        $("#project_id").val(aID);
        $("#project_type").val(data['project'].automobiletype);
        $("#project_location").val(data['project'].location);
        $("#project_details").val(data['project'].details);
        $("#project_skills").val(data['project'].skills);
      },
      error: function (jqXHR, textStatus, errorThrown){
        if(DEBUG) {
          console.log(errorThrown + ": ");
          console.log(JSON.parse(jqXHR.responseText));
        }
      }
    });
  }



  function PromptDeleteProject(){
    var id = "<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>";
    if(id != "") {
      ModalOpen("Warning");
      $("#light_warning").find("#warning_question").html("Are you sure you want to delete this project?");
      $("#light_warning").find("#yesButton").attr("onclick","DeleteProject("+id+");");
    }
  }

  function DeleteProject(aID){
    $.ajax({
      url : "user.php",
      type: "POST",
      dataType: 'json',
      data : {"oper":"DeleteProject",id:aID},
      success: function(data, textStatus, jqXHR) {
        var newLoc = "http://www.mechlink.org/projects.php?u="+data['username'];
        window.location.href = newLoc;
      },
      error: function (jqXHR, textStatus, errorThrown){
        if(DEBUG) {
          console.log(errorThrown + ": ");
          console.log(JSON.parse(jqXHR.responseText));
        }
      }
    });
  }

  function UploadNewProject(){
    var formError = false;
    $("#project_error_message").html("");

    var id = $("#project_id").val();
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
        data : {"oper":"AddOrEditProject",id:id,automobiletype:type,location:projectLocation, details:details, skills:skills},
        success: function(data, textStatus, jqXHR){
          var newLoc = "http://www.mechlink.org/project.php?u="+data['project'].username+"&id="+data['project'].id;
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
        <input id="project_id" type="hidden">
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

