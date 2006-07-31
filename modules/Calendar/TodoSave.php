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
require_once('modules/Activities/Activity.php');
require_once('include/logging.php');
require_once("config.php");
require_once('include/database/PearDatabase.php');

$local_log =& LoggerManager::getLogger('index');

$focus = new Activity();
$activity_mode = $_REQUEST['activity_mode'];
if($activity_mode == 'Task')
{
        $tab_type = 'Activities';
        $focus->column_fields["activitytype"] = 'Task';
}
 $focus->column_fields["subject"] = $_REQUEST["task_subject"];
 $focus->column_fields["time_start"] = $_REQUEST["task_time_start"];
 $focus->column_fields["assigned_user_id"] =  $_REQUEST["assigned_user_id"];
 $focus->column_fields["taskstatus"] =  $_REQUEST["taskstatus"];
 $focus->column_fields["date_start"] =  $_REQUEST["task_date_start"];
 $focus->save($tab_type);
 header("Location: index.php?action=index&module=Calendar&view=".$_REQUEST['view']."&hour=".$_REQUEST['hour']."&day=".$_REQUEST['day']."&month=".$_REQUEST['month']."&year=".$_REQUEST['year']."&viewOption=".$_REQUEST['viewBox']."&subtab=".$_REQUEST['subtab']."&parenttab=".$_REQUEST['parenttab']);
?>
