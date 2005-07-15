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
require_once('modules/Products/Vendor.php');
require_once('include/utils.php');
require_once('include/uifromdbutil.php');

global $app_strings;
global $mod_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Products');

global $list_max_entries_per_page;
global $urlPrefix;


global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$popuptype = '';
$popuptype = $_REQUEST["popuptype"];

require_once($theme_path.'layout_utils.php');
echo get_module_title("Vendor", "Vendor" , true);
echo "<br>";
//echo get_form_header("Product Search", "", false);

$xtpl=new XTemplate ('modules/Products/VendorPopup.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);
$xtpl->assign("THEME_PATH",$theme_path);

/*
$comboFieldNames = Array('manufacturer'=>'manufacturer_dom'
                      ,'productcategory'=>'productcategory_dom');
$comboFieldArray = getComboArray($comboFieldNames);
*/
$focus = new Vendor();

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

$url_string = ''; // assigning http url string
$sorder = 'ASC';  // Default sort order
if($popuptype!='') $url_string .= "&popuptype=".$popuptype;
if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
$sorder = $_REQUEST['sorder'];

if(isset($_REQUEST['query']) && $_REQUEST['query'] != '' && $_REQUEST['query'] == 'true')
{
	$url_string .="&query=true";
	if (isset($_REQUEST['vendorname'])) $vendorname = $_REQUEST['vendorname'];
        if (isset($_REQUEST['companyname'])) $companyname = $_REQUEST['companyname'];
        if (isset($_REQUEST['category'])) $category = $_REQUEST['category'];
	
	$where_clauses = Array();
	//$search_query='';

	//Added for Custom Field Search
	$sql="select * from field where tablename='vendorcf' order by fieldlabel";
	$result=$adb->query($sql);
	for($i=0;$i<$adb->num_rows($result);$i++)
	{
	        $column[$i]=$adb->query_result($result,$i,'columnname');
	        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
		$uitype[$i]=$adb->query_result($result,$i,'uitype');

	        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];

	        if(isset($customfield[$i]) && $customfield[$i] != '')
	        {
			if($uitype[$i] == 56)
                                $str=" vendorcf.".$column[$i]." = 1";
                        else
			        $str="vendorcf.".$column[$i]." like '$customfield[$i]%'";
		        array_push($where_clauses, $str);
	       	//	  $search_query .= ' and '.$str;
			$url_string .="&".$column[$i]."=".$customfield[$i];
	        }
	}
	//upto this added for Custom Field

	if (isset($vendorname) && $vendorname !='')
	{
		array_push($where_clauses, "vendorname like ".PearDatabase::quote($vendorname.'%'));
		//$search_query .= " and productname like '".$productname."%'";
		$url_string .= "&vendorname=".$vendorname;
	}

	if (isset($companyname) && $companyname !='')
	{
		array_push($where_clauses, "company_name like ".PearDatabase::quote($companyname.'%'));
		//$search_query .= " and productcode like '".$productcode."%'";
		$url_string .= "&companyname=".$companyname;
	}
	
	if (isset($category) && $category !='')
	{
		array_push($where_clauses, "category like ".PearDatabase::quote($category.'%'));
		//$search_query .= " and productcode like '".$productcode."%'";
		$url_string .= "&category=".$category;
	}

	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");


}

//Constructing the Search Form
if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
        // Stick the form header out there.
	echo get_form_header($current_module_strings['LBL_VENDOR_SEARCH_FORM_TITLE'],'', false);
        $search_form=new XTemplate ('modules/Products/PopupVendorSearchForm.html');
        $search_form->assign("MOD", $mod_strings);
        $search_form->assign("APP", $app_strings);
	$clearsearch = 'true';

	$search_form->assign("POPUPTYPE",$popuptype);
	if ($order_by !='') $search_form->assign("ORDER_BY", $order_by);
	if ($sorder !='') $search_form->assign("SORDER", $sorder);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	if($order_by != '') {
		$ordby = "&order_by=".$order_by;
	}
	else
	{
		$ordby ='';
	}
	$search_form->assign("BASIC_LINK", "index.php?module=Products".$ordby."&action=index".$url_string."&sorder=".$sorder);
	$search_form->assign("ADVANCE_LINK", "index.php?module=Products&action=index".$ordby."&advanced=true".$url_string."&sorder=".$sorder);

	if ($vendorname !='') $search_form->assign("VENDOR_NAME", $vendorname);
	if ($companyname !='') $search_form->assign("COMPANY_NAME", $companyname);
	if ($category !='') $search_form->assign("CATEGORY", $category);
        if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true')
	{
		$url_string .="&advanced=true";
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Products','index','vendorname','true','advanced'));

		$search_form->assign("SUPPORT_START_DATE",$_REQUEST['start_date']);
		$search_form->assign("SUPPORT_EXPIRY_DATE",$_REQUEST['expiry_date']);
		$search_form->assign("PURCHASE_DATE",$_REQUEST['purchase_date']);
		$search_form->assign("DATE_FORMAT", $current_user->date_format);

		//Added for Custom Field Search
		$sql="select * from field where tablename='vendorcf' order by fieldlabel";
		$result=$adb->query($sql);
		for($i=0;$i<$adb->num_rows($result);$i++)
		{
		        $column[$i]=$adb->query_result($result,$i,'columnname');
		        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
		        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];
		}
		require_once('include/CustomFieldUtil.php');
		$custfld = CustomFieldSearch($customfield, "vendorcf", "vendorcf", "vendorid", $app_strings,$theme,$column,$fieldlabel);
		$search_form->assign("CUSTOMFIELD", $custfld);
		//upto this added for Custom Field

                $search_form->parse("advanced");
                $search_form->out("advanced");
	}
	else
	{
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Products','VendorPopup','vendorname','true','basic',$popuptype));
		$search_form->parse("main");
	        $search_form->out("main");
	}
echo get_form_footer();
//echo '<br><br>';

}

//Retreive the list from Database

$list_query = getListQuery("Vendor");

if(isset($where) && $where != '')
{
        $list_query .= ' and '.$where;
}

$xtpl->assign("VENDORLISTHEADER", get_form_header($current_module_strings['LBL_LIST_VENDOR_FORM_TITLE'],'', false ));

if(isset($order_by) && $order_by != '')
{
        $list_query .= ' ORDER BY '.$order_by.' '.$sorder;
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

$listview_header = getSearchListViewHeader($focus,"Vendor",$url_string,$sorder,$order_by);
$xtpl->assign("LISTHEADER", $listview_header);


$listview_entries = getSearchListViewEntries($focus,"Vendor",$list_result,$navigation_array);
$xtpl->assign("LISTENTITY", $listview_entries);

if($order_by !='')
$url_string .="&order_by=".$order_by;
if($sorder !='')
$url_string .="&sorder=".$sorder;

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Vendor",'VendorPopup');
$xtpl->assign("NAVIGATION", $navigationOutput);
$xtpl->assign("RECORD_COUNTS", $record_string);

$xtpl->parse("main");
$xtpl->out("main");



?>
