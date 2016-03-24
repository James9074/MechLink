<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT']."/includes/check_login_status.php");
include_once($_SERVER['DOCUMENT_ROOT']."/includes/db_conn.php");
include_once($_SERVER['DOCUMENT_ROOT']."/includes/headerphpcode.php");


include_once($_SERVER['DOCUMENT_ROOT']."/includes/user_wrapper.php");

$projectHTML = "";
foreach($current_user->getProjects() as $project){
	$projectHTML .= "<div class='projects-container'>";
	$imgSrc = (sizeof($project->getGallery()->getPhotos()) > 0) ? 'user/'.array_values($project->getGallery()->getPhotos())[0]->username.'/'.array_values($project->getGallery()->getPhotos())[0]->filename : 'images/nocar.png';

	$projectHTML .= '<div class="project-thumbnail-container"><a href="project.php?u='.$u.'&id='.$project->id.'"> <img class="project-thumbnail" src="'.$imgSrc.'"></a></div>';

	$projectHTML .= '<a style="clear:both;" href="project.php?u='.$u.'&id='.$project->id.'">'.$project->automobiletype.'</a>';

	$projectHTML .=  '<p>Date Posted: '.$project->dateposted.'</p>';
	$projectHTML .= "</div>";
	$projectHTML .=  '<hr />';
}
if($projectHTML == ""){
	$projectHTML = "<p style='text-align:center;font-weight:bold'>Sorry, this user has no uploaded projects.</p>";
}

echo $projectHTML; ?>

<style>
	.projects-container{
		height: 85px;
	}
	.project-thumbnail {
		height: 80px;
	}
	.project-thumbnail-container {
		float: left;
		height: 80px;
		margin-right:10px;
		text-align: center;
		overflow: hidden;
		width:150px;
		background-color: #232323;
	}
</style>
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