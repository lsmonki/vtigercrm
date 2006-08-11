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
require_once('include/database/PearDatabase.php');

global $mod_strings;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

/**
 * To get the lists of sharedids 
 * @param $id -- The user id :: Type integer
 * @returns $sharedids -- The shared vtiger_users id :: Type Array
 */
function getSharedUserId($id)
{
        global $adb;
	$sharedid = Array();
        $query = "SELECT * from vtiger_sharedcalendar where userid=".$id;
        $result = $adb->query($query);
        $rows = $adb->num_rows($result);
        for($j=0;$j<$rows;$j++)
        {
	        $sharedid[] = $adb->query_result($result,$j,'sharedid');
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
 * @param $check -- true/false :: Type boolean
 * @returns $user_details -- Array in the following format:
 * $user_details=Array($userid1=>$username, $userid2=>$username,............,$useridn=>$username);
 */
function getOtherUserName($id,$check)
{
	global $adb;
	if($check)
		$query="select * from vtiger_users where deleted=0 and status='Active' and id!=".$id;
	else
		$query="select * from vtiger_users where deleted=0 and status='Active' and is_admin='off' and id!=".$id;
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	$user_details=Array();
	for($i=0;$i<$num_rows;$i++)
	{
		$userid=$adb->query_result($result,$i,'id');
		$username=$adb->query_result($result,$i,'user_name');
 		$user_details[$userid]=$username;
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
		if($hr <= 11)
		{
			$timearr['starthour'] = $sthr;
			$timearr['startfmt'] = 'am';
		}
		else
		{
			if($hr == 12)
				$sthr = $hr;
			else
				$sthr = $hr - 12;
			if($sthr <= 9 && strlen(trim($sthr)) < 2)
                        {
                                $hrvalue= '0'.$sthr;
                        }else $hrvalue=$sthr;
			$timearr['starthour'] = $hrvalue;
			$timearr['startfmt'] = 'pm';
		}
		$ehr = $edhr+0;
                if($ehr <= 11)
                {
                        $timearr['endhour'] = $edhr;
                        $timearr['endfmt'] = 'am';
                }
                else
                {
			if($edhr == 12)
				$edhr =	$edhr;
			else
				$edhr = $edhr - 12;
                        if($edhr <= 9 && strlen(trim($edhr)) < 2)
                        {
                                $hrvalue= '0'.$edhr;
                        }else $hrvalue=$edhr;
                        $timearr['endhour'] = $hrvalue;
                        $timearr['endfmt'] = 'pm';
                }
		$timearr['startmin']  = $stmin;
		$timearr['endmin']    = $edmin;
		return $timearr;
	}
	if($format == '24')
	{
		$timearr['starthour'] = $sthr;
		$timearr['startmin']  = $stmin;
		$timearr['startfmt']  = '';
		$timearr['endhour']   = $edhr;
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
function getTimeCombo($format,$bimode,$hour='',$min='',$fmt='')
{
	$combo = '';
	if($format == 'am/pm')
	{
		$combo .= '<select class=small name="'.$bimode.'hr" id="'.$bimode.'hr">';
		for($i=1;$i<=12;$i++)
		{
			if($i <= 9 && strlen(trim($i)) < 2)
			{
				$hrvalue= '0'.$i;
			}
			//elseif($i == 12) $hrvalue = '00';
			else $hrvalue= $i;
			if($hour == $hrvalue)
				$hrsel = 'selected';
			else
				$hrsel = '';
			$combo .= '<option value="'.$hrvalue.'" "'.$hrsel.'">'.$i.'</option>';
		}
		$combo .= '</select>&nbsp;';
		$combo .= '<select name="'.$bimode.'min" id="'.$bimode.'min" class=small value="'.$min.'">';
		for($i=0;$i<12;$i++)
		{
			$minvalue = 5;
			$value = $i*5;
			if($value <= 9 && strlen(trim($value)) < 2)
			{
				$value= '0'.$value;
			}
			else $value= $value;
			if($min == $value)
				$minsel = 'selected';
			else
				$minsel = '';
				$combo .= '<option value="'.$value.'" "'.$minsel.'">'.$value.'</option>';
		}
		$combo .= '</select>&nbsp;';
		$combo .= '<select name="'.$bimode.'fmt" id="'.$bimode.'fmt" value="'.$fmt.'" class=small>';
		if($fmt == 'am')
		{
			$amselected = 'selected';
			$pmselected = '';
		}
		elseif($fmt == 'pm')
		{
			$amselected = '';
			$pmselected = 'selected';
		}
		$combo .= '<option value="am" '.$amselected.'>AM</option>';
		$combo .= '<option value="pm" '.$pmselected.'>PM</option>';
		$combo .= '</select>';
		}
		else
		{
			$combo .= '<select name="'.$bimode.'hr" id="'.$bimode.'hr" class=small value="'.$hour.'">';
			for($i=0;$i<=23;$i++)
			{
				if($i <= 9 && strlen(trim($i)) < 2)
				{
					$hrvalue= '0'.$i;
				}
				else $hrvalue = $i;
				if($hour == $hrvalue)
					$hrsel = 'selected';
				else
					$hrsel = '';
				$combo .= '<option value="'.$hrvalue.'" "'.$hrsel.'">'.$i.'</option>';
			}
			$combo .= '</select>Hr&nbsp;';
			$combo .= '<select name="'.$bimode.'min" id="'.$bimode.'min" class=small value="'.$min.'">';
			for($i=0;$i<12;$i++)
			{
				$minvalue = 5;
				$value = $i*5;
				if($value <= 9 && strlen(trim($value)) < 2)
				{
					$value= '0'.$value;
				}
				else $value=$value;
				if($min == $value)
					$minsel = 'selected';
				else
					$minsel = '';
				$combo .= '<option value="'.$value.'" "'.$minsel.'">'.$value.'</option>';
			}
			$combo .= '</select>&nbsp;min<input type="hidden" name="'.$bimode.'fmt" id="'.$bimode.'fmt" value="'.$fmt.'">';
		}
		return $combo;
}

//Code Added by Minnie -Ends
?>
