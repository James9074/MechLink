<script>
function restrict(elem){
	var tf = _(elem);
	var rx = new RegExp;
	if(elem == "email"){
		rx = /[' "]/gi;
	} else if(elem == "username"){
		rx = /[^a-z0-9]/gi;
	} else if(elem == "rlname"){
		rx = /[^a-z0-9\s.-]/gi;
	}
	if(tf != null)
		tf.value = tf.value.replace(rx, "");
}
function emptyElement(x){
	_(x).innerHTML = "";
}
function checkusername(){
	var u = _("username").value;
	if(u != ""){
		_("unamestatus").innerHTML = 'checking ...';
		var ajax = ajaxObj("POST", "index.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            _("unamestatus").innerHTML = ajax.responseText;
	        }
        }
        ajax.send("usernamecheck="+u);
	}
}
function signup(){
	var r = _("rlname").value;
	var u = _("username").value;
	var e = _("email").value;
	var p1 = _("pass1").value;
	var p2 = _("pass2").value;
	var status = _("status");
	if(r == "" || u == "" || e == "" || p1 == "" || p2 == ""){
		status.innerHTML = "Please fill in all of the fields...";
	} else if(p1 != p2){
		status.innerHTML = "Your password fields do not match!";
	} else {
		_("signupbtn").style.display = "none";
		status.innerHTML = '<img src="http://www.mechlink.org/gifs/blueloader.gif" alt="Loading..." />';
		var ajax = ajaxObj("POST", "index.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText != "signup_success"){
					status.innerHTML = ajax.responseText;
					_("signupbtn").style.display = "block";
				} else {
					window.scrollTo(0,0);
					_("signupform").innerHTML = "OK "+r+", check your email inbox and junk mail box at <u>"+e+"</u> in a moment to activate your account! <br /><br />Once your account is activated, you can make your Mechlink profile.";
				}
	        }
        }
        ajax.send("r="+r+"&u="+u+"&e="+e+"&p="+p1);
	}
}

/* function addEvents(){
	_("elemID").addEventListener("click", func, false);
}
window.onload = addEvents; */
</script>
<script>
function cdelay(){

    // anon wrapper function, 60 second delay
    setTimeout( function () {
        document.getElementById('light1').style.display='none';document.getElementById('fade').style.display='none'
    } , 60000 );

}
</script>

<div align="center">
  <div id="boxform">
    <div align="center">
      <div id="form"> <a href = "javascript:void(0)" onclick = "document.getElementById('light1').style.display='none';document.getElementById('fade').style.display='none'"><img src="images/x.png" style="height: auto; width: 100%; max-height: 30px; max-width: 30px;"/></a>
        <form name="signupform" class="formafter" id="signupform" onsubmit="return false;">
              <div>
                <input id="rlname" type="text" class="formfields" spellcheck="false" tabindex="1" onfocus="emptyElement('status')" onkeyup="restrict('rlname')" maxlength="60" placeholder="Your Name or Business Name">
              </div>
             
              
              <div>
                <input id="username" type="text" class="formfields" size="8" tabindex="2" onblur="checkusername()" onkeyup="restrict('username')" maxlength="40" placeholder="Choose a Username">
              </div>
              <div id="name_status"> <span id="unamestatus" class="namestatus"></span> </div>
              <div>
                <input id="email" type="text" class="formfields" spellcheck="false" tabindex="3" onfocus="emptyElement('status')" onkeyup="restrict('email')" maxlength="88" placeholder="Your Email">
              </div>
              <div>
                <input id="pass1" type="password" class="formfields" tabindex="4" onfocus="emptyElement('status')" maxlength="100" placeholder="Password">
              </div>
              <div>
                <input id="pass2" type="password" class="formfields" tabindex="5" onfocus="emptyElement('status')" maxlength="100" placeholder="Re-enter Password">
              </div>
              <div>
                <div>
              <button id="signupbtn" onclick="signup();cdelay()" class="loginbtn" tabindex="6">Join Now</button>
            </div>
          </div>
          <div id="status"><span id="status"></span></div>
        </form>
      </div>
    </div>
  </div>
</div>
