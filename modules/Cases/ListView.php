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
 * $Header:  vtiger_crm/modules/Cases/ListView.php,v 1.1 2004/08/17 15:03:56 gjk Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Cases/Case.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');

global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Cases');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('case_list');

global $currentModule;

global $theme;

if (!isset($where)) $where = "";

$seedCase = new aCase();
if(isset($_REQUEST['query']))
{
	// we have a query
	if (isset($_REQUEST['name'])) $name = $_REQUEST['name'];
	if (isset($_REQUEST['account_name'])) $account_name = $_REQUEST['account_name'];
	if (isset($_REQUEST['status'])) $status = $_REQUEST['status'];
	if (isset($_REQUEST['number'])) $number = $_REQUEST['number'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];

	$where_clauses = Array();

	if(isset($name) && $name != "") array_push($where_clauses, "cases.name like '$name%'");
	if(isset($account_name) && $account_name != "") array_push($where_clauses, "accounts.name like '$account_name%'");
	if(isset($status) && $status != "") array_push($where_clauses, "cases.status = '$status'");
	if(isset($number) && $number != "") array_push($where_clauses, "cases.number like '$number%'");
	if(isset($current_user_only) && $current_user_only != "") array_push($where_clauses, "cases.assigned_user_id='$current_user->id'");

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
	$search_form=new XTemplate ('modules/Cases/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	
	if(isset($name)) $search_form->assign("NAME", $name);
	if(isset($account_name)) $search_form->assign("ACCOUNT_NAME", $account_name);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	
	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");
	
	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);
	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') { 
		if (isset($number)) $search_form->assign("NUMBER", $number);

		$case_status_dom = & $app_list_strings['case_status_dom'];
		array_unshift($case_status_dom, '');
		if (isset($status)) $search_form->assign("STATUS_OPTIONS", get_select_options($case_status_dom, $status));
		else $search_form->assign("STATUS_OPTIONS", get_select_options($case_status_dom, ''));

		$search_form->parse("advanced");
		$search_form->out("advanced");
	}
	else {
		$search_form->parse("main");
		$search_form->out("main");
	}
	echo get_form_footer();
	echo "\n<BR>\n";
}

$list_form=new XTemplate ('modules/Cases/ListView.html');
$list_form->assign("MOD", $current_module_strings);
$list_form->assign("APP", $app_strings);
$list_form->assign("THEME", $theme);
$list_form->assign("IMAGE_PATH", $image_path);
$list_form->assign("MODULE_NAME", $currentModule);

$current_offset = 0;
if(isset($_REQUEST['current_offset']))
    $current_offset = $_REQUEST['current_offset'];

$response = $seedCase->get_list("name", $where, $current_offset);

$caseList = $response['list'];
$row_count = $response['row_count'];
$next_offset = $response['next_offset'];
$previous_offset = $response['previous_offset'];

$start_record = $current_offset + 1;

// Set the start row to 0 if there are no rows (adding one looks bad)
if($row_count == 0)
    $start_record = 0;

$end_record = $start_record + $list_max_entries_per_page;

// back up the the last page.
if($end_record > $row_count+1)
{
    $end_record = $row_count+1;
}

// Deterime the start location of the last page
if($row_count == 0)
	$number_pages = 0;
else
	$number_pages = floor(($row_count - 1) / $list_max_entries_per_page);

$last_page_offset = $number_pages * $list_max_entries_per_page;


// Create the base URL without the current offset.
// Check to see if the current offset is already there
// If not, add it to the end.

// All of the other values should use a regular expression search
$base_URL = $_SERVER['REQUEST_URI'].'?'.$_SERVER['QUERY_STRING']."&current_offset=";
$start_URL = $base_URL."0";
$previous_URL  = $base_URL.$previous_offset;
$next_URL  = $base_URL.$next_offset;
$end_URL  = $base_URL.$last_page_offset;

$sort_URL_base = $base_URL.$current_offset."&sort_order=";

$log->debug("Offsets: (start, previous, next, last)(0, $previous_offset, $next_offset, $last_page_offset)");

if(0 == $current_offset)
    $start_link = $app_strings['LNK_LIST_START'];
else
    $start_link = "<a href=\"$start_URL\" class=\"listFormHeaderLinks\">".$app_strings['LNK_LIST_START']."</a>";

if($previous_offset < 0)
    $previous_link = $app_strings['LNK_LIST_PREVIOUS'];
else
    $previous_link = "<a href=\"$previous_URL\" class=\"listFormHeaderLinks\">".$app_strings['LNK_LIST_PREVIOUS']."</a>";

if($next_offset >= $end_record)
    $next_link = $app_strings['LNK_LIST_NEXT'];
else
    $next_link = "<a href=\"$next_URL\" class=\"listFormHeaderLinks\">".$app_strings['LNK_LIST_NEXT']."</a>";

if($last_page_offset <= $current_offset)
    $end_link = $app_strings['LNK_LIST_END'];
else
    $end_link = "<a href=\"$end_URL\" class=\"listFormHeaderLinks\">".$app_strings['LNK_LIST_END']."</a>";

$log->info("Offset (next, current, prev)($next_offset, $current_offset, $previous_offset)");
$log->info("Start/end records ($start_record, $end_record)");

$list_form->assign("START_RECORD", $start_record);
$list_form->assign("END_RECORD", $end_record-1);
$list_form->assign("ROW_COUNT", $row_count);
if ($start_link !== "") $list_form->assign("START_LINK", "[ ".$start_link." ]");
if ($end_link !== "") $list_form->assign("END_LINK", "[ ".$end_link." ]");
if ($next_link !== "") $list_form->assign("NEXT_LINK", "[ ".$next_link." ]");
if ($previous_link !== "") $list_form->assign("PREVIOUS_LINK", "[ ".$previous_link." ]");
$list_form->parse("main.list_nav_row");


$oddRow = true;
foreach($caseList as $case)
{
	$case_fields = array(
		'NUMBER' => $case->number,
		'ACCOUNT_NAME' => $case->account_name,
		'ACCOUNT_ID' => $case->account_id,
		'ID' => $case->id,
		'NAME' => $case->name,
		'STATUS' => $app_list_strings['case_status_dom'][$case->status],
		'ASSIGNED_USER_NAME' => $case->assigned_user_name
	);
	
	$list_form->assign("CASE", $case_fields);
	
	if($oddRow)
    {
        //todo move to themes
		$list_form->assign("ROW_COLOR", 'oddListRow');
    }
    else
    {
        //todo move to themes
		$list_form->assign("ROW_COLOR", 'evenListRow');
    }
    $oddRow = !$oddRow;

	$list_form->parse("main.row");
// Put the rows in.
}

$list_form->parse("main");

echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'], "", false);
$list_form->out("main");
echo get_form_footer();

echo "</td></tr>\n</table>\n";

?>
