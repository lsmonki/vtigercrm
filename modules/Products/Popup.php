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
require_once('XTemplate/xtpl.php');
require_once('modules/Products/Product.php');
require_once('include/utils.php');

global $app_strings;
global $mod_strings;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Products/Popup.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);
$xtpl->assign("THEME_PATH",$theme_path);

$popuptype = '';
$popuptype = $_REQUEST["popuptype"];
$xtpl->assign("POPUPTYPE",$popuptype);


if(isset($_REQUEST['query']) && $_REQUEST['query'] != '' && $_REQUEST['query'] == 'true')
{
	
	$query_val = "true";
	if (isset($_REQUEST['productname'])) $productname = $_REQUEST['productname'];
        if (isset($_REQUEST['productcode'])) $productcode = $_REQUEST['productcode'];
        if (isset($_REQUEST['commissionrate'])) $commissionrate = $_REQUEST['commissionrate'];
	if (isset($_REQUEST['qtyperunit'])) $qtyperunit = $_REQUEST['qtyperunit'];
        if (isset($_REQUEST['unitprice'])) $unitprice = $_REQUEST['unitprice'];

//	$search_query="select * from products inner jon crmentity on crmentity.crmid=products.productid where crmentity.deleted =0";

	if (isset($productname) && $productname !='')
	{
		$search_query .= " and productname like '".$productname."%'";
		$query_val .= "&productname=".$productname;
		$xtpl->assign("PRODUCT_NAME", $productname);
	}
	
	if (isset($productcode) && $productcode !='')
	{
		$search_query .= " and productcode like '".$productcode."%'";
		$query_val .= "&productcode=".$productcode;
		$xtpl->assign("PRODUCT_CODE", $productcode);
	}

	if (isset($commissionrate) && $commissionrate !='')
	{
		 $search_query .= " and commissionrate like '".$commissionrate."%'";
		 $query_val .= "&commissionrate=".$commissionrate;
		 $xtpl->assign("COMMISSION_RATE", $commissionrate);
	}
	
	if (isset($qtyperunit) && $qtyperunit !='')
	{
	 	$search_query .= " and qty_per_unit like '".$qtyperunit."%'";
		$query_val .= "&qtyperunit=".$qtyperunit;
		 $xtpl->assign("QTYPERUNIT", $qtyperunit);
	}
	
	if (isset($unitprice) && $unitprice !='')
	{
	 	$search_query .= " and unit_price like '".$unitprice."%'";
		$query_val .= "&unitprice=".$unitprice;
		$xtpl->assign("UNITPRICE", $unitprice);
	}
	 
        //echo $search_query;
	//echo '<BR>';
	//echo $_REQUEST['query'];
//	$tktresult = $adb->query($search_query);
}
echo get_module_title("Products", $mod_strings['LBL_MODULE_NAME'].": Home" , true);
echo "<br>";
echo get_form_header("Product Search", "", false);

$xtpl->assign("PRODUCTLISTHEADER", get_form_header("Products List", "", false ));

$focus = new Product();

//Retreive the list from Database
$query = getListQuery("Products");

if(isset($search_query) && $search_query!='')
{
	$query .= $search_query;
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

$focus->list_mode="search";
$focus->popup_type=$popuptype;

$listview_header = getSearchListViewHeader($focus,"Products");
$xtpl->assign("LISTHEADER", $listview_header);


$listview_entries = getSearchListViewEntries($focus,"Products",$list_result,$navigation_array);
$xtpl->assign("LISTENTITY", $listview_entries);
$query_val = 'false';

if(isset($navigation_array['start']))
{
	$startoutput = '<a href="index.php?action=Popup&module=Products&start='.$navigation_array['start'].'&query='.$query_val.'&popuptype='.$popuptype.'"><b>Start</b></a>';
}
else
{
        $startoutput = '[ Start ]';
}
if(isset($navigation_array['end']))
{
        $endoutput = '<a href="index.php?action=Popup&module=Products&start='.$navigation_array['end'].'&query='.$query_val.'&popuptype='.$popuptype.'"><b>End</b></a>';
}
else
{
        $endoutput = '[ End ]';
}
if(isset($navigation_array['next']))
{
        $nextoutput = '<a href="index.php?action=Popup&module=Products&start='.$navigation_array['next'].'&query='.$query_val.'&popuptype='.$popuptype.'"><b>Next</b></a>';
}
else
{
        $nextoutput = '[ Next ]';
}
if(isset($navigation_array['prev']))
{
        $prevoutput = '<a href="index.php?action=Popup&module=Products&start='.$navigation_array['prev'].'&query='.$query_val.'&popuptype='.$popuptype.'"><b>Prev</b></a>';
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
