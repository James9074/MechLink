<?
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

	if ($oper == "EditUser"){

	} else if ($oper == "EditAbout") {
		CheckSession();
		try {
			$user = User::withUsername($_SESSION["username"]);
			$returnData['user'] = $user;
			$user->update(array("description"=>$_POST["description"]));
			print json_encode($returnData);
		}catch (PDOException $e) {
			header('HTTP/1.1 500 Internal Server Error');
			die(json_encode(array('status' => 'DB Error', 'error' => $e)));
		}
	} else if ($oper == "AddProject") {
		CheckSession();
		$newProject = array(
			"automobiletype" => $_POST["automobiletype"],
			"location" => $_POST["location"],
			"details" => $_POST["details"],
			"skills" => $_POST["skills"],
			"username" => $_SESSION["username"]
		);

		try {
			$createdProject = Project::createNew($newProject);
			$returnData["newProject"] = $createdProject;
			print json_encode($returnData);
		}catch (PDOException $e) {
			header('HTTP/1.1 500 Internal Server Error');
			die(json_encode(array('status' => 'DB Error', 'error' => $e)));
		}
	} else if ($oper == "AddOrEditSkillset") {
		CheckSession();
		$awards = json_decode($_POST["awards"], true);
		$schools = json_decode($_POST["schools"], false);
		//print json_encode($schools);
		$newSkillset = array(
			"id" => $_POST["id"],
			"automobiletype" => $_POST["automobiletype"],
			"location" => $_POST["location"],
			"restoredfrom" => $_POST["restoredfrom"],
			"restoredto" => $_POST["restoredto"],
			"award1" => $awards[0],
			"award2" => $awards[1],
			"award3" => $awards[2],
			"award4" => $awards[3],
			"skills" => $_POST["skills"],
			"username" => $_SESSION["username"]
			);

		try {
			if(isset($newSkillset->id)) {
				$createdSkillset = Skillset::withID($newSkillset->id);
				$createdSkillset->update($newSkillset);
			}
			else
				$createdSkillset = Skillset::createNew($newSkillset);
			$returnData["skillset"] = $createdSkillset;
			foreach($schools as $school){
				$degrees = $school->degrees;
				$newSchool = array(
					"id" => $school->id,
					"name" => $school->name,
					"location" => $school->location,
					"attendedfrom" => $school->attendedfrom,
					"attendedto" => $school->attendedto,
					"awards" => $school->awards,
					"degree1" => $degrees[0],
					"degree2" => $degrees[1],
					"degree3" => $degrees[2],
					"degree4" => $degrees[3],
					"skillset" => $createdSkillset->id
				);
				if(isset($newSchool->id)) {
					$createdSchool = School::withID($newSchool->id);
					$createdSchool->update($newSchool);
				}
				else
					$createdSchool = School::createNew($newSchool);
			}
			print json_encode($returnData);
		}catch (PDOException $e) {
			header('HTTP/1.1 500 Internal Server Error');
			die(json_encode(array('status' => 'DB Error', 'error' => $e)));
		}
	} else if ($oper == "GetSkillset") {
		try {
			$skillset = Skillset::withID($_POST["id"]);
			$returnData["skillset"] = $skillset;
			print json_encode($returnData);
		}catch (PDOException $e) {
			header('HTTP/1.1 500 Internal Server Error');
			die(json_encode(array('status' => 'DB Error', 'error' => $e)));
		}
	} else if ($oper == "DeleteSkillset") {
		CheckSession();
		try {
			$skillset = Skillset::withID($_POST["id"]);
			if($_SESSION["username"] != $skillset->username){
				header('HTTP/1.1 500 Internal Server Error');
				die(json_encode(array('status' => 'Authorization Error', 'error' => 'You do not own this skillset!')));
			} else {
				$returnData["username"] = $skillset->username;
				$skillset->delete();
			}
			print json_encode($returnData);
		}catch (PDOException $e) {
			header('HTTP/1.1 500 Internal Server Error');
			die(json_encode(array('status' => 'DB Error', 'error' => $e)));
		}
	}
	exit();
}

function CheckSession(){
	if(!isset($_SESSION["username"])){
		header('HTTP/1.1 500 Internal Server Error');
		die(json_encode(array('status' => 'Authorization Error', 'error' => 'You must log in first!')));
	}
}

include_once("includes/user_wrapper.php");

//HTML STARTS HERE!
include_once("includes/header.php"); ?>

<?php include_once("includes/navbar.php"); ?>
<?php include_once("includes/overlay_edit_name.php"); ?>
<?php include_once("includes/overlay_edit_location.php"); ?>
<?php include_once("includes/overlay_edit_status.php"); ?>
<?php include_once("includes/overlaysshare.php"); ?>
<?php include_once("includes/overlay_edit_about.php"); ?>
<?php include_once("includes/overlay_first.php"); ?>
<?php include_once("includes/overlay_skills.php"); ?>
<?php include_once("includes/overlay_project.php"); ?>
<?php include_once("includes/overlay_upload_pics.php"); ?>
<?php include_once("includes/overlay_post.php"); ?>
<div id="container">
  <div class="gridHeader clearfix">
    <div id="content">
      <div id="contentInner">
        <div id="contentitem3">
          <div id="profile_pic_box"><?php echo issetor($profile_pic_btn); ?><?php echo $profile_pic; ?></div>
          <div id="standardUpload">
            <form id="form" enctype="multipart/form-data" method="post" action="php_parsers/photo_system.php">
              <input type="file" name="avatar" required id="FileUpload" onChange="form.submit()">
            </form>
          </div>
          <div id="info_box"> <span class="style3"><?php echo $current_user->rlname; ?><?php echo issetor($rlname_edit_btn); ?></span> <br />
            <br />
            <span class="style4">
            Where are you?<?php echo issetor($location_edit_btn); ?></span><br />
            <span class="style4">Status:<?php echo issetor($status_edit_btn); ?></span> </div>
          	<div id="info_box2">
			<?php $show = $isOwner ? 'none' : 'visible'; ?>
            <div id="prof_links" style="display:visible;"><a href = "javascript:void(0)" onclick = "ModalOpen('Share');" class="a4">Share&nbsp;<img src="http://www.mechlink.org/images/sharebutton.png" alt="Share" style="margin-top:-5px;"/></a></div>
            <div id="prof_links" style="display:<? echo $show; ?>"><a href ="#" class="a4">Send Message</a></div>
            <div id="prof_links" style="display:<? echo $show; ?>;"><span id="friendBtn2"><?php echo $friend_button2; ?></span></div>
          </div>
        </div>
        <div id="contentInner2">
		<hr />
			<?php include_once("includes/prof_nav.php"); ?>
          <div id="main_cont"> <div id="user_about" descriptionprovided="<? echo $current_user->description == "" ? "false" : "true"; ?>" ><?php echo issetor($about_edit_btn); echo "<span id='user_about_text'>"; echo $current_user->description == "" ? "Tell others about yourself or your business." : nl2br($current_user->description) ?>  </span></div></div>
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






