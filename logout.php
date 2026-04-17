<?php

setcookie(md5("whname"), $username, time()-36000);
header("Location: loggedout.php");

?>
