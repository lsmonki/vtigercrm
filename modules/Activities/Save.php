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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Activities/Save.php,v 1.6 2005/03/02 13:56:52 jack Exp $
 * Description:  Saves an Account record and then redirects the browser to the 
 * defined return URL.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Activities/Activity.php');
require_once('include/logging.php');
require("modules/Emails/class.phpmailer.php");
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
elseif($activity_mode == 'Events')
{
        $tab_type = 'Events';
}


if(isset($_REQUEST['record']))
{
	$focus->id = $_REQUEST['record'];
}
if(isset($_REQUEST['mode']))
{
	$focus->mode = $_REQUEST['mode'];
}

//$focus->retrieve($_REQUEST['record']);
if((isset($_REQUEST['change_status']) && $_REQUEST['change_status']) && $_REQUEST['status']!='')
{
	
	$return_id = $focus->id;
	$status = $_REQUEST['status'];
	ChangeStatus($status,$return_id);
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

	$focus->saveentity($tab_type);
	$return_id = $focus->id;
}
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Activities";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

$activemode = "";
if($activity_mode != '') $activemode = "&activity_mode=".$activity_mode;

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id$activemode");
?>
