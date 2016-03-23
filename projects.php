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
	if ($oper == "DeleteSkillset") {

	} else if ($oper == "EditSkillset") {

	}
	exit();
}
/*
if(isset($_GET['id'])) {
	$project = Skillset::withID($_GET['id']);
	if($project->id == null) {
		header("location: http://www.mechlink.org/404");
		exit();
	}
}
else{
	header("location: http://www.mechlink.org/404");
	exit();
}
*/

include_once("includes/user_wrapper.php");


$projectHTML = "";
foreach($current_user->getProjects() as $project){
	$projectHTML .= "<div class='projects-container'>";
	$imgSrc = (sizeof($project->getPhotos()) > 0) ? 'images/'.array_values($project->getPhotos())[0]->filename : 'images/nocar.png';

	$projectHTML .= '<img class="project-thumbnail" src="'.$imgSrc.'">';


	$projectHTML .= '<a style="clear:both;" href="project.php?u='.$u.'&id='.$project->id.'">'.$project->automobiletype.'</a>';

	$projectHTML .=  '<p>Date Posted: '.$project->dateposted.'</p>';
	$projectHTML .= "</div>";
	$projectHTML .=  '<hr />';
}
if($projectHTML == ""){
	$projectHTML = "<p style='text-align:center;font-weight:bold'>Sorry, this user has no uploaded projects.</p>";
}

echo $projectHTML; ?>

<style>
	.projects-container{
		height: 85px;
	}
	.project-thumbnail {
		float: left;
		height: 80px;
		margin-right:5px;
	}
</style>
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