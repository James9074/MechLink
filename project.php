<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT']."/includes/check_login_status.php");
include_once($_SERVER['DOCUMENT_ROOT']."/includes/db_conn.php");
include_once($_SERVER['DOCUMENT_ROOT']."/includes/headerphpcode.php");

if(isset($_GET['id'])) {
	$project = Project::withID($_GET['id']);
	if ($project->id == null) {
		header("location: http://www.mechlink.org/404");
		exit();
	}
}
else{
	header("location: http://www.mechlink.org/404");
	exit();
}

if($project->username != $_GET['u']) {
	header("location: http://www.mechlink.org/project.php?u=".$project->username."&id=".$project->id);
	exit();
}

include_once($_SERVER['DOCUMENT_ROOT']."/includes/user_wrapper.php");
?>
<style>
	@media only screen and (min-width: 1123px) {
		.project-thumbnail {
			max-height: 95px;
		}

		.project-thumbnail-container {
			height: 95px;
			margin-left: 6px;
			margin-right: 6px;
			text-align: center;
			overflow: hidden;
			width: 185px;
			background-color: #232323;
			display: inline-block;
		}
	}

	@media only screen and (max-width: 481px) {
		.project-thumbnail {
			height:100%;
		}

		.project-thumbnail-container {
			height: 205px;
			margin-top: 10px;
			margin-top: 10px;
			text-align: center;
			overflow: hidden;
			width: 100%;
			vertical-align: middle;
			background-color: #232323;
			display: inline-block;
		}

		#UploadPics{
			margin-top:5px;
		}
	}
</style>
          <div style="float:left;"><?php echo $project_edit_btn; ?></div>

		  <div style="float:right;"><?php echo $project_delete_btn; ?></div>

		  <p class="section_header" style="clear:both;">Restoration Pictures</p>
		  <div class="data_section" style="padding:10px;">
			  <?php foreach($project->getGallery()->getPhotos() as $picture){ ?>
				  <div class="project-thumbnail-container"><img onclick="DisplayPic(<?php echo $picture->id; ?>)" src="<?php echo "user/".$picture->username."/".$picture->filename; ?>" class="project-thumbnail"/></div>
			  <?php } if(sizeof($project->getGallery()->getPhotos()) == 0){
				  echo "<b>No pictures uploaded. Why not add some?</b><br/>";
			  } ?>
			  <br/>
			  <button id="UploadPics" tabindex="5" onclick = "PrepPicsUpload(<?php echo $project->gallery.",".$project->id; ?>);">Add photos</button>
		  </div>

		  <p class="section_header">Restoration Project</p>
		  <div class="data_section">
			  <p>Automobile Type: <?php echo $project->automobiletype; ?></p>
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
          <?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/footer.php"); ?>
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
<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/footer_over.php"); ?>
</body>
</html>