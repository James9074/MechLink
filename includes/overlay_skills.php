<div id="light_skills" class="white_content_skills">
  <div align="center">
    <div id="boxform">
      <div id="form_long"> <br />
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
        <div>
          <input id="skill_set_award1" type="text" class="formfields" spellcheck="false" tabindex="7" onfocus="emptyElement('status')" onkeyup="restrict('')" maxlength="60" placeholder="Award received (optional)">
        </div>
        <br />
        <button tabindex="8"><img src="images/add_btn.png" />&nbsp;&nbsp;Add an award</button>
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
        <b>School #1</b>
        <div>
          <input id="" type="text" class="formfields" spellcheck="false" tabindex="10" onkeyup="restrict('')" maxlength="100" placeholder="Name of school (optional)">
        </div>
        <div>
          <input id="" type="text" class="formfields" spellcheck="false" tabindex="11" onkeyup="restrict('')" maxlength="60" placeholder="City + state or location (optional)">
        </div>
        <br />
        Attended from (optional): <br />
        <select id="" class="formfields_select_short" tabindex="12">
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
        <input id="" type="text" class="formfields_short" spellcheck="false" tabindex="13" onkeyup="restrict('')" maxlength="4" placeholder="Year">
        to
        <select id="" class="formfields_select_short" tabindex="14">
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
        <input id="" type="text" class="formfields_short" spellcheck="false" tabindex="15" onkeyup="restrict('')" maxlength="4" placeholder="Year">
        <div>
          <input id="" type="text" class="formfields" spellcheck="false" tabindex="16" onfocus="emptyElement('status')" onkeyup="restrict('')" maxlength="60" placeholder="Degree (optional)">
        </div>
        <br />
        <button tabindex="17"><img src="images/add_btn.png" />&nbsp;&nbsp;Add a degree</button>
        <div>
          <input id="" type="text" class="formfields" spellcheck="false" tabindex="18" onfocus="emptyElement('status')" onkeyup="restrict('')" maxlength="60" placeholder="Awards (optional)">
        </div>
        <br />
        <button tabindex="19"><img src="images/add_btn.png" />&nbsp;&nbsp;Add a school</button>
        <br />
        <br />
        <span id="skillset_error_message" class="status_error"></span>
        <br />
        <br/>
        <button onclick = "UserUploadNewSkillset();" tabindex="20">Post</button>
        <button onclick = "document.getElementById('light_skills').style.display='none';document.getElementById('light_select').style.display='block'" tabindex="21">Cancel</button>
        <br />
        <br />
      </div>
    </div>
  </div>
</div>
<!--light_skills--> 

