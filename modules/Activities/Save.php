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

require_once('modules/Activities/Activity.php');
require_once('include/logging.php');
//require("modules/Emails/class.phpmailer.php");
require_once("config.php");
require_once('include/database/PearDatabase.php');

$local_log =& LoggerManager::getLogger('index');
global $vtlog;
if($_REQUEST['sendnotification'] == 'on')
{
$vtlog->logthis("send notification is on",'info');  
	include("modules/Emails/send_mail.php");
	send_mail('users',$_REQUEST['assigned_user_id'],$current_user->user_name,$_REQUEST['subject'],$_REQUEST['description'],$mail_server,$mail_server_username,$mail_server_password,$filename);
}

$focus = new Activity();

$activity_mode = $_REQUEST['activity_mode'];
if($activity_mode == 'Task')
{
        $tab_type = 'Activities';
	$focus->column_fields["activitytype"] = 'Task';
}
elseif($activity_mode == 'Events')
{
        $tab_type = 'Events';
}


if(isset($_REQUEST['record']))
{
	$focus->id = $_REQUEST['record'];
$vtlog->logthis("id is ".$id,'debug');  	
}
if(isset($_REQUEST['mode']))
{
	$focus->mode = $_REQUEST['mode'];
}

//$focus->retrieve($_REQUEST['record']);
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
		}
		
	}

	//print_r($focus->column_fields);

	//$focus->saveentity($tab_type);
	$focus->save($tab_type);
	$return_id = $focus->id;
}
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Activities";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

if($_REQUEST['mode'] != 'edit' && (($_REQUEST['return_module'] == 'HelpDesk') || ($_REQUEST['return_module']== 'Products')  ))
{
	if($_REQUEST['ticket_id'] != '')
		$crmid = $_REQUEST['ticket_id'];
	if($_REQUEST['product_id'] != '')
		$crmid = $_REQUEST['product_id'];
	if($crmid != $_REQUEST['parent_id'])
	{
		$sql = "insert into seactivityrel (activityid, crmid) values('".$focus->id."','".$crmid."')";
		$adb->query($sql);
	}
}

$activemode = "";
if($activity_mode != '') $activemode = "&activity_mode=".$activity_mode;

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id$activemode");
?>
