<?
session_start();
include_once("includes/check_login_status.php");
include_once("includes/db_conn.php");

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
		$database->query('INSERT INTO skillsets (automobiletype, location, restoredfrom, restoredto, award1, award2, award3, award4, skills, username) VALUES (:automobiletype, :location, :restoredfrom, :restoredto, :award1, :award2, :award3, :award4, :skills, :username)');
		$database->bind(':automobiletype',$_POST["automobiletype"]);
		$database->bind(':location',$_POST["location"]);
		$database->bind(':restoredfrom',$_POST["restoredto"]);
		$database->bind(':restoredto',$_POST["restoredto"]);
		$database->bind(':award1',$awards[0]);
		$database->bind(':award2',$awards[1]);
		$database->bind(':award3',$awards[2]);
		$database->bind(':award4',$awards[3]);
		$database->bind(':skills',$_POST["skills"]);
		$database->bind(':username',$_SESSION["username"]);

		try {
			$result = $database->execute();
			print json_encode($returnData);
		}catch (PDOException $e) {
			header('HTTP/1.1 500 Internal Server Error');
			die(json_encode(array('status' => 'DB Error', 'error' => $e)));
		}

	}
	exit();
}

include_once("includes/check_login_status.php");

// Make sure the _GET username is set, and sanitize it
if(isset($_GET["u"])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
} else {
    header("location: http://www.mechlink.org");
    exit();
}
include_once("includes/headerphpcode.php");

$user = User::withUsername($u);

if(!(bool)$user->activated){
	header("location: http://www.mechlink.org/404");
	exit();
}


$log_username = $_SESSION['username'];

// Check to see if the viewer is the account owner
$isOwner = "no";
if($_SESSION['username'] == $user->username && $user_ok == true){
	$isOwner = "yes";
	$profile_pic_btn = '<button class="profile_pic_btn" style="display:block;" onclick="triggerUpload(event, \'FileUpload\')"></button>';
	$user->rlname_edit_btn = '<button class="rlname_edit_btn" style="display:inline-block; margin-top:3px;" onclick = "document.getElementById(\'light_edit_name\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'"></button>';
	$category_edit_btn = '<button class="category_edit_btn" style="display:inline-block; margin-top:1px;"></button>';
	$location_edit_btn = '<button class="location_edit_btn" style="display:inline-block; margin-top:1px;" onclick = "document.getElementById(\'light_edit_location\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'"></button>';
	$status_edit_btn = '<button class="status_edit_btn" style="display:inline-block; margin-top:1px;" onclick = "document.getElementById(\'light_edit_status\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'"></button>';
	$about_edit_btn = '<button class="about_edit_btn" style="display:block;" onclick = "ModalOpen(\'EditAbout\');"></button>';
}

$profile_pic = '<img src="user/'.$u.'/'.$user->avatar.'" alt="'.$u.'">';
if($user->avatar == NULL)
	$profile_pic = '<img src="images/avatardefault_large.png" alt="'.$user->username.'">';

$isFriend = false;
$ownerBlockViewer = false;
$viewerBlockOwner = false;
if($u != $log_username && $user_ok == true){
	$friend_check = "SELECT id FROM friends WHERE user1='$log_username' AND user2='$u' AND accepted='1' OR user1='$u' AND user2='$log_username' AND accepted='1' LIMIT 1";
	if(mysqli_num_rows(mysqli_query($db_conx, $friend_check)) > 0){
        $isFriend = true;
    }
	$block_check1 = "SELECT id FROM blockedusers WHERE blocker='$u' AND blockee='$log_username' LIMIT 1";
	if(mysqli_num_rows(mysqli_query($db_conx, $block_check1)) > 0){
        $ownerBlockViewer = true;
    }
	$block_check2 = "SELECT id FROM blockedusers WHERE blocker='$log_username' AND blockee='$u' LIMIT 1";
	if(mysqli_num_rows(mysqli_query($db_conx, $block_check2)) > 0){
        $viewerBlockOwner = true;
    }
}
?>
<?php
$friend_button = '<button class="friendBtn" disabled style="display:none;">Friend</button>';
$block_button = '<button disabled>Block User</button>';
// LOGIC FOR FRIEND BUTTON
if($isFriend == true){
	$friend_button = '<button class="friendBtn" style="display:block;" onclick="friendToggle(\'unfriend\',\''.$u.'\',\'friendBtn\')">Unfriend</button>';
} else if($user_ok == true && $u != $log_username && $ownerBlockViewer == false){
	$friend_button = '<button class="friendBtn" style="display:block;" onclick="friendToggle(\'friend\',\''.$u.'\',\'friendBtn\')">Friend</button>';
}
// LOGIC FOR BLOCK BUTTON
if($viewerBlockOwner == true){
	$block_button = '<button onclick="blockToggle(\'unblock\',\''.$u.'\',\'blockBtn\')">Unblock User</button>';
} else if($user_ok == true && $u != $log_username){
	$block_button = '<button onclick="blockToggle(\'block\',\''.$u.'\',\'blockBtn\')">Block User</button>';
}
?>
<?php
$friend_button2 = '<button class="friendBtn2" disabled style="display:none;">Friend</button>';
// LOGIC FOR FRIEND BUTTON
if($isFriend == true){
	$friend_button2 = '<button class="friendBtn2" style="display:block;" onclick="friendToggle(\'unfriend\',\''.$u.'\',\'friendBtn2\')">Unfriend</button>';
} else if($user_ok == true && $u != $log_username && $ownerBlockViewer == false){
	$friend_button2 = '<button class="friendBtn2" style="display:block;" onclick="friendToggle(\'friend\',\''.$u.'\',\'friendBtn2\')">Friend</button>';
}
?>
<?php
$friendsHTML = '';
$friends_view_all_link = '';
$sql = "SELECT COUNT(id) FROM friends WHERE user1='$u' AND accepted='1' OR user2='$u' AND accepted='1'";
$query = mysqli_query($db_conx, $sql);
$query_count = mysqli_fetch_row($query);
$friend_count = $query_count[0];
if($friend_count < 1){
	$friendsHTML = '<span class="style2">'.$user->rlname.' has no friends';
} else {
	$max = 6;
	$all_friends = array();
	$sql = "SELECT user1 FROM friends WHERE user2='$u' AND accepted='1' ORDER BY RAND() LIMIT $max";
	$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		array_push($all_friends, $row["user1"]);
	}
	$sql = "SELECT user2 FROM friends WHERE user1='$u' AND accepted='1' ORDER BY RAND() LIMIT $max";
	$query = mysqli_query($db_conx, $sql);
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		array_push($all_friends, $row["user2"]);
	}
	$friendArrayCount = count($all_friends);
	if($friendArrayCount > $max){
		array_splice($all_friends, $max);
	}
	if($friend_count > $max){
		$friends_view_all_link = '<a class="a5" href="friends.php?u='.$u.'">view all</a>';
	}
	$orLogic = '';
	foreach($all_friends as $key => $user){
			$orLogic .= "username='$user' OR ";
	}
	$orLogic = chop($orLogic, "OR ");
	$sql = "SELECT username, avatar FROM users WHERE $orLogic";
	$query = mysqli_query($db_conx, $sql);
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$friend_username = $row["username"];
		$friend_avatar = $row["avatar"];
		if($friend_avatar != ""){
			$friend_pic = 'user/'.$friend_username.'/'.$friend_avatar.'';
		} else {
			$friend_pic = 'http://www.mechlink.org/images/avatardefault_small.png';
		}
		$friendsHTML .= '<a href="user.php?u='.$friend_username.'"><img class="friendpics" src="'.$friend_pic.'" alt="'.$friend_username.'" title="'.$friend_username.'"></a>';
	}
}

include_once("includes/header.php");
?>
<script type="text/javascript">
	$(function(){
		document.title = "<?php echo $user->rlname; ?> • MechLink";
	});

	function friendToggle(type,user,elem){
	var conf = confirm("Press OK to "+type+" <?php echo $user->rlname; ?>.");
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
				_(elem).innerHTML = '<button onclick="friendToggle(\'connect\',\'<?php echo $user->rlname; ?>\',\'friendBtn\')">Friend</button>';
			} else {
				alert(ajax.responseText);
				_(elem).innerHTML = '<span class="style5">Please try again later</span>';
			}
		}
	}
	ajax.send("type="+type+"&user="+user);
}

	function blockToggle(type,blockee,elem){
	var conf = confirm("Press OK to confirm the '"+type+"' action on user <?php echo $user->rlname; ?>.");
	if(conf != true){
		return false;
	}
	var elem = document.getElementById(elem);
	elem.innerHTML = '<img src="http://www.mechlink.org/gifs/greenloader.gif" alt="Loading..." />';
	var ajax = ajaxObj("POST", "php_parsers/block_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "blocked_ok"){
				elem.innerHTML = '<button onclick="blockToggle(\'unblock\',\'<?php echo $user->rlname; ?>\',\'blockBtn\')">Unblock User</button>';
			} else if(ajax.responseText == "unblocked_ok"){
				elem.innerHTML = '<button onclick="blockToggle(\'block\',\'<?php echo $user->rlname; ?>\',\'blockBtn\')">Block User</button>';
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
<?php //include_once("includes/overlay_edit_about.php"); ?>
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
          <div id="profile_pic_box"><?php echo $profile_pic_btn; ?><?php echo $profile_pic; ?></div>
          <div id="standardUpload">
            <form id="form" enctype="multipart/form-data" method="post" action="php_parsers/photo_system.php">
              <input type="file" name="avatar" required id="FileUpload" onChange="form.submit()">
            </form>
          </div>
          <div id="info_box"> <span class="style3"><?php echo $user->rlname; ?><?php echo $user->rlname_edit_btn; ?></span> <br />
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
          <div id="main_cont"> <div id="user_about" descriptionprovided="<? echo $user->description == "" ? "false" : "true"; ?>" ><?php echo $about_edit_btn; echo "<span id='user_about_text'>"; echo $user->description == "" ? "Tell others about yourself or your business." : nl2br($user->description) ?>  </span></div></div>
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






