<?php
error_reporting(E_ALL ^ E_NOTICE);
include("includes/config.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo $site_name; ?></title>
<link href="includes/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
echo "<table width=100%><tr valign=top><td width=50% valign=left><img src=$logo></td><td align=right>IP Address {$_SERVER['REMOTE_ADDR']} [logged] &nbsp; </td></tr></table>";
include("includes/links.php");
?>
