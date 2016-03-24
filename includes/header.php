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
<link href="http://www.mechlink.org/styles/boilerplate.css" rel="stylesheet" type="text/css">
<link href="http://www.mechlink.org/styles/common.css" rel="stylesheet" type="text/css">
<link href="http://www.mechlink.org/styles/mainuser.css" rel="stylesheet" type="text/css">
<link href="http://www.mechlink.org/styles/textusr.css" rel="stylesheet" type="text/css">
<link href="http://www.mechlink.org/styles/user.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="http://www.mechlink.org/images/favicon.ico?v=2" type="image/x-icon">
<link rel="icon" href="http://www.mechlink.org/images/favicon.ico" type="image/x-icon">
<script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "d7ff69d9-2897-4dc9-ac03-f66f1c76496f", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
<script src="js/respond.min.js"></script>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/common.js"></script>
<script language="javascript" type="text/javascript">
    var dateObject=new Date();
    var CURRENT_USER = {'username':'<?echo $_COOKIE['user'];?>'};
</script>
<script src="http://www.mechlink.org/js/main.js"></script>
<script src="http://www.mechlink.org/js/ajax.js"></script>
<script type="text/javascript" src="/js/datejs/date.js"></script>
    <meta name="Description" content="This is <?php echo $current_user->rlname; ?>'s Mechlink profile.">
    <title><?php echo $current_user->rlname; ?> • MechLink"</title>
    <script src="js/user.js"></script>
    <script>
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
    </script>
    <script>
        function triggerUpload(event,elem){
            event.preventDefault();
            document.getElementById(elem).click();
        }
    </script>
</head>

<body>
<div id="light_warning" class="white_content_skills">
    <div align="center">
        <div id="boxform">
            <div id="form_long"> <br />
                <span id="warning_question"><b>Are you sure?</b></span> <br />
                <br />
                <div>
                    <button id="yesButton" tabindex="20">Delete</button>
                    <button id="noButton" onclick = "ModalOpen('None');" tabindex="21">Cancel</button>
                    <br />
                    <br />
                </div>
            </div>
        </div>
    </div>
</div>
<div id="fade" class="black_overlay"></div>
