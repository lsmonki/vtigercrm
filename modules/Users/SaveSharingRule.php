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


$sharing_module=$_REQUEST['sharing_module'];
$tabid=getTabid($sharing_module);
$share_entity_type = $_REQUEST['memberType'];
$to_entity_type = $_REQUEST['share_memberType'];
$share_entity_id=$_REQUEST['availList'];
$to_entity_id=$_REQUEST['share_availList'];
$module_sharing_access=$_REQUEST[$sharing_module.'_access'];
$mode=$_REQUEST['mode'];
/*
echo '<BR>';
echo $share_entity_type;
echo '<BR>';
echo $to_entity_type;
*/

$relatedShareModuleArr=getRelatedSharingModules($tabid);
if($mode == 'create')
{
	$shareId=addSharingRule($tabid,$share_entity_type,$to_entity_type,$share_entity_id,$to_entity_id,$module_sharing_access);

	//Adding the Related ModulePermission Sharing
	foreach($relatedShareModuleArr as $reltabid=>$ds_rm_id)
	{
		$reltabname=getTabModuleName($reltabid);
		$relSharePermission=$_REQUEST[$reltabname.'_access'];	
		addRelatedModuleSharingPermission($shareId,$tabid,$reltabid,$relSharePermission);	
	}
	
}
elseif($mode == 'edit')
{
	$shareId=$_REQUEST['shareId'];
	updateSharingRule($shareId,$tabid,$share_entity_type,$to_entity_type,$share_entity_id,$to_entity_id,$module_sharing_access);
	//Adding the Related ModulePermission Sharing
	foreach($relatedShareModuleArr as $reltabid=>$ds_rm_id)
	{
		$reltabname=getTabModuleName($reltabid);
		$relSharePermission=$_REQUEST[$reltabname.'_access'];	
		updateRelatedModuleSharingPermission($shareId,$tabid,$reltabid,$relSharePermission);	
	}	
}



$loc = "Location: index.php?action=OrgSharingDetailView&module=Users";
header($loc);
?>
