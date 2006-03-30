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
require_once('include/database/PearDatabase.php');
require_once('Smarty_setup.php');
require_once('modules/PriceBooks/PriceBook.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');

global $app_strings,$mod_strings,$list_max_entries_per_page,$currentModule,$theme;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'PriceBook');

$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$focus = new PriceBook();
$other_text=Array();

if (!isset($where)) $where = "";

//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>
if($_REQUEST['order_by'] != '')
	$order_by = $_REQUEST['order_by'];
else
	$order_by = (($_SESSION['PRICEBOOK_ORDER_BY'] != '')?($_SESSION['PRICEBOOK_ORDER_BY']):($focus->default_order_by));

if($_REQUEST['sorder'] != '')
	$sorder = $_REQUEST['sorder'];
else
	$sorder = (($_SESSION['PRICEBOOK_SORT_ORDER'] != '')?($_SESSION['PRICEBOOK_SORT_ORDER']):($focus->default_sort_order));

$_SESSION['PRICEBOOK_ORDER_BY'] = $order_by;
$_SESSION['PRICEBOOK_SORT_ORDER'] = $sorder;
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>

if(isset($_REQUEST['query']) && $_REQUEST['query'] != '' && $_REQUEST['query'] == 'true')
{
	$where=Search($currentModule);
	
	$url_string .="&query=true";
	if (isset($_REQUEST['bookname'])) $bookname = $_REQUEST['bookname'];
        if (isset($_REQUEST['active'])) $active = $_REQUEST['active'];

	$log->info("Here is the where clause for the list view: $where");

}

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("PriceBooks");
$viewid = $oCustomView->getViewId($currentModule);
$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid);
$viewnamedesc = $oCustomView->getCustomViewByCvid($viewid);
//<<<<<customview>>>>>

$other_text['del'] = $app_strings[LBL_MASS_DELETE];
if($viewnamedesc['viewname'] == 'All')
{
	$smarty->assign("ALL", 'All');
}

//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("PriceBooks");
	$list_query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"PriceBooks");
}else
{
	$list_query = getListQuery("PriceBooks");
}
$list_query = getListQuery("PriceBooks");
//<<<<<<<<customview>>>>>>>>>


if(isset($where) && $where != '')
{
        $list_query .= ' and '.$where;
}

if(isset($order_by) && $order_by != '')
{
	$tablename = getTableNameForField('PriceBooks',$order_by);
	$tablename = (($tablename != '')?($tablename."."):'');

        $list_query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
}

$list_result = $adb->query($list_query);

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
//modified by rdhital
$start_rec = $navigation_array['start'];
$end_rec = $navigation_array['end_val']; 
//By Raju Ends

$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header

$listview_header = getListViewHeader($focus,"PriceBooks",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);

$listview_header_search = getSearchListHeaderValues($focus,"PriceBooks",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("SEARCHLISTHEADER",$listview_header_search);

$listview_entries = getListViewEntries($focus,"PriceBooks",$list_result,$navigation_array,'','&return_module=PriceBooks&return_action=index','EditView','Delete',$oCustomView);
$smarty->assign("LISTENTITY", $listview_entries);
$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"PriceBooks","index",$viewid);
$alphabetical = AlphabeticalSearch($currentModule,'index','bookname','true','basic',"","","","",$viewid);
$smarty->assign("ALPHABETICAL", $alphabetical);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("CUSTOMVIEW_OPTION",$customviewcombo_html);
$smarty->assign("VIEWID", $viewid);
$smarty->assign("RECORD_COUNTS", $record_string);
$smarty->assign("SELECT_SCRIPT", $view_script);
$smarty->assign("BUTTONS", $other_text);


if(isset($_REQUEST['ajax']) && $_REQUEST['ajax'] != '')
	$smarty->display("ListViewEntries.tpl");
else	
	$smarty->display("ListView.tpl");
?>
