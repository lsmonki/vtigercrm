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

$sql2 = "select * from def_org_share";
$result2 = $adb->query($sql2);
$num_rows = $adb->num_rows($result2);

for($i=0; $i<$num_rows; $i++)
{
	$ruleid=$adb->query_result($result2,$i,'ruleid');
	$tabid=$adb->query_result($result2,$i,'tabid');
	if($tabid != 8 && $tab_id != 14 && $tab_id != 15 && $tab_id != 18 && $tab_id != 19 && $tab_id != 16 & $tab_id != 22)
	{
		$reqval = $tabid.'_per';	
		$permission=$_REQUEST[$reqval];
		$sql7="update def_org_share set permission=".$permission." where tabid=".$tabid." and ruleid=".$ruleid;
		//echo $sql7;
		//echo '<BR>';
		$adb->query($sql7);
		if($tabid == 9)
		{
			$sql8="update def_org_share set permission=".$permission." where tabid=16";
			$adb->query($sql8);
			
		}
		if($tabid == 21)
		{
			
			$sql8="update def_org_share set permission=".$permission." where tabid=22";
			$adb->query($sql8);
			
		}		
	}
}
$loc = "Location: index.php?action=OrgSharingDetailView&module=Users";
header($loc);
?>
