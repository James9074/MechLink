<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT']."/includes/check_login_status.php");
include_once($_SERVER['DOCUMENT_ROOT']."/includes/db_conn.php");
include_once($_SERVER['DOCUMENT_ROOT']."/includes/headerphpcode.php");

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
          
          <div style="float:left;"><?php echo $project_edit_btn; ?></div>

		  <div style="float:right;"><?php echo $project_delete_btn; ?></div>

		  <p class="section_header" style="clear:both;">Restoration Pictures</p>
		  <div class="data_section" style="padding:10px">
			  <?php foreach($project->getPhotos() as $picture){ ?>
				  <img src="/images/<?php echo $picture->filename; ?>" style="max-height:101px; max-width:180px; display:inline-block; vertical-align:middle; margin-bottom:5px;"/>
			  <?php } ?>
			  <br/>
			  <button tabindex="5" onclick = "PrepPicsUpload();">Add photos</button>
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