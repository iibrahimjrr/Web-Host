<?php
if($_POST['config_submit']){
	$admin_email = $_POST['admin_email'];
	setcookie('admin_email', $admin_email, time()+3600);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>KASL WebHost Installation</title>
<link href="includes/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
$kwh_version = "3.5";
$url_path = $_SERVER['SERVER_NAME'];

//File will be rewritten if already exists
function write_file($filename,$newdata) {
	  $f=fopen($filename,"w");
	  fwrite($f,$newdata);
	  fclose($f);  
}
function append_file($filename,$newdata) {
	  $f=fopen($filename,"a");
	  fwrite($f,$newdata);
	  fclose($f);  
}
function read_file($filename) {
	  $f=fopen($filename,"r");
	  $data=fread($f,filesize($filename));
	  fclose($f);  
	  return $data;
}

?>
<table width=777><tr><td>
<a href=http://www.kasl.info target=blank><img src=images/kasl_webhost.png border=0></a><br />
 <p>Thank you for using KASL WebHost!</p>
 <p>If you like this program, please support the cause by sending a donation to me by <a href=http://www.kasl.info/donate.php target=blank>CLICKING HERE</a>.</p>
 
 <!-- New automatic database setup -->
 <h2>Welcome to the KASL WebHost Installation Wizard!</h2>
 This wizard will set up KASL WebHost so that it will be ready to use.<br /><br />

 <h2>Permissions</h2>
 Before we start, please ensure that the <b>root</b> directory and the <b>includes</b> directory both have read + write permissions set - for example:
 <br /><br />
In Linux - chmod 777 webhost, or in Windows, IUSR_Local (Internet Guest User) full permissions to the two directories.
 <br />
<h2>Setup</h2>
 As you go through this wizard, please make sure you go through the following links in order:<br />
 <br /><br />
 <b>1. &nbsp;</b><a href=<?php echo $_SERVER['PHP_SELF']; ?>?cmd=set_db>Set Database Connection</a><br />
 <b>2. &nbsp;</b><a href=<?php echo $_SERVER['PHP_SELF']; ?>?cmd=config>Create Configuration</a><br />
 <b>3. &nbsp;</b><a href=<?php echo $_SERVER['PHP_SELF']; ?>?cmd=create_table>Create Database Table</a><br />
 <b>4. &nbsp;</b><a href=<?php echo $_SERVER['PHP_SELF']; ?>?cmd=finish>Finishing Up</a><br />
 
<?php

// Database connection form
if($_GET['cmd'] == 'set_db'){

	if(!@touch('test.php')){
		echo "<br /><br /><b>Your permissions seem to be wrong.</b><br /><br />
			Please ensure that you followed Permissions instructions above.<br /><br />If you did everything correctly, please contact your server administrator for assistance.<br /><br />
			<a href=". $_SERVER['PHP_SELF'] ."?database=setup>Click Here to Try Again.</a>";
	}
	else{
		@unlink('test.php');
?>
		 <p><b>Permissions seem to be set correctly.  Now we need to connect to your database.</b></p>
		 <table border=0 cellpadding=3 cellspacing=0>
		 <form name=create_table action=<?php echo $_SERVER['PHP_SELF']; ?> method=post>
		 <tr><td><b>MySql Host Server:</b> </td><td><input type=text name=host value="localhost" onFocus="this.value=''"></td></tr>
		 <tr><td><b>MySql User Name:</b> </td><td><input type=text name=sqlname></td></tr>
		 <tr><td><b>MySql Password:</b> </td><td><input type=password name=sqlpassword></td></tr>
		 <tr><td><b>MySql Database Name:</b> </td><td><input type=text name=sqldb></td></tr>
		 <tr><td colspan=2><center><input type=submit name=dbsubmit value="Click Here When Finished"></center></td></tr>
		 </form>
		 </table>
<?php
	}
}

// Create database connection page
if($_POST['dbsubmit']){
		$host = $_POST['host'];
		$sqlname = $_POST['sqlname'];
		$sqlpassword = $_POST['sqlpassword'];
		$sqldb = $_POST['sqldb'];

	if(@touch('includes/connect.php')){		 
$content = '<?php
$db_server ="'. $host .'";
$db_name = "'. $sqldb .'";
$sqlusername = "'. $sqlname .'";
$sqlpassword = "'. $sqlpassword .'";        

$dbh = @mysql_connect($db_server,$sqlusername,$sqlpassword) or die 
("I could not connect to the database server.");

$db = @mysql_select_db($db_name) or die 
("I connected to the database server just fine, but I could not find the database.");
?>';
		
		write_file("includes/connect.php","$content\n");
		?>
		<br /><b>The Database connection is set.</b>  If you need to modify the database values in the future, just run this installation wizard again.  You could always try to edit /includes/config.php, but you may not have server permissions to do so since the server daemon has control over it.<br /><br />
		Please proceed to step 2 in the menu above.<br /><br />
<?php
	}
	else{
		echo "Permissions have not been properly set on the includes directory.";
	}
}

// ------------------- Start KASL WebHost Configuration

if($_GET['cmd'] == 'config'){
?>
	<h2>Configuration:</h2>
	<p>Now we will configure the website variables to work with your server.  If any variables are correct, you may leave them as they are.</p>
	
	<form action=<?php echo $_SERVER['PHP_SELF']; ?> method=post>
	<table border=0 cellpadding=5 cellspacing=0>
	<tr><td colspan=2><hr align="left" color="#4F4F4F" noshade></td></tr>
	<tr><td width=300><b>Allow File Uploads?</b><br /><br />
	<font size=1>Allowing File Uploads is dangerous and it could potentially allow someone to compromise the security of your server.  It is recommended that you say no.</font></td>
	<td><select name="file_uploads"><option value="no" selected>No</option><option value="yes">Yes</option></select></td></tr>
	
	<tr><td colspan=2><hr align="left" color="#4F4F4F" noshade></td></tr>
	
	<tr><td><b>Website URL:</b><br />
	<font size=1 color=red>Replace <b>DIRECTORY</b> with the name of the subdirectory your installation is in (only if this is going into a subdirectory) - else remove <b>DIRECTORY</b> and just leave the base url.</font></td><td><input type="text" name="url" value="http://<?php echo $url_path; ?>/DIRECTORY" size="40"></td></tr>
	<tr><td bgcolor="#E8E8E8"><b>Website Path:</b><br /><font size=1 color=red><b>Windows Server Users:</b>  If your path is something like C:\inetpub\webs\whatever, please change the backslashes (\) to forward slashes (/).  Thus your path will look like C:/inetpub/webs/whatever.  If you don't do this, KASL WebHost will not work!'</font></td><td bgcolor="#E8E8E8"><input type="text" name="webpath" value="<?php echo getcwd() ?>" size="40"></td></tr>
	
	<tr><td colspan=2><hr align="left" color="#4F4F4F" noshade></td></tr>
	
	<tr><td><b>Admin Name:</b><br />
	<font size=1 color=red><b>NOTE</b> - This is not the actual admin user for the program.  It is only your contact name for users.  The actual admin name will be revealed in the long paragraph at the last step.</font><font size=1> <b>For this step</b>, you can use your first name or nick name.</td><td><input type=text name=admin size="40"></td></tr>
	<tr><td bgcolor="#E8E8E8"><b>Admin Email Adress:</b></td><td bgcolor="#E8E8E8"><input type="text" name="admin_email" size="40"></td></tr>
	<tr><td><b>Site Name:</b></td><td><input type="text" name="site_name" value="KASL WebHost" OnClick="value=''" size="40"></td></tr>
	<tr><td bgcolor="#E8E8E8"><b>Logo URL:</b></td><td bgcolor="#E8E8E8"><input type="text" name="logo" value="images/kasl_webhost.png" size="40"></td></tr>
	
	<tr><td colspan=2><hr align="left" color="#4F4F4F" noshade></td></tr>
	
	<tr><td bgcolor="#E8E8E8"><b>Email Verification?</b><br /><font size=1>If you want to verify email addresses of users before they can use KASL WebHost, put yes.  By putting yes, users will not enter their password, but will have one randomly created for them and will be sent to them by email.  If you put no, they will create their own password and their email address will not be verified.</font></td><td bgcolor="#E8E8E8"><select name="email_verify"><option value="no" selected>No</option><option value="yes">Yes</option></select></td></tr>
	<tr><td><b>User accounts start unlocked?</b><br /><font size=1>If you would like users to sign up and for their sites to be automatically unlocked, put yes.  Else, put No - which keeps them from managing websites.  You may toggle this on or off in the <a href=http://www.kasl.info/products.php?product_id=12 target=blank><font size=1><b>KASL WebHost Admin Panel</b></font></a>.  <font color=red>If you have not purchased (or do not plan to purchase) KASL WebHost Admin Panel, keep it as yes as it will be useless to you since you will not be able to approve users.</font></font></td><td><select name="approved"><option value="yes" selected>Yes</option><option value="no">No</option></select></td></tr>
	<tr><td bgcolor="#E8E8E8"><b>Member List Open to Public?</b><br /><font size=1>If you would like everybody to be able to see a list of members and view their websites, leave it at the default yes.  If you don't want this, put no</font></td><td bgcolor="#E8E8E8"><select name="show_members"><option value="yes" selected>Yes</option><option value="no">No</option></select></td></tr>
	
	<tr><td colspan=2><hr align="left" color="#4F4F4F" noshade></td></tr>
	
	<tr><td><b>Table BG Color:</b></td><td><input type="text" name="table_bg_color" value="#CCCCCC" size="40"></td></tr>
	<tr><td bgcolor="#E8E8E8"><b>1st Cell BG Color:</b></td><td bgcolor="#E8E8E8"><input type="text" name="td_bg_color" value="#EFEFEF" size="40"></td></tr>
	<tr><td><b>2nd Cell BG Color:</b></td><td><input type="text" name="td_bg_color2" value="#FEFEFE" size="40"></td></tr>
	
	<tr><td colspan=2><hr align="left" color="#4F4F4F" noshade></td></tr>
	
	<tr><td bgcolor="#E8E8E8"><b>Space Allowed for users:</b><br /><font size=1>Default is 205Kb (5 MB).  <b>NOTE</b> - Users may go over or under as this is only a guide for the program to know if it should allow the user to create a new file or not.  If they are over their limit, the program will not let them create a new file, but this will not stop them from adding to a file and making it larger in size.</font></b></td><td bgcolor="#E8E8E8"><input type="text" name="space_allocated" value="205"></td></tr>
	<tr><td colspan=2><center><input type="submit" name="config_submit" value="Click Here When Finished"></center></td></tr>
	</table>
	</form>
<?php
}
if($_POST['config_submit']){
	$admin = $_POST['admin'];
	$site_name = $_POST['site_name'];
	$logo = $_POST['logo'];
	$email_verify = $_POST['email_verify'];
	$approved = $_POST['approved'];
	$show_members = $_POST['show_members'];
	$space_allocated = $_POST['space_allocated'];
	$table_bg_color = $_POST['table_bg_color'];
	$td_bg_color = $_POST['td_bg_color'];
	$td_bg_color2 = $_POST['td_bg_color2'];
	$webpath = $_POST['webpath'];
	$url = $_POST['url'];
	$file_uploads = $_POST['file_uploads'];
	$webpath = str_replace('\\','/',$webpath);
	
	$content2 = '<?php
$webpath = "'. stripslashes($webpath) .'";
$url = "'. stripslashes($url) .'";

$admin = "'. stripslashes($admin) .'";
$admin_email = "'. stripslashes($admin_email) .'";
$site_name = "'. stripslashes($site_name) .'";
$logo = "'. stripslashes($logo) .'";

$email_verify = "'. stripslashes($email_verify) .'";
$approved = "'. strtolower(stripslashes($approved)) .'";
$show_members = "'. stripslashes($show_members) .'";
$space_allocated = "'. stripslashes($space_allocated) .'";

$table_bg_color = "'. stripslashes($table_bg_color) .'";
$td_bg_color = "'. stripslashes($td_bg_color) .'";
$td_bg_color2 = "'. stripslashes($td_bg_color2) .'";
$file_uploads = "'. strtolower(stripslashes($file_uploads)) .'";

$kwh_version = "'. stripslashes($kwh_version) .'";
?>';
@touch(includes/config.php);
write_file("includes/config.php","$content2\n");
	echo "<br /><br /><b>Config file created successfully.</b><br /><br />
	If you need to modify this file, just run this installation wizard again.  Or else, if you can manage to get proper permissions, it is at /includes/config.php.<br /><br />
	Please contine to step 3 in the menu above.";
}

// ------------------- parse information to create database table
if($_GET['cmd'] == 'create_table'){
	echo "
	<br /><br />
	<form action={$_SERVER['PHP_SELF']} method=post>
	<input type=hidden value=$table_data>
	<input type=submit name=create_table value=\"Click here to set up the database table\">
	</form>
	";
}

// ------------------- Create table using the information from above
if($_POST['create_table']){
	if(file_exists('includes/config.php')){
		include("includes/config.php");
	}	
	echo '<br /><br />';
	include("includes/connect.php");
	
	$result = mysql_query("
		CREATE TABLE users(
		  user_id int(15) NOT NULL auto_increment,
		  username varchar(32) NOT NULL default '',
		  password varchar(32) NOT NULL default '',
		  email varchar(55) NOT NULL default '',
		  wysiwyg varchar(3) NOT NULL default 'on',
		  approved varchar(3) NOT NULL default '". $approved ."',
		  ip varchar(40) NOT NULL default '',
		  PRIMARY KEY (user_id))");
	$result2 = mysql_query("insert into users (username, password, email) values ('root', '5f4dcc3b5aa765d61d8327deb882cf99', '$admin_email')");
	if($result && $result2){
		echo "<br /><b>The 'users' table has been created. Please proceed to step 4</b>.";
	}
	else{
		echo "<br /><span class=warning><b>There was a problem creating your table.</b></span><br /><br />
		This is most likely caused by one of the following reasons:<br /><br />
		- Your MySql user not having the permissions required to perform this action.<br />
		- A table with the name 'users' already exists in this database.<br /><br />
		<b>Setup cannot continue effectively until this is fixed.</b>";
	}
}
 
// ----------- Finishing Up

if($_GET['cmd'] == 'finish'){
?>
<script>
	alert('Now that installation has finished, you may rename first_run.php to first_run.php.bak and then login with the following information:\n\nUsername: root\nPassword: password\n\nThere are a couple of things that you can check on while logged in as root.');
</script>
<?php	
echo "<h2>Congratulations!</h2>
		<b>Installation of KASL WebHost is now complete!</b><br /><br />
		<b><font color=red>IMPORTANT:</font></b> Please immediately create a backup copy of this page (first_run.php) and put it in a safe place away from the server.  If you ever need to change the configuration or database connection, recreate the database tables, or change the configuration file, put this page back and run the parts you need again (Keep in mind that the config.php and connect.php files will be owned by apache or IIS (depending on which server this runs on) and your username will not have permissions - but this file takes care of that problem).<br /><br />
		After that, open up your web browser and go to <a href=http://". $url_path ."/index.php>". $url_path ."/index.php</a> (remember, you must rename this file (first_run.php to first_run.php.bak or something similar) or this page will always come up).<br /><br />
		Don't forget to purchase KASL WebHost Admin Panel at <a href=http://www.kasl.info/products.php?product_id=12 target=blank>www.kasl.info/products.php?product_id=12</a>.<br /><br />
		<noscript>Now that installation has finished, you may rename first_run.php to first_run.php.bak and then login with the following information:<br /><br /><b>Username:</b> root<br /><b>Password:</b> password<br /><br />There are a couple of things that you can check on while logged in as root.</noscript>";
}
?>
</td></tr></table>
<br /><br />
<center>&copy; 2005 - <a href=http://www.kasl.info/>KASL</a></center>
</body>
</html>
