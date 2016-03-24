<script>

  $(function(){
    if (window.FormData) {
      formdata = new FormData();
      //
    }
  });
  function PrepPicsUpload(aGalleryID, aProjectID) {
      $("#galleryID").val(aGalleryID);
    if(typeof(aProjectID) != "undefined"){
      $("#projectID").val(aProjectID);
    }
      ModalOpen("Pics");
  }
</script>

<div id="lightupload_pics" class="white_content_upload">
  <div align="center">
    <div id="boxform">
      <div id="form_upload_overlay"> <a href = "javascript:void(0)" onclick = "ModalOpen('None');"><img src="images/x.png" style="height: auto; width: 100%; max-height: 30px; max-width: 30px;"/></a> <br />
        <br />
        <br />
        <div id="previewPictures"></div>
        <img id="uploadImg" src="http://www.mechlink.org/images/upload_img.png" style="cursor:pointer; height:auto; width:auto; max-height:113px; max-width:113px;" onclick="triggerUpload(event, 'FileUploadpics')" title="Select a photo to upload" alt="Select a photo to upload"/>
        <div id="standardUpload">

          <form id="picsForm" enctype="multipart/form-data" method="post" action="php_parsers/photo_system.php">
            <input type="hidden" name="gallery" id="galleryID">
            <input type="hidden" name="project" id="projectID">
            <input type="file" name="photo" required id="FileUploadpics" accept="image/x-png, image/png,  image/gif, image/jpeg, image/jpg" onChange="form.submit()">
          </form>
        </div>
        <p onclick="triggerUpload(event, 'FileUploadpics')" style="cursor:pointer;">Select photos to upload.</p>
        Each photo must be jpeg, png, gif, or tiff and 2MB or smaller.
        <p>You can upload up to 4 photos.</p>
        <div id="pic_upload_loading"></div>
        <br />
        <br />
      </div>
    </div>
  </div>
</div>


<!--lightupload_pics--> 

