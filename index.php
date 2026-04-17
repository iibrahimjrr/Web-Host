<?php
session_start();
if(file_exists('includes/connect.php')){
	include("includes/connect.php");
	$usr = $_COOKIE[md5('whname')];
	$chk_usr = mysql_query("select * from users where username='$usr' limit 1");
	$usr_sts = mysql_fetch_array($chk_usr);
}

if(file_exists('first_run.php')){
	header("Location: first_run.php");
	exit();
}else{

	include("includes/config.php");

	if(!isset($_COOKIE[md5('whname')])){
		header("Location: login.php");
	}else{		
		$ip_adr = $_SERVER['REMOTE_ADDR'];
		//----- Record IP
		if($usr_sts['ip'] == ''){
			$ip = "update users set ip='$ip_adr' where username='$usr'";
			$ipr = @mysql_query($ip);
			$msg = mysql_error();
		}

		if(!session_is_registered(ip_update)){
			$ip = "update users set ip='$ip_adr' where username='$usr'";
			$ipr = @mysql_query($ip);
			$msg = mysql_error();
			if(!ipr){
				$subject = "$site_name security warning";
				$from = "$site_name mail daemon";
				$body = "Greetings, $admin\n\nThis email is to let you know that your database could not be updated to record the IP addresses of members.\n\nTo fix this problem, make sure that the 'ip' table exists in your KASL WebHost database.  If it doesn't, you can run the following sql query to create it: ALTER TABLE users ADD ip VARCHAR(40) NOT NULL;\nIf this still doesn't fix the problem, please visit http://www.kasl.info/forum and create a new topic in the bug reports forum.\n\nRegards,\nKris Law\nKASL WebHost Developer";
				if(!@mail($admin_email, $subject, $body, $from)){
					echo "<span class=warning>A bug has occurred in the system.  Please contact $admin at $admin_email and give them the following code number:  101.</span>";
				}
			}
			session_register(ip_update);
		}	
	
		//----- Determine user's approval status and act accordingly
		if($approved == 'no'){
			if($usr_sts['approved'] != 'yes'){
				include("includes/header.php");
				$not_approved = "<span class=warning>Your account has not yet been approved by $admin.</span>";
				include("includes/footer.php");
				exit();
			}
		}
		
		?><head>
		<!--
		Define the popup help window properties 
		-->
		<SCRIPT LANGUAGE="JavaScript">
		<!-- Begin
		function popUp(URL) {
		day = new Date();
		id = day.getTime();
		eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=300,height=250,left = 362,top = 209');");
		}
		// End -->
		</script>
		</head>
		
		<?php
		include("includes/header.php");
		$username = $_COOKIE[md5('whname')];
		$path = ("$webpath/$username");
		$dir = @opendir($path);
		$display = @readdir($dir);
	
		do {
			$main_file_size = round(@filesize("$username/$display") / 1024, 2);
			$total_directory_size += $main_file_size;
		}while($display = @readdir($dir));
		$total_directory_size = round($total_directory_size - 8, 2);
		$unit = 'Kb';
		
		if($total_directory_size >= 1024){
			$total_directory_size = round($total_directory_size / 1024, 2);
			$unit = 'Mb';
		}
	
		if($total_directory_size < "$space_allocated"){
			$allo_add_files = 'yes';
		}		
		
		if(!is_dir("$username")){
			include("includes/mkdir_inc.php");	
		}
		else{
			?>
			<center>Your website url is <a href="<?php echo "$url/$username"; ?>" title="Click the link to view your home page!"><u><?php echo "$url/$username"; ?></u></a><br /><br />
			<table width=95% align=center border=0 cellspacing=10 cellpadding=5>
			 <tr valign=top>
			  <td width=50%>
			<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="menu">Refresh This Page</a><br />
			<hr>
			<!--
			Show File Creation Forms
			-->
			<b>Create A File:</b> &nbsp; <a href="javascript:popUp('help.php#new_file')"><img src="images/help.gif" border="0" hspace="1" width="16" height="16" align="middle" title="Click here for help on creating a new file."></a><br /><br />
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<?php
			if($allo_add_files == 'yes'){
			?>
			File Name: <input type="text" name="new_file_name" size="15" value="Enter Filename" onFocus="this.value=''" title="Enter a new file name">
			<input type="submit" name="new_file" value="Go >>">
			<?php
		}else{
			echo "<span class=warning>You are out of disk space.</span><br /><br />If you would like to create or upload more files, you will need to reduce the size of, or delete other files.";
		}
		echo "</form>";
		
  		//----- Delete the file(s)
		include("includes/delete_file_inc.php");
			
		//----- Create New File [form]
		include("includes/create_file_inc.php");
		
		//----- Upload File [Form]
		if($file_uploads == 'yes'){
			if($allo_add_files == 'yes'){			
				?>
				<hr>
				<b>File Upload:</b> &nbsp; <a href="javascript:popUp('help.php#upload')"><img src="images/help.gif" border="0" hspace="1" width="16" height="16" align="middle" title="Click here for help on uploading a new file."></a><br /><br />
				<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				File Name: <input type="file" name="thefile" size="15" title="Click the browse button to search for a file."><br />
				<input type="hidden" name="MAX_FILE_SIZE" value=3000>
				<input type="submit" name="upload_file" value="Upload >>">
				</form>
				<?php
			}
		}else{
			echo "File uploads have been disabled for security reasons.";
		}
		
		include("includes/upload_inc.php");
		include("includes/rename_inc.php");	
	
		?>
	
		<hr>
		<b>Image Legend:</b><br /><br />
		<table width=100% border=0>
		 <tr>
		  <td width=50%>
		<img src=images/edit.gif border=0 hspace=1 align=middle title="Click on this image to edit a web page."> = Edit<br /><br />
		<img src=images/help.gif border=0 hspace=1 align=middle title="Click on this image if you need help with something."> = Help<br /><br />
		  </td>
		  <td width=50%>
		<img src=images/rename.gif border=0 hspace=1 align=middle title="Click on this image to rename a web page."> = Rename<br /><br />
		<img src=images/file.gif border=0 hspace=1 align=middle title="This image indicates that the item is a file."> = File<br /><br />
		  </td>
		 </tr>
		</table>
		<br />
		<br /><br />
		  </td>
		  <td width=50%>	
		<hr><b>List of <?php echo $username; ?>'s files:</b> &nbsp; <a href="javascript:popUp('help.php#list')"><img src=images/help.gif border=0 hspace=1 width=16 height=16 align=middle title="Click here for help regarding your file list."></a><br /><br />
		<table border=0>
		<form name=delete method=post action="<?php echo $_SERVER['PHP_SELF']; ?>">
		
		<?php
		$row_count = 0; 
		$path = ("$webpath/$username");
		if(!$dir = @opendir($path)){
		  echo "<span class=warning><b>I couldn't open $username's directory!</b></span><br /><br />
				<span class=warning>Please check server permissions, and that the path to this directory is correct.  This problem could be caused by either one.</span>";
		}
		$display = @readdir($dir);
		
			do {
				$dyn_tr_bg_color = ($row_count % 2) ? $td_bg_color : $td_bg_color2;
				if($display != "." && $display != ".."){
					echo "
					<tr><td bgcolor=$dyn_tr_bg_color>
					<input type=checkbox name=delete[] value=$display>&nbsp;&nbsp;
					<a href={$_SERVER['PHP_SELF']}?name=$username&change_name=$display alt=rename title=\"Rename $display\"><img src=images/rename.gif border=0 hspace=1 align=middle></a>&nbsp;&nbsp;
					<a href=edit.php?name=$username&filename=$display alt=edit title=\"Edit $display\"><img src=images/edit.gif border=0 hspace=1 align=middle></a> &nbsp;&nbsp;
					<img src=images/file.gif  border=0 hspace=1 align=middle>&nbsp;&nbsp;<a href=$url/$username/$display target=blank title=\"View $display\">$display</a>";
					$size = round(filesize("$username/$display") / 1024, 2);
					$type = Kb;
					if($size >= 1024){
						$size = round($size / 1024, 2);
						$type = Mb;
					}
					echo " ~ $size $type<br />
					</td></tr><tr><td>";
					round($total_file_size += $size, 2);
				}
				$row_count++;
			}while($display = @readdir($dir));
		$space_left = round($space_allocated - $total_file_size, 2);
		@closedir($dir);
		?>
		
		
		<br />
		<p><input type=submit name=submit_delete value=Delete onClick="javascript:return confirm('Are you sure you want to delete this file?')"> &nbsp; <a href="javascript:popUp('help.php#delete')"><img src=images/help.gif border=0 hspace=1 width=16 height=16 align=middle title="Click here for info about deleting files."></a></p>
		<br />
		<fieldset><legend> <b> Statistics: </b> </legend>
		<p><div align=right>Total size of files combined:  <b><?php echo $total_directory_size; ?></b> <?php echo $unit; ?></p>
		<p>You have <b><?php echo $space_left; ?></b> Kb of disk space left.</div></p>
		</fieldset>
			</td></tr></table>
		</form>
		
		  </td>
		 </tr>
		</table>
		
		<?php
			}
		}
	include("includes/footer.php");
}

?>
