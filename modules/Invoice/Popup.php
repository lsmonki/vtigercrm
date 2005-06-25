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
require_once('modules/Invoice/Invoice.php');
require_once('include/utils.php');
require_once('include/uifromdbutil.php');

global $app_strings;
global $mod_strings;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Invoice/Popup.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);
$xtpl->assign("THEME_PATH",$theme_path);

$url_string = '';
$search_query = '';
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
        $xtpl->assign("RETURN_MODULE",$_REQUEST['return_module']);

$popuptype = '';
$popuptype = $_REQUEST["popuptype"];
$xtpl->assign("POPUPTYPE",$popuptype);
$xtpl->assign("RECORDID",$_REQUEST['recordid']);

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

$sorder = 'ASC';
if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
$sorder = $_REQUEST['sorder'];

if($popuptype!='') $url_string .= "&popuptype=".$popuptype;

if(isset($_REQUEST['query']) && $_REQUEST['query'] != '' && $_REQUEST['query'] == 'true')
{
	$url_string .="&query=true";
	if (isset($_REQUEST['subject'])) $subject = $_REQUEST['subject'];
        if (isset($_REQUEST['salesorder'])) $salesorder = $_REQUEST['salesorder'];
	
	if ($order_by !='') $xtpl->assign("ORDER_BY", $order_by);
	if ($sorder !='') $xtpl->assign("SORDER", $sorder);

//	$search_query="select * from products inner jon crmentity on crmentity.crmid=products.productid where crmentity.deleted =0";

	if (isset($subject) && $subject !='')
	{
		$search_query .= " and invoice.subject like '".$subject."%'";
		$url_string .= "&subject=".$subject;
		$xtpl->assign("SUBJECT", $subject);
	}
	
	if (isset($salesorder) && $salesorder !='')
	{
		$search_query .= " and salesorder.subject like '%".$salesorder."%'";
		$url_string .= "&salesorder=".$salesorder;
		$xtpl->assign("SALESORDER", $salesorder);
	}
	 
}
echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], "", false);

$xtpl->assign("INVOICELISTHEADER", get_form_header($mod_strings['LBL_LIST_FORM_TITLE'], "", false ));

$focus = new Invoice();

//Retreive the list from Database
$query = getListQuery("Invoice");

if(isset($search_query) && $search_query!='')
{
	$query .= $search_query;
}

if(isset($order_by) && $order_by != '')
{
        $query .= ' ORDER BY '.$order_by.' '.$sorder;
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

$focus->list_mode="search";
$focus->popup_type=$popuptype;

$listview_header = getSearchListViewHeader($focus,"Invoice",$url_string,$sorder,$order_by);
$xtpl->assign("LISTHEADER", $listview_header);

$listview_entries = getSearchListViewEntries($focus,"Invoice",$list_result,$navigation_array);
$xtpl->assign("LISTENTITY", $listview_entries);

if($order_by !='')
$url_string .="&order_by=".$order_by;
if($sorder !='')
$url_string .="&sorder=".$sorder;

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Invoice","Popup");
$xtpl->assign("NAVIGATION", $navigationOutput);
$xtpl->assign("RECORD_COUNTS", $record_string);

$xtpl->assign("ALPHABETICAL",AlphabeticalSearch('Invoice','Popup','subject','true','basic',$popuptype,$_REQUEST['recordid'],$_REQUEST['return_module']));
$xtpl->parse("main");
$xtpl->out("main");



?>
