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
require_once('modules/Users/UserInfoUtil.php');
require_once('include/utils.php');
global $adb;
$profileid = $_REQUEST['profileid'];
//Retreiving the tabs permisson array

$tab_perr_result = $adb->query("select * from profile2tab where profileid=".$profileid);
$act_perr_result = $adb->query("select * from profile2standardpermissions where profileid=".$profileid);
$act_utility_result = $adb->query("select * from profile2utility where profileid=".$profileid);
$num_tab_per = $adb->num_rows($tab_perr_result);
$num_act_per = $adb->num_rows($act_perr_result);
$num_act_util_per = $adb->num_rows($act_utility_result);


//Updating the profile2tab table
for($i=0; $i<$num_tab_per; $i++)
{
	$tab_id = $adb->query_result($tab_perr_result,$i,"tabid");
	$request_var = $tab_id.'_tab';
	if($tab_id != 1 && $tab_id != 3 && $tab_id != 16 && $tab_id != 15  && $tab_id != 17 && $tab_id != 18 && $tab_id != 19 && $tab_id != 22)
	{
		$permission = $_REQUEST[$request_var];
		if($permission == 'on')
		{
			$permission_value = 0;
		}
		else
		{
			$permission_value = 1;
		}
		$update_query = "update profile2tab set permissions=".$permission_value." where tabid=".$tab_id." and profileid=".$profileid;
		$adb->query($update_query);
		if($tab_id ==9)
		{
			$update_query = "update profile2tab set permissions=".$permission_value." where tabid=16 and profileid=".$profileid;
			$adb->query($update_query);
		}
		if($tab_id ==14)
		{
			$update_query = "update profile2tab set permissions=".$permission_value." where tabid=18 and profileid=".$profileid;
			$adb->query($update_query);
			$update_query = "update profile2tab set permissions=".$permission_value." where tabid=19 and profileid=".$profileid;
			$adb->query($update_query);

		}
		if($tab_id == 21)
		{
			$update_query = "update profile2tab set permissions=".$permission_value." where tabid=22 and profileid=".$profileid;
			$adb->query($update_query);
		}
	}
}	
//Updating the profile2standardpermissions table
for($i=0; $i<$num_act_per; $i++)
{
	$tab_id = $adb->query_result($act_perr_result,$i,"tabid");
	if($tab_id != 1 && $tab_id != 3 && $tab_id != 16 && $tab_id != 15  && $tab_id != 17 && $tab_id != 18 && $tab_id != 19 && $tab_id != 22)
	{
		$action_id = $adb->query_result($act_perr_result,$i,"operation");
		$action_name = getActionname($action_id);
		if($action_name == 'EditView' || $action_name == 'Delete' || $action_name == 'DetailView')
		{
			$request_var = $tab_id.'_'.$action_name;
		}
		elseif($action_name == 'Save')
		{
			$request_var = $tab_id.'_EditView';
		}
		elseif($action_name == 'index')
		{
			$request_var = $tab_id.'_DetailView';
		}
	
		/*	
		echo 'tabid isss '.$tab_id;
		echo '   action id iss'.$action_id.'     action name iss '.$action_name.'    requestvar is    '.$request_var;
		echo '<BR>';
		*/

		$permission = $_REQUEST[$request_var];
		if($permission == 'on')
		{
			$permission_value = 0;
		}
		else
		{
			$permission_value = 1;
		}
		$update_query = "update profile2standardpermissions set permissions=".$permission_value." where tabid=".$tab_id." and Operation=".$action_id." and profileid=".$profileid;
		//echo $update_query;
		//echo '<BR>';
		$adb->query($update_query);
		if($tab_id ==9)
		{
			$update_query = "update profile2standardpermissions set permissions=".$permission_value." where tabid=16 and Operation=".$action_id." and profileid=".$profileid;
		$adb->query($update_query);
		}
		
		if($tab_id == 14)
		{
			$update_query = "update profile2standardpermissions set permissions=".$permission_value." where tabid=18 and Operation=".$action_id." and profileid=".$profileid;
		$adb->query($update_query);
			$update_query = "update profile2standardpermissions set permissions=".$permission_value." where tabid=19 and Operation=".$action_id." and profileid=".$profileid;
		$adb->query($update_query);
		}

		if($tab_id ==21)
		{
			$update_query = "update profile2standardpermissions set permissions=".$permission_value." where tabid=22 and Operation=".$action_id." and profileid=".$profileid;
		$adb->query($update_query);
		}	

	}
}

//Updating the profile2utility table
for($i=0; $i<$num_act_util_per; $i++)
{
	$tab_id = $adb->query_result($act_utility_result,$i,"tabid");
	if($tab_id != 1 && $tab_id != 3 && $tab_id != 16 && $tab_id != 15  && $tab_id != 17 && $tab_id != 18 && $tab_id != 19  && $tab_id != 22)
	{
		$action_id = $adb->query_result($act_utility_result,$i,"activityid");
		$action_name = getActionname($action_id);
		$request_var = $tab_id.'_'.$action_name;
	
		/*	
		echo 'tabid isss '.$tab_id;
		echo '   action id iss'.$action_id.'     action name iss '.$action_name.'    requestvar is    '.$request_var;
		echo '<BR>';
		*/

		$permission = $_REQUEST[$request_var];
		if($permission == 'on')
		{
			$permission_value = 0;
		}
		else
		{
			$permission_value = 1;
		}
		//Fix for Mail Merge
		if($action_id == '8')
		{
			$permission_value = 0;
		}

		$update_query = "update profile2utility set permission=".$permission_value." where tabid=".$tab_id." and activityid=".$action_id." and profileid=".$profileid;
		/*
		echo $update_query;
		echo '<BR>';
		*/
		$adb->query($update_query);
		if($tab_id ==9)
		{
			$update_query = "update profile2utility set permission=".$permission_value." where tabid=16 and activityid=".$action_id." and profileid=".$profileid;
			$adb->query($update_query);
		}
		if($tab_id ==14)
		{
			$update_query = "update profile2utility set permission=".$permission_value." where tabid=18 and activityid=".$action_id." and profileid=".$profileid;
			$adb->query($update_query);
			$update_query = "update profile2utility set permission=".$permission_value." where tabid=19 and activityid=".$action_id." and profileid=".$profileid;
			$adb->query($update_query);
		}
		if($tab_id ==21)
		{
			$update_query = "update profile2utility set permission=".$permission_value." where tabid=22 and activityid=".$action_id." and profileid=".$profileid;
			$adb->query($update_query);
		}

	}
}


$loc = "Location: index.php?action=ProfileDetailView&module=Users&fld_module=".$fld_module."&profileid=".$profileid;
header($loc);
?>
