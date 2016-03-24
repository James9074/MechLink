<?
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

	if ($oper == "EditUser"){
		CheckSession();
		try {
			$user = User::withUsername($_SESSION["username"]);
			$returnData['user'] = $user;
			$updateVars = array();
			$user->update($_POST);
			print json_encode($returnData);
		}catch (PDOException $e) {
			header('HTTP/1.1 500 Internal Server Error');
			die(json_encode(array('status' => 'DB Error', 'error' => $e)));
		}
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
	} else if ($oper == "AddOrEditProject") {
		CheckSession();
		$newProject = array(
			"id" => $_POST["id"],
			"automobiletype" => $_POST["automobiletype"],
			"location" => $_POST["location"],
			"details" => $_POST["details"],
			"skills" => $_POST["skills"],
			"username" => $_SESSION["username"]
		);
		try {
			if($newProject['id'] != null) {
				$createdProject = Project::withID($newProject['id']);
				$createdProject->update($newProject);
			}
			else {
				$gallery = array(
					"owner" => 	$_SESSION["username"],
					"description" => "Gallery for ".$newProject['automobiletype']
				);
				$newGallery = Gallery::createNew($gallery);
				$returnData["gallery"] = $newGallery;
				if($newGallery->id != null) {
					$newProject["gallery"] = $newGallery->id;
					$createdProject = Project::createNew($newProject);
				}
				else{
					header('HTTP/1.1 500 Internal Server Error: Error Adding New Gallery');
					die(json_encode(array('status' => 'DB Error', 'error' => $e)));
				}
			}

			$returnData["project"] = $createdProject;
			print json_encode($returnData);
		}catch (PDOException $e) {
			header('HTTP/1.1 500 Internal Server Error');
			die(json_encode(array('status' => 'DB Error', 'error' => $e)));
		}
	} else if ($oper == "GetProject") {
		try {
			$project = Project::withID($_POST["id"]);
			$returnData["project"] = $project;
			print json_encode($returnData);
		}catch (PDOException $e) {
			header('HTTP/1.1 500 Internal Server Error');
			die(json_encode(array('status' => 'DB Error', 'error' => $e)));
		}
	} else if ($oper == "DeleteProject") {
		CheckSession();
		try {
			$project = Project::withID($_POST["id"]);
			if($_SESSION["username"] != $project->username){
				header('HTTP/1.1 500 Internal Server Error');
				die(json_encode(array('status' => 'Authorization Error', 'error' => 'You do not own this skillset!')));
			} else {
				$returnData["username"] = $project->username;
				$returnData['result'] = $project->delete();
			}
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
			if($newSkillset['id'] != null) {
				$createdSkillset = Skillset::withID($newSkillset['id']);
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
				if($newSchool['id'] != null) {
					$createdSchool = School::withID($newSchool['id']);
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
	} else if ($oper == "AddProjectPictures"){
		CheckSession();
		try {
			$newPictures = array(
				"galleryID" => $_POST['galleryID'],
				"files" => $_POST['files']
			);
			$returnData['files'] = $newPictures;
			print json_encode($returnData);
		}catch (PDOException $e) {
			header('HTTP/1.1 500 Internal Server Error');
			die(json_encode(array('status' => 'DB Error', 'error' => $e)));
		}
	} else if ($oper == "DeletePhoto"){
		CheckSession();
		try {
			$id = preg_replace('#[^0-9]#', '', $_POST["id"]);
			$photo = Photo::withID($id);
			if($photo->id != null) {
				if ($photo->username == $_SESSION["username"]) {
					$picurl = "user/$log_username/$photo->filename";
					if (file_exists($picurl)) {
						unlink($picurl);
						$photo->delete();
					}
					else{
						header('HTTP/1.1 500 Internal Server Error');
						die(json_encode(array('status' => 'Error', 'error' => 'This photo does not exist in the filesystem!')));
					}
				}
				else{
					header('HTTP/1.1 500 Internal Server Error');
					die(json_encode(array('status' => 'Authorization Error', 'error' => 'You do not own this photo!')));
				}
			}
			$returnData['username'] = $photo->username;
			print json_encode($returnData);
		}catch (PDOException $e) {
			header('HTTP/1.1 500 Internal Server Error');
			die(json_encode(array('status' => 'DB Error', 'error' => $e)));
		}
	} else{
		header('HTTP/1.1 500 Internal Server Error: Oper Not Found');
		die(json_encode(array('status' => 'Oper not found', 'error' => $e)));
	}
	exit();
}

function CheckSession(){
	if(!isset($_SESSION["username"])){
		header('HTTP/1.1 500 Internal Server Error');
		die(json_encode(array('status' => 'Authorization Error', 'error' => 'You must log in first!')));
	}
}

include_once($_SERVER['DOCUMENT_ROOT']."/includes/user_wrapper.php");

?>

			  <div id="user_about" descriptionprovided="<? echo $current_user->description == "" ? "false" : "true"; ?>" ><?php echo issetor($about_edit_btn); echo "<span id='user_about_text'>"; echo $current_user->description == "" ? "Tell others about yourself or your business." : nl2br($current_user->description) ?>  </span></div></div>
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






