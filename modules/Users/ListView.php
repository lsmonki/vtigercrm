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
 * $Header:  vtiger_crm/modules/Users/ListView.php,v 1.1 2004/08/17 15:06:40 gjk Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Users/User.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');

global $app_strings;
global $app_list_strings;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'Users');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('user_list');

global $currentModule;

global $theme;

if (!isset($where)) $where = "";

$seedUser = new User();
if(isset($_REQUEST['query']))
{
	// we have a query
	if (isset($_REQUEST['first_name'])) $first_name = $_REQUEST['first_name'];
	if (isset($_REQUEST['last_name'])) $last_name = $_REQUEST['last_name'];
	if (isset($_REQUEST['user_name'])) $user_name = $_REQUEST['user_name'];
	if (isset($_REQUEST['phone'])) $phone = $_REQUEST['phone'];
	if (isset($_REQUEST['department'])) $department = $_REQUEST['department'];
	if (isset($_REQUEST['status'])) $status = $_REQUEST['status'];
	if (isset($_REQUEST['is_admin'])) $is_admin = $_REQUEST['is_admin'];
	if (isset($_REQUEST['email'])) $email = $_REQUEST['email'];
	if (isset($_REQUEST['yahoo_id'])) $yahoo_id = $_REQUEST['yahoo_id'];
	if (isset($_REQUEST['address_street'])) $address_street = $_REQUEST['address_street'];
	if (isset($_REQUEST['address_city'])) $address_city = $_REQUEST['address_city'];
	if (isset($_REQUEST['address_state'])) $address_state = $_REQUEST['address_state'];
	if (isset($_REQUEST['address_postalcode'])) $address_postalcode = $_REQUEST['address_postalcode'];
	if (isset($_REQUEST['address_country'])) $address_country = $_REQUEST['address_country'];

	
	$where_clauses = Array();

	if(isset($last_name) && $last_name != "") array_push($where_clauses, "last_name like '$last_name%'");
	if(isset($first_name) && $first_name != "") array_push($where_clauses, "first_name like '$first_name%'");
	if(isset($user_name) && $user_name != "") array_push($where_clauses, "user_name like '$user_name%'");
	if(isset($status) && $status != "") array_push($where_clauses, "status = '$status'");
	if(isset($is_admin) && $is_admin != "") array_push($where_clauses, "is_admin = '$is_admin'");
	if(isset($phone) && $phone != "") array_push($where_clauses, "(phone_home like '%$phone%' OR phone_mobile like '%$phone%' OR phone_work like '%$phone%' OR phone_other like '%$phone%' OR phone_fax like '%$phone%')");
	if(isset($email) && $email != "") array_push($where_clauses, "(users.email1 like '$email%' OR users.email2 like '$email%')");
	if(isset($yahoo_id) && $yahoo_id != "") array_push($where_clauses, "yahoo_id like '$yahoo_id%'");
	if(isset($department) && $department != "") array_push($where_clauses, "department like '$department%'");
	if(isset($title) && $title != "") array_push($where_clauses, "title like '$title%'");
	if(isset($address_street) && $address_street != "") array_push($where_clauses, "address_street like '$address_street%'");
	if(isset($address_city) && $address_city != "") array_push($where_clauses, "address_city like '$address_city%'");
	if(isset($address_state) && $address_state != "") array_push($where_clauses, "address_state like '$address_state%'");
	if(isset($address_postalcode) && $address_postalcode != "") array_push($where_clauses, "address_postalcode like '$address_postalcode%'");
	if(isset($address_country) && $address_country != "") array_push($where_clauses, "address_country like '$address_country%'");
	
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
	$search_form=new XTemplate ('modules/Users/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	
	if (isset($first_name)) $search_form->assign("FIRST_NAME", $_REQUEST['first_name']);
	if (isset($last_name)) $search_form->assign("LAST_NAME", $_REQUEST['last_name']);
	if (isset($companyName)) $search_form->assign("USER_NAME", $_REQUEST['user_name']);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	
	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);
	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') { 
		if(isset($title)) $search_form->assign("TITLE", $title);
		if(isset($phone)) $search_form->assign("PHONE", $phone);
		if(isset($email)) $search_form->assign("EMAIL", $email);
		if(isset($yahoo_id)) $search_form->assign("YAHOO_ID", $yahoo_id);
		if(isset($is_admin)) $search_form->assign("IS_ADMIN", 'checked');
		if(isset($department)) $search_form->assign("DEPARTMENT", $department);
		if(isset($address_street)) $search_form->assign("ADDRESS_STREET", $address_street);
		if(isset($address_city)) $search_form->assign("ADDRESS_CITY", $address_city);
		if(isset($address_state)) $search_form->assign("ADDRESS_STATE", $address_state);
		if(isset($address_postalcode)) $search_form->assign("ADDRESS_POSTALCODE", $address_postalcode);
		if(isset($address_country)) $search_form->assign("ADDRESS_COUNTRY", $address_country);

		$user_status_dom = & $app_list_strings['user_status_dom'];
		array_unshift($user_status_dom, '');
		if (isset($status)) $search_form->assign("STATUS_OPTIONS", get_select_options($user_status_dom, $status));
		else $search_form->assign("STATUS_OPTIONS", get_select_options($user_status_dom, ''));

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

$list_form=new XTemplate ('modules/Users/ListView.html');
$list_form->assign("MOD", $current_module_strings);
$list_form->assign("APP", $app_strings);

$list_form->assign("THEME", $theme);
$list_form->assign("IMAGE_PATH", $image_path);
$list_form->assign("MODULE_NAME", $currentModule);

$current_offset = 0;
if(isset($_REQUEST['current_offset']))
    $current_offset = $_REQUEST['current_offset'];

$response = $seedUser->get_list("first_name, last_name", $where, $current_offset);

$userList = $response['list'];
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
$base_URL = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'?'.$_SERVER['QUERY_STRING']."&current_offset=";
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
$log->info("Start/end records ($start_record, ".($end_record-1).")");

$list_form->assign("START_RECORD", $start_record);
$list_form->assign("END_RECORD", $end_record-1);
$list_form->assign("ROW_COUNT", $row_count);
if ($start_link !== "") $list_form->assign("START_LINK", "[ ".$start_link." ]");
if ($end_link !== "") $list_form->assign("END_LINK", "[ ".$end_link." ]");
if ($next_link !== "") $list_form->assign("NEXT_LINK", "[ ".$next_link." ]");
if ($previous_link !== "") $list_form->assign("PREVIOUS_LINK", "[ ".$previous_link." ]");


$oddRow = true;
foreach($userList as $user)
{
	$user_fields = array(
		'YAHOO_ID' => $user->yahoo_id,
		'FIRST_NAME' => $user->first_name,
		'LAST_NAME' => $user->last_name,
		'USER_NAME' => $user->user_name,
		'ID' => $user->id,
		'DEPARTMENT' => $user->department,
		'EMAIL1' => $user->email1,
		'PHONE_WORK' => $user->phone_work
	);

	if ($user->is_admin == 'on') $user_fields['IS_ADMIN'] = 'X';
	
	$list_form->assign("USER", $user_fields);
	
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

	// If there is a YMId, parse that row
	if(isset($user->yahoo_id) && $user->yahoo_id != '')
		$list_form->parse("main.row.yahoo_id");
	else
		$list_form->parse("main.row.no_yahoo_id");

	$list_form->parse("main.row");
// Put the rows in.
}

$list_form->parse("main");
$button = "<table cellspacing='0' cellpadding='1' border='0'><form name='EditView' method='POST' action='index.php'>\n";
$button .= "<input type='hidden' name='module' value='Users'>\n";
$button .= "<input type='hidden' name='action' value='EditView'>\n";
$button .= "<input type='hidden' name='return_action' value='ListView'>\n";
$button .= "<input type='hidden' name='return_module' value='Users'>\n";
$button .= "<tr><td><input title='".$current_module_strings['LBL_NEW_USER_BUTTON_TITLE']."' accessyKey='".$current_module_strings['LBL_NEW_USER_BUTTON_KEY']."' class='button' type='submit' name='button' value='".$current_module_strings['LBL_NEW_USER_BUTTON_LABEL']."' ></td></tr>\n";
$button .= "</form></table>\n";
echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'], $button, false);
$list_form->out("main");
echo get_form_footer();

echo "</td></tr>\n</table>\n";

?>
