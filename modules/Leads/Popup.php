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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Leads/Popup.php,v 1.4 2005/03/04 08:25:49 jack Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Leads/Lead.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/database/PearDatabase.php');
#require_once('include/listview.php');
require_once('include/ComboUtil.php');

global $app_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Leads');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('contact_list');

global $currentModule;
global $theme;

$popuptype = '';
$popuptype = $_REQUEST["popuptype"];
// Get _dom arrays from Database
$comboFieldNames = Array('leadsource'=>'leadsource_dom'
                      ,'leadstatus'=>'leadstatus_dom'
                      ,'rating'=>'rating_dom'
                      ,'industry'=>'industry_dom');
$comboFieldArray = getComboArray($comboFieldNames);

if (!isset($where)) $where = "";

$seedLead = new Lead();

if(isset($_REQUEST['query']))
{
	// we have a query
	if (isset($_REQUEST['first_name'])) $first_name = $_REQUEST['first_name'];
	if (isset($_REQUEST['last_name'])) $last_name = $_REQUEST['last_name'];
	if (isset($_REQUEST['company'])) $company = $_REQUEST['company'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];

	
	$where_clauses = Array();

	if(isset($last_name) && $last_name != "") array_push($where_clauses, "leaddetails.lastname like '$last_name%'");
	if(isset($first_name) && $first_name != "")	array_push($where_clauses, "leaddetails.firstname like '$first_name%'");
	if(isset($company) && $company != "")	array_push($where_clauses, "leaddetails.company like '$company%'");
	if(isset($current_user_only) && $current_user_only != "") array_push($where_clauses, "crmentity.smownerid='$current_user->id'");
	if(isset($assigned_user_id) && $assigned_user_id != "") array_push($where_clauses, "crmentity.smownerid = '$assigned_user_id'");
	
	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");

}

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Leads/PopupSearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	$search_form->assign("POPUPTYPE",$popuptype);

	if (isset($first_name)) $search_form->assign("FIRST_NAME", $_REQUEST['first_name']);
	if (isset($last_name)) $search_form->assign("LAST_NAME", $_REQUEST['last_name']);
	if (isset($company)) $search_form->assign("COMPANY", $_REQUEST['company']);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	
	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);

	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");
	
	$search_form->parse("main");
	$search_form->out("main");

	echo get_form_footer();
	echo "\n<BR>\n";
}
/*
$ListView = new ListView();
$ListView->initNewXTemplate('modules/Leads/ListView.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE']);
$ListView->setQuery($where, "", "first_name, last_name", "LEAD");
$ListView->processListView($seedLead, "main", "LEAD");

#listView($current_module_strings['LBL_LIST_FORM_TITLE'] , "LEAD", 'modules/Leads/ListView.html', $seedLead, "first_name, last_name");
*/

$focus = new Lead();

echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],'', false);
$xtpl=new XTemplate ('modules/Leads/Popup.html');
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);
$xtpl->assign("THEME_PATH",$theme_path);

//Retreive the list from Database
$query = getListQuery("Leads");

if(isset($where) && $where != '')
{
        $query .= ' and '.$where;
}

$list_result = $adb->query($query);

//Retreiving the no of rows
$noofrows = $adb->num_rows($list_result);
//Retreiving the start value from request
if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
{
        $start = $_REQUEST['start'];
}
else
{

        $start = 1;
}
//Retreive the Navigation array
$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);

//Retreive the List View Table Header
$focus->list_mode="search";
$focus->popup_type=$popuptype;

$listview_header = getSearchListViewHeader($focus,"Leads");
$xtpl->assign("LISTHEADER", $listview_header);

//creating a variable to store the relationship requesting record id 
$record_id = $_REQUEST['recordid'];
$focus->record_id = $record_id;
$listview_entries = getSearchListViewEntries($focus,"Leads",$list_result,$navigation_array);
$xtpl->assign("LISTENTITY", $listview_entries);
$query_val = 'false';

if(isset($navigation_array['start']))
{
        $startoutput = '<a href="index.php?action=Popup&module=Leads&start='.$navigation_array['start'].'&query='.$query_val.'&popuptype='.$popuptype.'"><b>Start</b></a>';
}
else
{
        $startoutput = '[ Start ]';
}
if(isset($navigation_array['end']))
{
        $endoutput = '<a href="index.php?action=Popup&module=Leads&start='.$navigation_array['end'].'&query='.$query_val.'&popuptype='.$popuptype.'"><b>End</b></a>';
}
else
{
        $endoutput = '[ End ]';
}
if(isset($navigation_array['next']))
{
        $nextoutput = '<a href="index.php?action=Popup&module=Leads&start='.$navigation_array['next'].'&query='.$query_val.'&popuptype='.$popuptype.'"><b>Next</b></a>';
}
else
{
        $nextoutput = '[ Next ]';
}
if(isset($navigation_array['prev']))
{
        $prevoutput = '<a href="index.php?action=Popup&module=Leads&start='.$navigation_array['prev'].'&query='.$query_val.'&popuptype='.$popuptype.'"><b>Prev</b></a>';
}
else
{
        $prevoutput = '[ Prev ]';
}
$xtpl->assign("Start", $startoutput);
$xtpl->assign("End", $endoutput);
$xtpl->assign("Next", $nextoutput);
$xtpl->assign("Prev", $prevoutput);

$xtpl->parse("main");
$xtpl->out("main");

?>
