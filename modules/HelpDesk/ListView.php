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

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

require_once('modules/HelpDesk/HelpDesk.php');
require_once('include/database/PearDatabase.php');
require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('include/logging.php');
require_once('include/ComboUtil.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');

global $app_strings;
global $mod_strings;
global $currentModule;

$focus = new HelpDesk();
$smarty = new vtigerCRM_Smarty;
$category = getParentTab();
$other_text = Array();

$url_string = ''; // assigning http url string

//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>
if($_REQUEST['order_by'] != '')
	$order_by = $_REQUEST['order_by'];
else
	$order_by = (($_SESSION['HELPDESK_ORDER_BY'] != '')?($_SESSION['HELPDESK_ORDER_BY']):($focus->default_order_by));

if($_REQUEST['sorder'] != '')
	$sorder = $_REQUEST['sorder'];
else
	$sorder = (($_SESSION['HELPDESK_SORT_ORDER'] != '')?($_SESSION['HELPDESK_SORT_ORDER']):($focus->default_sort_order));

$_SESSION['HELPDESK_ORDER_BY'] = $order_by;
$_SESSION['HELPDESK_SORT_ORDER'] = $sorder;
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>



if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	$where=Search($currentModule);
	$url_string .="&query=true";

	if (isset($_REQUEST['ticket_title'])) $name = $_REQUEST['ticket_title'];
	if (isset($_REQUEST['ticket_id'])) $ticket_id_val = $_REQUEST['ticket_id'];
	if (isset($_REQUEST['contact_name'])) $contact_name = $_REQUEST['contact_name'];
	if (isset($_REQUEST['priority'])) $priority = $_REQUEST['priority'];
	if (isset($_REQUEST['status'])) $status = $_REQUEST['status'];
	if (isset($_REQUEST['category'])) $category = $_REQUEST['category'];
	if (isset($_REQUEST['date'])) $date = $_REQUEST['date'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];

}

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("HelpDesk");
$viewid = $oCustomView->getViewId($currentModule);
$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid);
$viewnamedesc = $oCustomView->getCustomViewByCvid($viewid);
//<<<<<customview>>>>>

if($viewid != 0)
{
        $CActionDtls = $oCustomView->getCustomActionDetails($viewid);
}
// Buttons and View options
if(isPermitted('HelpDesk',2,'') == 'yes')
	$other_text['del'] = $app_strings[LBL_MASS_DELETE];

if($viewnamedesc['viewname'] == 'All')
{
	$smarty->assign("ALL", 'All');
}
$smarty->assign("CUSTOMVIEW_OPTION",$customviewcombo_html);
$smarty->assign("VIEWID", $viewid);
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("BUTTONS",$other_text);
$smarty->assign("CATEGORY",$category);
$smarty->assign("SINGLE_MOD",'HelpDesk');

//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("HelpDesk");
	$list_query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"HelpDesk");
}
else
{
	$list_query = getListQuery("HelpDesk");
}
//<<<<<<<<customview>>>>>>>>>

if(isset($where) && $where != '')
{
	$list_query .= ' and '.$where;
}

//sort by "assignedto" and default sort by "ticketid"(DESC)
if(isset($order_by) && $order_by != '')
{
	if($order_by == 'smownerid')
	{
		$list_query .= ' ORDER BY users.user_name '.$sorder;
	}
	else
	{
		$tablename = getTableNameForField('HelpDesk',$order_by);
		$tablename = (($tablename != '')?($tablename."."):'');

	        $list_query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
	}
}
else
{
	$list_query .= ' order by troubletickets.ticketid DESC';
}


$list_result = $adb->query($list_query);

//Constructing the list view

//Retreiving the no of rows
$noofrows = $adb->num_rows($list_result);

//Retreiving the start value from request
if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
{
	$start = $_REQUEST['start'];

	//added to remain the navigation when sort
	$url_string = "&start=".$_REQUEST['start'];
}
else
{
	$start = 1;
}
//Retreive the Navigation array
$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);

// Setting the record count string
//modified by rdhital
$start_rec = $navigation_array['start'];
$end_rec = $navigation_array['end_val']; 
//By Raju Ends

//mass merge for word templates -- *Raj*17/11
while($row = $adb->fetch_array($list_result))
{
	$ids[] = $row["crmid"];
}
if(isset($ids))
{
	echo "<input name='allids' type='hidden' value='".implode($ids,";")."'>";
}
if(isPermitted("HelpDesk",8,'') == 'yes') 
{
        $smarty->assign("MERGEBUTTON","<input title=\"$app_strings[LBL_MERGE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_MERGE_BUTTON_KEY]\" class=\"button\" onclick=\"return massMerge()\" type=\"submit\" name=\"Merge\" value=\" $app_strings[LBL_MERGE_BUTTON_LABEL]\"></td>");
	$wordTemplateResult = fetchWordTemplateList("HelpDesk");
	$tempCount = $adb->num_rows($wordTemplateResult);
	$tempVal = $adb->fetch_array($wordTemplateResult);
	for($templateCount=0;$templateCount<$tempCount;$templateCount++)
	{
		$optionString .="<option value=\"".$tempVal["templateid"]."\">" .$tempVal["filename"] ."</option>";
		$tempVal = $adb->fetch_array($wordTemplateResult);
	}
        $smarty->assign("WORDTEMPLATEOPTIONS","<td align=right>&nbsp;&nbsp;".$mod_strings['LBL_SELECT_TEMPLATE_TO_MAIL_MERGE']."<select name=\"mergefile\">".$optionString."</select>");
}
//mass merge for word templates

$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header
if($viewid !='')
	$url_string .="&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"HelpDesk",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);

$listview_header_search = getSearchListHeaderValues($focus,"HelpDesk",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("SEARCHLISTHEADER",$listview_header_search);

$listview_entries = getListViewEntries($focus,"HelpDesk",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$smarty->assign("LISTENTITY", $listview_entries);
$smarty->assign("SELECT_SCRIPT", $view_script);
$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"HelpDesk","index",$viewid);
$alphabetical = AlphabeticalSearch($currentModule,'index','ticket_title','true','basic',"","","","",$viewid);
$smarty->assign("ALPHABETICAL", $alphabetical);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);

if(isset($_REQUEST['ajax']) && $_REQUEST['ajax'] != '')
	$smarty->display("ListViewEntries.tpl");
else	
	$smarty->display("ListView.tpl");
?>
