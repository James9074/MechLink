<script>
  function PromptDeletePhoto(aID){
    var id = "<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>";
    if(id != "") {
      ModalOpen("Warning", false);
      $("#light_warning").find("#warning_question").html("Are you sure you want to delete this photo?");
      $("#light_warning").find("#yesButton").attr("onclick","DeletePhoto("+aID+");");
      $("#light_warning").find("#noButton").attr("onclick","ModalClose('Warning', false);");
    }
  }

  function DeletePhoto(aID){
    ShowLoading($("#form_long"));
    $.ajax({
      url : "user.php",
      type: "POST",
      dataType: 'json',
      data : {"oper":"DeletePhoto",id:aID},
      success: function(data, textStatus, jqXHR){
        window.location.reload();
        return;
        HideLoading($("#form_long"));
        //ModalOpen("None");
      },
      error: function (jqXHR, textStatus, errorThrown){
        if(DEBUG) {
          console.log(errorThrown + ": ");
          console.log(JSON.parse(jqXHR.responseText));
        }
        HideLoading($("#form_long"));
      }
    });
  }
  function DisplayPic(aID) {
    ModalOpen("Pic");
    $("#deletePhoto").attr('onclick','PromptDeletePhoto('+aID+')');

    ShowLoading($("#picture"));
    $.ajax({
      url : "user.php",
      type: "POST",
      dataType: 'json',
      data : {"oper":"GetPhoto",id:aID},
      success: function(data, textStatus, jqXHR){
        //console.dir(data);
        if(data['photo'].id != null){
          $("#pictureImg").attr('src','/user/'+data['photo'].username+'/'+data['photo'].filename);
        }
        else
          $("#picture").html("<b>Error Retrieving Image</b>");
        HideLoading($("#picture"));
      },
      error: function (jqXHR, textStatus, errorThrown){
        if(DEBUG) {
          console.log(errorThrown + ": ");
          console.log(JSON.parse(jqXHR.responseText));
        }
        $("#picture").html("<b>Error Retrieving Image</b>");
        HideLoading($("#picture"));
      }
    });

  }
</script>

<div id="light_pic" class="white_content_upload">
  <div align="center">
    <div id="boxform">
      <div id="form"> <a href = "javascript:void(0)" onclick = "ModalOpen('None');"><img src="images/x.png" style="height: auto; width: 100%; max-height: 30px; max-width: 30px;"/></a> <br />
        <br />
        <br />
        <div id="picture">
          <img id="pictureImg" src=""/>

          <br/>
          <br />
          <?php if($log_username == $current_user->username && $user_ok == true) echo '<button id="deletePhoto" onclick = "" tabindex="6">Delete</button>'; ?>
          <button onclick = "ModalOpen('None')" tabindex="7">Cancel</button>
          <br />
          <br />
        </div>
      </div>
    </div>
  </div>
</div>


<!--lightupload_pics--> 

