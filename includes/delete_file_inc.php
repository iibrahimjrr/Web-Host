<?php // delete_file_inc.php

if($_POST['submit_delete']){
	$display = $_POST['display'];
	$delete = $_POST['delete'];
	echo "<br /><b><u>Files Removed:</u></b><br /></b><ul>";
		foreach($delete as $display){
			@unlink("$webpath/$username/$display");  // delete the files
			echo "<li><span class=warning>$display</span></li>";
	}
	echo "</ul>";
}
		
?>