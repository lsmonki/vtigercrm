<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

require_once('include/database/PearDatabase.php');
global $adb;

$profilename = $_REQUEST['profileName'];
$parentProfileId= $_REQUEST['parentProfileId'];
//Inserting values into Profile Table
$sql1 = "insert into profile values('','".$profilename."')";
$adb->query($sql1);

//Retreiving the profileid
$sql2 = "select max(profileid) as current_id from profile";
$result2 = $adb->query($sql2);
$current_profile_id = $adb->query_result($result2,0,'current_id');

//Inserting values into Profile2tab table
$sql3 = "select * from profile2tab where profileid=".$parentProfileId;
$result3= $adb->query($sql3);
$p2tab_rows = $adb->num_rows($result3);
for($i=0; $i<$p2tab_rows; $i++)
{
	$tab_id=$adb->query_result($result3,$i,'tabid');
	$permissions=$adb->query_result($result3,$i,'permissions');
	$sql4="insert into profile2tab values(".$current_profile_id.", ".$tab_id.", ".$permissions.")";
	$adb->query($sql4);	
}

//Inserting values into Profile2standard table
$sql6 = "select * from profile2standardpermissions where profileid=".$parentProfileId;
$result6= $adb->query($sql6);
$p2per_rows = $adb->num_rows($result6);
for($i=0; $i<$p2per_rows; $i++)
{
	$tab_id=$adb->query_result($result6,$i,'tabid');
	$action_id=$adb->query_result($result6,$i,'operation');	
	$permissions=$adb->query_result($result6,$i,'permissions');
	$sql7="insert into profile2standardpermissions values(".$current_profile_id.", ".$tab_id.", ".$action_id.", ".$permissions.")";
	$adb->query($sql7);	
}

//Inserting values into Profile2Utility table
$sql8 = "select * from profile2utility where profileid=".$parentProfileId;
$result8= $adb->query($sql8);
$p2util_rows = $adb->num_rows($result8);
for($i=0; $i<$p2util_rows; $i++)
{
	$tab_id=$adb->query_result($result8,$i,'tabid');
	$action_id=$adb->query_result($result8,$i,'activityid');	
	$permissions=$adb->query_result($result8,$i,'permission');
	$sql9="insert into profile2utility values(".$current_profile_id.", ".$tab_id.", ".$action_id.", ".$permissions.")";
	$adb->query($sql9);	
}

//Inserting values into Profile2field table
$sql10 = "select * from profile2field where profileid=".$parentProfileId;
$result10= $adb->query($sql10);
$p2field_rows = $adb->num_rows($result10);
for($i=0; $i<$p2field_rows; $i++)
{
	$tab_id=$adb->query_result($result10,$i,'tabid');
	$fieldid=$adb->query_result($result10,$i,'fieldid');	
	$permissions=$adb->query_result($result10,$i,'visible');
	$readonly=$adb->query_result($result10,$i,'readonly');
	$sql11="insert into profile2field values(".$current_profile_id.", ".$tab_id.", ".$fieldid.", ".$permissions." ,".$readonly.")";
	$adb->query($sql11);	
}
$loc = "Location: index.php?action=ListProfiles&module=Users";
header($loc);
?>
