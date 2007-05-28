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
require_once('include/freetag/freetag.class.php');
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

$query = "SELECT vtiger_users.homeorder FROM vtiger_users WHERE id=".$current_user->id;
$result =& $adb->query($query, true,"Error getting home order");
$row = $adb->fetchByAssoc($result);

if($row != null)
{
	$home_section_order = $row['homeorder'];
}
if( count($home_section_order) < 1 )
{
	$home_section_order = array("ALVT","HDB","PLVT","QLTQ","CVLVT","HLT","OLV","GRT","OLTSO","ILTI","MNL","OLTPO","LTFAQ");
}

require('user_privileges/user_privileges_'.$current_user->id.'.php');
foreach ( explode(",",$home_section_order) as $section )
{
	switch( $section )
	{
		case 'OLV':
	if(isPermitted('Calendar','index') == "yes")
	{
		$activities = Array();
                include("modules/Calendar/OpenListView.php") ;
                $activities[] = getPendingActivities(0);
                $activities[] = getPendingActivities(1);
	}
            break;
        case 'ALVT':


	//Added to support the inclusion of the Top Accounts in the Home Page. 
	//Fix given by Mike Crowe
        if(isPermitted('Accounts','index') == "yes")
        {
                include("modules/Accounts/ListViewTop.php");
		$home_values['Accounts']=getTopAccounts();
        }
            break;
        case 'PLVT':
	if(isPermitted('Potentials','index') == "yes")
        {
		 include("modules/Potentials/ListViewTop.php");
		 $home_values['Potentials']=getTopPotentials();
	}
            break;

        case 'MNL':
	if(isPermitted('Leads','index') == "yes")
        {
		 include("modules/Leads/ListViewTop.php");
		 $home_values['Leads']=getNewLeads();
	}
            break;

	case 'GRT':
		$home_values['GroupAllocation']=getGroupTaskLists();	   
   		break;
        case 'HLT':
        if(isPermitted('HelpDesk','index') == "yes")
        {
		require_once('modules/HelpDesk/ListTickets.php');
		$home_values['HelpDesk']=getMyTickets();
	}
        	break;
        case 'CVLVT':
	include("modules/CustomView/ListViewTop.php");
	$home_values['CustomView'] = getKeyMetrics();
        	break;
        case 'QLTQ':
        if(isPermitted('Quotes','index') == "yes")
        {
		require_once('modules/Quotes/ListTopQuotes.php');
		$home_values['Quotes']=getTopQuotes();
	}
        	break;
        case 'OLTSO':
        if(isPermitted('SalesOrder','index') == "yes")
        {
		require_once('modules/SalesOrder/ListTopSalesOrder.php');
		$home_values['SalesOrder']=getTopSalesOrder();
	}
        	break;
        case 'ILTI':
        if(isPermitted('Invoice','index') == "yes")
        {
		require_once('modules/Invoice/ListTopInvoice.php');
		$home_values['Invoice']=getTopInvoice();
	}
        	break;
	case 'HDB':
	if(isPermitted('Dashboard','index') == "yes")
	{
		$smarty->assign('IS_HOMEDASH','true');
		$home_values['Dashboard']="true";
	}
		break;
	case 'OLTPO':
        if(isPermitted('PurchaseOrder','index') == "yes")
        {
		require_once('modules/PurchaseOrder/ListTopPurchaseOrder.php');
		$home_values['PurchaseOrder']=getTopPurchaseOrder();
	}
		break;
	case 'LTFAQ':
        if(isPermitted('Faq','index') == "yes")
        {
		require_once('modules/Faq/ListFaq.php');
		$home_values['Faq']=getMyFaq();
	}
        	break;

    }
}

	/** Function to get the ActivityType for the given entity id
	 *  @param entityid : Type Integer
	 *  return the activity type for the given id
	 */
function getActivityType($id)
{
	global $adb;
	$quer = "select activitytype from vtiger_activity where activityid=".$id;
	$res = $adb->query($quer);
	$acti_type = $adb->query_result($res,0,"activitytype");
	return $acti_type;

}

global $current_language;
$current_module_strings = return_module_language($current_language, 'Calendar');

$t=Date("Ymd");
$buttoncheck['Calendar'] = isPermitted('Calendar','index');
$smarty->assign("CHECK",$buttoncheck);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP",$app_strings);
$smarty->assign("MOD",$mod_strings);
$smarty->assign("MODULE",'Home');
$smarty->assign("CATEGORY",getParenttab('Home'));
$smarty->assign("HOMEDETAILS",$home_values);
$smarty->assign("HOMEDEFAULTVIEW",DefHomeView());
$smarty->assign("ACTIVITIES",$activities);
$freetag = new freetag();
$smarty->assign("ALL_TAG",$freetag->get_tag_cloud_html("",$current_user->id));
$smarty->display("HomePage.tpl");

	/** Function to get the Tasks assigned to the group for the currentUser 
	 *  This function accepts no arguments
	 * @returns  $group related tasks Array in the following format
	 * $values = Array('Title'=>Array(0=>'image name',
	 *				 1=>'My Group Allocation',
	 *			 	 2=>'home_mygrp'
	 *			 	),
	 *		  'Header'=>Array(0=>'Entity Name',
	 *	  			  1=>'Group Name',
	 *				  2=>'Entity Type'	
	 *			  	),
	 *		  'Entries'=>Array($id=>Array(
	 *			  			0=>$name,
	 *						1=>$groupname,
	 *						2=>$entityname
	 *					       ),
	 *				   $id1=>Array(
         *                                               0=>$name1,
         *                                               1=>$groupname1,
	 *						 2=>$entityname1	
         *                                              ),
	 *					|
	 *					|
         *				   $idn=>Array(
         *                                               0=>$namen,
         *                                               1=>$groupnamen,
	 *						 2=>$entitynamen		
         *                                              )	
	 *				  )
	 *
        */
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
		$query = '';
		if(isPermitted('Leads','index') == "yes")
        	{
			//code modified to list the vtiger_groups associates to a user om 21-11-05
			//Get the leads assigned to group
			$query = "select vtiger_leaddetails.leadid as id,vtiger_leaddetails.lastname as name,vtiger_leadgrouprelation.groupname as groupname, 'Leads     ' as Type from vtiger_leaddetails inner join vtiger_leadgrouprelation on vtiger_leaddetails.leadid=vtiger_leadgrouprelation.leadid inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_leaddetails.leadid inner join vtiger_groups on vtiger_leadgrouprelation.groupname=vtiger_groups.groupname where  vtiger_crmentity.deleted=0  and vtiger_leadgrouprelation.groupname is not null and vtiger_groups.groupid in (".$groupids.")";
		}
		if(isPermitted('Calendar','index') == "yes")
        	{
			if($query !='')
			$query .= " union all ";
			//Get the activities assigned to group
			$query .= "select vtiger_activity.activityid id,vtiger_activity.subject as name,vtiger_activitygrouprelation.groupname,'Activities' as Type from vtiger_activity inner join vtiger_activitygrouprelation on vtiger_activitygrouprelation.activityid=vtiger_activity.activityid inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_activity.activityid inner join vtiger_groups on vtiger_activitygrouprelation.groupname=vtiger_groups.groupname where  vtiger_crmentity.deleted=0 and ((vtiger_activity.eventstatus !='held'and (vtiger_activity.status is null or vtiger_activity.status ='')) or (vtiger_activity.status !='completed' and (vtiger_activity.eventstatus is null or vtiger_activity.eventstatus=''))) and vtiger_activitygrouprelation.groupname is not null and vtiger_groups.groupid in (".$groupids.")";
		}
		if(isPermitted('HelpDesk','index') == "yes")
                {
			if($query !='')
			$query .= " union all ";
			//Get the tickets assigned to group (status not Closed -- hardcoded value)
			$query .= "select vtiger_troubletickets.ticketid,vtiger_troubletickets.title as name,vtiger_ticketgrouprelation.groupname,'Tickets   ' as Type from vtiger_troubletickets inner join vtiger_ticketgrouprelation on vtiger_ticketgrouprelation.ticketid=vtiger_troubletickets.ticketid inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_troubletickets.ticketid inner join vtiger_groups on vtiger_ticketgrouprelation.groupname=vtiger_groups.groupname where vtiger_crmentity.deleted=0 and vtiger_troubletickets.status != 'Closed' and vtiger_ticketgrouprelation.groupname is not null and vtiger_groups.groupid in (".$groupids.")";

		}
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
				$row["type"]=trim($row["type"]);
				if($row["type"] == "Tickets")
				{	
					$list = '<a href=index.php?module=HelpDesk';
					$list .= '&action=DetailView&record='.$row["id"].'>'.$row["name"].'</a>';
				}
				elseif($row["type"] == "Activities")
				{
					$row["type"] = 'Calendar';
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
					$list .= '&action=DetailView&record='.$row["id"].'>'.$row["name"].'</a>';
				}
				else
				{
					$list = '<a href=index.php?module='.$row["type"];
					$list .= '&action=DetailView&record='.$row["id"].'>'.$row["name"].'</a>';
				}

				$value[]=$list;	
				$value[]= $row["groupname"];
				$value[]= $row["type"];
				$entries[$row["id"]]=$value;	
				$i++;
			}
		}

		$values=Array('Title'=>$title,'Header'=>$header,'Entries'=>$entries);
		if(count($entries)>0)	
			return $values;
		} 
}
?>
