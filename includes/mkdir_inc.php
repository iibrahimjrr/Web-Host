<?php  // mkdir_inc.php

if($_POST['create_dir']){
	if(!mkdir("$username", 0777)){
		echo "<span class=warning>I was unable to create a directory for $username.</span>";
	}
	include("includes/create_file.php");
	echo "Your directory has been set up successfully.  <a href=". $_SERVER['PHP_SELF'] .">Click Here to Continue</a>.";
}

else{
?>
	Before creating your website, you must first create your directory for your webspace
	<br /><br />
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type=submit name=create_dir value="Click Here to Set Up Your WebHost Directory">
	</form>
<?php
}

?>