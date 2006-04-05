<?
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
require_once('modules/Products/Product.php');
require_once('include/ComboUtil.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');

global $app_strings;
global $mod_strings;
global $list_max_entries_per_page;
global $currentModule;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');


$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'Product');
$category = getParentTab();
$smarty->assign("CATEGORY",$category);
$other_text = Array();

$url_string = '&smodule=PRODUCTS'; // assigning http url string

$focus = new Product();

//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>
if($_REQUEST['order_by'] != '')
	$order_by = $_REQUEST['order_by'];
else
	$order_by = (($_SESSION['PRODUCTS_ORDER_BY'] != '')?($_SESSION['PRODUCTS_ORDER_BY']):($focus->default_order_by));

if($_REQUEST['sorder'] != '')
	$sorder = $_REQUEST['sorder'];
else
	$sorder = (($_SESSION['PRODUCTS_SORT_ORDER'] != '')?($_SESSION['PRODUCTS_SORT_ORDER']):($focus->default_sort_order));

$_SESSION['PRODUCTS_ORDER_BY'] = $order_by;
$_SESSION['PRODUCTS_SORT_ORDER'] = $sorder;
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>


if(isset($_REQUEST['query']) && $_REQUEST['query'] != '' && $_REQUEST['query'] == 'true')
{
	$where=Search($currentModule);
	
	$url_string .="&query=true";
	
	if (isset($_REQUEST['productname'])) $productname = $_REQUEST['productname'];
        if (isset($_REQUEST['productcode'])) $productcode = $_REQUEST['productcode'];
        if (isset($_REQUEST['commissionrate'])) $commissionrate = $_REQUEST['commissionrate'];
	if (isset($_REQUEST['qtyperunit'])) $qtyperunit = $_REQUEST['qtyperunit'];
        if (isset($_REQUEST['unitprice'])) $unitprice = $_REQUEST['unitprice'];
        if (isset($_REQUEST['manufacturer'])) $manufacturer = $_REQUEST['manufacturer'];
        if (isset($_REQUEST['productcategory'])) $productcategory = $_REQUEST['productcategory'];
	if (isset($_REQUEST['start_date'])) $start_date = $_REQUEST['start_date'];
        if (isset($_REQUEST['expiry_date'])) $expiry_date = $_REQUEST['expiry_date'];
        if (isset($_REQUEST['purchase_date'])) $purchase_date = $_REQUEST['purchase_date'];

	$log->info("Here is the where clause for the list view: $where");
}

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("Products");
$viewid = $oCustomView->getViewId($currentModule);
$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid);
$viewnamedesc = $oCustomView->getCustomViewByCvid($viewid);
//<<<<<customview>>>>>
if($viewnamedesc['viewname'] == 'All')
{
	$smarty->assign("ALL", 'All');
}

if(isPermitted('Products',2,'') == 'yes')
{
	$other_text['del'] = $app_strings[LBL_MASS_DELETE];
}

//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("Products");
	$list_query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Products");
}else
{
	$list_query = getListQuery("Products");
}
//<<<<<<<<customview>>>>>>>>>


if(isset($where) && $where != '')
{
        $list_query .= ' and '.$where;
}


if(isset($order_by) && $order_by != '')
{
	$tablename = getTableNameForField('Products',$order_by);
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

	//added to remain the navigation when sort
	$url_string = "&start=".$_REQUEST['start'];
}
else
{
        $start = 1;
}
//Retreive the Navigation array
$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);

//modified by rdhital
$start_rec = $navigation_array['start'];
$end_rec = $navigation_array['end_val']; 
//By Raju Ends


$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header
if($viewid !='')
$url_string .= "&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"Products",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);

$listview_header_search = getSearchListHeaderValues($focus,"Products",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("SEARCHLISTHEADER",$listview_header_search);

$listview_entries = getListViewEntries($focus,"Products",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$smarty->assign("LISTENTITY", $listview_entries);
$smarty->assign("SELECT_SCRIPT", $view_script);

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Products","index",$viewid);
$alphabetical = AlphabeticalSearch($currentModule,'index','productname','true','basic',"","","","",$viewid);
$smarty->assign("ALPHABETICAL", $alphabetical);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);
$smarty->assign("CUSTOMVIEW_OPTION",$customviewcombo_html);
$smarty->assign("VIEWID", $viewid);
$smarty->assign("BUTTONS", $other_text);


if(isset($_REQUEST['ajax']) && $_REQUEST['ajax'] != '')
	$smarty->display("ListViewEntries.tpl");
else	
	$smarty->display("ListView.tpl");
?>
