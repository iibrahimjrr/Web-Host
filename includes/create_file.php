<?php
include("functions.php");
include("includes/config.php");

$content = '
<?php include("../includes/config.php"); ?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<link href="../includes/style.css" rel="stylesheet" type="text/css" />
<title>Website Creation Successful</title>
</head>

<body>
<center>
<script type="text/javascript"><!--
google_ad_client = "pub-3495458709249854";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as_rimg";
google_cpa_choice = "CAAQq8WdzgEaCCQIMpsWzihvKNvD93M";
//--></script>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</center>
<br /><br />
<table align=center border=0 width=85%><tr><td>
<h2>Website Creation Successful!</h2>
<big><b>If you are the registrar of this site:</b></big><br />You may now remove this page and put up your own by clicking on <a href="<?php echo $url; ?>/index.php">this link (the WebSite Manager)</a> link in the menu at the left side of the page at <?php echo $url; ?>.
<br /><br />
If you feel that this service is worth it and would like to tell your friends, please do!
<br /><br />
<big><b>If you are a member of the general public:</b></big>
<p>You are viewing a website that has been registered for free at <a href=<?php echo $url; ?>><?php echo $url; ?></a>.&nbsp;
If you are interested in building a free website of your own, register at <?php echo $url; ?>
today.&nbsp; <a href=<?php echo $url; ?>/register.php title=Register+Today!>Click
here to sign up now</a>!</p>
</td></tr></table>

<?php include("../ads.htm"); ?>

</body>

</html>
';

write_file("$webpath/$username/index.php","$content\n");
?>
