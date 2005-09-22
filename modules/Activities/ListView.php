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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Activities/ListView.php,v 1.14 2005/03/26 09:45:00 samk Exp $
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
require_once('include/uifromdbutil.php');
require_once('modules/CustomView/CustomView.php');

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

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("Activities");
$customviewcombo_html = $oCustomView->getCustomViewCombo();
if(isset($_REQUEST['viewname']) == false)
{
	if($oCustomView->setdefaultviewid != "")
	{
		$viewid = $oCustomView->setdefaultviewid;
	}else
	{
		$viewid = "0";
	}
}else
{
	$viewid =  $_REQUEST['viewname'];
}
//<<<<<customview>>>>>

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Activities/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);

	//viewid is given to show the actual view<<<<<<<<<<customview>>>>>>>>
	$search_form->assign("VIEWID",$viewid);
	//<<<<<<<customview>>>>>>>>>>

	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Activities','index','name','true','basic',"","","","",$viewid));

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

$url_string = ''; // assigning http url string
$sorder = 'ASC';  // Default sort order
if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
$sorder = $_REQUEST['sorder'];

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	// we have a query
	$url_string .="&query=true";
	if (isset($_REQUEST['name'])) $name = $_REQUEST['name'];
	if (isset($_REQUEST['contactname'])) $contactname = $_REQUEST['contactname'];
	if (isset($_REQUEST['date_due'])) $date_due = $_REQUEST['date_due'];
	if (isset($_REQUEST['status'])) $status = $_REQUEST['status'];

	$where_clauses = Array();

	if(isset($current_user_only) && $current_user_only != ""){
		//fix as requested by Fredy for getting the proper behaviour in Activity Search
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
		//$contactnames = explode(" ", $contactname);
		//foreach ($contactnames as $name) {
		array_push($where_clauses, "(contactdetails.firstname like ".PearDatabase::quote($contactname.'%')." OR contactdetails.lastname like ".PearDatabase::quote($contactname.'%').")");
		$url_string .= "&contactname=".$contactname;
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


// Buttons and View options
/*$other_text = '<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<form name="massdelete" method="POST">
	<tr>
	<input name="idlist" type="hidden">
	<input name="viewname" type="hidden">
	<input name="change_owner" type="hidden">
	<input name="change_status" type="hidden">
		<td><input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/>
   		<!--input class="button" type="submit" value="'.$app_strings[LBL_CHANGE_OWNER].'" onclick="this.form.change_owner.value=\'true\'; return changeStatus()"/>
	       <input class="button" type="submit" value="'.$app_strings[LBL_CHANGE_STATUS].'" onclick="this.form.change_status.value=\'true\'; return changeStatus()"/--></td>
		<td align="right">'.$app_strings[LBL_VIEW].'
			<SELECT NAME="view" onchange="showDefaultCustomView(this)">
				<OPTION VALUE="'.$mod_strings[MOD.LBL_ALL].'">'.$mod_strings[LBL_ALL].'</option>
				<OPTION VALUE="'.$mod_strings[LBL_CALL].'">'.$mod_strings[LBL_CALL].'</option>
				<OPTION VALUE="'.$mod_strings[LBL_MEETING].'">'.$mod_strings[LBL_MEETING].'</option>
				<OPTION VALUE="'.$mod_strings[LBL_TASK].'">'.$mod_strings[LBL_TASK].'</option>
			</SELECT>
		</td>
	</tr>
	</table>';
//*/
if($viewid == 0)
{
$cvHTML = '<span class="bodyText disabled">'.$app_strings['LNK_CV_EDIT'].'</span>
<span class="sep">|</span>
<span class="bodyText disabled">'.$app_strings['LNK_CV_DELETE'].'</span><span class="sep">|</span>
<a href="index.php?module=Activities&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}
else
{
$cvHTML = '<a href="index.php?module=Activities&action=CustomView&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_EDIT'].'</a>
<span class="sep">|</span>
<a href="index.php?module=CustomView&action=Delete&dmodule=Activities&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_DELETE'].'</a>
<span class="sep">|</span>
<a href="index.php?module=Activities&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}

$other_text = '<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<form name="massdelete" method="POST">
	<tr>
	<input name="idlist" type="hidden">
	<input name="viewname" type="hidden" value="'.$viewid.'">
	<input name="change_owner" type="hidden">
	<input name="change_status" type="hidden">
		<td>';
if(isPermitted("Activities",2,$_REQUEST['record']) == 'yes')
{
	$other_text .= '<input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/>';
}
   	$other_text .='<!--input class="button" type="submit" value="'.$app_strings[LBL_CHANGE_OWNER].'" onclick="this.form.change_owner.value=\'true\'; return changeStatus()"/>
	       <input class="button" type="submit" value="'.$app_strings[LBL_CHANGE_STATUS].'" onclick="this.form.change_status.value=\'true\'; return changeStatus()"/--></td>
		<td align="right">'.$app_strings[LBL_VIEW].'
			<SELECT NAME="view" onchange="showDefaultCustomView(this)">
				<OPTION VALUE="0">'.$mod_strings[LBL_ALL].'</option>
				'.$customviewcombo_html.'
			</SELECT>
			'.$cvHTML.'
		</td>
	</tr>
	</table>';
//

global  $task_title;
$title_display = $current_module_strings['LBL_LIST_FORM_TITLE'];
if ($task_title) $title_display= $task_title;

//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("Activities");
	$list_query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Activities");
}else
{
	$list_query = getListQuery("Activities");
}
//<<<<<<<<customview>>>>>>>>>

if(isset($where) && $where != '')
{
	$list_query .= " AND " .$where;
}

/*if(isset($_REQUEST['viewname']) && $_REQUEST['viewname']!='')
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
}*/
$view_script = "<script language='javascript'>
	function set_selected()
	{
		len=document.massdelete.view.length;
		for(i=0;i<len;i++)
		{
			if(document.massdelete.view[i].value == '$viewid')
				document.massdelete.view[i].selected = true;
		}
	}
	set_selected();
	</script>";

$list_query .= ' GROUP BY crmentity.crmid'; //Appeding for the recurring event by jaguar

if(isset($order_by) && $order_by != '')
{
        $list_query .= ' ORDER BY '.$order_by.' '.$sorder;
}

$list_result = $adb->query($list_query);

//Constructing the list view


echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],$other_text, false);
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
if($viewid !='')
$url_string .="&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"Activities",$url_string,$sorder,$order_by,"",$oCustomView);
$xtpl->assign("LISTHEADER", $listview_header);

$listview_entries = getListViewEntries($focus,"Activities",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$xtpl->assign("LISTENTITY", $listview_entries);
$xtpl->assign("SELECT_SCRIPT", $view_script);

if($order_by !='')
$url_string .="&order_by=".$order_by;
if($sorder !='')
$url_string .="&sorder=".$sorder;

$navigationOutput = getTableHeaderNavigation($navigation_array,$url_string,"Activities","index",$viewid);
$xtpl->assign("NAVIGATION", $navigationOutput);
$xtpl->assign("RECORD_COUNTS", $record_string);

$xtpl->parse("main");

$xtpl->out("main");

?>
