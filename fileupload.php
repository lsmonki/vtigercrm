<?php

mkdir("./cache/mails", 0700);
$uploadDir = './cache/mails/';
$uploadFile = $uploadDir . $_FILES['userfile']['name'];

print $uploadFile;
print $_FILES['userfile']['tmp_name'];
print '<br>----------------------------------------';

print '<br>userfile,name array : ' ;
print $_FILES['userfile']['name'];
print '<br>userfile array : ' ;
print $_FILES['userfile'];
print '<br>name array : ' ;
print $_FILES['name'];

print $_FILES['userfile1']['name'];

print $_REQUEST['userfile1'];
//print $_REQUEST['userfile'];



print "<pre>";
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile))

{
    print "File is valid, and was successfully uploaded. ";
    print "Here's some more debugging info:\n";
    print_r($_FILES);
}
else
{
    print "Possible file upload attack!  Here's some debugging info:\n";
    print_r($_FILES);
}
print "</pre>";
?>

