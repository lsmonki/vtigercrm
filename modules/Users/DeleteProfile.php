<?php
//File to Delete the Profile
global $adb;
$profileid= $_REQUEST['profileid'];
//Delete entries from profile2tab table
$sql1 = "delete from profile2tab where profileid=".$profileid;
$adb->query($sql1);
//Delete entries from profile2standard table
$sql2 = "delete from profile2standardpermissions where profileid=".$profileid;
$adb->query($sql2);
//Delete from profile2utilitytable
$sql3 = "delete from profile2utility where profileid=".$profileid;
$adb->query($sql3);
//Delete from profile2field
$sql4 = "delete from profile2field where profileid=".$profileid;
$adb->query($sql4);
//Delete from profiletable
$sql5 = "delete from profile where profileid=".$profileid;
$adb->query($sql5);

$loc = "Location: index.php?action=ListProfiles&module=Users";
header($loc);
?>
