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

$groupName = trim($_REQUEST['groupName']);
$description = $_REQUEST['description'];
function groupexists($groupName)
{	
	global $adb;
	$query = "select * from groups where groupname='".$groupName."'";
	$result = $adb->query($query);
	if($adb->query_result($result,0,"groupname")==$groupName)
	{	
		return true;
	}
	else
	{
		return false;
	}
}

function constructGroupMemberArray($member_array)
	{
		global $adb;

		$groupMemberArray=Array();
		$roleArray=Array();
		$roleSubordinateArray=Array();
		$groupArray=Array();
		$userArray=Array();

		foreach($member_array as $member)
		{
			$memSubArray=explode('::',$member);
			if($memSubArray[0] == 'groups')
			{
				$groupArray[]=$memSubArray[1];			
			}
			if($memSubArray[0] == 'roles')
			{
				$roleArray[]=$memSubArray[1];			
			}
			if($memSubArray[0] == 'rs')
			{
				$roleSubordinateArray[]=$memSubArray[1];			
			}
			if($memSubArray[0] == 'users')
			{
				$userArray[]=$memSubArray[1];			
			}
		}

		$groupMemberArray['groups']=$groupArray;
		$groupMemberArray['roles']=$roleArray;
		$groupMemberArray['rs']=$roleSubordinateArray;
		$groupMemberArray['users']=$userArray;

		return $groupMemberArray;

	}

	if(isset($_REQUEST['returnaction']) && $_REQUEST['returnaction'] != '')
	{
		$returnaction=$_REQUEST['returnaction'].'&roleid='.$_REQUEST['roleid'];
	}
	else
	{
		$returnaction='GroupDetailView';
	}

	//Inserting values into Role Table
	if(isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'edit')
	{
		$groupId = $_REQUEST['groupId'];
		$selected_col_string = 	$_REQUEST['selectedColumnsString'];
		$member_array = explode(';',$selected_col_string);
		$groupMemberArray=constructGroupMemberArray($member_array);
		updateGroup($groupId,$groupName,$groupMemberArray,$description);

		$loc = "Location: index.php?action=".$returnaction."&module=Users&groupId=".$groupId;
	}
	elseif(isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'create')
	{
		if(!groupexists($groupName))
		{
			$selected_col_string = 	$_REQUEST['selectedColumnsString'];
			$member_array = explode(';',$selected_col_string);
			$groupMemberArray=constructGroupMemberArray($member_array);
			$groupId=createGroup($groupName,$groupMemberArray,$description);
			//Inserting into role Table
			//$roleId = createRole($rolename,$parentRoleId,$profile_array);
			 $loc = "Location: index.php?action=".$returnaction."&module=Users&groupId=".$groupId; 	 
		}
		else
		{
			$loc = "Location: index.php?action=createnewgroup&module=Users&groupname=".$groupName."&desc=".$description."&error=true";
		}


	}

	header($loc);
?>
