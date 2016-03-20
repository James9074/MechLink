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

	if ($oper == "EditAbout") {
		$database->query('UPDATE users SET description=:description WHERE username=:username');
		$database->bind(':description',$_POST["about"]);
		$database->bind(':username',$_SESSION["username"]);

		try {
			$result = $database->execute();
			print json_encode($returnData);
		}catch (PDOException $e) {
			header('HTTP/1.1 500 Internal Server Error');
			die(json_encode(array('status' => 'DB Error', 'error' => $e)));
		}
	} else if ($oper == "AddSkillset") {
		$awards = json_decode($_POST["awards"], true);
		$schools = json_decode($_POST["schools"], false);
		//print json_encode($schools);
		$newSkillset = array(
			"automobiletype" => $_POST["automobiletype"],
			"location" => $_POST["location"],
			"restoredfrom" => $_POST["restoredfrom"],
			"restoredto" => $_POST["restoredto"],
			"award1" => $awards[0],
			"award2" => $awards[1],
			"award3" => $awards[2],
			"award4" => $awards[3],
			"skills" => $_POST["automobiletype"],
			"username" => $_SESSION["username"]
			);

		try {
			$createdSkillset = Skillset::createNew($newSkillset);
			$returnData["newSkillset"] = $createdSkillset;
			foreach($schools as $school){
				$degrees = $school->degrees;
				$newSchool = array(
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
				$createdSchool = School::createNew($newSchool);
			}
			print json_encode($returnData);
		}catch (PDOException $e) {
			header('HTTP/1.1 500 Internal Server Error');
			die(json_encode(array('status' => 'DB Error', 'error' => $e)));
		}
	}
	exit();
}

include_once("includes/user_wrapper.php");

//HTML STARTS HERE!
include_once("includes/header.php"); ?>
	<meta name="Description" content="This is <?php echo $current_user->rlname; ?>'s Mechlink profile.">
	<title><?php echo $current_user->rlname; ?> • MechLink"</title>
	<script src="js/user.js"></script>
</head>

<body>
<script type="text/javascript">
	$(function(){
		<? if($log_username == $current_user->username){?>
		if($("#user_about").attr("descriptionprovided") == 'false') {
			UserConfigureInitialProfileSetup();
		}
		<?}?>

	});

	function friendToggle(type,user,elem){
	var conf = confirm("Press OK to "+type+" <?php echo $current_user->rlname; ?>.");
	if(conf != true){
		return false;
	}
	_(elem).innerHTML = '<img src="http://www.mechlink.org/gifs/greenloader.gif" alt="Loading..." />';
	var ajax = ajaxObj("POST", "php_parsers/friend_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "friend_request_sent"){
				_(elem).innerHTML = '<span class="friendBtn">Request Sent</span>';
			} else if(ajax.responseText == "unfriend_ok"){
				_(elem).innerHTML = '<button onclick="friendToggle(\'connect\',\'<?php echo $current_user->rlname; ?>\',\'friendBtn\')">Friend</button>';
			} else {
				alert(ajax.responseText);
				_(elem).innerHTML = '<span class="style5">Please try again later</span>';
			}
		}
	}
	ajax.send("type="+type+"&user="+user);
}

	function blockToggle(type,blockee,elem){
	var conf = confirm("Press OK to confirm the '"+type+"' action on user <?php echo $current_user->rlname; ?>.");
	if(conf != true){
		return false;
	}
	var elem = document.getElementById(elem);
	elem.innerHTML = '<img src="http://www.mechlink.org/gifs/greenloader.gif" alt="Loading..." />';
	var ajax = ajaxObj("POST", "php_parsers/block_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "blocked_ok"){
				elem.innerHTML = '<button onclick="blockToggle(\'unblock\',\'<?php echo $current_user->rlname; ?>\',\'blockBtn\')">Unblock User</button>';
			} else if(ajax.responseText == "unblocked_ok"){
				elem.innerHTML = '<button onclick="blockToggle(\'block\',\'<?php echo $current_user->rlname; ?>\',\'blockBtn\')">Block User</button>';
			} else {
				alert(ajax.responseText);
				elem.innerHTML = 'Try again later';
			}
		}
	}
	ajax.send("type="+type+"&blockee="+blockee);
}

	function triggerUpload(event,elem){
		event.preventDefault();
		document.getElementById(elem).click();
	}
</script>

<div id="fade" class="black_overlay"></div>
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
		<div align="center">
			<div id="prof_nav">
				<div id="prof_nav_item"><a class="a3" href="user.php?u=<?php echo $u; ?>">About</a></div>

				<div id="prof_nav_item"><a class="a3" href="pics.php?u=<?php echo $u; ?>">Pics</a></div>

				<div id="prof_nav_item"><a class="a3" href="friends.php?u=<?php echo $u; ?>">Friends</a></div>
			</div>
		</div>
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






