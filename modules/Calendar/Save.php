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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Activities/Save.php,v 1.11 2005/04/18 10:37:49 samk Exp $
 * Description:  Saves an Account record and then redirects the browser to the 
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Calendar/Activity.php');
require_once('include/logging.php');
//require("modules/Emails/class.phpmailer.php");
require_once("config.php");
require_once('include/database/PearDatabase.php');
require_once('modules/Calendar/CalendarCommon.php');
require_once('data/CRMEntity.php');
global $adb;
$local_log =& LoggerManager::getLogger('index');
$focus = new Activity();
$activity_mode = $_REQUEST['activity_mode'];
$tab_type = 'Calendar';
$focus->column_fields["activitytype"] = 'Task';
if(isset($_REQUEST['record']))
{
	$focus->id = $_REQUEST['record'];
	$local_log->debug("id is ".$id);
}
if(isset($_REQUEST['mode']))
{
	$focus->mode = $_REQUEST['mode'];
}

if((isset($_REQUEST['change_status']) && $_REQUEST['change_status']) && ($_REQUEST['status']!='' || $_REQUEST['eventstatus']!=''))
{
	$status ='';
	$activity_type='';
	$return_id = $focus->id;
	if(isset($_REQUEST['status']))
	{
		$status = $_REQUEST['status'];	
		$activity_type = "Task";	
	}
	elseif(isset($_REQUEST['eventstatus']))
	{
		$status = $_REQUEST['eventstatus'];	
		$activity_type = "Events";	
	}
	
	ChangeStatus($status,$return_id,$activity_type);
}
else
{
	foreach($focus->column_fields as $fieldname => $val)
	{
		if(isset($_REQUEST[$fieldname]))
		{
			$value = $_REQUEST[$fieldname];
			$focus->column_fields[$fieldname] = $value;
			if(($fieldname == 'notime') && ($focus->column_fields[$fieldname]))
			{	
				$focus->column_fields['time_start'] = '';
				$focus->column_fields['duration_hours'] = '';
				$focus->column_fields['duration_minutes'] = '';
			}	
			if(($fieldname == 'recurringtype') && ! isset($_REQUEST['recurringcheck']))
				$focus->column_fields['recurringtype'] = '--None--';
		}
	}
	if(isset($_REQUEST['visibility']) && $_REQUEST['visibility']!= '')
	        $focus->column_fields['visibility'] = $_REQUEST['visibility'];
	else
	        $focus->column_fields['visibility'] = 'Private';
	$focus->save($tab_type);
	/* For Followup START -- by Minnie */
	if(isset($_REQUEST['followup']) && $_REQUEST['followup'] == 'on' && $activity_mode == 'Events' && isset($_REQUEST['followup_time_start']) &&  $_REQUEST['followup_time_start'] != '')
	{
		$focus->column_fields['subject'] = '[Followup] '.$focus->column_fields['subject'];
		$focus->column_fields['date_start'] = $_REQUEST['followup_date'];
		$focus->column_fields['due_date'] = $_REQUEST['followup_date'];
		$focus->column_fields['time_start'] = $_REQUEST['followup_time_start'];
		$focus->column_fields['time_end'] = $_REQUEST['followup_time_end'];
		$focus->column_fields['eventstatus'] = 'Planned';
		$focus->mode = 'create';
		$focus->save($tab_type);

	}
	/* For Followup END -- by Minnie */
	$return_id = $focus->id;
}

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") 
	$return_module = $_REQUEST['return_module'];
else 
	$return_module = "Calendar";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") 
	$return_action = $_REQUEST['return_action'];
else 
	$return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") 
	$return_id = $_REQUEST['return_id'];

if($_REQUEST['mode'] != 'edit' && $_REQUEST['return_module'] == 'Products')
{
	if($_REQUEST['product_id'] != '')
		$crmid = $_REQUEST['product_id'];
	if($crmid != $_REQUEST['parent_id'])
	{
		$sql = "insert into vtiger_seactivityrel (activityid, crmid) values('".$focus->id."','".$crmid."')";
		$adb->query($sql);
	}
}
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] == "Contacts" && $_REQUEST['activity_mode'] == 'Events')
{
	        if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "")
	        {
	                $sql = "insert into vtiger_cntactivityrel values (".$_REQUEST['return_id'].",".$focus->id.")";
	                $adb->query($sql);
	        }
}
									
									
$activemode = "";
if($activity_mode != '') 
	$activemode = "&activity_mode=".$activity_mode;

//Added code to send mail to the assigned to user about the details of the vtiger_activity if sendnotification = on and assigned to user
if($_REQUEST['sendnotification'] == 'on')
{
	global $current_user;
	$local_log->info("send notification is on");
        require_once("modules/Emails/mail.php");
	$subject = $_REQUEST['activity_mode'].' : '.$_REQUEST['subject'];

	$crmentity = new CRMEntity();
	if($_REQUEST['assigntype'] == 'U')
	{
	        $to_email[0] = getUserEmailId('id',$_REQUEST['assigned_user_id']);
		$description = getActivityDetails($_REQUEST['description']);
		$mail_status  = send_mail('Calendar',$to_email,$current_user->user_name,'',$subject,$description);
	}
	//code added to send mail to group
	if($_REQUEST['assigntype'] == 'T')
	{
		$groupname=$_REQUEST['assigned_group_name'];
		$resultqry=$adb->query("select groupid from vtiger_groups where groupname='".$groupname."'");
		$groupid=$adb->query_result($resultqry,0,"groupid");
		 require_once('include/utils/GetGroupUsers.php');
		 $getGroupObj=new GetGroupUsers();
		 $getGroupObj->getAllUsersInGroup($groupid);
		 $userIds=$getGroupObj->group_users;
		 $groupqry="select email1,id from vtiger_users where id in(".implode(',',$userIds).")";
		 $groupqry_res=$adb->query($groupqry);
		 $noOfRows = $adb->num_rows($groupqry_res);
		 for($z=0;$z < $noOfRows;$z++)
		 {
			 $emailadd = $adb->query_result($groupqry_res,$z,'email1');
			 $curr_userid = $adb->query_result($groupqry_res,$z,'id');
			 $description = getActivityDetails($_REQUEST['description'],$curr_userid);
			 $mail_status = send_mail('Calendar',$emailadd,getUserName($curr_userid),'',$subject,$description);

		 }


	}

}


//code added to send mail to the vtiger_invitees
if(isset($_REQUEST['inviteesid']) && $_REQUEST['inviteesid']!='')
{
	global $current_user;
	$local_log->info("send notification is on");
	require_once("modules/Emails/mail.php");
	$selected_users_string =  $_REQUEST['inviteesid'];
	$invitees_array = explode(';',$selected_users_string);
	$subject = $_REQUEST['activity_mode'].' : '.$_REQUEST['subject'];
	$record = $focus->id;
	foreach($invitees_array as $inviteeid)
	{
		if($inviteeid != '')
		{
			$description=getActivityDetails($_REQUEST['description'],$inviteeid);
			$to_email = getUserEmailId('id',$inviteeid);
			$mail_status  = send_mail('Calendar',$to_email,$current_user->user_name,'',$subject,$description);
		}
	}
}

if(isset($_REQUEST['contactidlist']) && $_REQUEST['contactidlist'] != '')
{
	//split the string and store in an array
	$storearray = explode (";",$_REQUEST['contactidlist']);
	foreach($storearray as $id)
	{
		if($id != '')
		{
			$record = $focus->id;
			$sql = "insert into vtiger_cntactivityrel values (".$id.",".$record.")";
			$adb->query($sql);
		}
	}
}


if(isset($_REQUEST['view']) && $_REQUEST['view']!='')
	$view=$_REQUEST['view'];
if(isset($_REQUEST['hour']) && $_REQUEST['hour']!='')
	$hour=$_REQUEST['hour'];
if(isset($_REQUEST['day']) && $_REQUEST['day']!='')
	$day=$_REQUEST['day'];
if(isset($_REQUEST['month']) && $_REQUEST['month']!='')
	$month=$_REQUEST['month'];
if(isset($_REQUEST['year']) && $_REQUEST['year']!='') 
	$year=$_REQUEST['year'];
if(isset($_REQUEST['viewOption']) && $_REQUEST['viewOption']!='') 
	$viewOption=$_REQUEST['viewOption'];
if(isset($_REQUEST['subtab']) && $_REQUEST['subtab']!='') 
	$subtab=$_REQUEST['subtab'];

//code added for returning back to the current view after edit from list view
if($_REQUEST['return_viewname'] == '') 
	$return_viewname='0';
if($_REQUEST['return_viewname'] != '')
	$return_viewname=$_REQUEST['return_viewname'];
if($_REQUEST['parenttab'] != '')
	$parenttab=$_REQUEST['parenttab'];
if($_REQUEST['start'] !='')
	$page='&start='.$_REQUEST['start'];
if($_REQUEST['maintab'] == 'Calendar')
	header("Location: index.php?action=".$return_action."&module=".$return_module."&view=".$view."&hour=".$hour."&day=".$day."&month=".$month."&year=".$year."&record=".$return_id."&viewOption=".$viewOption."&subtab=".$subtab."&parenttab=$parenttab");
else
	header("Location: index.php?action=$return_action&module=$return_module$view$hour$day$month$year&record=$return_id$activemode&viewname=$return_viewname$page&parenttab=$parenttab");

?>
