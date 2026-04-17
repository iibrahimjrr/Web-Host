<?php
include("includes/connect.php");

if(!isset($_COOKIE[md5('whname')])){
	$msg = "<center>You must be logged in to view this page!<br /><br /><a href=$url/login.php>Click Here to Login</a>.</center>";
}else{
	$username = $_COOKIE[md5('whname')];
    $result = @mysql_query("select * from users where username='$username'");
    $row = @mysql_fetch_array($result);
    echo mysql_error();
    
        if($_GET['tinymce']){
            $status = $_GET['tinymce'];
            $editor = @mysql_query("update users set wysiwyg='$status' where username='$username'");
            header("Location: {$_SERVER['PHP_SELF']}?name=$username&filename={$_GET['filename']}");
        }
    
        if($row['wysiwyg'] == 'on'){
            include("tinymce.php");
		}

    include("includes/header.php");

	if($_GET['filename']){
		$filename = $_GET['filename'];
		$filename = str_replace('../','',$filename);
	
		if(!file_exists("$webpath/$username/$filename")){
			echo "<center><span class=warning>$filename does not exist!</span><br /><br />Please use your browser's back button to continue.</center>";
		}else{
			$filewrite = $_GET['filename'];
			$file = "$webpath/$username/$filename";
			$fp = @fopen($file,'r');
			$content = @fread($fp,filesize($file));
			$content = str_replace('<?php include("../ads.htm"); ?>','',$content);
			$content = str_replace('<!--#include virtual="../ads.htm"-->','',$content);
			?>
			<noscript><span class=warning><center>In order to use the WYSIWYG editor,<br />you must first have JavaScript enabled in your browser.</span></center><br /><br /></noscript>
			<big><big><b>Web Page Editor - <?php echo "$filename"; ?></b></big></big>
			<?php
                if($row['wysiwyg'] == 'on'){
                  echo "<a href={$_SERVER['PHP_SELF']}?tinymce=off&name=$username&filename=$filename title=\"Turn WYSIWYG Editor Off\"><img src=images/wysiwyg_off.gif border=1 hspace=1 width=20 height=20 align=top></a>";
                }else{
                  echo "<a href={$_SERVER['PHP_SELF']}?tinymce=on&name=$username&filename=$filename title=\"Turn WYSIWYG Editor On\"><img src=images/wysiwyg_on.gif border=1 hspace=1 width=20 height=20 align=top></a>";
                }
            ?>
			<br /><br /><a href=index.php><b>Return to site manager</b></a>
			<br /><br />
			<center>
			<form action="<?php echo $_SERVER['PHP_SELF'] ; ?>" method="post">
			<textarea name="data" cols="75" rows="25"><? echo "$content"; ?></textarea><br>
			<br />
			<input type="hidden" name="filename" value="<?php echo "$file"; ?>">
			<input type="hidden" name="filewrite" value="<?php echo $filewrite; ?>">
			<input type="submit" value="Update"><input type="reset" value="Reset">
			</form>
			</center>
		
			<?php
		}
	}else{
		$filewrite = $_POST['filewrite'];
		$filename = $_POST['filename'];
		$ext = explode('.', $filewrite);
		$n = count($ext);
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
		
		$data = $_POST['data'];
		$data = "$data\r\n$footer\r\n";
		$newdata = $_POST["data"];
		
		$values = "$data\r\n";
		$fp = fopen("$filename", "w") or die("Couldn't open $filename!  Check your permissions");
		$numBytes = fwrite($fp, stripslashes("$values")) or die("Couldn't write to file!");
		fclose($fp);
		$filename = str_replace($webpath,'',$filename);
		$total_size = round($numBytes / 1024, 2);
		echo "
		<h3>$filewrite</h3>
		<a href=index.php><b>Return to site manager</b></a><br /><br />
		<center>Successfully wrote $total_size Kb to $filewrite!<br /><br /><a href=index.php>Click Here to Continue</a></center>";
	}
}

echo $msg;
include("includes/footer.php");

?>
