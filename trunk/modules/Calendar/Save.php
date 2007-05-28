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
global $adb;
$local_log =& LoggerManager::getLogger('index');
$focus = new Activity();
$activity_mode = $_REQUEST['activity_mode'];
if($activity_mode == 'Task')
{
        $tab_type = 'Calendar';
	$focus->column_fields["activitytype"] = 'Task';
}
elseif($activity_mode == 'Events')
{
        $tab_type = 'Events';
}


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
	$return_id = $focus->id;
}
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Calendar";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

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
if($activity_mode != '') $activemode = "&activity_mode=".$activity_mode;

//Added code to send mail to the assigned to user about the details of the vtiger_activity if sendnotification = on and assigned to user
if($_REQUEST['sendnotification'] == 'on' && $_REQUEST['assigntype'] == 'U')
{
	global $current_user;
	$local_log->info("send notification is on");
        require_once("modules/Emails/mail.php");
        $to_email = getUserEmailId('id',$_REQUEST['assigned_user_id']);

	$subject = $_REQUEST['activity_mode'].' : '.$_REQUEST['subject'];
	$description = getActivityDetails($_REQUEST['description']);

        $mail_status  = send_mail('Calendar',$to_email,$current_user->user_name,'',$subject,$description);
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
	$description = getActivityDetails($_REQUEST['description']);
	foreach($invitees_array as $inviteeid)
	{
		if($inviteeid != '')
		{
			$to_email = getUserEmailId('id',$inviteeid);
			$mail_status  = send_mail('Calendar',$to_email,$current_user->user_name,'',$subject,$description);
			$record = $focus->id;
			$sql = "insert into vtiger_salesmanactivityrel values (".$inviteeid.",".$record.")";
			$adb->query($sql);
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


if(isset($_REQUEST['view']) && $_REQUEST['view']!='') $view=$_REQUEST['view'];
if(isset($_REQUEST['hour']) && $_REQUEST['hour']!='') $hour=$_REQUEST['hour'];
if(isset($_REQUEST['day']) && $_REQUEST['day']!='') $day=$_REQUEST['day'];
if(isset($_REQUEST['month']) && $_REQUEST['month']!='') $month=$_REQUEST['month'];
if(isset($_REQUEST['year']) && $_REQUEST['year']!='') $year=$_REQUEST['year'];
if(isset($_REQUEST['viewOption']) && $_REQUEST['viewOption']!='') $viewOption=$_REQUEST['viewOption'];
if(isset($_REQUEST['subtab']) && $_REQUEST['subtab']!='') $subtab=$_REQUEST['subtab'];

//code added for returning back to the current view after edit from list view
if($_REQUEST['return_viewname'] == '') $return_viewname='0';
if($_REQUEST['return_viewname'] != '')$return_viewname=$_REQUEST['return_viewname'];
if($_REQUEST['parenttab'] != '')$parenttab=$_REQUEST['parenttab'];
if($_REQUEST['start'] !='')$page='&start='.$_REQUEST['start'];
if($_REQUEST['maintab'] == 'Calendar')
	header("Location: index.php?action=".$return_action."&module=".$return_module."&view=".$view."&hour=".$hour."&day=".$day."&month=".$month."&year=".$year."&record=".$return_id."&viewOption=".$viewOption."&subtab=".$subtab."&parenttab=$parenttab");
else
	header("Location: index.php?action=$return_action&module=$return_module$view$hour$day$month$year&record=$return_id$activemode&viewname=$return_viewname$page&parenttab=$parenttab");

/**
 * Function to get the vtiger_activity details for mail body
 * @param   string   $description       - activity description
 * return   string   $list              - HTML in string format
 */
function getActivityDetails($description)
{
	global $log;
	$log->debug("Entering getActivityDetails(".$description.") method ...");
	global $adb;

	$reply = (($_REQUEST['mode'] == 'edit')?'Replied':'Created');
	$name = getUserName($_REQUEST['assigned_user_id']);
	$status = (($_REQUEST['activity_mode']=='Task')?($_REQUEST['taskstatus']):($_REQUEST['eventstatus']));

	$list = 'Dear '.$name.',';
	$list .= '<br><br> There is an vtiger_activity('.$_REQUEST['activity_mode'].')'.$reply.'. The details are :';
	$list .= '<br>Subject : '.$_REQUEST['subject'];
	$list .= '<br>Status : '.$status;
	$list .= '<br>Priority : '.$_REQUEST['taskpriority'];
	$list .= '<br>Related to : '.$_REQUEST['parent_name'];
	$list .= '<br>Contact : '.$_REQUEST['contact_name'];
	$list .= '<br><br> Description : '.$description;

	$log->debug("Exiting getActivityDetails method ...");
	return $list;
}
?>
