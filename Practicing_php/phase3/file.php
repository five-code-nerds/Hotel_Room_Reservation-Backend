
<?php

$newfile = fopen("sample.txt","r") or die("unable to read");
$txt = "abebe beso bela";
// fwrite($newfile , $txt);
echo fread($newfile,filesize("sample.txt"));
?>