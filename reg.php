<?php
include("includes/header.php");

if(!$_POST['register']){
	echo "
	<form action={$_SERVER['PHP_SELF']} method=post>
	<center><h2>Registration</h2></center>
	<table align=center border=0 bgcolor=$table_bg_color cellspacing=1 cellpadding=5>
	<tr><td bgcolor=$td_bg_color><b>Username:</b></td><td bgcolor=$td_bg_color2> <input type=text name=username></td></tr>";
	if($email_verify != 'yes'){
		echo "
		<tr><td bgcolor=$td_bg_color><b>Password:</b></td><td bgcolor=$td_bg_color2> <input type=password name=password></td></tr>
		<tr><td bgcolor=$td_bg_color><b>Confirm Password:</b></td><td bgcolor=$td_bg_color2> <input type=password name=password2></td></tr>";
	}
	echo "
	<tr><td bgcolor=$td_bg_color><b>Email Address:</b></td><td bgcolor=$td_bg_color2> <input type=text name=email></td></tr>
	<tr bgcolor=$td_bg_color><td><a href=login.php>Already Registered?<br />Click Here</a></td><td align=center><input type=submit name=register value=Done!></td></tr>
	</table>
	</form>
	";
}else{
		if(isset($_POST['password']) && isset($_POST['password2'])){
			$password = $_POST['password'];
			$password2 = $_POST['password2'];
		}else{
			$password = rand();
			$password2 = $password;
		}
		$username = strtolower($_POST['username']);
		$email = $_POST['email'];

		if (!$username || !$email || !$password){
			echo "<center><span class=warning>You have not entered all of the required information.</span><br /><br />
			Please go back and try again.<br /><br />
			<a href={$_SERVER['HTTP_REFERER']}>Go Back</a></center>";
			include("includes/footer.php");
			exit();
		}
		elseif (eregi("[[:space:]]", $username)) {
			echo "<center><span class=warning><i>$username</i> is an invalid user name - it must <u>not</u> contain spaces!</span><br /><br />
			Please go back and try again.<br /><br />
			<a href={$_SERVER['HTTP_REFERER']}>Go Back</a></center>";
			include("includes/footer.php");
			exit();
		}
		elseif ($username == 'admin' || $username == 'includes' || $username == 'root' || $userame == 'administrator') {
			echo "<center><span class=warning><i>$username</i> is an invalid user name!</span><br /><br />
			Please go back and try again.<br /><br />
			<a href={$_SERVER['HTTP_REFERER']}>Go Back</a></center>";
			include("includes/footer.php");
			exit();
		}
		elseif (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$", $email)) {
			echo "<center><span class=warning>$email is not a valid email address!</span><br /><br />
			Please go back and try again.<br /><br />
			<a href={$_SERVER['HTTP_REFERER']}>Go Back</a></center>";
			include("includes/footer.php");
			exit();
		}
		elseif ($password != $password2){
			echo "<center><span class=warning>Your passwords do not match.  Please go back and try again.</span></center>";
			include("includes/footer.php");
			exit();
		}
		else{
		include("includes/connect.php");
		
		$result1 = mysql_query("select * from users where username='$username'");
		$num1 = mysql_num_rows($result1);
		
		$result2 = mysql_query("select * from users where email='$email'");
		$num2 = mysql_num_rows($result2);
		
		if($num1 > 0){
			echo "<center><br /><br /><br />That username is already taken.  <a href={$_SERVER['HTTP_REFERER']}>Click Here to Try Again</a>.</center>";
			include("includes/footer.php");
			exit();
		}elseif($num2 > 0){
			echo "<center><br /><br /><br />A user has already registered with that email address.  <a href={$_SERVER['HTTP_REFERER']}>Click Here to Try Again</a>.</center>";
			include("includes/footer.php");
			exit();
		}else{
			$query = "insert into users (username, password, email) values ('". mysql_real_escape_string($username) ."', '". mysql_real_escape_string(md5($password)) ."', '". mysql_real_escape_string($email) ."')";
			$result = @mysql_query($query);
			echo mysql_error();
			if($result){
			
			if(!@mkdir("$username", 0777)){
                echo "There was a problem creating a directory for $username!<br /><br />";
				if(is_dir()){
					echo "The directory already existed.";
				}
            }else{
                include("includes/create_file.php");
            }
			
			// Email the User
			$from = "From: $admin_email";
			$subject = "$site_name registration";
			$body = "Thank you for registering with $site_name! Here is your login information:\n\nUsername: $username\nPassword: $password\n\nYou can login at $url/login.php.\n\nRegards,\n$admin\n$url";
            if(!@mail($email, $subject, $body, $from)){
              echo "Thank you $username, you have successfully registered.<br /><br />A confirmation email was supposed to be sent to you, but couldn't be done due to a system error.";
            }else{
			
			$new_member_message = "$admin - \n\nA new member by the name of $username has just registered on $site_name!  $username's email address is $email.\n\nRegards,\n$site_name mail daemon";
			
			// Comment the following line to stop receiving emails when members register.  A comment is two forward slashes like this: //
			@mail($admin_email, 'New Registrant', $new_member_message, 'From: '. $admin_email);
			echo "Thank you $username, you have successfully registered.<br /><br />A confirmation email with your password will be sent to $email within one hour.<br /><br /><span class=warning><b>NOTE:</b></span> Because the email is being sent by a server and not a person, it may be redirected to your SPAM or bulk mail folder.  Please be sure to check those folders if you do not get the email to your inbox.<br /><br /><center><a href=login.php>Click Here to Log In</a>.</center>";
	}
 }
        else{
            echo "There was a problem with your registration";
        }
		}
	}
}

include("includes/footer.php");
?> 
