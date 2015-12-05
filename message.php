<?php
include_once("includes/check_login_status.php");
// If user is already logged in, header them away
if($user_ok == true){
	header("location: user.php?u=".$_SESSION["username"]);
    exit();
}
?>
<?php
$message = "";
$msg = preg_replace('#[^a-z 0-9.:_()]#i', '', $_GET['msg']);
if($msg == "activation_failure"){
	$message = '<div align="center"><span class="style2"><h2>Activation Error</h2> Sorry there seems to have been an issue activating your account at this time. We have already notified ourselves of this issue and we will contact you via email when we have identified the issue.</span></div>';
} else if($msg == "activation_success"){
	$message = '<div align="center"><span class="style2">Welcome to Mechlink</span></div>';
} else {
	$message = $msg;
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
<meta name="Description" content="">
<title>Welcome to Mechlink</title>
<link href="http://www.mechlink.org/styles/boilerplate.css" rel="stylesheet" type="text/css">
<link href="http://www.mechlink.org/styles/common.css" rel="stylesheet" type="text/css">
<link href="http://www.mechlink.org/styles/mainin.css" rel="stylesheet" type="text/css">
<link href="http://www.mechlink.org/styles/textin.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="http://www.mechlink.org/images/favicon.ico?v=2" type="image/x-icon">
<link rel="icon" href="http://www.mechlink.org/images/favicon.ico" type="image/x-icon">
<style>
.loginitbtn {
	position: relative;
	width: 100%;
	max-width: 300px;
	height: auto;
	margin-top: 15px;
	font-size: 18px;
	font-family: Arial, Helvetica, sans-serif;
	color: #FFF;
	background-color: #ff0000;
	border-radius: 5px;
	padding: 18px;
	border: none;
}
.loginitbtn:hover {
	position: relative;
	width: 100%;
	max-width: 300px;
	height: auto;
	margin-top: 15px;
	font-size: 18px;
	font-family: Arial, Helvetica, sans-serif;
	color: #FFF;
	background-color: #d00000;
	border-radius: 5px;
	padding: 18px;
	border: none;
}
</style>
<script src="http://www.mechlink.org/js/respond.min.js"></script>
<script language="javascript" type="text/javascript">
var dateObject=new Date();
</script>
<script src="http://www.mechlink.org/js/main.js"></script>
<script src="http://www.mechlink.org/js/ajax.js"></script>
</head>

<body>
<?php include_once("includes/headeractivation.php"); ?>
<div align="center">
  <div id="container">
    <div class="gridHeader clearfix">
      <div id="content">
        <div id="contentInner">
          <div id="contentInner2">
            <div id="main_cont"> <br />
              <div><?php echo $message; ?></div>
              <br />
              <div align="center">
                <button onclick = "document.getElementById('light2').style.display='block';document.getElementById('fade').style.display='block'" class="loginitbtn"> Click here to sign in</button>
              </div>
            </div>
            <br />
            <br />
          </div>
          <!--contentInner2--> 
          
        </div>
        <!--contentInner--> 
        
      </div>
    </div>
  </div>
  <!--container--> 
</div>
</body>
</html>
<?php include("includes/overlay_sign_join_forms.php"); ?>
