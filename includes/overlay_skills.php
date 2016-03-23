<script>
  $( document ).ready(function() {
    AddSchools();
  });

  function AddDegree(aSchoolID){
    for(var i=0; i < 5; i++){
      var degree = $("#school_"+aSchoolID).find("#school_degree_"+i);
      if ($(degree).css("display") == "none"){
        $(degree).show(); break;
      }
    }
  }

  function HideSchools(){
    for(var i=0; i < 5; i++){
      $("#school_"+i).hide();
    }
  }

  function AddSchool(){
    for(var i=0; i < 5; i++){
      if ($("#school_"+i).css("display") == "none"){
        $("#school_"+i).show(); break;
      }
    }
  }

  function AddAward(){
    for(var i=0; i < 5; i++){
      if ($("#skillset_award_"+i).css("display") == "none"){
        $("#skillset_award_"+i).show(); break;
      }
    }
  }

  function AddSchools(){
    var template = $("#skillset_schools #school_1").html();

    for(var i=2; i < 5; i++){
      $("#skillset_schools").append('<div style="display:none;" id="school_'+i+'" class="skillset_school_container">'+template+'</div>');
      $('#school_'+i + ' #school_title').text("School #"+i);
      $('#school_'+i + ' #school_add_degree').attr("onclick","AddDegree("+i+")");

    }
  }

  function PromptDeleteSkill(){
    var id = "<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>";
    if(id != "") {
      ModalOpen("Warning");
      $("#light_warning").find("#warning_question").html("Are you sure you want to delete this skillset?");
      $("#light_warning").find("#yesButton").attr("onclick","DeleteSkill("+id+");");
    }
  }

  function DeleteSkill(aID){
    $.ajax({
      url : "user.php",
      type: "POST",
      dataType: 'json',
      data : {"oper":"DeleteSkillset",id:aID},
      success: function(data, textStatus, jqXHR) {
        var newLoc = "http://www.mechlink.org/skills.php?u="+data['username'];
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

  function EditSkill(){
    var id = "<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>";
    if(id != "") {
      PrepEdit(id);
      ModalOpen("Skillset");
    }
  }

  function PrepEdit(aID){
    if(typeof aID == "undefined") aID = -1;
    $.ajax({
      url : "user.php",
      type: "POST",
      dataType: 'json',
      data : {"oper":"GetSkillset",id:aID},
      success: function(data, textStatus, jqXHR){
        console.dir(data);
        if(data['skillset'].id == null){
          $("#skillset_id").val("");
          return;
        }
        $("#skillset_type").val(data['skillset'].automobiletype);
        $("#skillset_id").val(aID);
        $("#skillset_location").val(data['skillset'].location);
        var fromDate = Date.parse(data['skillset'].restoredfrom);
        var toDate = Date.parse(data['skillset'].restoredto);
        $("#skillset_restored_from_month").val(fromDate.getMonth()+1);
        $("#skillset_restored_from_year").val(fromDate.getFullYear());
        $("#skillset_restored_to_month").val(toDate.getMonth()+1);
        $("#skillset_restored_to_year").val(toDate.getFullYear());

        if(data['skillset'].award1 != ""){
          $("#skillset_award_1").val(data['skillset'].award1);
          $("#skillset_award_1").show();
        }
        if(data['skillset'].award2 != ""){
          $("#skillset_award_2").val(data['skillset'].award2);
          $("#skillset_award_2").show();
        }
        if(data['skillset'].award3 != ""){
          $("#skillset_award_3").val(data['skillset'].award3);
          $("#skillset_award_3").show();
        }
        if(data['skillset'].award4 != ""){
          $("#skillset_award_4").val(data['skillset'].award4);
          $("#skillset_award_4").show();
        }

        $("#skillset_skills").val(data['skillset'].skills);

        HideSchools();
        for(var i=1; i <= data['skillset'].schools.length;i++){
          AddSchool();
          var schoolData = data['skillset'].schools[i-1];
          var school = $("#school_"+i);
          $(school).find("#school_id").val(schoolData.id);
          $(school).find("#school_name").val(schoolData.name);
          $(school).find("#school_location").val(schoolData.location);

          if(schoolData.attendedfrom != null) {
            var fromDate = Date.parse(schoolData.attendedfrom);
            $("#school_attended_from_month").val(fromDate.getMonth() + 1);
            $("#school_attended_from_year").val(fromDate.getFullYear());
          }
          if(schoolData.attendedto != null) {
            var toDate = Date.parse(schoolData.attendedto);
            $("#school_attended_to_month").val(toDate.getMonth() + 1);
            $("#school_attended_to_year").val(toDate.getFullYear());
          }

          $(school).find("#school_awards").val(schoolData.awards);
          $(school).find("#school_degree_1").val(schoolData.degree1);
          $(school).find("#school_degree_2").val(schoolData.degree2);
          $(school).find("#school_degree_3").val(schoolData.degree3);
          $(school).find("#school_degree_4").val(schoolData.degree4);

        }

      },
      error: function (jqXHR, textStatus, errorThrown){
        if(DEBUG) {
          console.log(errorThrown + ": ");
          console.log(JSON.parse(jqXHR.responseText));
        }
      }
    });
  }


  /**
   * Linked to the "Post" button
   */
  function UploadNewSkillset(){
    var formError = false;
    $("#skillsetErrorMessage").html("");

    var type = $("#skillset_type").val();
    var id = $("#skillset_id").val();
    var skillLocation = $("#skillset_location").val();
    var dateStartMonth = $("#skillset_restored_from_month").val();
    var dateStartYear = $("#skillset_restored_from_year").val();
    var dateEndMonth = $("#skillset_restored_to_month").val();
    var dateEndYear = $("#skillset_restored_to_year").val();
    var dateStart = Date.parse(dateStartYear + "-" + dateStartMonth + "-01");
    var dateEnd = Date.parse(dateEndYear + "-" + dateEndMonth + "-01");
    var awards = [];
    awards[0] = $("#skillset_award_1").val();
    awards[1] = $("#skillset_award_2").val();
    awards[2] = $("#skillset_award_3").val();
    awards[3] = $("#skillset_award_4").val();
    var skills = $("#skillset_skills").val();
    var schools = [];


    //region Skillset Checks
    if(type.length < 1) {
      formError = true;
      ApplyError($("#skillset_type"),true);
    }
    else ApplyError($("#skillset_type"),false);

    if(skills.length < 1) {
      formError = true;
      ApplyError($("#skillset_skills"),true);
    }
    else ApplyError($("#skillset_skills"),false);

    if(dateStart == null || dateEnd == null || dateStart > dateEnd) {
      formError = true;
      ApplyError($("#skillset_restored_date_label"),true);
    } else {
      ApplyError($("#skillset_restored_date_label"), false);
      dateStart = dateStart.toString('yyyy-MM-dd');
      dateEnd = dateEnd.toString('yyyy-MM-dd');
    }

    //Schools
    var schoolsData = [];
    for(var i=1; i < 5; i++){
      var school = $("#school_"+i);
      if ($(school).css("display") != "none"){

        var nameElement = $(school).find("#school_name");
        var name = $(nameElement).val()
        if(name.length < 1) {
          formError = true;
          ApplyError($(nameElement),true);
        }
        else ApplyError($(nameElement),false);

        var school_location = $(school).find("#school_location").val();
        var school_id = $(school).find("#school_id").val();
        var attendedfromMonth = $(school).find("#school_attended_from_month").val();
        var attendedfromYear = $(school).find("#school_attended_from_year").val();
        var attendedtoMonth = $(school).find("#school_attended_to_month").val();
        var attendedtoYear = $(school).find("#school_attended_to_year").val();
        var attendedfrom = Date.parse(attendedfromYear + "-" + attendedfromMonth + "-01");
        var attendedto = Date.parse(attendedtoYear + "-" + attendedtoMonth + "-01");

        if(attendedfrom != null) attendedfrom = attendedfrom.toString('yyyy-MM-dd');
        if(attendedto != null) attendedto = attendedto.toString('yyyy-MM-dd');

        var school_awards = $(school).find("#school_awards").val();
        var degree1 = $(school).find("#school_degree_1").val();
        var degree2 = $(school).find("#school_degree_2").val();
        var degree3 = $(school).find("#school_degree_3").val();
        var degree4 = $(school).find("#school_degree_4").val();

        schoolsData[i-1] = {id : school_id,name:name, location:school_location, attendedfrom:attendedfrom, attendedto:attendedto,
            awards: school_awards, degrees: [degree1,degree2,degree3,degree4]};
      }
    }

    //endregion

    if(formError){
      ApplyError($("#skillset_project_details"),true);
      $("#skillset_error_message").html("Please fix all fields");
    }
    else{
      ApplyError($("#skillset_project_details"),false);
      $("#skillset_error_message").html("");

      //var schools = JSON.stringify([{name:"UNCC",location:"Charlotte NC", attendedfrom:"2001-01-10", attendedto:"2001-01-10", awards: "none", degrees: ['test1','test2','test3','test4']}]);

      $.ajax({
        url : "user.php",
        type: "POST",
        dataType: 'json',
        data : {"oper":"AddOrEditSkillset",id:id,automobiletype:type,location:skillLocation,restoredfrom:dateStart,
          restoredto:dateEnd, awards: JSON.stringify(awards), skills:skills, schools: JSON.stringify(schoolsData)},
        success: function(data, textStatus, jqXHR){
          var newLoc = "http://www.mechlink.org/skill.php?u="+data['skillset'].username+"&id="+data['skillset'].id;
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
<style>
  .skillset_school_container{
    padding-top:10px;
  }
</style>
<div id="light_skills" class="white_content_skills">
  <div align="center">
    <div id="boxform">
      <div id="form_long"> <br />
        <input id="skillset_id" type="hidden">
        <span id="skillset_project_details"><b>Restoration project details</b></span> <br />
        <br />
        <div>
          <input id="skillset_type" type="text" class="formfields" spellcheck="false" tabindex="1" onkeyup="restrict('')" maxlength="100" placeholder="Type of automobile (required)">
        </div>
        <div>
          <input id="skillset_location" type="text" class="formfields" spellcheck="false" tabindex="2" onkeyup="restrict('')" maxlength="60" placeholder="City + state or location (optional)">
        </div>
        <br />
        <span id="skillset_restored_date_label">Restoration Date Range (required):</span> <br />
        <select id="skillset_restored_from_month" class="formfields_select_short" tabindex="3">
          <option value="">Month</option>
          <option value="01">January</option>
          <option value="02">February</option>
          <option value="03">March</option>
          <option value="04">April</option>
          <option value="05">May</option>
          <option value="06">June</option>
          <option value="07">July</option>
          <option value="08">August</option>
          <option value="09">September</option>
          <option value="10">October</option>
          <option value="11">November</option>
          <option value="12">December</option>
        </select>
        <input id="skillset_restored_from_year" type="text" class="formfields_short" spellcheck="false" tabindex="4" onkeyup="restrict('')" maxlength="4" placeholder="Year">
        to
        <select id="skillset_restored_to_month" class="formfields_select_short" tabindex="5">
          <option value="">Month</option>
          <option value="01">January</option>
          <option value="02">February</option>
          <option value="03">March</option>
          <option value="04">April</option>
          <option value="05">May</option>
          <option value="06">June</option>
          <option value="07">July</option>
          <option value="08">August</option>
          <option value="09">September</option>
          <option value="10">October</option>
          <option value="11">November</option>
          <option value="12">December</option>
        </select>
        <input id="skillset_restored_to_year" type="text" class="formfields_short" spellcheck="false" tabindex="6" onkeyup="restrict('')" maxlength="4" placeholder="Year">
        <div id="skillset_awards">
          <input id="skillset_award_1" type="text" class="formfields" spellcheck="false" tabindex="7" onfocus="emptyElement('status')" onkeyup="restrict('')" maxlength="60" placeholder="Award received (optional)">
          <input id="skillset_award_2" style="display:none" type="text" class="formfields" spellcheck="false" tabindex="7" onfocus="emptyElement('status')" onkeyup="restrict('')" maxlength="60" placeholder="Award received (optional)">
          <input id="skillset_award_3" style="display:none" type="text" class="formfields" spellcheck="false" tabindex="7" onfocus="emptyElement('status')" onkeyup="restrict('')" maxlength="60" placeholder="Award received (optional)">
          <input id="skillset_award_4" style="display:none" type="text" class="formfields" spellcheck="false" tabindex="7" onfocus="emptyElement('status')" onkeyup="restrict('')" maxlength="60" placeholder="Award received (optional)">
        </div>
        <br />
        <button tabindex="8" onclick="AddAward();"><img src="images/add_btn.png" />&nbsp;&nbsp;Add an award</button>
        <br />
        <br />
        <br />
        <b>Specific skills</b> <br />
        <br />
        <div>
          <textarea class="modal_description_area" id="skillset_skills" placeholder="Describe your specific skills with this type of automobile (required)" tabindex="9"></textarea>
        </div>
        <br />
        <br />
        <br />

        <b>Education & training</b> <br />
        <br />
        <div id="skillset_schools">
          <div id="school_1" style="display: none" class="skillset_school_container">
            <input id="school_id" type="hidden">
            <b id="school_title">School #1</b>
            <div>
              <input id="school_name" type="text" class="formfields" spellcheck="false" tabindex="10" onkeyup="restrict('')" maxlength="100" placeholder="Name of school (required)">
            </div>
            <div>
              <input id="school_location" type="text" class="formfields" spellcheck="false" tabindex="11" onkeyup="restrict('')" maxlength="60" placeholder="City + state or location (optional)">
            </div>
            <br />
            Attended from (optional): <br />
            <select id="school_attended_from_month" class="formfields_select_short" tabindex="12">
              <option value="">Month</option>
              <option value="">January</option>
              <option value="">February</option>
              <option value="">March</option>
              <option value="">April</option>
              <option value="">May</option>
              <option value="">June</option>
              <option value="">July</option>
              <option value="">August</option>
              <option value="">September</option>
              <option value="">October</option>
              <option value="">November</option>
              <option value="">December</option>
            </select>
            <input id="school_attended_from_year" type="text" class="formfields_short" spellcheck="false" tabindex="13" onkeyup="restrict('')" maxlength="4" placeholder="Year">
            to
            <select id="school_attended_to_month" class="formfields_select_short" tabindex="14">
              <option value="">Month</option>
              <option value="">January</option>
              <option value="">February</option>
              <option value="">March</option>
              <option value="">April</option>
              <option value="">May</option>
              <option value="">June</option>
              <option value="">July</option>
              <option value="">August</option>
              <option value="">September</option>
              <option value="">October</option>
              <option value="">November</option>
              <option value="">December</option>
            </select>
            <input id="school_attended_to_year" type="text" class="formfields_short" spellcheck="false" tabindex="15" onkeyup="restrict('')" maxlength="4" placeholder="Year">
            <div id="school_degrees">
              <input id="school_degree_1" type="text" class="formfields" spellcheck="false" tabindex="16" onfocus="emptyElement('status')" onkeyup="restrict('')" maxlength="60" placeholder="Degree (optional)">
              <input id="school_degree_2" style="display:none" type="text" class="formfields" spellcheck="false" tabindex="16" onfocus="emptyElement('status')" onkeyup="restrict('')" maxlength="60" placeholder="Degree (optional)">
              <input id="school_degree_3" style="display:none" type="text" class="formfields" spellcheck="false" tabindex="16" onfocus="emptyElement('status')" onkeyup="restrict('')" maxlength="60" placeholder="Degree (optional)">
              <input id="school_degree_4" style="display:none" type="text" class="formfields" spellcheck="false" tabindex="16" onfocus="emptyElement('status')" onkeyup="restrict('')" maxlength="60" placeholder="Degree (optional)">
              <input id="school_degree_5" style="display:none" type="text" class="formfields" spellcheck="false" tabindex="16" onfocus="emptyElement('status')" onkeyup="restrict('')" maxlength="60" placeholder="Degree (optional)">
            </div>
            <br />
            <button id="school_add_degree" tabindex="17" onclick="AddDegree(1);"><img src="images/add_btn.png" />&nbsp;&nbsp;Add a degree</button>
            <div>
              <input id="school_awards" type="text" class="formfields" spellcheck="false" tabindex="18" onfocus="emptyElement('status')" onkeyup="restrict('')" maxlength="60" placeholder="Awards (optional)">
            </div>
          </div>
        </div>
        <br />
        <button tabindex="19" onclick="AddSchool();"><img src="images/add_btn.png" />&nbsp;&nbsp;Add a school</button>
        <br />
        <br />
        <span id="skillset_error_message" class="status_error"></span>
        <br />
        <br/>
        <button onclick = "UploadNewSkillset();" tabindex="20">Post</button>
        <button onclick = "ModalOpen('None');" tabindex="21">Cancel</button>
        <br />
        <br />
      </div>
    </div>
  </div>
</div>
<!--light_skills-->
