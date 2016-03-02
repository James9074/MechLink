<?php
include_once("includes/check_login_status.php");
// If user is already logged in, header that person away
if($user_ok == true){
	header("location: user.php?u=".$_SESSION["username"]);
    exit();
}
?>
<?php
include_once("includes/headerphpcode.php");
?>
<?php
// AJAX CALLS THIS CODE TO EXECUTE
if(isset($_POST["e"])){
	$e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$sql = "SELECT id, username FROM users WHERE email='$e' AND activated='1' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
	if($numrows > 0){
		while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
			$id = $row["id"];
			$u = $row["username"];
		}
		$emailcut = substr($e, 0, 4);
		$randNum = rand(10000,99999);
		$tempPass = "$emailcut$randNum";
		$hashTempPass = md5($tempPass);
		$sql = "UPDATE useroptions SET temp_pass='$hashTempPass' WHERE username='$u' LIMIT 1";
	    $query = mysqli_query($db_conx, $sql);
		$to = "$e";
		$from = "service@mechlink.org";
		$headers ="From: $from\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1 \n";
		$subject ="Mechlink Temporary Password";
		$msg = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Mechlink Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#ff0000; font-size:24px; color:#FFF;"><img src="http://www.mechlink.org/images/activation_img.png" width="30" height="35" alt="Mechlink" style="border:none; float:left; padding-right:20px;">Your Mechlink Password</div><div style="padding:24px; font-size:17px;">Hello '.$u.',<br /><br /><p>This automated message from Mechlink was sent because someone told us you have forgotten your password. If you did not recently ask us to send you a new password, please disregard this email.</p><p>We are giving you a temporary password so you can sign in. Once you have signed in, you can change your password to anything you like.</p><p>After you click the link below, your password to sign in will be:<br /><b>'.$tempPass.'</b></p><p><a href="http://www.mechlink.org/forgps.php?u='.$u.'&p='.$hashTempPass.'">Click here now to use the temporary password</a></p><p>If you do not click the link in this email, no changes will be made to your account. In order to set your sign in password to the temporary password, you must click the link above.</p></div></body></html>';
		if(mail($to,$subject,$msg,$headers)) {
			echo "success";
			exit();
		} else {
			echo "email_send_failed";
			exit();
		}
    } else {
        echo "no_exist";
    }
    exit();
}
?>
<?php
// EMAIL LINK CLICK CALLS THIS CODE TO EXECUTE
if(isset($_GET['u']) && isset($_GET['p'])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
	$temppasshash = preg_replace('#[^a-z0-9]#i', '', $_GET['p']);
	if(strlen($temppasshash) < 10){
		exit();
	}
	$sql = "SELECT id FROM useroptions WHERE username='$u' AND temp_pass='$temppasshash' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
	if($numrows == 0){
		header("location: message.php?msg=There is no match for that username with that temporary password in the system. We cannot proceed.");
    	exit();
	} else {
		$row = mysqli_fetch_row($query);
		$id = $row[0];
		$sql = "UPDATE users SET password='$temppasshash' WHERE id='$id' AND username='$u' LIMIT 1";
	    $query = mysqli_query($db_conx, $sql);
		$sql = "UPDATE useroptions SET temp_pass='' WHERE username='$u' LIMIT 1";
	    $query = mysqli_query($db_conx, $sql);
	    header("location: login.php");
        exit();
    }
}
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
<title>Reset Your Password</title>
<link href="http://www.mechlink.org/styles/boilerplate.css" rel="stylesheet" type="text/css">
<link href="http://www.mechlink.org/styles/common.css" rel="stylesheet" type="text/css">
<link href="http://www.mechlink.org/styles/mainsi.css" rel="stylesheet" type="text/css">
<link href="http://www.mechlink.org/styles/textsi.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="http://www.mechlink.org/images/favicon.ico?v=2" type="image/x-icon">
<link rel="icon" href="http://www.mechlink.org/images/favicon.ico" type="image/x-icon">
<script language="javascript" type="text/javascript">
var dateObject=new Date();
</script>
<script src="http://www.mechlink.org/js/main.js"></script>
<script src="http://www.mechlink.org/js/ajax.js"></script>
<script>
function forgotpass(){
	var e = _("email2").value;
	if(e == ""){
		_("status5").innerHTML = "Please type in your email address";
	} else {
		_("forgotpassbtn").style.display = "none";
		_("status5").innerHTML = '<img src="http://www.mechlink.org/gifs/greenloader.gif" alt="Loading..." />';
		var ajax = ajaxObj("POST", "forgps.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
				var response = ajax.responseText;
				if(response == "success"){
					_("forgotpassform").innerHTML = '<h3>Check your email inbox in a few minutes to get your temporary password.</h3><p>You can close this window or tab if you like.</p>';
				} else if (response == "no_exist"){
					_("status5").innerHTML = "Sorry, that email address is not in our system";
					_("forgotpassbtn").style.display = "block";
				} else if(response == "email_send_failed"){
					_("status5").innerHTML = "Mail function failed to execute";
					_("forgotpassbtn").style.display = "block";
				} else {
					_("status5").innerHTML = "An unknown error occurred";
					_("forgotpassbtn").style.display = "block";
				}
	        }
        }
        ajax.send("e="+e);
	}
}
</script>
</head>

<body>
<?php include_once("includes/navbar.php"); ?>
<div id="container">
  <div class="gridHeader clearfix">
    <div align="center">
      <div id="content">
        <div id="contentInner">
          <div align="center"><span class="style3">Make a Temporary Password</span></div>
          <br />
          <div align="center">
            <form name="forgotpassform" class="formafter" id="forgotpassform" onsubmit="return false;">
              <div>
                <input id="email2" type="text" class="formfields" spellcheck="false" tabindex="2" onfocus="_('status').innerHTML='';" maxlength="88" placeholder="Enter Your Email">
              </div>
              <div>
                <div>
                  <button id="forgotpassbtn" class ="forgotpassbtn" onclick="forgotpass()">Get Your Temporary Password</button>
                </div>
              </div>
              <div id="status2"><span id="status5"></span></div>
              
            </form>
          </div>
        </div>
        <!--contentinner--> 
        
      </div>
    </div>
  </div>
</div>

<br />
<br />
<?php include_once("includes/footer.php"); ?>
</body>
</html>