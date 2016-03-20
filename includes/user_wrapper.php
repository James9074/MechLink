<?php // Make sure the _GET username is set, and sanitize it
if(isset($_GET["u"]))
$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);

//========== Grab the user from the DB ==========//
$current_user = User::withUsername(issetor($u,true));
if(!(bool)$current_user->activated){
header("location: http://www.mechlink.org/404");
exit();
}

//========== Check to see if the viewer is the account owner ==========//
$isOwner = false;
$skill_edit_btn = "";
$skill_delete_btn = "";
$profile_pic_btn = "";
$rlname_edit_btn = "";
$category_edit_btn = "";
$location_edit_btn = "";
$status_edit_btn = "";
$project_edit_btn = "";
$project_delete_btn = "";
if($log_username == $current_user->username && $user_ok == true){
    $isOwner = true;
    $profile_pic_btn = '<button class="profile_pic_btn" style="display:block;" onclick="triggerUpload(event, \'FileUpload\')"></button>';
    $rlname_edit_btn = '<button class="edit_btn" style="display:inline-block; margin-top:3px;" onclick = "document.getElementById(\'light_edit_name\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'"></button>';
    $category_edit_btn = '<button class="edit_btn" style="display:inline-block; margin-top:1px;"></button>';
    $location_edit_btn = '<button class="edit_btn" style="display:inline-block; margin-top:1px;" onclick = "document.getElementById(\'light_edit_location\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'"></button>';
    $status_edit_btn = '<button class="edit_btn" style="display:inline-block; margin-top:1px;" onclick = "document.getElementById(\'light_edit_status\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'"></button>';
    $about_edit_btn = '<button class="edit_btn" style="display:block;" onclick = "ModalOpen(\'EditAbout\');"></button>';
    $skill_edit_btn = '<button class="edit_btn" onclick="EditSkill();"></button>';
    $skill_delete_btn = '<button class="delete_btn" onclick="PromptDeleteSkill();"></button>';
    $project_edit_btn = '<button class="edit_btn" onclick="EditProject();"></button>';
    $project_delete_btn = '<button class="delete_btn" onclick="PromptDeleteProject();"></button>';
}

//========== Set profile pic ==========//
$profile_pic = '<img src="user/'.$u.'/'.$current_user->avatar.'" alt="'.$u.'">';
if($current_user->avatar == null)
    $profile_pic = '<img src="images/avatardefault_large.png" alt="'.$current_user->username.'">';

//========== Check current user/logged in user relationship ==========//
$isFriend = false;
$ownerBlockViewer = false;
$viewerBlockOwner = false;
if($u != $log_username && $user_ok == true) {
    //========== Check if friend with current user ==========//
    foreach ($current_user->getFriends() as $friend) {
        if ($log_username == $friend['user1'] || $log_username == $friend['user2'])
            $isFriend = true;
}
//========== Check if blocked by/for current user ==========//
foreach ($current_user->getBlockedUsers() as $block) {
    if ($log_username == $block['blocker'])
        $ownerBlockViewer = true;
    if ($log_username == $block['blockee'])
        $viewerBlockOwner = true;
    }
}
$friend_button = '<button class="friendBtn" disabled style="display:none;">Friend</button>';
$friend_button2 = '<button class="friendBtn2" disabled style="display:none;">Friend</button>';
// LOGIC FOR FRIEND BUTTON
if($isFriend){
    $friend_button = '<button class="friendBtn" style="display:block;" onclick="friendToggle(\'unfriend\',\''.$u.'\',\'friendBtn\')">Unfriend</button>';
    $friend_button2 = '<button class="friendBtn2" style="display:block;" onclick="friendToggle(\'unfriend\',\''.$u.'\',\'friendBtn2\')">Unfriend</button>';
} else if($user_ok == true && $u != $log_username && $ownerBlockViewer == false){
    $friend_button = '<button class="friendBtn" style="display:block;" onclick="friendToggle(\'friend\',\''.$u.'\',\'friendBtn\')">Friend</button>';
    $friend_button2 = '<button class="friendBtn2" style="display:block;" onclick="friendToggle(\'friend\',\''.$u.'\',\'friendBtn2\')">Friend</button>';
}

$block_button = '<button disabled>Block User</button>';
// LOGIC FOR BLOCK BUTTON
if($viewerBlockOwner == true){
    $block_button = '<button onclick="blockToggle(\'unblock\',\''.$u.'\',\'blockBtn\')">Unblock User</button>';
} else if($user_ok == true && $u != $log_username){
    $block_button = '<button onclick="blockToggle(\'block\',\''.$u.'\',\'blockBtn\')">Block User</button>';
}


//========== Showing up to 6 friends of the current profile ==========//
$friend_count = sizeof($current_user->getFriends());
$friends_view_all_link = "";
$friendsHTML = '';
if($friend_count < 1){
    $friendsHTML = '<span class="style2">'.$current_user->rlname.' has no friends';
} else {
    $max = 6;
    $all_friends = array();
    $sql = "SELECT user1 FROM friends WHERE user2='$u' AND accepted='1' ORDER BY RAND() LIMIT $max";
    $query = mysqli_query($db_conx, $sql);
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC))
        array_push($all_friends, $row["user1"]);

    $sql = "SELECT user2 FROM friends WHERE user1='$u' AND accepted='1' ORDER BY RAND() LIMIT $max";
    $query = mysqli_query($db_conx, $sql);
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC))
        array_push($all_friends, $row["user2"]);

    $friendArrayCount = count($all_friends);
    if($friendArrayCount > $max)
        array_splice($all_friends, $max);



    if($friend_count > $max)
        $friends_view_all_link = '<a class="a5" href="friends.php?u='.$u.'">view all</a>';

    $orLogic = '';
    foreach($all_friends as $key => $user) $orLogic .= "username='$user' OR ";



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
