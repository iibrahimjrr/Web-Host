<?php

echo "<div align=left>";
echo "<table bgcolor=#CCCCCC cellspacing=1 cellpadding=5 border=0><tr>";

echo "<td bgcolor=#EDEDED align=center><a href=$url/index.php><b>Home</b></a></td>";

if(isset($_COOKIE[md5('whname')])){
	echo "<td bgcolor=#EDEDED align=center><a href=$url/profile_edit.php><b>Edit Your Profile</b></a></td>";
	echo "<td bgcolor=#EDEDED align=center><a href=$url/logout.php><b>Logout</b></a></td>";
}
else{
	echo "<td bgcolor=#EDEDED align=center><a href=$url/login.php><b>Login</b></a></td><td bgcolor=#EDEDED align=center><a href=$url/register.php><b>Register</b></a></td>";
}

if($show_members = 'yes'){
	echo "<td bgcolor=#EDEDED align=center><a href=$url/members_list.php><b>Members List</b></a></td>";
}

if($_COOKIE[md5('whname')] == 'root'){
	echo "<td bgcolor=#EDEDED align=center><a href=http://www.kasl.info/update.php?product=KASL_WebHost&version=$kwh_version target=blank><b>Check for Updates</b></a></td>";
	if(is_dir("admin")){
		echo "<td bgcolor=#EDEDED align=center><a href=$url/admin><b>Admin Panel</b></a></td>";
	}
}

echo "</tr></table>";

echo "</div>";
echo "<br /><br />";
?>