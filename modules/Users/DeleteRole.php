<?php
//File to Delete the Profile
global $adb;
$roleid= $_REQUEST['roleid'];
//Delete entries from role2profile table
$sql1 = "delete from role2profile where roleid=".$roleid;
$adb->query($sql1);
//Delete from profiletable
$sql5 = "delete from role where roleid=".$roleid;
$adb->query($sql5);

$loc = "Location: index.php?action=listroles&module=Users";
header($loc);
?>
