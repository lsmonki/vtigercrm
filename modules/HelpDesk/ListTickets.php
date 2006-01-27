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
function getMyTickets()
{
global $current_user;
global $theme;
global $current_language;
global $adb;
$current_module_strings = return_module_language($current_language, 'HelpDesk');
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$search_query="select troubletickets.ticketid, parent_id, priority, troubletickets.status, category, troubletickets.title, troubletickets.description, update_log, version_id,
		crmentity.createdtime, crmentity.modifiedtime, 
		contactdetails.firstname, contactdetails.lastname, 
		account.accountid, account.accountname, 
		users.user_name from 
		troubletickets 
			inner join crmentity on crmentity.crmid = troubletickets.ticketid 
			inner join users on users.id = crmentity.smownerid 
			left join contactdetails on troubletickets.parent_id = contactdetails.contactid 
			left join account on account.accountid = troubletickets.parent_id 
			left join seticketsrel on seticketsrel.ticketid = troubletickets.ticketid 
		where crmentity.smownerid = ".$current_user->id." and crmentity.deleted = 0 and troubletickets.status <> 'Closed'  ORDER BY createdtime DESC";

$resultcount = $adb->num_rows($adb->query($search_query));
if($resultcount > 0)
{
	$tktresult = $adb->limitquery($search_query,0,5);
	$title=array();
	$title[]='myTickets.gif';
	$title[]=$current_module_strings['LBL_MY_TICKETS'];
	$title[]='home_mytkt';

	$header=array();
	$header[]=$current_module_strings['LBL_TICKET_ID'];
	$header[]=$current_module_strings['LBL_SUBJECT'];
	$header[]=$current_module_strings['Related To'];
	$header[]=$current_module_strings['LBL_STATUS'];
	$header[]=$current_module_strings['LBL_CREATED_DATE'];
	$header[]=$current_module_strings['LBL_ASSIGNED_TO'];

	$noofrows = $adb->num_rows($tktresult);
	for ($i=0; $i<$adb->num_rows($tktresult); $i++)
	{
		$value=array();
		$ticketid = $adb->query_result($tktresult,$i,"ticketid");
		$value[]=$ticketid;
		$value[]= '<a href="index.php?action=DetailView&module=HelpDesk&record='.$adb->query_result($tktresult,$i,"ticketid").'">'.$adb->query_result($tktresult,$i,"title").'</a>';

		$parent_id = $adb->query_result($tktresult,$i,"parent_id");
		$parent_name = '';
		if($parent_id != '' && $parent_id != NULL)
		{
			$parent_name = getParentLink($parent_id);
		}

		$value[]=$parent_name;
		$value[]=$adb->query_result($tktresult,$i,"status");
		$value[]=getDisplayDate($adb->query_result($tktresult,$i,"createdtime"));
		$value[]=$adb->query_result($tktresult,$i,"user_name");
		$entries[$ticketid]=$value;
	}
	$values=Array('Title'=>$title,'Header'=>$header,'Entries'=>$entries);
	if ( ($display_empty_home_blocks && $noofrows == 0 ) || ($noofrows>0) )	
		return $values;
}
}
function getParentLink($parent_id)
{
	global $adb;

	$sql = "select setype from crmentity where crmid=".$parent_id;
	$parent_module = $adb->query_result($adb->query($sql),0,'setype');

	if($parent_module == 'Contacts')
	{
		$sql = "select firstname,lastname from contactdetails where contactid=".$parent_id;
		$parentname = $adb->query_result($adb->query($sql),0,'firstname');
		$parentname .= ' '.$adb->query_result($adb->query($sql),0,'lastname');
	        $parent_name = '<a href="index.php?action=DetailView&module='.$parent_module.'&record='.$parent_id.'">'.$parentname.'</a>';
	}
	if($parent_module == 'Accounts')
	{
		$sql = "select accountname from account where accountid=".$parent_id;
		$parentname = $adb->query_result($adb->query($sql),0,'accountname');
	        $parent_name = '<a href="index.php?action=DetailView&module='.$parent_module.'&record='.$parent_id.'">'.$parentname.'</a>';
	}

	return $parent_name;
}
?>
