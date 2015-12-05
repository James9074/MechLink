<?php
include_once("includes/check_login_status.php");
// If the page requestor is not logged in, usher them away
if($user_ok != true || $log_username == ""){
	header("location: http://www.mechlink.org");
    exit();
}
$notification_list = "";
$sql = "SELECT * FROM notifications WHERE username LIKE BINARY '$log_username' ORDER BY date_time DESC";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
if($numrows < 1){
	$notification_list = "You do not have any notifications";
} else {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$noteid = $row["id"];
		$initiator = $row["initiator"];
		$app = $row["app"];
		$note = $row["note"];
		$date_time = $row["date_time"];
		$date_time = strftime("%b %d, %Y", strtotime($date_time));
		$notification_list .= "<p><a href='user.php?u=$initiator'>$initiator</a> | $app<br />$note</p>";
	}
}
mysqli_query($db_conx, "UPDATE users SET notescheck=now() WHERE username='$log_username' LIMIT 1");
?>
<?php
$friend_requests = "";
$sql = "SELECT * FROM friends WHERE user2='$log_username' AND accepted='0' ORDER BY datemade ASC";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
if($numrows < 1){
	$friend_requests = 'No friend requests';
} else {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$reqID = $row["id"];
		$user1 = $row["user1"];
		$datemade = $row["datemade"];
		$datemade = strftime("%B %d", strtotime($datemade));
		$thumbquery = mysqli_query($db_conx, "SELECT avatar FROM users WHERE username='$user1' LIMIT 1");
		$thumbrow = mysqli_fetch_row($thumbquery);
		$user1avatar = $thumbrow[0];
		$user1pic = '<img src="user/'.$user1.'/'.$user1avatar.'" alt="'.$user1.'" class="user_pic">';
		if($user1avatar == NULL){
			$user1pic = '<img src="http://www.mechlink.org/images/avatardefault_small.png" alt="'.$user1.'" class="user_pic" title="'.$user1.'">';
		}
		$friend_requests .= '<div id="friendreq_'.$reqID.'" class="friendrequests">';
		$friend_requests .= '<a href="user.php?u='.$user1.'" target="_blank">'.$user1pic.'</a>';
		$friend_requests .= '<div class="user_info" id="user_info_'.$reqID.'"><a href="user.php?u='.$user1.'" class="a6" target="_blank">'.$user1.'</a><br /><br />';
		$friend_requests .= '<button class="acceptBtn" onclick="friendReqHandler(\'accept\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">Accept</button>&nbsp;';
		$friend_requests .= '<button class="rejectBtn" onclick="friendReqHandler(\'reject\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">Reject</button>';
		$friend_requests .= '</div>';
		$friend_requests .= '</div>';
	}
}
?>
<?php
include_once("includes/headerphpcode.php");
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
<title>Notifications</title>
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
<script>
function friendReqHandler(action,reqid,user1,elem){
	var conf = confirm("Press OK to "+action+" this friend request.");
	if(conf != true){
		return false;
	}
	_(elem).innerHTML = "processing ...";
	var ajax = ajaxObj("POST", "php_parsers/friend_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "accept_ok"){
				_(elem).innerHTML = "<b>Request Accepted!</b><br />Your are now friends";
			} else if(ajax.responseText == "reject_ok"){
				_(elem).innerHTML = "<b>Request rejected</b><br />You chose not to be friends with this user";
			} else {
				_(elem).innerHTML = ajax.responseText;
			}
		}
	}
	ajax.send("action="+action+"&reqid="+reqid+"&user1="+user1);
}
</script>
</head>

<body>
<?php include_once("includes/header.php"); ?>
<?php include_once("includes/overlay_skills.php"); ?>
<?php include_once("includes/overlay_project.php"); ?>
<?php include_once("includes/overlay_post.php"); ?>
<div id="container">
  <div class="gridHeader clearfix">
    <div id="contentNotes">
      <div align="center">
        <div id="notesBox">
          <h2 class="style3">Notifications</h2>
          <hr style="height:2px; border:0; background-color:#e0e0e0;">
          <?php echo $notification_list; ?> </div>
        <div id="ReqBox">
          <h2 class="style3">Friend Requests</h2>
          <hr style="height:2px; border:0; background-color:#e0e0e0;">
          <?php echo $friend_requests; ?> </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>