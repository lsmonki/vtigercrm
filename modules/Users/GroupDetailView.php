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
require_once('XTemplate/xtpl.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/utils/UserInfoUtil.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;


$groupId=$_REQUEST['groupId'];
$groupInfoArr=getGroupInfo($groupId);


echo '<form action="index.php" method="post" name="new" id="form">';
echo get_module_title("Users",' Group: '.$groupInfoArr[0], true);

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Users/GroupDetailView.html');

$standCustFld = getStdOutput($groupInfoArr,$groupId, $mod_strings);

//Standard PickList Fields
function getStdOutput($groupInfoArr,$groupId, $mod_strings)
{
	global $adb;
	//echo get_form_header("Profiles", "", false );
	$standCustFld= '';
	$standCustFld .= '<input type="hidden" name="module" value="Users">';
	$standCustFld .= '<input type="hidden" name="action" value="createnewgroup">';
	$standCustFld .= '<input type="hidden" name="groupId" value="'.$groupId.'">';
	$standCustFld .= '<input type="hidden" name="mode" value="edit">';
	$standCustFld .= '<br><input title="Edit" accessKey="E" class="button" type="submit" name="Edit" value="Edit">&nbsp;&nbsp;';
	$standCustFld .= '<input title="Delete" accessKey="D" class="button" type="submit" name="Delete" value="Delete" onclick="this.form.action.value=\'DeleteGroup\'">&nbsp;&nbsp;';
	$standCustFld .= '<input title="Back" accessKey="C" class="button" onclick="this.form.action.value=\'listgroups\';this.form.module.value=\'Users\'" type="submit" name="New" value="Back">';
	$standCustFld .= '<br><BR>';
	
	$standCustFld .= '<table border="0" cellpadding="5" cellspacing="1" width="50%" class="formOuterBorder">';
        $standCustFld .=  '<tr colspan="2">';
        $standCustFld .=   '<td class="formSecHeader" colspan="2">Group Information</td>';
        $standCustFld .=  '</tr>';
        $standCustFld .=  '<tr>';
        $standCustFld .=   '<td width="40%" nowrap class="dataLabel" height="21">Group Name: </td>';
        $standCustFld .=   '<td class="dataField">'.$groupInfoArr[0].'</td>';
        $standCustFld .=  '</tr>';
        $standCustFld .=  '<tr>';
        $standCustFld .=   '<td class="dataLabel" nowrap height="21">Description: </td>';
        $standCustFld .=   '<td class="dataField">'.$groupInfoArr[1].'</td>';
        $standCustFld .=  '</tr>';
        $standCustFld .='</table>';
        $standCustFld .='<BR><BR>';

 
	$standCustFld .= '<table border="0" cellpadding="5" cellspacing="1" class="FormBorder" width="40%">';
	$standCustFld .=  '<tr height=20>';
	$standCustFld .=   '<td class="ModuleListTitle" height="20" style="padding:0px 3px 0px 3px;"><div align="center"><b>Group Member Name</b></div></td>';
	$standCustFld .=   '<td class="ModuleListTitle" height="20" style="padding:0px 3px 0px 3px;"><b>Type</b></td>';
	$standCustFld .=  '</tr>';

	$row=1;
	$groupMember=$groupInfoArr[2];
	foreach($groupMember as $memberType=>$memberValue)
	{

		foreach($memberValue as $memberId)
		{

			if ($row%2==0)
			{
				$trowclass = 'evenListRow';
			}
			else
			{	
				$trowclass = 'oddListRow';
			}

			$standCustFld .= '<tr class="'.$trowclass.'">';
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

			$standCustFld .= '<td width="18%" height="21" style="padding:0px 3px 0px 3px;"><div align="center"><a href="index.php?module=Users&action='.$memberAction.'&'.$memberActionParameter.'='.$memberId.'">'.$memberName.'</a></div></td>';
			$standCustFld .= '<td wheight="21" style="padding:0px 3px 0px 3px;">'.$memberDisplayType.'</td></tr>';
			$row++;

		}
	}
	$standCustFld .='</table>';
	//echo $standCustFld;	
	return $standCustFld;
}
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("GROUPINFO", $standCustFld);


$xtpl->parse("main");
$xtpl->out("main");

?>
