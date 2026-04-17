<?
   //File will be rewritten if already exists
   function write_file($filename,$newdata) {
        if(!$f=@fopen($filename,"w")){
          echo "<span class=warning>I couldn't open $filename.  Does it even exist?</span><br /><br />";
        }
        if(!@fwrite($f,$newdata)){
          echo "<span class=warning>I couldn't write to $filename.  Are the permissions set correctly?</span><br /><br />";
        }
        if(!@fclose($f)){
          echo "<span class=warning>I couldn't close $filename - I probably couldn't open it either... ?</span><br /><br />";
        }
   }

   function append_file($filename,$newdata) {
        if(!$f=@fopen($filename,"a")){
            echo "<span class=warning>I couldn't open $filename.  Does it even exist?</span><br /><br />";
        }
        if(!@fwrite($f,$newdata)){
            echo "<span class=warning>I couldn't write to $filename.  Are the permissions set correctly?</span><br /><br />";
        }
        if(!@fclose($f)){
            echo "<span class=warning>I couldn't close $filename - I probably couldn't open it either... ?</span><br /><br />";
        }
   }

   function read_file($filename) {
        if(!$f=@fopen($filename,"r")){
            echo "<span class=warning>I couldn't open $filename.  Does it even exist?</span><br /><br />";
        }
        if(!$data=@fread($f,filesize($filename))){
            echo "<span class=warning>I couldn't write to $filename.  Are the permissions set correctly?</span><br /><br />";
        }
        if(!@fclose($f)){
            echo "<span class=warning>I couldn't close $filename - I probably couldn't open it either... ?</span><br /><br />";
        }
          return $data;
   }
?>
