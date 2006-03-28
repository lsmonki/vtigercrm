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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Activities/GroupAllocationView.php,v 1.1 2005/02/21 15:49:47 jack Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Activities/Activity.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');

global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Activities');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('group_task_list');

global $currentModule;

global $image_path;
global $theme;

// focus_list is the means of passing data to a ListView.
global $focus_list;

if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];


//get a list of all the group allocated tasks/calls/leads
if(isset($_REQUEST['query']))
{

  $query = "select leads.last_name,leadgrouprelation.groupname from leads inner join leadgrouprelation on leads.id=leadgrouprelation.leadid where leadgrouprelation.deleted=0  and leads.deleted=0";
  $log->info("Here is the where clause for the list view: $query");

}


$title_display = $current_module_strings['LBL_GROUP_ALLOCATION_TITLE'];
$ListView = new ListView();
$ListView->initNewXTemplate('modules/Activities/GroupAllocationView.html',$current_module_strings);
$ListView->setCurrentModule("Home");
$ListView->setHeaderTitle($title_display);


$ListView->processListView($seedActivity, "main", "TASK");
