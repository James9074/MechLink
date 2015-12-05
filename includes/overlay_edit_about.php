<div id="light_edit_about" class="white_content_edit_about">
  <div align="center">
    <div id="boxform">
      <div id="form"> <br />
        <span id="edit_about_desc">Edit your information</span> <br />
        <span id="edit_about_error" class="status_error">Please enter at least 100 characters.</span>
        <br />
        <textarea id="about" placeholder="Describe yourself or your auto business (at least 100 characters)" tabindex="1"></textarea>
        <br />
        <br />
        <button id="edit_about_post" onclick = "UserUploadAbout();document.getElementById('light_edit_about').style.display='none';document.getElementById('fade').style.display='none'" tabindex="2">Post</button>
        <button id="edit_about_cancel" onclick = "document.getElementById('light_edit_about').style.display='none';document.getElementById('fade').style.display='none'" tabindex="3">Cancel</button>
        <br />
        <img id="edit_about_loading" style="display:none;" src="http://www.mechlink.org/gifs/blueloader.gif" alt="Loading..." />
        <br />
      </div>
    </div>
  </div>
</div>
<!--lightabout-->

