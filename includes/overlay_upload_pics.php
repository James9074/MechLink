<div id="lightupload_pics" class="white_content_upload">
  <div align="center">
    <div id="boxform">
      <div id="form_upload_overlay"> <a href = "javascript:void(0)" onclick = "document.getElementById('lightupload_pics').style.display='none';document.getElementById('fade').style.display='none'"><img src="images/x.png" style="height: auto; width: 100%; max-height: 30px; max-width: 30px;"/></a> <br />
        <br />
        <br />
        <img src="http://www.mechlink.org/images/upload_img.png" style="cursor:pointer; height:auto; width:auto; max-height:113px; max-width:113px;" onclick="triggerUpload(event, 'FileUploadpics')" title="Select a photo to upload" alt="Select a photo to upload"/>
        <div id="standardUpload">
          <form id="form" enctype="multipart/form-data"  action="">
            <input type="file" name="FileUploadpics" required id="FileUploadpics" onChange="form.submit()">
          </form>
        </div>
        <p onclick="triggerUpload(event, 'FileUploadpics')" style="cursor:pointer;">Select photos to upload.</p>
        Each photo must be jpeg, png, gif, or tiff and 2MB or smaller.
        <p>You can upload up to 4 photos.</p>
        <br />
        <br />
      </div>
    </div>
  </div>
</div>
<!--lightupload_pics--> 

