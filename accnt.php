<?php
include_once($_SERVER['DOCUMENT_ROOT']."/includes/check_login_status.php");
// Initialize any variables that the page might echo
$rlname = "";
$location = "";
$u = "";
$e = "";
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
$profile_pic_btn = "";
$rlname_edit_btn = "";
$category_edit_btn = "";
$location_edit_btn = "";
if($u == $log_username && $user_ok == true){
	$isOwner = "yes";
	$profile_pic_btn = '<button class="profile_pic_btn" style="display:block;" onclick="triggerUpload(event, \'FileUpload\')"></button>';
	$rlname_edit_btn = '<button class="rlname_edit_btn" style="display:inline-block;"></button>';
	$category_edit_btn = '<button class="category_edit_btn" style="display:inline-block;"></button>';
	$location_edit_btn = '<button class="location_edit_btn" style="display:inline-block;"></button>';
}
// Fetch the user row from the query above
while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
	$e = $row["email"];
	$rlname = $row["rlname"];
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
<title><?php echo $rlname; ?> • Account Settings</title>
<link href="http://www.mechlink.org/styles/boilerplate.css" rel="stylesheet" type="text/css">
<link href="http://www.mechlink.org/styles/common.css" rel="stylesheet" type="text/css">
<link href="http://www.mechlink.org/styles/mainuser.css" rel="stylesheet" type="text/css">
<link href="http://www.mechlink.org/styles/textusr.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="http://www.mechlink.org/images/favicon.ico?v=2" type="image/x-icon">
<link rel="icon" href="http://www.mechlink.org/images/favicon.ico" type="image/x-icon">
<script src="respond.min.js"></script>
<script language="javascript" type="text/javascript">
var dateObject=new Date();
</script>
<script src="http://www.mechlink.org/js/main.js"></script>
<script src="http://www.mechlink.org/js/ajax.js"></script>
<script src="http://mechlink.org/js/jquery-1.9.1.min.js"></script>
<script>
	function EditFieldDiv(Div_id) {
    	if (false == $(Div_id).is(':visible')) {
        	$(Div_id).show(100);
            }
            else {
                $(Div_id).hide(100);
            }
        }
</script>
<script>
	$(document).ready(function() {
		$(function () {
    		var maxL = 35;
    			$('.editSettingSubject').each(function (i, div) {
        		var text = $(div).text();
        if(text.length > maxL) {
            
            var begin = text.substr(0, maxL),
                end = text.substr(maxL);

            $(div).html(begin)
                .append($('<class="readmore"/>').attr('href', '#').html('...'))
                .append($('<div class="hidden_text" />').html(end));
        }
    });
})
});
</script>
</head>

<body>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/navbar.php"); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/overlay_skills.php"); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/overlay_project.php"); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/overlay_post.php"); ?>
<div id="container">
  <div class="gridHeader clearfix">
    <div id="contentAccnt">
      <div align="center">
        <div id="notesBox">
          <h2 class="style6">General Settings</h2>
          <hr style="height:2px; border:0; background-color:#e0e0e0;">
          <div class="editSetting">Email</div>
          <div class="editSettingSubject"><?php echo $e; ?></div>
          <button class="editSettingBtn" onclick="EditFieldDiv(editCont)">Edit</button>
          <div id="editCont" style="display: none;" class="editCont">
            <input id="#" type="text" class="formfields" tabindex="1"  maxlength="255" placeholder="New email">
            <br />
            <input id="#" type="text" class="formfields" tabindex="2"  maxlength="255" placeholder="Confirm new email">
            <br />
            <br />
            <div align="center">
              <button class="editSettingControlBtn">Save</button>
              <button class="editSettingControlBtn" onclick="EditFieldDiv(editCont)">Cancel</button>
            </div>
          </div>
          <hr style="height:1px; border:0; background-color:#e0e0e0; clear:both;">
          <br />
          <div class="editSetting">Password</div>
          <button class="editSettingBtn" onclick="EditFieldDiv(editCont2)">Edit</button>
          <div id="editCont2" style="display: none;" class="editCont">
            <input id="#" type="text" class="formfields" tabindex="1"  maxlength="255" placeholder="Current password">
            <br />
            <input id="#" type="text" class="formfields" tabindex="2"  maxlength="255" placeholder="New password">
            <br />
            <input id="#" type="text" class="formfields" tabindex="3"  maxlength="255" placeholder="Confirm new password">
            <br />
            <br />
            <div align="center">
              <button class="editSettingControlBtn">Save</button>
              <button class="editSettingControlBtn" onclick="EditFieldDiv(editCont2)">Cancel</button>
            </div>
          </div>
          <hr style="height:1px; border:0; background-color:#e0e0e0; clear:both;">
          <br />
          <div class="editSetting">Account Status</div>
          <div class="editSettingSubject">Active</div>
          <button class="editSettingBtn" onclick="EditFieldDiv(editCont3)">Edit</button>
          <div id="editCont3" style="display: none;" class="editCont">
            <div align="center">
              <button class="editSettingControlBtn2">Deactivate Account</button>
              <button class="editSettingControlBtn2" onclick="EditFieldDiv(editCont3)">Cancel</button>
            </div>
          </div>
          <hr style="height:1px; border:0; background-color:#e0e0e0; clear:both;">
          <br />
          <h2 class="style6">Privacy Settings</h2>
          <hr style="height:2px; border:0; background-color:#e0e0e0;">
          <div class="editSetting">Friends</div>
          <div class="editSettingSubject">Public</div>
          <button class="editSettingBtn" onclick="EditFieldDiv(editCont5)">Edit</button>
          <div id="editCont5" style="display: none;" class="editCont">
            <div align="center"> <span class="style7"><b>Make your Friends</b></span> <br />
              <br />
              <br />
              <form action="">
                <input type="radio" name="group1" value="public">
                <span class="style7">&nbsp;&nbsp;Public</span> &nbsp;&nbsp;
                <input type="radio" name="group1" value="private">
                <span class="style7">&nbsp;&nbsp;Private</span>
              </form>
              <br />
              <br />
              <button class="editSettingControlBtn">Save</button>
              <button class="editSettingControlBtn" onclick="EditFieldDiv(editCont5)">Cancel</button>
            </div>
          </div>
          <hr style="height:1px; border:0; background-color:#e0e0e0; clear:both;">
          <br />
          <div class="editSetting">Location</div>
          <div class="editSettingSubject">Public</div>
          <button class="editSettingBtn" onclick="EditFieldDiv(editCont6)">Edit</button>
          <div id="editCont6" style="display: none;" class="editCont">
            <div align="center"> <span class="style7"><b>Make your Location</b></span> <br />
              <br />
              <br />
              <form action="">
                <input type="radio" name="group1" value="public">
                <span class="style7">&nbsp;&nbsp;Public</span> &nbsp;&nbsp;
                <input type="radio" name="group1" value="private">
                <span class="style7">&nbsp;&nbsp;Private</span>
              </form>
              <br />
              <br />
              <button class="editSettingControlBtn">Save</button>
              <button class="editSettingControlBtn" onclick="EditFieldDiv(editCont6)">Cancel</button>
            </div>
          </div>
          <hr style="height:1px; border:0; background-color:#e0e0e0; clear:both;">
        </div>
      </div>
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