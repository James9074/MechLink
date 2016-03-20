<?php
session_start();
include_once("includes/check_login_status.php");
include_once("includes/db_conn.php");
include_once("includes/headerphpcode.php");

// AJAX CALLS
if(isset($_POST["oper"])) {
	$oper = $_POST["oper"];
	$returnData = array();
	$returnData["oper"] = $_POST["oper"];
	$returnData["postData"] = $_POST;
	$database = new Database();
	header('Content-Type: application/json');
	if ($oper == "DeleteSkillset") {

	} else if ($oper == "EditSkillset") {

	}
	exit();
}

if(isset($_GET['id'])) {
	$skillset = Skillset::withID($_GET['id']);
	if($skillset->id == null) {
		header("location: http://www.mechlink.org/404");
		exit();
	}
}
else{
	header("location: http://www.mechlink.org/404");
	exit();
}

if($skillset->username != $_GET['u']) {
	header("location: http://www.mechlink.org/skill.php?u=".$skillset->username."&id=".$skillset->id);
	exit();
}

include_once("includes/user_wrapper.php");


?>
			  <div style="float:left;"><?php echo $skill_edit_btn; ?></div>

			  <div style="float:right;"><?php echo $skill_delete_btn; ?></div>
		  <br />
            <br />
            <p class="section_header" style="clear:both;">Restoration Project</p>
		    <div class="data_section">
				<p>Type of automobile: <?php echo $skillset->automobiletype; ?></p>
				Location: <?php echo $skillset->location; ?>
				<p>Restored from: <?php echo $skillset->restoredfrom; ?> - <?php echo $skillset->restoredto; ?></p>
				<?php if($skillset->hasAwards()){ ?>
				<p>Awards:
					<ul>
						<?php if($skillset->award1 != null) echo "<li>".$skillset->award1."</li>"; ?>
						<?php if($skillset->award2 != null) echo "<li>".$skillset->award2."</li>"; ?>
						<?php if($skillset->award3 != null) echo "<li>".$skillset->award3."</li>"; ?>
						<?php if($skillset->award4 != null) echo "<li>".$skillset->award4."</li>"; ?>
					</ul>
				</p>
				<?php } ?>
			</div>
            <p class="section_header">Specific Skills</p>
			  <div class="data_section">
			  	<p><?php echo $skillset->skills; ?></p>
			  </div>
			  <p class="section_header"> Education & Training</p>
			  <?php
				foreach($skillset->getSchools() as $school){ ?>
					<div class="data_section">
						<p><b>Name of School:</b> <?php echo $school->name; ?></p>
						<?php if($school->location != null) echo "<p>Location: ". $school->location."</p>"; ?>
						<?php if($school->attendedfrom != null || $school->attendedto != null)
							echo "<p>Attended: ". $school->attendedfrom." - ".$school->attendedto."</p>"; ?>

						<?php if($school->hasDegrees()){ ?>
							<p>Degrees:
							<ul>
								<?php if($school->degree1 != null) echo "<li>".$school->degree1."</li>"; ?>
								<?php if($school->degree2 != null) echo "<li>".$school->degree2."</li>"; ?>
								<?php if($school->degree3 != null) echo "<li>".$school->degree3."</li>"; ?>
								<?php if($school->degree4 != null) echo "<li>".$school->degree4."</li>"; ?>
							</ul>
							</p>
						<?php } ?>

						<?php if($school->awards != null) echo "<p>Awards: ". $school->awards."</p>"; ?>

					</div>
			  <?php } if(sizeof($skillset->getSchools()) == 0){
				  echo '<div class="data_section"><p>This skillset has no connected schools</p></div>';
			  } ?>
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
          <?php include_once("includes/footer.php"); ?>
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
<?php include_once("includes/footer_over.php"); ?>
</body>
</html>/html>