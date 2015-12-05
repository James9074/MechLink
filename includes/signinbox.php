<script>
function emptyElement(x){
	_(x).innerHTML = "";
}
function login(){
	var e = _("email1").value;
	var p = _("password1").value;
	if(e == "" || p == ""){
		_("status2").innerHTML = "Please fill in all of the fields...";
	} else {
		_("loginbtn").style.display = "none";
		_("status2").innerHTML = '<img src="http://www.mechlink.org/gifs/blueloader.gif" alt="Loading..." />';
		var ajax = ajaxObj("POST", "php_parsers/login_parse.php"); 
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText == "login_failed"){
					_("status2").innerHTML = "Login unsuccessful, please try again";
					_("loginbtn").style.display = "block";
				} else {
					window.location = "user.php?u="+ajax.responseText;
					//Added:
					window.location.reload(true);
				}
	        }
        }
        ajax.send("e="+e+"&p="+p);
	}
}
</script>
<script>
function cdelay2(){
    // anon wrapper function, 30 second delay
    setTimeout( function () {
        document.getElementById('light2').style.display='none';document.getElementById('fade').style.display='none'
    } , 30000 );

}
</script>

<div align="center">
  <div id="boxform">
    <div id="form"> <a href = "javascript:void(0)" onclick = "document.getElementById('light2').style.display='none';document.getElementById('fade').style.display='none'"><img src="images/x.png" style="height: auto; width: 100%; max-height: 30px; max-width: 30px;"/></a>
      <form name="loginform" class="formafter" id="loginform" onsubmit="return false;">
        <div>
          <input id="email1" type="text" class="formfields" spellcheck="false" tabindex="1" onfocus="emptyElement('status2')" onkeyup="restrict('email')" maxlength="88" placeholder="Your Email">
        </div>
        <div>
          <input id="password1" type="password" class="formfields" tabindex="2" onfocus="emptyElement('status2')" maxlength="100" placeholder="Password">
        </div>
        <div>
          <div>
            <button id="loginbtn" tabindex="3" onclick="login();cdelay2()" class="loginbtn">Sign In</button>
          </div>
        </div>
        <div id="status2"><span id="status2"></span></div>
        <a class="a2" href="http://www.mechlink.org/forgps.php">Forgot Your Password?</a>
        
        <br />
        <br />
      </form>
    </div>
  </div>
</div>
