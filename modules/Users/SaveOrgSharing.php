<?php
require_once('include/database/PearDatabase.php');
global $adb;

$sql2 = "select * from default_org_sharingrule";
$result2 = $adb->query($sql2);
$num_rows = $adb->num_rows($result2);

for($i=0; $i<$num_rows; $i++)
{
	$ruleid=$adb->query_result($result2,$i,'ruleid');
	$tabid=$adb->query_result($result2,$i,'tabid');
	$reqval = $tabid.'_per';	
	$permission=$_REQUEST[$reqval];
	$sql7="update default_org_sharingrule set permission=".$permission." where tabid=".$tabid." and ruleid=".$ruleid;
	//echo $sql7;
	//echo '<BR>';
	$adb->query($sql7);	
}
$loc = "Location: index.php?action=OrgSharingDetailView&module=Users";
header($loc);
?>
