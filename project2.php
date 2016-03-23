<?php
include_once($_SERVER['DOCUMENT_ROOT']."/includes/check_login_status.php");
// Initialize any variables that the page might echo
$rlname = "";
$category = "";
$location = "";
$u = "";
$sex = "Male";
$userlevel = "";
$profile_pic = "";
$profile_pic_btn = "";
$avatar_form = "";
$country = "";
$joindate = "";
$lastsession = "";
// Make sure the _GET username is set, and sanitize it
if(isset($_GET["u"])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
} else {
    header("location: http://www.mechlink.org");
    exit();	
}
// Select the member from the users table
$sql = "SELECT * FROM users WHERE username='$u' AND activated='1' LIMIT 1";
$user_query = mysqli_query($db_conx, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($user_query);
if($numrows < 1){
	header("location: http://www.mechlink.org/404");
    exit();	
}
// Check to see if the viewer is the account owner
$isOwner = "no";
if($u == $log_username && $user_ok == true){
	$isOwner = "yes";
	$profile_pic_btn = '<button class="profile_pic_btn" style="display:block;" onclick="triggerUpload(event, \'FileUpload\')"></button>';
	$rlname_edit_btn = '<button class="rlname_edit_btn" style="display:inline-block; margin-top:3px;" onclick = "document.getElementById(\'light_edit_name\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'"></button>';
	$category_edit_btn = '<button class="category_edit_btn" style="display:inline-block; margin-top:1px;"></button>';
	$location_edit_btn = '<button class="location_edit_btn" style="display:inline-block; margin-top:1px;" onclick = "document.getElementById(\'light_edit_location\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'"></button>';
	$status_edit_btn = '<button class="status_edit_btn" style="display:inline-block; margin-top:1px;" onclick = "document.getElementById(\'light_edit_status\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'"></button>'; 
	$about_edit_btn = '<button class="about_edit_btn" style="display:block;" onclick = "document.getElementById(\'light_edit_about\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'"></button>';
	$project_edit_btn = '<button class="project_edit_btn" style="display:block;" onclick = "document.getElementById(\'light_project\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'"></button>';
	$project_delete_btn = '<button onclick = "document.getElementById(\'light_delete_project\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'">Delete</button>';
}
// Fetch the user row from the query above
while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
	$rlname = $row["rlname"];
	$category = $row["category"];
	$location = $row["location"];
	$profile_id = $row["id"];
	$gender = $row["gender"];
	$country = $row["country"];
	$userlevel = $row["userlevel"];
	$avatar = $row["avatar"];
	$signup = $row["signup"];
	$lastlogin = $row["lastlogin"];
	$joindate = strftime("%b %d, %Y", strtotime($signup));
	$lastsession = strftime("%b %d, %Y", strtotime($lastlogin));
}
if($gender == "f"){
		$sex = "Female";
}
$profile_pic = '<img src="user/'.$u.'/'.$avatar.'" alt="'.$u.'">';
if($avatar == NULL){
	$profile_pic = '<img src="images/avatardefault_large.png" alt="'.$user1.'">';
}
?>
<?php
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
	$friendsHTML = '<span class="style2">'.$rlname.' has no friends';
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
?>
<?php
include_once($_SERVER['DOCUMENT_ROOT']."/includes/headerphpcode.php");
?>
<!doctype html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
<!--<![endif]-->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow, noarchive" />
<meta name="Description" content="This is <?php echo $rlname; ?>'s Mechlink profile.">
<title><?php echo $rlname; ?> • Mechlink</title>
<link href="http://www.mechlink.org/styles/boilerplate.css" rel="stylesheet" type="text/css">
<link href="http://www.mechlink.org/styles/common.css" rel="stylesheet" type="text/css">
<link href="http://www.mechlink.org/styles/mainuser.css" rel="stylesheet" type="text/css">
<link href="http://www.mechlink.org/styles/textusr.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="http://www.mechlink.org/images/favicon.ico?v=2" type="image/x-icon">
<link rel="icon" href="http://www.mechlink.org/images/favicon.ico" type="image/x-icon">
<script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "d7ff69d9-2897-4dc9-ac03-f66f1c76496f", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
<script src="respond.min.js"></script>
<script language="javascript" type="text/javascript">
var dateObject=new Date();
</script>
<script src="http://www.mechlink.org/js/main.js"></script>
<script src="http://www.mechlink.org/js/ajax.js"></script>
<script type="text/javascript">
function friendToggle(type,user,elem){
	var conf = confirm("Press OK to "+type+" <?php echo $rlname; ?>.");
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
				_(elem).innerHTML = '<button onclick="friendToggle(\'connect\',\'<?php echo $rlname; ?>\',\'friendBtn\')">Friend</button>';
			} else {
				alert(ajax.responseText);
				_(elem).innerHTML = '<span class="style5">Please try again later</span>';
			}
		}
	}
	ajax.send("type="+type+"&user="+user);
}
function blockToggle(type,blockee,elem){
	var conf = confirm("Press OK to confirm the '"+type+"' action on user <?php echo $rlname; ?>.");
	if(conf != true){
		return false;
	}
	var elem = document.getElementById(elem);
	elem.innerHTML = '<img src="http://www.mechlink.org/gifs/greenloader.gif" alt="Loading..." />';
	var ajax = ajaxObj("POST", "php_parsers/block_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "blocked_ok"){
				elem.innerHTML = '<button onclick="blockToggle(\'unblock\',\'<?php echo $rlname; ?>\',\'blockBtn\')">Unblock User</button>';
			} else if(ajax.responseText == "unblocked_ok"){
				elem.innerHTML = '<button onclick="blockToggle(\'block\',\'<?php echo $rlname; ?>\',\'blockBtn\')">Block User</button>';
			} else {
				alert(ajax.responseText);
				elem.innerHTML = 'Try again later';
			}
		}
	}
	ajax.send("type="+type+"&blockee="+blockee);
}
</script>
<script>
function triggerUpload(event,elem){
	event.preventDefault();
	document.getElementById(elem).click();	
}
</script>
</head>

<body>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/navbar.php"); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/overlay_edit_name.php"); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/overlay_edit_location.php"); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/overlay_edit_status.php"); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/overlaysshare.php"); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/overlay_edit_about.php"); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/overlay_skills.php"); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/overlay_project.php"); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/overlay_upload_pics.php"); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/overlay_delete_project.php"); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/overlay_post.php"); ?>
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
          <div id="info_box"> <span class="style3"><?php echo $rlname; ?><?php echo $rlname_edit_btn; ?></span> <br />
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
          <?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/prof_nav.php"); ?>
          <div id="main_cont"> 
          
          <?php echo $project_edit_btn; ?>
          
          <?php echo $project_delete_btn; ?>
          
          <br />
          <br />
          
          <img src="images/WP_20151107_023_zpsghlycfcu.jpg" style="max-height:101px; max-width:180px; display:inline-block; vertical-align:middle; margin-bottom:5px;"/>
          
          <img src="images/WP_20151107_010_zpsli4cb3uy.jpg" style="max-height:101px; max-width:180px; display:inline-block; vertical-align:middle; margin-bottom:5px;"/>
          
          <img src="images/WP_20151107_012_zpsx08niq55.jpg" style="max-height:101px; max-width:180px; display:inline-block; vertical-align:middle; margin-bottom:5px;"/>
          
          <img src="images/s-l500.jpg" style="max-height:101px; max-width:180px; display:inline-block; vertical-align:middle; margin-bottom:5px;"/>
          
          <br />
          <br />
          
          <b>Restoration Project</b>
          
          <p>Type of automobile</p>
          
          City + state or location
          
          <p>Restoration details</p>
          
          
          
          <p><b>Required Skills</b></p>
          
          Required skills
          
          
          
          
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