<div id="light_edit_name" class="white_content_edit_name">
  <div align="center">
    <div id="boxform">
      <div id="form"> <br />
        <b>Edit your name</b> <br />
        <br />
        <div>
          <input id="user_rl_name" type="text" class="formfields" spellcheck="false" tabindex="1" onkeyup="restrict('')" maxlength="60" placeholder="<?php echo $current_user->rlname; ?>">
        </div>
        <br />
        <br />
        <button onclick = "UserUploadRealName();" tabindex="2">Post</button>
        <button onclick = "ModalOpen('None');" tabindex="3">Cancel</button>
        <br />
        <br />
      </div>
    </div>
  </div>
</div>
<!--light_skills-->

<div id="fade" class="black_overlay"></div>
