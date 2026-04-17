<?php  // rename_inc.php

//----- Rename the file	
if($_GET['change_name']){
	echo "<hr>";
	if($_GET['rename']){
	  $newname = $_GET['newname'];
	  
	  $ext = explode('.', $newname);
	  include("includes/allowed_files.php");
	  
	foreach($allowed_files as $allowed){
		if($ext[1] == $allowed){
			$okay = true;
		}
	}
	  
	if($okay){
		$change_name = $_GET['change_name'];
			if (eregi("[[:space:]]", $newname)){
				echo "<br /><center><span class=warning><i>$newname</i> is an invalid name -<br />it can <u>not</u> contain spaces!</span></center>";
			}else{
				rename("$webpath/$username/$change_name", "$webpath/$username/$newname");
				echo "<br /><span class=warning>Your file has been successfully renamed to <i>$newname</i></span><br />";
			}
	}else{
		echo '<br /><span class=warning>Your file cannot be changed to that extension!</span><br />';
	}
	}else{
		$change_name = $_GET['change_name'];
		?>
			<br /><span class=warning>Please enter the new name of your file:</span><br /><br />
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
			<input type="hidden" name="change_name" value="<?php echo $change_name; ?>">
			<input type="text" size="40" name="newname" value="<?php echo $change_name; ?>">
			<input type="submit" name="rename" value="Go!">
			</form>
		<?php
	}
echo "<br />";
}
	
?>