<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);


spl_autoload_register(function($className)
{
	$class="classes/".strtolower($className).".class.php";
	include_once($class);
});

// It is important for any file that includes this file, to have
// check_login_status.php included at its very top.
$envelope = '<img src="http://www.mechlink.org/gifs/notifications.gif" style="width:0px; height:0px; display:none;" alt="Notifications" title="Sign in to see notifications">';
$loginLink = '<button class="actionbtn" onclick = "document.getElementById(\'light4\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'">Sign in</button>';
$loginLink2 = '<button class="actionbtn" onclick = "document.getElementById(\'light3\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'" style="display:none;"><img src="http://www.mechlink.org/images/actionbutimg.png" style="margin-top:-2px; height:26px; width:33px;"/></button>';
$pstbtn = '<button class="pstbtn" onclick = "document.getElementById(\'light_select_post\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'" style="display:none;">Post</button>';
if($user_ok == true) {
	$sql = "SELECT notescheck FROM users WHERE username='$log_username' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_fetch_row($query);
	$notescheck = ("");
	$sql = "SELECT id FROM notifications WHERE username='$log_username' AND date_time > '$notescheck' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $numrows = mysqli_num_rows($query);
	$sql = "SELECT id FROM friends WHERE user2 = '$log_username' AND accepted = '0' AND datemade > '$notescheck = $row[0];' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $numrows1= mysqli_num_rows($query);
	$sql = "SELECT * FROM notifications WHERE username = '$log_username' AND date_time > '$notescheck' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
    $numrows = mysqli_num_rows($query);
	$sql = "SELECT * FROM friends WHERE user2 = '$log_username' AND accepted = '0' AND datemade > '$notescheck' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $numrows1= mysqli_num_rows($query); 
	if ($numrows == 0 && $numrows1 == 0) {
		$envelope = '<a href="notifications.php" title="Your notifications"><img src="http://www.mechlink.org/images/notout.png" style="width:27px; height:18px;" alt="Notifications"></a>';
    } else {
		$envelope = '<a href="notifications.php" title="You have new notifications"><img src="http://www.mechlink.org/gifs/notifications.gif" style="width:27px; height:18px;" alt="Notifications"></a>';
	}
	$loginLink = '<button class="actionbtn" onclick = "document.getElementById(\'light4\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'" style="display:none;">Sign in</button>';
    $loginLink2 = '<button class="actionbtn" onclick = "document.getElementById(\'light3\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'"><img src="http://www.mechlink.org/images/actionbutimg.png" style="margin-top:-2px; height:26px; width:33px;"/></button>';
	$pstbtn = '<button class="pstbtn" onclick = "document.getElementById(\'light_select_post\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'">Post</button>';
}
?>