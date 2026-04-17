<?php

	if(isset($_POST['new_file_name'])){
	$new_file_name = $_POST['new_file_name'];
		if(!$new_file_name){
			echo "<p><span class=warning>You did not specify a file name.</span></p>";
		}else{
			$new_file_name = str_replace(' ','_',$new_file_name);
			
	$ext = explode('.', $new_file_name);
	$n = count($ext);
	if($n > 2){
		echo "<span class=warning>File names are only allowed to have one decimal ( . )!</span>";
	}else{
		include("includes/allowed_files.php");
		//----- find out which footer to use
		if($ext[1] == 'php'){
			$footer = '<?php include("../ads.htm"); ?>';
		}
		if($ext[1] == 'shtml'){
			$footer = '<!--#include virtual="../ads.htm"-->';
		}
		if($ext[1] == 'asp'){
			$footer = '<!--#include virtual="../ads.htm"-->';
		}
		
		$okay = false;
		foreach($allowed_files as $allowed){
			if($ext[1] == $allowed){
				$okay = true;
			}
		}
	}
	  
	if($okay){
					
			
			@touch("$webpath/$username/$new_file_name");
			include("includes/functions.php");
			$content = "
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">
<html>
<head>
 <title>Page Title Here</title>
 <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<link href=\"/includes/style.css\" rel=\"stylesheet\" type=\"text/css\" />
</head>

<body>

<br /><br /><br />

<center>

<h2>Page Created Successfully!</h2>
<br />
You may now delete all of this content and add your own.

</center>
$footer
</body>

</html>
";
			write_file("$webpath/$username/$new_file_name","$content\n");
			?>
			<br /><span class=warning><i><?php echo $new_file_name; ?></i> was successfully created!</span><br /><br />
			<?php
			}else{
				echo "<br /><span class=warning>Your new file name must include a valid file extension!</span><br /><br /><b>Here is a list of allowed extensions:</b><br /><br />";
				include("includes/allowed_files.php");
				foreach($allowed_files as $allowed){
					echo "<i>$allowed</i> &nbsp;";
				}
				echo "<br /><br />";
			}
		}
	
	}
	
?>
