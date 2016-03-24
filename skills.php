<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT']."/includes/check_login_status.php");
include_once($_SERVER['DOCUMENT_ROOT']."/includes/db_conn.php");
include_once($_SERVER['DOCUMENT_ROOT']."/includes/headerphpcode.php");

include_once($_SERVER['DOCUMENT_ROOT']."/includes/user_wrapper.php");

$skillsetHTML = "";
foreach($current_user->getSkillsets() as $skillset){
	$skillsetHTML .= '<a href="skill.php?u='.$u.'&id='.$skillset->id.'">'.$skillset->automobiletype.'</a>';

	$skillsetHTML .=  '<p>Date Posted: '.$skillset->dateposted.'</p>';

	$skillsetHTML .=  '<hr />';
}
if($skillsetHTML == ""){
	$skillsetHTML = "<p style='text-align:center;font-weight:bold'>Sorry, this user has no uploaded skillsets.</p>";
}
echo $skillsetHTML; ?>

          
          </div>
          <hr />
          <div align="center">
            <div id="section_header"> <span class="style2"> Friends <?php echo "(".$friend_count.")"; ?>&nbsp;&nbsp;<?php echo $friends_view_all_link; ?> </span>
              <div id="sk_btns"> <span id="friendBtn"><?php echo $friend_button; ?></span> </div>
            </div>
            <hr />
            <div id="main_cont2">
              <p><?php echo $friendsHTML; ?></p>
            </div>
          </div>
          <hr />
          <?php include_once($_SERVER['DOCUMENT_ROOT']."/includes/footer.php"); ?>
        </div>
        <!--contentInner2--> 
        
      </div>
      <!--contentInner-->
      
      <?php include("includes/sidecont.php"); ?>
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