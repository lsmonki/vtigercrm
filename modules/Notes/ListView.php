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
 * Contributor(s): ______________________________________..
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Notes/ListView.php,v 1.7 2005/02/24 15:14:32 jack Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Notes/Note.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/utils.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
$current_module_strings = return_module_language($current_language, 'Notes');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('note_list');

global $currentModule;

global $image_path;
global $theme;

// focus_list is the means of passing data to a ListView.
global $focus_list;

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Notes/SearchForm.html');
	$search_form->assign("MOD", $mod_strings);
	$search_form->assign("APP", $app_strings);

	if(isset($_REQUEST['query'])) {
		if(isset($_REQUEST['title'])) $search_form->assign("NAME", $_REQUEST['title']);
		if(isset($_REQUEST['contact_name'])) $search_form->assign("CONTACT_NAME", $_REQUEST['contact_name']);
	}
	$search_form->parse("main");

	echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], "", false);
	$search_form->out("main");
	echo get_form_footer();
	echo "\n<BR>\n";
}

$where = "";

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	// we have a query
	if (isset($_REQUEST['title'])) $name = $_REQUEST['title'];
	if (isset($_REQUEST['contact_name'])) $contact_name = $_REQUEST['contact_name'];

	$where_clauses = Array();

	if(isset($name) && $name != '')
	{
		array_push($where_clauses, "notes.title like ".PearDatabase::quote($name.'%')."");
	}
	if(isset($contact_name) && $contact_name != '')
	{
		$contact_names = explode(" ", $contact_name);
		foreach ($contact_names as $name) {
			array_push($where_clauses, "(contactdetails.firstname like ".PearDatabase::quote($name.'%')." OR contactdetails.lastname like ".PearDatabase::quote($name.'%').")");
		}
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
/*
global $note_title;
$display_title = $mod_strings['LBL_LIST_FORM_TITLE'];
if ($note_title) $display_title = $note_title;
$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/Notes/ListView.html',$mod_strings);
$ListView->setHeaderTitle($display_title );
$ListView->setQuery($where, "", "notes.date_entered DESC", "NOTE");
$ListView->processListView($seedNote, "main", "NOTE");
*/

$focus = new Note();

echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],'', false);
$xtpl=new XTemplate ('modules/Notes/ListView.html');
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);

//Retreive the list from Database
$query = getListQuery("Notes");

if(isset($where) && $where != '')
{
        $query .= ' and '.$where;
}

$url_qry = getURLstring($focus);

if(isset($order_by) && $order_by != '')
{
        $query .= ' ORDER BY '.$order_by;
        $url_qry .="&order_by=".$order_by;
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

$listview_header = getListViewHeader($focus,"Notes");
$xtpl->assign("LISTHEADER", $listview_header);


$listview_entries = getListViewEntries($focus,"Notes",$list_result,$navigation_array);
$xtpl->assign("LISTENTITY", $listview_entries);

if(isset($navigation_array['start']))
{
	$startoutput = '<a href="index.php?action=index&module=Notes'.$url_qry.'&start=1"><b>Start</b></a>';
}
else
{
        $startoutput = '[ Start ]';
}
if(isset($navigation_array['end']))
{
        $endoutput = '<a href="index.php?action=index&module=Notes'.$url_qry.'&start='.$navigation_array['end'].'&query='.$query_val.
'"><b>End</b></a>';
}
else
{
        $endoutput = '[ End ]';
}
if(isset($navigation_array['next']))
{
        $nextoutput = '<a href="index.php?action=index&module=Notes'.$url_qry.'&start='.$navigation_array['next'].'&query='.$query_val.'"><b>Next</b></a>';
}
else
{
        $nextoutput = '[ Next ]';
}
if(isset($navigation_array['prev']))
{
        $prevoutput = '<a href="index.php?action=index&module=Notes'.$url_qry.'&start='.$navigation_array['prev'].'&query='.$query_val.'"><b>Prev</b></a>';
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
