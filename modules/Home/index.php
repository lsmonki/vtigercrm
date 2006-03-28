<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Home/index.php,v 1.28 2005/04/20 06:57:47 samk Exp $
 * Description:  Main file for the Home module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('Smarty_setup.php');
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils/UserInfoUtil.php');
require_once('include/utils/CommonUtils.php');
global $app_strings;
global $app_list_strings;
global $mod_strings;
$smarty = new vtigerCRM_Smarty;

$_REQUEST['search_form'] = 'false';
$_REQUEST['query'] = 'true';
$_REQUEST['status'] = 'In Progress--Not Started';
$_REQUEST['current_user_only'] = 'On';

$task_title = $mod_strings['LBL_OPEN_TASKS'];

// MWC Home Order Sorting functions given by mike
global $adb;
global $current_user;

$query = "SELECT users.homeorder FROM users WHERE id=".$current_user->id;
$result =& $adb->query($query, true,"Error getting home order");
$row = $adb->fetchByAssoc($result);

if($row != null)
{
	$home_section_order = $row['homeorder'];
}
if( count($home_section_order) < 1 )
{
	$home_section_order = array("ALVT","PLVT","QLTQ","CVLVT","HLT","OLV","GRT","OLTSO","ILTI");
}

foreach ( explode(",",$home_section_order) as $section )
{
	switch( $section )
	{
		case 'OLV':
	if(isPermitted('Activities','index') == "yes")
	{
		include("modules/Activities/OpenListView.php") ;
		$home_values[]= getPendingActivities();
	}
            break;
        case 'ALVT':


	//Added to support the inclusion of the Top Accounts in the Home Page. 
	//Fix given by Mike Crowe
        if(isPermitted('Accounts','index') == "yes")
        {
                include("modules/Accounts/ListViewTop.php");
		$home_values[]=getTopAccounts();
        }
            break;
        case 'PLVT':
	if(isPermitted('Potentials','index') == "yes")
        {
		 include("modules/Potentials/ListViewTop.php");
		 $home_values[]=getTopPotentials();
	}
            break;

        case 'MNL':
	if(isPermitted('Leads','index') == "yes")
        {
		 include("modules/Leads/ListViewTop.php");
		 $home_values[]=getNewLeads();
	}
            break;

	case 'GRT':
	if(isPermitted('Activities','index') == "yes")
	{
		$home_values[]=getGroupTaskLists();	   
	}
   			break;
        case 'HLT':
        if(isPermitted('HelpDesk','index') == "yes")
        {
		require_once('modules/HelpDesk/ListTickets.php');
		$home_values[]=getMyTickets();
	}
        	break;
        case 'CVLVT':
	include("modules/CustomView/ListViewTop.php");
	$home_values[] = getKeyMetrics();
        	break;
        case 'QLTQ':
        if(isPermitted('Quotes','index') == "yes")
        {
		require_once('modules/Quotes/ListTopQuotes.php');
		$home_values[]=getTopQuotes();
	}
        	break;
        case 'OLTSO':
        if(isPermitted('SalesOrder','index') == "yes")
        {
		require_once('modules/SalesOrder/ListTopSalesOrder.php');
		$home_values[]=getTopSalesOrder();
	}
        	break;
        case 'ILTI':
        if(isPermitted('Invoice','index') == "yes")
        {
		require_once('modules/Invoice/ListTopInvoice.php');
		$home_values[]=getTopInvoice();
	}
        	break;
    }
}
function getActivityType($id)
{
	global $adb;
	$quer = "select activitytype from activity where activityid=".$id;
	$res = $adb->query($quer);
	$acti_type = $adb->query_result($res,0,"activitytype");
	return $acti_type;

}
$query="select tagcloud from users where id=".$current_user->id;
$result=$adb->query($query);
$tagcloud_js=$adb->query_result($result,0,'tagcloud');
$smarty->assign("TAGCLOUD_JS",$tagcloud_js);
$smarty->assign("TAGCLOUD_CSS",ereg_replace('/js/','/css/',$tagcloud_js));
$smarty->assign("LOGINHISTORY",getLoginHistory());
global $current_language;
$current_module_strings = return_module_language($current_language, 'Calendar');

$t=Date("Ymd");
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("HOMEDETAILS",$home_values);
$smarty->assign("HOMEDEFAULTVIEW",DefHomeView());
$smarty->display("HomePage.tpl");

function getLoginHistory()
{
	global $current_user;
	global $adb;
	global $app_strings;
	$i=0;
	$userid= $current_user->id;
	$query="select * from loginhistory inner join users on loginhistory.user_name=users.user_name where users.id=".$userid;
	$result=$adb->query($query);
	$count=$adb->num_rows($result);
	$logout_time=$adb->query_result($result,$count-2,'logout_time');
	if($logout_time !='' && $logout_time != '0000-00-00 00:00:00' && $count >= 2)
	{
		$query ="select * from crmentity where modifiedtime > '".$logout_time."'and smownerid =".$userid;
		$result=$adb->query($query);
		$entry_list=array();
		for(;$i < $adb->num_rows($result);$i++)
		{
			$entries=array();
			$entries['setype'] =$adb->query_result($result,$i,'setype');	
			$entries['modifiedby'] = getUserName($adb->query_result($result,$i,'modifiedby'));
			$entries['modifiedtime'] = $adb->query_result($result,$i,'modifiedtime');
			$entries['crmid'] = $adb->query_result($result,$i,'crmid');
			$entry_list[]=$entries;	
		}
		if($i > 0)
			return $entry_list;
	}
}
	
function getGroupTaskLists()
{
	//get all the group relation tasks
	global $current_user;
	global $adb;
	global $log;
	global $app_strings;
	$userid= $current_user->id;
	$groupids = fetchUserGroupids($userid);
	if($groupids !='')
	{
		//code modified to list the groups associates to a user om 21-11-05
		//Get the leads assigned to group
		$query = "select leaddetails.leadid as id,leaddetails.lastname as name,leadgrouprelation.groupname as groupname, 'Leads     ' as Type from leaddetails inner join leadgrouprelation on leaddetails.leadid=leadgrouprelation.leadid inner join crmentity on crmentity.crmid = leaddetails.leadid inner join groups on leadgrouprelation.groupname=groups.groupname where  crmentity.deleted=0  and leadgrouprelation.groupname is not null and groups.groupid in (".$groupids.")";
		$query .= " union all ";
		//Get the activities assigned to group
		$query .= "select activity.activityid id,activity.subject,activitygrouprelation.groupname,'Activities' as Type from activity inner join activitygrouprelation on activitygrouprelation.activityid=activity.activityid inner join crmentity on crmentity.crmid = activity.activityid inner join groups on activitygrouprelation.groupname=groups.groupname where  crmentity.deleted=0 and ((activity.eventstatus !='held'and (activity.status is null or activity.status ='')) or (activity.status !='completed' and (activity.eventstatus is null or activity.eventstatus=''))) and activitygrouprelation.groupname is not null and groups.groupid in (".$groupids.")";
		$query .= " union all ";
		//Get the tickets assigned to group (status not Closed -- hardcoded value)
		$query .= "select troubletickets.ticketid,troubletickets.title,ticketgrouprelation.groupname,'Tickets   ' as Type from troubletickets inner join ticketgrouprelation on ticketgrouprelation.ticketid=troubletickets.ticketid inner join crmentity on crmentity.crmid = troubletickets.ticketid inner join groups on ticketgrouprelation.groupname=groups.groupname where crmentity.deleted=0 and troubletickets.status != 'Closed' and ticketgrouprelation.groupname is not null and groups.groupid in (".$groupids.")";


		$log->info("Here is the where clause for the list view: $query");
		$result = $adb->limitquery($query,0,5) or die("Couldn't get the group listing");

		$title=array();
		$title[]='myGroupAllocation.gif';
		$title[]=$app_strings['LBL_GROUP_ALLOCATION_TITLE'];
		$title[]='home_mygrp';
		$header=array();
		$header[]=$app_strings['LBL_ENTITY_NAME'];
		$header[]=$app_strings['LBL_GROUP_NAME'];
		$header[]=$app_strings['LBL_ENTITY_TYPE'];



		if($groupids !='')
		{
			$i=1;
			while($row = $adb->fetch_array($result))
			{
				$value=array();	
				if($row["type"] == "Tickets")
				{	
					$list = '<a href=index.php?module=HelpDesk';
				}
				elseif($row["type"] == "Activities")
				{
					$acti_type = getActivityType($row["id"]);
					$list = '<a href=index.php?module='.$row["type"];
					if($acti_type == 'Task')
					{
						$list .= '&activity_mode=Task';
					}
					elseif($acti_type == 'Call' || $acti_type == 'Meeting')
					{
						$list .= '&activity_mode=Events';
					}
				}
				else
				{
					$list = '<a href=index.php?module='.$row["type"];
				}

				$list .= '&action=DetailView&record='.$row["id"].'>'.$row["name"].'</a>';
				$value[]=$list;	
				$value[]= $row["groupname"];
				$value[]= $row["type"];
				$entries[$row["id"]]=$value;	
				$i++;
			}
		}

		$values=Array('Title'=>$title,'Header'=>$header,'Entries'=>$entries);
		return $values;
		} 
}
?>

