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
//Code Added by Minnie -Starts
/**
 * To get the lists of sharedids 
 * @param $id -- The user id :: Type integer
 * @returns $sharedids -- The shared vtiger_users id :: Type Array
 */
function getSharedUserId($id)
{
	global $adb;
        $sharedid = Array();
        $query = "SELECT vtiger_users.user_name,vtiger_sharedcalendar.* from vtiger_sharedcalendar left join vtiger_users on vtiger_sharedcalendar.sharedid=vtiger_users.id where userid=".$id;
        $result = $adb->query($query);
        $rows = $adb->num_rows($result);
        for($j=0;$j<$rows;$j++)
        {

                $id = $adb->query_result($result,$j,'sharedid');
                $sharedname = $adb->query_result($result,$j,'user_name');
                $sharedid[$id]=$sharedname;

        }
	return $sharedid;
}

/**
 * To get the lists of vtiger_users id who shared their calendar with specified user
 * @param $sharedid -- The shared user id :: Type integer
 * @returns $shared_ids -- a comma seperated vtiger_users id  :: Type string
 */
function getSharedCalendarId($sharedid)
{
	global $adb;
	$query = "SELECT * from vtiger_sharedcalendar where sharedid=".$sharedid;
	$result = $adb->query($query);
	if($adb->num_rows($result)!=0)
	{
		for($j=0;$j<$adb->num_rows($result);$j++)
			$userid[] = $adb->query_result($result,$j,'userid');
		$shared_ids = implode (",",$userid);
	}
	return $shared_ids;
}

/**
 * To get userid and username of all vtiger_users except the current user
 * @param $id -- The user id :: Type integer
 * @returns $user_details -- Array in the following format:
 * $user_details=Array($userid1=>$username, $userid2=>$username,............,$useridn=>$username);
 */
function getOtherUserName($id)
{
	global $adb;
	$user_details=Array();
		$query="select * from vtiger_users where deleted=0 and status='Active' and id!=".$id;
		$result = $adb->query($query);
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$userid=$adb->query_result($result,$i,'id');
			$username=$adb->query_result($result,$i,'user_name');
			$user_details[$userid]=$username;
		}
		return $user_details;
}

/**
 * To get userid and username of vtiger_users in hierarchy level
 * @param $id -- The user id :: Type integer
 * @returns $user_details -- Array in the following format:
 * $user_details=Array($userid1=>$username, $userid2=>$username,............,$useridn=>$username);
 */

function getSharingUserName($id)
{
	global $adb,$current_user;
        require('user_privileges/user_privileges_'.$current_user->id.'.php');
        require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
        $user_details=Array();

		if($is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[getTabid('Calendar')] == 3 or $defaultOrgSharingPermission[getTabid('Calendar')] == 0))
		{
			$user_details = get_user_array(FALSE, "Active", $id, 'private');
			unset($user_details[$id]);
		}
		else
		{
			$user_details = get_user_array(FALSE, "Active", $id);
			unset($user_details[$id]);
		}
	return $user_details;
}

/**
 * To get hour,minute and format
 * @param $starttime -- The date&time :: Type string
 * @param $endtime -- The date&time :: Type string
 * @param $format -- The format :: Type string
 * @returns $timearr :: Type Array
*/
function getaddEventPopupTime($starttime,$endtime,$format)
{
	$timearr = Array();
	list($sthr,$stmin) = explode(":",$starttime);
	list($edhr,$edmin)  = explode(":",$endtime);
	if($format == 'am/pm')
	{
		$hr = $sthr+0;
		$timearr['startfmt'] = ($hr >= 12) ? "pm" : "am";
		if($hr == 0) $hr = 12;
		$timearr['starthour'] = twoDigit(($hr>12)?($hr-12):$hr);
		$timearr['startmin']  = $stmin;

		$edhr = $edhr+0;
		$timearr['endfmt'] = ($edhr >= 12) ? "pm" : "am";
		if($edhr == 0) $edhr = 12;
		$timearr['endhour'] = twoDigit(($edhr>12)?($edhr-12):$edhr);
		$timearr['endmin']    = $edmin;
		return $timearr;
	}
	if($format == '24')
	{
		$timearr['starthour'] = twoDigit($sthr);
		$timearr['startmin']  = $stmin;
		$timearr['startfmt']  = '';
		$timearr['endhour']   = twoDigit($edhr);
		$timearr['endmin']    = $edmin;
		$timearr['endfmt']    = '';
		return $timearr;
	}
}

/**
 *To construct time select combo box
 *@param $format -- the format :: Type string
 *@param $bimode -- The mode :: Type string
 *constructs html select combo box for time selection
 *and returns it in string format.
 */
function getTimeCombo($format,$bimode,$hour='',$min='',$fmt='',$todocheck=false)
{
	global $mod_strings;
	$combo = '';
	$min = $min - ($min%5);
	if($bimode == 'start' && !$todocheck)
		$jsfn = 'onChange="changeEndtime_StartTime();"';
	else
		$jsfn = null;
	if($format == 'am/pm')
	{
		$combo .= '<select class=small name="'.$bimode.'hr" id="'.$bimode.'hr" '.$jsfn.'>';
		for($i=0;$i<12;$i++)
		{
			if($i == 0)
			{
				$hrtext= 12;
				$hrvalue = 12;
			}
			else
				$hrvalue = $hrtext = twoDigit($i);
			$hrsel = ($hour == $hrvalue)?'selected':'';	
			$combo .= '<option value="'.$hrvalue.'" '.$hrsel.'>'.$hrtext.'</option>';
		}
		$combo .= '</select>&nbsp;';
		$combo .= '<select name="'.$bimode.'min" id="'.$bimode.'min" class=small '.$jsfn.'>';
		for($i=0;$i<12;$i++)
		{
			$value = $i*5;
			$value = twoDigit($value);
			$minsel = ($min == $value)?'selected':'';
			$combo .= '<option value="'.$value.'" '.$minsel.'>'.$value.'</option>';
		}
		$combo .= '</select>&nbsp;';
		$combo .= '<select name="'.$bimode.'fmt" id="'.$bimode.'fmt" class=small>';
		$amselected = ($fmt == 'am')?'selected':'';
		$pmselected = ($fmt == 'pm')?'selected':'';
		$combo .= '<option value="am" '.$amselected.'>AM</option>';
		$combo .= '<option value="pm" '.$pmselected.'>PM</option>';
		$combo .= '</select>';
		}
		else
		{
			$combo .= '<select name="'.$bimode.'hr" id="'.$bimode.'hr" class=small '.$jsfn.'>';
			for($i=0;$i<=23;$i++)
			{
				$hrvalue = twoDigit($i);
				$hrsel = ($hour == $hrvalue)?'selected':'';
				$combo .= '<option value="'.$hrvalue.'" '.$hrsel.'>'.$hrvalue.'</option>';
			}
			$combo .= '</select>'.$mod_strings[LBL_HR].'&nbsp;';
			$combo .= '<select name="'.$bimode.'min" id="'.$bimode.'min" class=small '.$jsfn.'>';
			for($i=0;$i<12;$i++)
			{
				$value = $i*5;
				$value= twoDigit($value);
				$minsel = ($min == $value)?'selected':'';
				$combo .= '<option value="'.$value.'" '.$minsel.'>'.$value.'</option>';
			}
			$combo .= '</select>&nbsp;'.$mod_strings[LBL_MIN].'<input type="hidden" name="'.$bimode.'fmt" id="'.$bimode.'fmt">';
		}
		return $combo;
}

/**
 *Function to construct HTML select combo box
 *@param $fieldname -- the field name :: Type string
 *@param $tablename -- The table name :: Type string
 *constructs html select combo box for combo field
 *and returns it in string format.
 */

function getActFieldCombo($fieldname,$tablename)
{
	global $adb, $mod_strings;
	$combo = '';
	$js_fn = '';
	if($fieldname == 'eventstatus')
		$js_fn = 'onChange = "getSelectedStatus();"';
	$combo .= '<select name="'.$fieldname.'" id="'.$fieldname.'" class=small '.$js_fn.'>';
	$q = "select * from ".$tablename;
	$Res = $adb->query($q);
	$noofrows = $adb->num_rows($Res);

	for($i = 0; $i < $noofrows; $i++)
	{
		$value = $adb->query_result($Res,$i,$fieldname);
		$combo .= '<option value="'.$value.'">'.$value.'</option>';
	}

	$combo .= '</select>';
	return $combo;
}

/*Fuction to get value for Assigned To field
 *returns values of Assigned To field in array format
*/
function getAssignedTo($tabid)
{
	global $current_user,$noof_group_rows,$adb;
	$assigned_user_id = $current_user->id;
	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	if($is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[$tabid] == 3 or $defaultOrgSharingPermission[$tabid] == 0))
	{
		$result=get_current_user_access_groups('Calendar');
	}
	else
	{
		$result = get_group_options();
	}
	$nameArray = $adb->fetch_array($result);
	
	if($is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[$tabid] == 3 or $defaultOrgSharingPermission[$tabid] == 0))
	{
		$users_combo = get_select_options_array(get_user_array(FALSE, "Active", $assigned_user_id,'private'), $assigned_user_id);
	}
	else
	{
		$users_combo = get_select_options_array(get_user_array(FALSE, "Active", $assigned_user_id), $assigned_user_id);
	}
	if($noof_group_rows!=0)
	{
		do
		{
			$groupname=$nameArray["groupname"];
			$group_option[] = array($groupname=>$selected);

		}while($nameArray = $adb->fetch_array($result));
	}
	$fieldvalue[]=$users_combo;
	$fieldvalue[] = $group_option;
	return $fieldvalue;
}

//Code Added by Minnie -Ends
/**
 * Function to get the vtiger_activity details for mail body
 * @param   string   $description       - activity description
 * return   string   $list              - HTML in string format
 */
function getActivityDetails($description,$inviteeid='')
{
        global $log,$current_user;
        global $adb,$mod_strings;
        $log->debug("Entering getActivityDetails(".$description.") method ...");

        $reply = (($_REQUEST['mode'] == 'edit')?'updated':'created');
        if($inviteeid=='')
        $name = getUserName($_REQUEST['assigned_user_id']);
        else
        $name = getUserName($inviteeid);

        $current_username = getUserName($current_user->id);
        $status = (($_REQUEST['activity_mode']=='Task')?($_REQUEST['taskstatus']):($_REQUEST['eventstatus']));

        $list = $name.',';
        $list .= '<br><br>'.$mod_strings['LBL_ACTIVITY_STRING'].' '.$reply.'.<br> '.$mod_strings['LBL_DETAILS_STRING'].':';
        $list .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$mod_strings["LBL_SUBJECT"].' '.$_REQUEST['subject'];
        $list .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$mod_strings["LBL_STATUS"].': '.$status;
        $list .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$mod_strings["Priority"].': '.$_REQUEST['taskpriority'];
        $list .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$mod_strings["Related To"].' : '.$_REQUEST['parent_name'];
	if($_REQUEST['activity_mode']!= 'Events')
	{
        	$list .= '<br>'.$mod_strings["LBL_CONTACT"].' '.$_REQUEST['contactlist'];
	}
        $list .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$mod_strings["LBL_APP_DESCRIPTION"].': '.$description;
        $list .= '<br><br>'.$mod_strings["LBL_REGARDS_STRING"].' ,';
        $list .= '<br>'.$current_username.'.';

        $log->debug("Exiting getActivityDetails method ...");
        return $list;
}

function twoDigit( $no ){
	if($no < 10 && strlen(trim($no)) < 2) return "0".$no;
	else return "".$no;
}

function timeString($datetime,$fmt){

	if(is_object($datetime)){
		$hr = $datetime->hour;
		$min = $datetime->minute;
	} else {
		$hr = $datetime['hour'];
		$min = $datetime['minute'];
	}
	$timeStr = "";
	if($fmt != 'am/pm'){
		$timeStr .= twoDigit($hr).":".twoDigit($min);
	}else{
		$am = ($hr >= 12) ? "pm" : "am";
		if($hr == 0) $hr = 12;
		$timeStr .= ($hr>12)?($hr-12):$hr;
		$timeStr .= ":".twoDigit($min);
		$timeStr .= $am;
	}
	return $timeStr;
}


?>
