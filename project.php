<?php
session_start();
include_once("includes/check_login_status.php");
include_once("includes/db_conn.php");
include_once("includes/headerphpcode.php");

// AJAX CALLS
if(isset($_POST["oper"])) {
	$oper = $_POST["oper"];
	$returnData = array();
	$returnData["oper"] = $_POST["oper"];
	$returnData["postData"] = $_POST;
	$database = new Database();
	header('Content-Type: application/json');
	if ($oper == "DeleteProject") {

	} else if ($oper == "EditProject") {

	}
	exit();
}

include_once("includes/user_wrapper.php");

$project = Project::withID($_GET['id']);
if($project->id == null) {
	header("location: http://www.mechlink.org/404");
	exit();
}

include_once("includes/headerphpcode.php");

//HTML STARTS HERE!
include_once("includes/header.php"); ?>

<?php include_once("includes/navbar.php"); ?>
<?php include_once("includes/overlay_edit_name.php"); ?>
<?php include_once("includes/overlay_edit_location.php"); ?>
<?php include_once("includes/overlay_edit_status.php"); ?>
<?php include_once("includes/overlaysshare.php"); ?>
<?php include_once("includes/overlay_edit_about.php"); ?>
<?php include_once("includes/overlay_skills.php"); ?>
<?php include_once("includes/overlay_project.php"); ?>
<?php include_once("includes/overlay_upload_pics.php"); ?>
<?php include_once("includes/overlay_delete_project.php"); ?>
<?php include_once("includes/overlay_post.php"); ?>
<div id="container">
  <div class="gridHeader clearfix">
    <div id="content">
      <div id="contentInner">
        <div id="contentitem3">
          <div id="profile_pic_box"><?php echo $profile_pic_btn; ?><?php echo $profile_pic; ?></div>
          <div id="standardUpload">
            <form id="form" enctype="multipart/form-data" method="post" action="php_parsers/photo_system.php">
              <input type="file" name="avatar" required id="FileUpload" onChange="form.submit()">
            </form>
          </div>
          <div id="info_box"> <span class="style3"><?php echo $current_user->rlname; ?><?php echo $rlname_edit_btn; ?></span> <br />
            <br />
            <span class="style4">
            Where are you?<?php echo $location_edit_btn; ?></span><br />
            <span class="style4">Status:<?php echo $status_edit_btn; ?></span> </div>
          <div id="info_box2">
            <?php
$isOwner = "no";
if($u == $log_username && $user_ok == true){
	$isOwner = "yes";
?>
            <div id="prof_links" style="display:visible;"><a href = "javascript:void(0)" onclick = "document.getElementById('lightshare').style.display='block';document.getElementById('fade').style.display='block'" class="a4">Share&nbsp;<img src="http://www.mechlink.org/images/sharebutton.png" alt="Share" style="margin-top:-5px;"/></a></div>
            <div id="prof_links" style="display:none;"><a href ="#" class="a4">Send Message</a></div>
            <div id="prof_links" style="display:none;"><span id="friendBtn2"><?php echo $friend_button2; ?></span></div>
            <?php
}
else {
?>
            <div id="prof_links" style="display:visible;"><a href = "javascript:void(0)" onclick = "document.getElementById('lightshare').style.display='block';document.getElementById('fade').style.display='block'" class="a4">Share&nbsp;<img src="http://www.mechlink.org/images/sharebutton.png" alt="Share" style="margin-top:-5px;"/></a></div>
            <div id="prof_links" style="display:visible;"><a href ="#" class="a4">Send Message</a></div>
            <div id="prof_links" style="display:visible;"><span id="friendBtn2"><?php echo $friend_button2; ?></span></div>
            <?php
}
?>
          </div>
        </div>
        <div id="contentInner2">
          <hr />
          <?php include_once("includes/prof_nav.php"); ?>
          <div id="main_cont"> 
          
          <div style="float:left;"><?php echo $project_edit_btn; ?></div>

		  <div style="float:right;"><?php echo $project_delete_btn; ?></div>

		  <p class="section_header" style="clear:both;">Restoration Pictures</p>
		  <div class="data_section" style="padding:10px">
			  <?php foreach($project->getPhotos() as $picture){ ?>
				  <img src="/images/<?php echo $picture->filename; ?>" style="max-height:101px; max-width:180px; display:inline-block; vertical-align:middle; margin-bottom:5px;"/>
			  <?php } ?>
			  <br/>
			  <button tabindex="5" onclick = "document.getElementById('lightupload_pics').style.display='block';document.getElementById('fade').style.display='block'">Add photos</button>
		  </div>

		  <p class="section_header">Restoration Project</p>
		  <div class="data_section">
			  <p>Type of automobile: <?php echo $project->automobiletype; ?></p>
			  Location: <?php echo $project->location; ?>
		  </div>

		  <p class="section_header">Restoration Details</p>
		  <div class="data_section">
			  <p><?php echo $project->details; ?></p>
		  </div>

		  <?php if($project->skills != null){ ?>
			  <p class="section_header">Required Skills</p>
			  <div class="data_section">
				  <p><?php echo $project->skills; ?></p>
			  </div>
		  <?php } ?>

          </div>
          <hr />
          <div align="center">
            <div id="section_header"> <span class="style2"> Friends <?php echo "(".$friend_count.")"; ?>&nbsp;&nbsp;<?php echo $friends_view_all_link; ?> </span>
              <div id="sk_btns"> <span id="friendBtn"><?php echo $friend_button; ?></span> </div>
            </div>
            <hr />
            <div id="main_cont2">
              <p><?php echo $friendsHTML; ?></p>
            </div>
          </div>
          <hr />
          <?php include_once("includes/footer.php"); ?>
        </div>
        <!--contentInner2--> 
        
      </div>
      <!--contentInner-->
      
      <?php include("includes/sidecont.php"); ?>
    </div>
  </div>
</div>
<br />
<br />
<br />
<br />
<br />
<br />
<?php include_once("includes/footer_over.php"); ?>
</body>
</html>