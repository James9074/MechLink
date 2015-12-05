<?php
// Ajax calls this NAME CHECK code to execute
if(isset($_POST["usernamecheck"])){
	
	$username = preg_replace('#[^a-z0-9]#', '', $_POST['usernamecheck']);
	$sql = "SELECT id FROM users WHERE username='$username' LIMIT 1";
    $query = mysqli_query($db_conx, $sql); 
    $uname_check = mysqli_num_rows($query);
    if (strlen($username) < 3 || strlen($username) > 40) {
	    echo '<strong style="color:#F00;">Please use 3 - 40 characters</strong>';
	    exit();
    }
	if (is_numeric($username[0])) {
	    echo '<strong style="color:#F00;">Usernames must begin with a letter</strong>';
	    exit();
    }
    if ($uname_check < 1) {
	    echo '<strong style="color:#009fe4;">' . $username . ' is available</strong>';
	    exit();
    } else {
	    echo '<strong style="color:#F00;">' . $username . ' is taken</strong>';
	    exit();
    }
}
?>
<?php
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["u"])){
	// CONNECT TO THE DATABASE
	
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES
	$r = preg_replace('#[^a-z0-9\s.-]#i', '', $_POST['r']);
	$u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);
	$e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$p = $_POST['p'];
	// GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	// DUPLICATE DATA CHECKS FOR USERNAME AND EMAIL
	$sql = "SELECT id FROM users WHERE username='$u' LIMIT 1";
    $query = mysqli_query($db_conx, $sql); 
	$u_check = mysqli_num_rows($query);
	// -------------------------------------------
	$sql = "SELECT id FROM users WHERE email='$e' LIMIT 1";
    $query = mysqli_query($db_conx, $sql); 
	$e_check = mysqli_num_rows($query);
	// FORM DATA ERROR HANDLING
	if($r == "" || $u == "" || $e == "" || $p == ""){
		echo "The form submission is missing values.";
        exit();
	} else if ($u_check > 0){ 
        echo "The username you entered is alreay taken";
        exit();
	} else if ($e_check > 0){ 
        echo "That email address is already registered at Mechlink";
        exit();
	} else if (strlen($u) < 3 || strlen($u) > 40) {
        echo "Username must be between 3 and 40 characters";
        exit(); 
    } else if (is_numeric($u[0])) {
        echo 'Username cannot begin with a number';
        exit();
    } else {
	// END FORM DATA ERROR HANDLING
	    // Begin Insertion of data into the database
		// Hash the password and apply your own mysterious unique salt
		//$cryptpass = crypt($p);
		//include_once ("includes/randStrGen.php");
		//$p_hash = randStrGen(20)."$cryptpass".randStrGen(20);
		$p_hash = md5($p);
		// Add user info into the database table for the main site table
		$sql = "INSERT INTO users (rlname, username, email, password, ip, signup, lastlogin, notescheck)       
		        VALUES('$r','$u','$e','$p_hash','$ip',now(),now(),now())";
		$query = mysqli_query($db_conx, $sql); 
		$uid = mysqli_insert_id($db_conx);
		// Establish their row in the useroptions table
		$sql = "INSERT INTO useroptions (id, username, background) VALUES ('$uid','$u','original')";
		$query = mysqli_query($db_conx, $sql);
		// Create directory(folder) to hold each user's files(pics, MP3s, etc.)
		if (!file_exists("user/$u")) {
			mkdir("user/$u", 0755);
		}
		// Email the user their activation link
		$to = "$e";							 
		$from = "service@mechlink.org";
		$subject = 'Welcome to Mechlink';
		$message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Welcome to Mechlink</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#ff0000; font-size:24px; color:#FFF;"><img src="http://www.mechlink.org/images/activation_img.png" width="30" height="35" alt="Mechlink" style="border:none; float:left; padding-right:20px;">Activate your Mechlink account</div><div style="padding:24px; font-size:17px;">Hello '.$r.'<br /><br />Click the link below to activate your account:<br /><br /><a href="http://www.mechlink.org/activation.php?id='.$uid.'&u='.$u.'&e='.$e.'&p='.$p_hash.'">Click here to activate your account now</a><br /><br />After you activate your account you can sign in using your e-mail address: <p><b>'.$e.'</b></p></div></body></html>';
		$headers = "From: $from\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
		mail($to, $subject, $message, $headers);
		echo "signup_success";
		exit();
	}
	exit();
}
?>