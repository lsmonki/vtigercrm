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
 * Function to get the lists of sharedids 
 * This function accepts the user id as argument and
 * returns the shared ids related with the user id
 * as an array
 */
function getSharedUserId($id)
{
        global $adb;
	$sharedid = Array();
        $query = "SELECT * from sharedcalendar where userid=".$id;
        $result = $adb->query($query);
        $rows = $adb->num_rows($result);
        for($j=0;$j<$rows;$j++)
        {
	        $sharedid[] = $adb->query_result($result,$j,'sharedid');
        }
        return $sharedid;
}

/**
 * Function to get the lists of user ids who shared their calendar with an user
 * This function accepts the shared id as arguments and
 * returns the user ids related with the shared id
 * as a comma seperated string
 */
function getSharedCalendarId($sharedid)
{
	global $adb;
	$query = "SELECT * from sharedcalendar where sharedid=".$sharedid;
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
 * Function to get the label for user lists
 * Returns the label as an array
 */
function getSharedUserListViewHeader()
{
	global $mod_strings;
		$header_label=array($mod_strings['LBL_LIST_NAME'],
			            $mod_strings['LBL_LIST_USER_NAME'],
				   );
	return $header_label;
}

/**
 * Function to get the entries for user lists
 * This function accepts the shared id as arguments and
 * returns the user entries related with the shared id
 * as an array
 */
function getSharedUserListViewEntries($sharedid)
{
	global $adb;
	$query = "SELECT * from users where id=".$sharedid;
	$result =$adb->query($query);
	$entries[]=$adb->query_result($result,0,'first_name').' '.$adb->query_result($result,0,'last_name');
	$entries[]='<a href="index.php?action=DetailView&module=Users&parenttab=Settings&record='.$sharedid.'">'.$adb->query_result($result,0,'user_name').'</a>';
	return $entries;

}

/**
 * Function to get userid and username of all users except the current user
 * @returns $userArray -- User Array in the following format:
 * $userArray=Array($userid1=>$username, $userid2=>$username,............,$useridn=>$username);
 */
function getOtherUserName($id)
{
	global $adb;
	$query="select * from users where deleted=0 and status='Active' and id!=".$id;
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

//Code Added by Minnie -Ends
?>
