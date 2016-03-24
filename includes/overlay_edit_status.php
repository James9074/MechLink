<script>
  function UpdateStatus(){
    var status;
    if($("#status0").is(':checked','true')) {
      status = 0;
      $("#userStatus").text('Looking for mechanics');
    }
    else {
      status = 1;
      $("#userStatus").text('Looking for projects');
    }
    ShowLoading($("#statusLoad"));
    $.ajax({
      url : "user.php",
      type: "POST",
      dataType: 'json',
      data : {"oper":"EditUser", status:status},
      success: function(data, textStatus, jqXHR){
        HideLoading($("#statusLoad"));
        ModalOpen("None");
      },
      error: function (jqXHR, textStatus, errorThrown){
        if(DEBUG) {
          console.log(errorThrown + ": ");
          console.log(JSON.parse(jqXHR.responseText));
        }
        HideLoading($("#statusLoad"));
      }
    });
  }

  $(function(){
    var currentStatus = <?php echo $current_user->status; ?>;
    if(currentStatus == 0)
      $("#status0").click();
    else
      $("#status1").click();
  });

  function OpenStatus(){
    ModalOpen('Status');
  }
</script>
<div id="light_edit_status" class="white_content_edit_status">
  <div align="center">
    <div id="boxform">
      <div id="form"> <br />
        <b>Edit your status</b> <br />
        <br />
        <div>
          <form action="">
            <input id="status0" type="radio" name="group1" value="" tabindex="1">
            <span class="style7">&nbsp;&nbsp;Looking for mechanics</span> <br />
            <br />
            <input id="status1" type="radio" name="group1" value="" tabindex="2">
            <span class="style7">&nbsp;&nbsp;Looking for projects</span>
          </form>
        </div>
        <br />
        <div id="statusLoad"></div>
        <br />
        <button onclick = "UpdateStatus();" tabindex="3">Post</button>
        <button onclick = "ModalOpen('None');" tabindex="4">Cancel</button>
        <br />
        <br />
      </div>
    </div>
  </div>
</div>
<!--light_skills-->

