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
echo get_module_title("Products", $mod_strings['LBL_MODULE_NAME'].": Home" , true);
echo "<br>";
echo get_form_header("Product Search", "", false);

$xtpl=new XTemplate ('modules/Products/ProductsList.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);

$focus = new Product();

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

$query_val='';
if(isset($_REQUEST['query']) && $_REQUEST['query'] != '' && $_REQUEST['query'] == 'true')
{
	if (isset($_REQUEST['productname'])) $productname = $_REQUEST['productname'];
        if (isset($_REQUEST['productcode'])) $productcode = $_REQUEST['productcode'];
        if (isset($_REQUEST['commissionrate'])) $commissionrate = $_REQUEST['commissionrate'];
	if (isset($_REQUEST['qtyperunit'])) $qtyperunit = $_REQUEST['qtyperunit'];
        if (isset($_REQUEST['unitprice'])) $unitprice = $_REQUEST['unitprice'];

	$search_query='';

	//Added for Custom Field Search
	$sql="select * from field where tablename='productcf' order by fieldlabel";
	$result=$adb->query($sql);
	for($i=0;$i<$adb->num_rows($result);$i++)
	{
	        $column[$i]=$adb->query_result($result,$i,'columnname');
	        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
	        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];
	
	        if(isset($customfield[$i]) && $customfield[$i] != '')
	        {
	                $str=" productcf.".$column[$i]." like '$customfield[$i]%'";
//	                array_push($where_clauses, $str);
	                $search_query .= ' and '.$str;
	        }
	}
	//upto this added for Custom Field

	if (isset($productname) && $productname !='')
	{
		$search_query .= " and productname like '".$productname."%'";
		$query_val .= "&productname=".$productname;
	}
	
	if (isset($productcode) && $productcode !='')
	{
		$search_query .= " and productcode like '".$productcode."%'";
		$query_val .= "&productcode=".$productcode;
	}

	if (isset($commissionrate) && $commissionrate !='')
	{
		 $search_query .= " and commissionrate like '".$commissionrate."%'";
		 $query_val .= "&commissionrate=".$commissionrate;
	}
	
	if (isset($qtyperunit) && $qtyperunit !='')
	{
	 	$search_query .= " and qty_per_unit like '".$qtyperunit."%'";
		$query_val .= "&qtyperunit=".$qtyperunit;
	}
	
	if (isset($unitprice) && $unitprice !='')
	{
	 	$search_query .= " and unit_price like '".$unitprice."%'";
		$query_val .= "&unitprice=".$unitprice;
	}

	 
        //echo $search_query;
	//echo '<BR>';
	//echo $_REQUEST['query'];

//	$tktresult = $adb->query($search_query);
}

//Constructing the Search Form
if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
        // Stick the form header out there.
//	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'],'', false);
        $search_form=new XTemplate ('modules/Products/SearchForm.html');
        $search_form->assign("MOD", $mod_strings);
        $search_form->assign("APP", $app_strings);
	
	
	if ($productname !='') $search_form->assign("PRODUCT_NAME", $productname);
	if ($commissionrate !='') $search_form->assign("COMMISSION_RATE", $commissionrate);
	if ($productcode !='') $search_form->assign("PRODUCT_CODE", $productcode);
	if ($qtyperunit !='') $search_form->assign("QTYPERUNIT", $qtyperunit);
	if ($unitprice !='') $search_form->assign("UNITPRICE", $unitprice);
	

        if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') 
	{

		//Added for Custom Field Search
		$sql="select * from field where tablename='productcf' order by fieldlabel";
		$result=$adb->query($sql);
		for($i=0;$i<$adb->num_rows($result);$i++)
		{
		        $column[$i]=$adb->query_result($result,$i,'columnname');
		        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
		        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];
		}
		require_once('include/CustomFieldUtil.php');
		$custfld = CustomFieldSearch($customfield, "productcf", "productcf", "productid", $app_strings,$theme,$column,$fieldlabel);
		$search_form->assign("CUSTOMFIELD", $custfld);
		//upto this added for Custom Field

                $search_form->parse("advanced");
                $search_form->out("advanced");
	}
	else
	{        
		$search_form->parse("main");
	        $search_form->out("main");
	}
echo get_form_footer();
//echo '<br><br>';

}
//Retreive the list from Database

$list_query = getListQuery("Products");

if($search_query !='');
$list_query .= $search_query;

$xtpl->assign("PRODUCTLISTHEADER", get_form_header("Products List", "", false ));

if(isset($order_by) && $order_by != '')
{
        $list_query .= ' ORDER BY '.$order_by;
        $query_val .="&order_by=".$order_by;
}

$list_result = $adb->query($list_query);

if($_REQUEST['query'])
$query_val .="&query=true";

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

$listview_header = getListViewHeader($focus,"Products");
$xtpl->assign("LISTHEADER", $listview_header);


$listview_entries = getListViewEntries($focus,"Products",$list_result,$navigation_array);
$xtpl->assign("LISTENTITY", $listview_entries);

if(isset($navigation_array['start']))
{
	$startoutput = '<a href="index.php?action=index&module=Products'.$query_val.'&start='.$navigation_array['start'].'"><b>Start</b></a>';
}
else
{
        $startoutput = '[ Start ]';
}
if(isset($navigation_array['end']))
{
       $endoutput = '<a href="index.php?action=index&module=Products'.$query_val.'&start='.$navigation_array['end'].'"><b>End</b></a>';
}
else
{
        $endoutput = '[ End ]';
}
if(isset($navigation_array['next']))
{
        $nextoutput = '<a href="index.php?action=index&module=Products'.$query_val.'&start='.$navigation_array['next'].'"><b>Next</b></a>';
}
else
{
        $nextoutput = '[ Next ]';
}
if(isset($navigation_array['prev']))
{
        $prevoutput = '<a href="index.php?action=index&module=Products'.$query_val.'&start='.$navigation_array['prev'].'"><b>Prev</b></a>';
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
