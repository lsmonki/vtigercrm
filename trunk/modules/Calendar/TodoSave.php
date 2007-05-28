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
require_once('modules/Calendar/Activity.php');
require_once('include/logging.php');
require_once("config.php");
require_once('include/database/PearDatabase.php');

$local_log =& LoggerManager::getLogger('index');

$focus = new Activity();
$activity_mode = $_REQUEST['activity_mode'];
if($activity_mode == 'Task')
{
        $tab_type = 'Calendar';
        $focus->column_fields["activitytype"] = 'Task';
}

if(isset($_REQUEST['record']))
{
	        $focus->id = $_REQUEST['record'];
}
if(isset($_REQUEST['mode']))
{
        $focus->mode = $_REQUEST['mode'];
}
 $focus->column_fields["subject"] = $_REQUEST["task_subject"];
 $focus->column_fields["time_start"] = $_REQUEST["task_time_start"];
 $focus->column_fields["assigned_user_id"] =  $_REQUEST["task_assigned_user_id"];
 $_REQUEST["assigned_group_name"]  = $_REQUEST['task_assigned_group_name'];
 $_REQUEST['assigntype'] = $_REQUEST['task_assigntype'];
 $focus->column_fields["taskstatus"] =  $_REQUEST["taskstatus"];
 $focus->column_fields["date_start"] =  $_REQUEST["task_date_start"];
 $focus->column_fields["due_date"] =  $_REQUEST["task_due_date"];
 $focus->column_fields["taskpriority"] =  $_REQUEST["taskpriority"];
 $focus->column_fields["parent_id"] = $_REQUEST["task_parent_id"];
 $focus->column_fields["contact_id"] = $_REQUEST["task_contact_id"];
 $focus->column_fields["description"] =  $_REQUEST["task_description"];
 if(isset($_REQUEST['task_sendnotification']) && $_REQUEST['task_sendnotification'] != null)
 	$focus->column_fields["sendnotification"] =  $_REQUEST["task_sendnotification"];

 $focus->save($tab_type);
 header("Location: index.php?action=index&module=Calendar&view=".$_REQUEST['view']."&hour=".$_REQUEST['hour']."&day=".$_REQUEST['day']."&month=".$_REQUEST['month']."&year=".$_REQUEST['year']."&viewOption=".$_REQUEST['viewOption']."&subtab=".$_REQUEST['subtab']."&parenttab=".$_REQUEST['parenttab']);
?>
