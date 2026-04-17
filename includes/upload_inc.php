<?php  // upload_inc.php

if(isset($_POST['upload_file'])) {
	
	$sec = explode('.', $_FILES['thefile']['name']);
	$dots = count($sec);
	if($dots > 2){
		echo "Please remove all but one dot from your filename before uploading it.";
	}else{
		include("includes/allowed_files.php");
	
		$okay = false;
		foreach($allowed_files as $allowed){
			if($sec[1] == $allowed){
				$okay = true;
			}
		}
	}
	
	if($okay){

		if (move_uploaded_file ($_FILES['thefile']['tmp_name'], str_replace(" ","_","$webpath/$username/{$_FILES['thefile']['name']}"))) {
			echo '<p>Your file has been uploaded.</p>';
		} else {
			echo '<p>Your file could not be uploaded because <span class=warning>';

			switch ($_FILES['thefile']['error']) {
				case 1:
					echo 'The file exceeds the upload_max_filesize setting in php.ini';
				break;
				case 2:
					echo 'The file exceeds the MAX_FILE_SIZE setting in the HTML form';
				break;
				case 3:
					echo 'The file was only partially uploaded';
				break;
				case 4:
					echo 'No file was uploaded';
				break;
			}
			echo '</span></p>';
		}
	}else{
		echo '<br /><span class=warning>That file type is not allowed!</span><br /><br />';
	}
}

?>