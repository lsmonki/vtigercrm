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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Activities/ListView.php,v 1.7 2005/03/02 15:39:58 jack Exp $
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

global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Activities');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('task_list');

global $currentModule;

global $image_path;
global $theme;

// focus_list is the means of passing data to a ListView.
global $focus_list;

if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Activities/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	
	if(isset($_REQUEST['query'])) {
		if (isset($_REQUEST['name'])) $search_form->assign("NAME", $_REQUEST['name']);
		if (isset($_REQUEST['contactname'])) $search_form->assign("CONTACT_NAME", $_REQUEST['contactname']);
		if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");
	}
	$search_form->parse("main");
	
	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);
	$search_form->out("main");
	echo get_form_footer();
	echo "\n<BR>\n";
}


$where = "";


$focus = new Activity();

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	// we have a query
	if (isset($_REQUEST['name'])) $name = $_REQUEST['name'];
	if (isset($_REQUEST['contactname'])) $contactname = $_REQUEST['contactname'];
	if (isset($_REQUEST['date_due'])) $date_due = $_REQUEST['date_due'];
	if (isset($_REQUEST['status'])) $status = $_REQUEST['status'];

	$where_clauses = Array();

	if(isset($current_user_only) && $current_user_only != "") array_push($where_clauses, "crmentity.smcreatorid='$current_user->id'");
	if(isset($name) && $name != '')
	{
		array_push($where_clauses, "activity.subject like ".PearDatabase::quote($name.'%')."");
		$query_val .= "&name=".$name;		
	}
	if(isset($contactname) && $contactname != '')
	{
		//$contactnames = explode(" ", $contactname);
		//foreach ($contactnames as $name) {
		array_push($where_clauses, "(contactdetails.firstname like ".PearDatabase::quote($contactname.'%')." OR contactdetails.lastname like ".PearDatabase::quote($contactname.'%').")");
		$query_val .= "&contactname=".$contactname;		
		//}
	}
	if(isset($duedate) && $duedate != '')
	{
		array_push($where_clauses, "activity.duedate like ".PearDatabase::quote($datedue.'%')."");
	}
	if(isset($status) && $status != '')
	{
		$each_status = explode("--", $status);

		$the_where_clause = "(";
		$val = reset($each_status);
		do {
			$the_where_clause .= "activity.status = ".PearDatabase::quote($val);
			$val = next($each_status);
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


global  $task_title;
$title_display = $current_module_strings['LBL_LIST_FORM_TITLE'];
if ($task_title) $title_display= $task_title;

//Retreive the list from Database
$list_query = getListQuery("Activities");
if(isset($where) && $where != '')
{
	$list_query .= " AND " .$where;
}

if(isset($_REQUEST['viewname']) && $_REQUEST['viewname']!='')
{
	if($_REQUEST['viewname'] == 'All')
	   {
	           $defaultcv_criteria = '';
      }
     else
    {
          $defaultcv_criteria = $_REQUEST['viewname'];
       }

  $list_query .= " and activitytype like "."'%" .$defaultcv_criteria ."%'";
  $viewname = $_REQUEST['viewname'];
  $view_script = "<script language='javascript'>
		function set_selected()
		{
			len=document.massdelete.view.length;
			for(i=0;i<len;i++)
			{
				if(document.massdelete.view[i].value == '$viewname')
					document.massdelete.view[i].selected = true;
			}
		}
		set_selected();
		</script>";
}

$url_qry = getURLstring($focus);

if(isset($order_by) && $order_by != '')
{
        $list_query .= ' ORDER BY '.$order_by;
        $url_qry .="&order_by=".$order_by;
}

$list_result = $adb->query($list_query);

//Constructing the list view 


echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],'', false);
$xtpl=new XTemplate ('modules/Activities/ListView.html');
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

if($_REQUEST['query'])
$query_val .="&query=true";

//Retreive the List View Table Header

$listview_header = getListViewHeader($focus,"Activities",$query_val);
$xtpl->assign("LISTHEADER", $listview_header);



$listview_entries = getListViewEntries($focus,"Activities",$list_result,$navigation_array);
$xtpl->assign("LISTHEADER", $listview_header);
$xtpl->assign("LISTENTITY", $listview_entries);
$xtpl->assign("SELECT_SCRIPT", $view_script);

if(isset($navigation_array['start']))
{
	$startoutput = '<a href="index.php?action=index&module=Activities&start=1'.$query_val.'"><b>Start</b></a>';
}
else
{
	$startoutput = '[ Start ]';
}
if(isset($navigation_array['end']))
{
	$endoutput = '<a href="index.php?action=index&module=Activities'.$query_val.'&start='.$navigation_array['end'].'"><b>End</b></a>';
}
else
{
	$endoutput = '[ End ]';
}
if(isset($navigation_array['next']))
{
	$nextoutput = '<a href="index.php?action=index&module=Activities'.$query_val.'&start='.$navigation_array['next'].'"><b>Next</b></a>';
}
else
{
	$nextoutput = '[ Next ]';
}
if(isset($navigation_array['prev']))
{
	$prevoutput = '<a href="index.php?action=index&module=Activities'.$query_val.'&start='.$navigation_array['prev'].'"><b>Prev</b></a>';
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

/*
$ListView = new ListView();
$ListView->initNewXTemplate('modules/Activities/ListView.html',$current_module_strings);
$ListView->setCurrentModule("Activities");
$ListView->setHeaderTitle($title_display);
$ListView->setQuery($where, "", "duedate desc", "TASK");
$ListView->processListView($seedActivity, "main", "TASK");
*/
?>
