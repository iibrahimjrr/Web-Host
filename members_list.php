<?php
include("includes/header.php");
include("includes/config.php");
include("includes/connect.php");
?>
<center><h2><?php echo $site_name; ?> Member List</h2></center>
<table bgcolor=#EDEDED align=center border=0 cellpadding=5 cellspacing=1>
<tr><td bgcolor=#DDDDDD><b>Username</b></td><td bgcolor=#DDDDDD><b>Website</b></td></tr>

<?php

$result = mysql_query("select * from users");
$row = mysql_fetch_array($result);

$row_count = 0;

do{
	$dyn_tr_bg_color = ($row_count % 2) ? $td_bg_color : $td_bg_color2;
	echo "<tr><td bgcolor=$dyn_tr_bg_color><b>{$row['username']}</b></td><td bgcolor=$dyn_tr_bg_color><a href=\"$url/{$row['username']}\" target=blank>$url/{$row['username']}</a></td></tr>";
	$row_count++;
}while($row = mysql_fetch_array($result));

?>

</table>

<?php
include("includes/footer.php");
?>