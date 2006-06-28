<?
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*
********************************************************************************/


function SavePortal($portalname,$portalurl)
{
	global $adb;
	$adb->println("just entered the SavePortal method");
	$portalid=$adb->getUniqueID('vtiger_portal');
	$query="insert into vtiger_portal values(".$portalid.",'".$portalname."','".$portalurl."',0)";
	$adb->println($query);
	$result=$adb->query($query);
	return $portalid;
}

function UpdatePortal($portalname,$portalurl,$portalid)
{
	global $adb;
	$adb->println("just entered the SavePortal method");
	$query="update vtiger_portal set portalname='$portalname' ,portalurl='$portalurl' where portalid=$portalid";
	$adb->println($query);
	$result=$adb->query($query);
	return $portalid;
}
?>
