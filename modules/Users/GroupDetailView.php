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
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/utils/UserInfoUtil.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;


$groupId=$_REQUEST['groupId'];
$groupInfoArr=getGroupInfo($groupId);


$smarty = new vtigerCRM_Smarty;

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty->assign("GROUPINFO", getStdOutput($groupInfoArr,$groupId, $mod_strings));
$smarty->assign("GROUPID",$groupId);
$smarty->assign("GROUP_NAME",$groupInfoArr[0]);

function getStdOutput($groupInfoArr,$groupId, $mod_strings)
{
	global $adb;
    $groupfields['groupname'] = $groupInfoArr[0];    
    $groupfields['description'] = $groupInfoArr[1];

	$row=1;
	$groupMember = $groupInfoArr[2];
	$information = array();
	foreach($groupMember as $memberType=>$memberValue)
	{
		$memberinfo = array();
		foreach($memberValue as $memberId)
		{
			$groupmembers = array();
			if($memberType == 'roles')
			{
				$memberName=getRoleName($memberId);
				$memberAction="RoleDetailView";
				$memberActionParameter="roleid";
				$memberDisplayType="Role";
			}
			elseif($memberType == 'rs')
			{
				$memberName=getRoleName($memberId);
				$memberAction="RoleDetailView";
				$memberActionParameter="roleid";
				$memberDisplayType="Role and Subordinates";
			}
			elseif($memberType == 'groups')
			{
				$memberName=fetchGroupName($memberId);
				$memberAction="GroupDetailView";
				$memberActionParameter="groupId";
				$memberDisplayType="Group";
			}
			elseif($memberType == 'users')
			{
				$memberName=getUserName($memberId);
				$memberAction="DetailView";
				$memberActionParameter="record";
				$memberDisplayType="User";
			}
			$groupmembers ['membername'] = $memberName;
			$groupmembers ['memberid'] = $memberId;
			$groupmembers ['membertype'] = $memberDisplayType;
			$groupmembers ['memberaction'] = $memberAction;
			$groupmembers ['actionparameter'] = $memberActionParameter;
			$row++;
			$memberinfo [] = $groupmembers;
		}
		if(sizeof($memberinfo) >0)
			$information[$memberDisplayType] = $memberinfo;
	}
	$returndata=array($groupfields,$information);
	return $returndata;
}

$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);
$smarty->display("GroupDetailview.tpl");

?>
