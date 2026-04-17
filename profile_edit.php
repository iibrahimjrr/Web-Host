<?php
include("includes/header.php");
include("includes/connect.php");

if(!isset($_COOKIE[md5('whname')])){
  echo "<center><br /><br /><span class=warning>You must be logged in to view this page!</span><br /><br />
            <a href=$url/login.php>Click Here to Login</a></center><br /><br /><br />";
}
else{

echo "<center><h2>Profile Manager</h2></center>";

if(!$_POST['update']){

$username = $_COOKIE[md5('whname')];
$query = "select * from users where username='$username'";
$result = mysql_query($query);
$list = mysql_fetch_array($result);
echo mysql_error();

$username = $list['username'];
$password = $list['password'];
$email = $list['email'];

?>

        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <table align=center border="0" cellpadding="3" cellspacing="1" bgcolor="<?php echo $table_bg_color; ?>">
        <tr><td bgcolor="<?php echo $td_bg_color; ?>"><b>Username:</b></td>           <td bgcolor="<?php echo $td_bg_color2; ?>"><?php echo "$username"; ?></td></tr>
        <tr><td bgcolor="<?php echo $td_bg_color; ?>"><b>Password:</b></td>           <td bgcolor="<?php echo $td_bg_color2; ?>"><input type="password" name="upassword"></td></tr>
        <tr><td bgcolor="<?php echo $td_bg_color; ?>"><b>Confirm Password:</b></td>   <td bgcolor="<?php echo $td_bg_color2; ?>"><input type="password" name="upassword2"></td></tr>
        <tr><td bgcolor="<?php echo $td_bg_color; ?>"><b>Email:</b></td>              <td bgcolor="<?php echo $td_bg_color2; ?>"><input type="text" name="uemail" value="<?php echo "$email"; ?>"></td></tr>
        <tr><td bgcolor="<?php echo $td_bg_color; ?>" colspan="2" align="right"><input type="submit" name="update" value="Update"></td></tr>
        </table>
        </form>
        
<?php
}
else{
$username = $_COOKIE[md5('whname')];
$upassword = $_POST['upassword'];
$upassword2 = $_POST['upassword2'];
$uemail = $_POST['uemail'];
include("includes/connect.php");

    if($upassword != $upassword2){
      echo "<center>
            Your Passwords Do Not Match!<br /><br />
            <a href=javascript:history.go(-1)>Click Here to Try Again</a>.
            </center>";
    }
    else{
    $newpassword = md5($upassword);
    
        if($_POST['upassword']){

            $query2 = "update users set password='$newpassword', email='$uemail' where username='$username'";
            $result2 = mysql_query($query2);
			echo mysql_error();
        }
        else{
            $query2 = "update users set email='$uemail' where username='$username'";
            $result2 = mysql_query($query2);
			echo mysql_error();
        }

            if($result2){
				$username = $_COOKIE[md5('whname')];
				$q = "select * from users where username='$username'";
				$r = mysql_query($q);
				$l = mysql_fetch_array($r);
				$dbpasswd = $l['password'];
				
              echo "<center>Your Profile Has Been Updated!";
			  echo "<br /><br /><a href=$url>Click Here to Continue</a></center>";
            }
            else{
              echo "Your profile could not be updated due to a system error.  Please contact the administrator at $admin_email.";
            }
            }

}
}

include("includes/footer.php");
?>
