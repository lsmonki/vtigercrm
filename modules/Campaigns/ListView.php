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
require_once('modules/Campaigns/Campaign.php');
require_once('include/database/PearDatabase.php');
require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('include/logging.php');
require_once('include/ComboUtil.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');

global $app_strings,$mod_strings,$current_language;
$current_module_strings = return_module_language($current_language, 'Campaigns');

global $currentModule,$theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$focus = new Campaign();
$category = getParentTab();
$other_text = Array();
$url_string = ''; // assigning http url string

//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>
if($_REQUEST['order_by'] != '')
	$order_by = $_REQUEST['order_by'];
else
	$order_by = (($_SESSION['CAMPAIGN_ORDER_BY'] != '')?($_SESSION['CAMPAIGN_ORDER_BY']):($focus->default_order_by));

if($_REQUEST['sorder'] != '')
	$sorder = $_REQUEST['sorder'];
else
	$sorder = (($_SESSION['CAMPAIGN_SORT_ORDER'] != '')?($_SESSION['CAMPAIGN_SORT_ORDER']):($focus->default_sort_order));

$_SESSION['CAMPAIGN_ORDER_BY'] = $order_by;
$_SESSION['CAMPAIGN_SORT_ORDER'] = $sorder;
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>



if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	$where=Search($currentModule);
	
	$url_string .="&query=true";

	if (isset($_REQUEST['campaignid'])) $campaignid = $_REQUEST['campaignid'];
	if (isset($_REQUEST['campaignname'])) $campaignname = $_REQUEST['campaignname'];
	if (isset($_REQUEST['campaigntype'])) $campaigntype = $_REQUEST['campaigntype'];
	if (isset($_REQUEST['campaignstatus'])) $campaignstatus = $_REQUEST['campaignstatus'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];

}

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("Campaigns");
$viewid = $oCustomView->getViewId($currentModule);
$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid);
$viewnamedesc = $oCustomView->getCustomViewByCvid($viewid);

//<<<<<customview>>>>>

$smarty = new vtigerCRM_Smarty;
if($viewid != 0)
{
        $CActionDtls = $oCustomView->getCustomActionDetails($viewid);
}
// Buttons and View options
if(isPermitted('Campaigns',2,'') == 'yes')
{
        $other_text ['del']=$app_strings[LBL_MASS_DELETE];
}

if($viewnamedesc['viewname'] == 'All')
{
	$smarty->assign("ALL", 'All');
}

$customview= get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],$other_text, false);
$smarty->assign("CUSTOMVIEW_OPTION",$customviewcombo_html);
$smarty->assign("VIEWID", $viewid);
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MODULE",'Campaigns');
$smarty->assign("BUTTONS",$other_text);
$smarty->assign("CATEGORY",$category);
$smarty->assign("SINGLE_MOD",'Campaign');

//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("Campaigns");
	$list_query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Campaigns");
}
else
{
	$list_query = getListQuery("Campaigns");
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
		$tablename = getTableNameForField('Campaigns',$order_by);
		$tablename = (($tablename != '')?($tablename."."):'');

	        $list_query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
	}
}
else
{
	$list_query .= ' order by campaign.campaignid DESC';
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

$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header
if($viewid !='')
	$url_string .="&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"Campaigns",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);

$listview_header_search = getSearchListHeaderValues($focus,"Campaigns",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("SEARCHLISTHEADER",$listview_header_search);

$listview_entries = getListViewEntries($focus,"Campaigns",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$smarty->assign("LISTENTITY", $listview_entries);
$smarty->assign("SELECT_SCRIPT", $view_script);
$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Campaigns","index",$viewid);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);


if(isset($_REQUEST['ajax']) && $_REQUEST['ajax'] != '')
	$smarty->display("ListViewEntries.tpl");
else	
	$smarty->display("ListView.tpl");
?>
