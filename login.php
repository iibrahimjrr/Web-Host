<?php

if($_POST['login']){

$username = strtolower($_POST['username']);
$password = $_POST['password'];

  include("includes/connect.php");
  $query = "select * from users where username='". mysql_real_escape_string($username) ."' and password='". mysql_real_escape_string(md5($password)) ."'";
  $result = mysql_query($query);
  $num = mysql_num_rows($result);

     if($num > 0){
     setcookie(md5("whname"), $username, time()+36000);
     }
     else{
     header("Location: invalid_login.php");
     }
     
     if($num > 0){
     header("Location: index.php");
     }
}

// Login Form
else{
	
include("includes/header.php");
	
	if(isset($_COOKIE[md5("whname")])){
    $username = $_COOKIE[md5("whname")];
    echo "<center>You are already logged in as $username.</center>";
    }
    
    else{
	echo "
    <center><h2>Login</h2></center>
    <table border=0 align=center cellpadding=5 cellspacing=1 bgcolor=$table_bg_color>
     <form action=". $_SERVER['PHP_SELF'] ." method=post>
      <tr><td bgcolor=$td_bg_color><b>Username:</b> </td><td bgcolor=$td_bg_color2><input type=text name=username></td></tr>
      <tr><td bgcolor=$td_bg_color><b>Password:</b> </td><td bgcolor=$td_bg_color2><input type=password name=password></td></tr>
      <tr><td bgcolor=$td_bg_color><input type=submit value=Go! name=login></td><td bgcolor=$td_bg_color><a href=register.php>Not Registered?<br />Click Here</a></td></tr>
     </form>
    </table>
     ";
}
}

include("includes/footer.php");

?>
