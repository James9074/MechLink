<div id="light3" class="white_content_in">
  <?php include("includes/accountbox.php"); ?>
</div>
<!--light3-->

<div id="light4" class="white_content_in">
  <div align="center">
    <div id="boxform">
      <div id="form"> <a href = "javascript:void(0)" onclick = "document.getElementById('light4').style.display='none';document.getElementById('fade').style.display='none'"><img src="http://www.mechlink.org/images/x.png" style="height: auto; width: 100%; max-height: 30px; max-width: 30px;"/></a> <br />
        <br />
        <button onclick = "document.getElementById('light2').style.display='block';document.getElementById('fade').style.display='block';document.getElementById('light4').style.display='none'" class="loginbtn">Sign in</button> <br />
        <br />
        Or 
        <br />
        <button onclick = "document.getElementById('light1').style.display='block';document.getElementById('fade').style.display='block';document.getElementById('light4').style.display='none'" class="loginbtn">Join</button> <br />
        <br />
        <br />
        <br />
      </div>
    </div>
  </div>
</div>
<!--light4-->

<div id="fade" class="black_overlay"></div>
<?php include("includes/overlay_sign_join_forms.php"); ?>
