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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Emails/ListView.php,v 1.10 2005/03/21 07:21:48 samk Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Emails/Email.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/uifromdbutil.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;

global $list_max_entries_per_page;
global $urlPrefix;

$current_module_strings = return_module_language($current_language, 'Emails');
$log = LoggerManager::getLogger('email_list');

global $currentModule;

global $image_path;
global $theme;

$url_string = ''; // assigning http url string
$sorder = 'ASC';  // Default sort order
if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
$sorder = $_REQUEST['sorder'];

// focus_list is the means of passing data to a ListView.
global $focus_list;

if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Emails/SearchForm.html');
	$search_form->assign("MOD", $mod_strings);
	$search_form->assign("APP", $app_strings);

	$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Emails','index','name','true','basic'));

	if(isset($_REQUEST['query'])) {
		if(isset($_REQUEST['name'])) $search_form->assign("NAME", $_REQUEST['name']);
		if(isset($_REQUEST['contactname'])) $search_form->assign("CONTACT_NAME", $_REQUEST['contactname']);
		if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");
	}
	$search_form->parse("main");
	
	echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], "", false);
	$search_form->out("main");
	echo get_form_footer();
	echo "\n<BR>\n";
}

// Buttons and View options
$other_text = '<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<form name="massdelete" method="POST">
	<tr>
	<input name="idlist" type="hidden">
	<input name="change_status" type="hidden">
		<td><input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/>
   		</td>
		<td align="right">&nbsp;</td>
	</tr>
	</table>';
//



$where = "";

$focus = new Email();

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	// we have a query
	$url_string .="&query=true";
	if (isset($_REQUEST['name'])) $name = $_REQUEST['name'];
	if (isset($_REQUEST['contactname'])) $contactname = $_REQUEST['contactname'];
	if (isset($_REQUEST['date_start'])) $date_start = $_REQUEST['date_start'];
	if (isset($_REQUEST['location'])) $location = $_REQUEST['location'];

	$where_clauses = Array();

	if(isset($current_user_only) && $current_user_only != ""){
		array_push($where_clauses, "crmentity.smownerid='$current_user->id'");
		$url_string .= "&current_user_only=".$current_user_only;
	}
	if(isset($name) && $name != '')
	{
		array_push($where_clauses, "activity.subject like ".PearDatabase::quote($name.'%')."");
		$url_string .= "&name=".$name;
		
	}
	if(isset($contactname) && $contactname != '')
	{
		array_push($where_clauses, "(contactdetails.firstname like ".PearDatabase::quote($contactname.'%')." OR contactdetails.lastname like ".PearDatabase::quote($contactname.'%').")");
		$url_string .= "&contactname=".$contactname;

	}
	if(isset($date_start) && $date_start != '')
	{
		array_push($where_clauses, "events.eventdatestart like ".PearDatabase::quote($date_start.'%')."");
	}
	if(isset($location) && $location != '')
	{
		$each_location = explode("--", $location);

		$the_where_clause = "(";
		$val = reset($each_location);
		do
		{
			$the_where_clause .= "location = ".PearDatabase::quote($val)."";
			$val = next($each_location);
			if ($val) $the_where_clause .= " OR ";
		} while($val);
		$the_where_clause .= ")";
		array_push($where_clauses, $the_where_clause);
	}

	$where = "";
	if (isset($where_clauses)) {
		foreach($where_clauses as $clause)
		{
			if($where != "")
			$where .= " and ";
			$where .= $clause;
		}
	}
	$log->info("Here is the where clause for the list view: $where");

}


global $email_title;
$display_title = $mod_strings['LBL_LIST_FORM_TITLE'];
if($email_title)$display_title = $email_title;

//Retreive the list from Database
$list_query = getListQuery("Emails");
if(isset($where) && $where != '')
{
	$list_query .= " AND " .$where;
}
$list_result = $adb->query($list_query);

if(isset($order_by) && $order_by != '')
{
        $list_query .= ' ORDER BY '.$order_by.' '.$sorder;
}

//Constructing the list view 

echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],$other_text, false);
$xtpl=new XTemplate ('modules/Emails/ListView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);

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

// Setting the record count string
if ($navigation_array['start'] == 1)
{
	if($noofrows != 0)
	$start_rec = $navigation_array['start'];
	else
	$start_rec = 0;
	if($noofrows > $list_max_entries_per_page)
	{
		$end_rec = $navigation_array['start'] + $list_max_entries_per_page - 1;
	}
	else
	{
		$end_rec = $noofrows;
	}
	
}
else
{
	if($navigation_array['next'] > $list_max_entries_per_page)
	{
		$start_rec = $navigation_array['next'] - $list_max_entries_per_page;
		$end_rec = $navigation_array['next'] - 1;
	}
	else
	{
		$start_rec = $navigation_array['prev'] + $list_max_entries_per_page;
		$end_rec = $noofrows;
	}
}
$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header

$listview_header = getListViewHeader($focus,"Emails",$url_string,$sorder,$order_by);
$xtpl->assign("LISTHEADER", $listview_header);

$listview_entries = getListViewEntries($focus,"Emails",$list_result,$navigation_array);
$xtpl->assign("LISTENTITY", $listview_entries);

if($order_by !='')
$url_string .="&order_by=".$order_by;
if($sorder !='')
$url_string .="&sorder=".$sorder;

$navigationOutput = getTableHeaderNavigation($navigation_array,$url_string,"Emails");
$xtpl->assign("NAVIGATION", $navigationOutput);
$xtpl->assign("RECORD_COUNTS", $record_string);

$xtpl->parse("main");

$xtpl->out("main");

?>
