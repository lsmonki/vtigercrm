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
require_once('include/ComboUtil.php');
require_once('include/uifromdbutil.php');
require_once('modules/CustomView/CustomView.php');

global $app_strings;
global $mod_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Products');

global $list_max_entries_per_page;
global $urlPrefix;


global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
//echo get_module_title("Products", $mod_strings['LBL_MODULE_NAME'].": Home" , true);
echo "<br>";
//echo get_form_header("Product Search", "", false);

$xtpl=new XTemplate ('modules/Products/ListView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);

$comboFieldNames = Array('manufacturer'=>'manufacturer_dom'
                      ,'productcategory'=>'productcategory_dom');
$comboFieldArray = getComboArray($comboFieldNames);

$focus = new Product();

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

$url_string = '&smodule=PRODUCTS'; // assigning http url string
$sorder = 'ASC';  // Default sort order
if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
$sorder = $_REQUEST['sorder'];

if(isset($_REQUEST['query']) && $_REQUEST['query'] != '' && $_REQUEST['query'] == 'true')
{
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

	$where_clauses = Array();
	//$search_query='';

	//Added for Custom Field Search
	$sql="select * from field where tablename='productcf' order by fieldlabel";
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
                                $str=" productcf.".$column[$i]." = 1";
                        else
			        $str=" productcf.".$column[$i]." like '$customfield[$i]%'";
		        array_push($where_clauses, $str);
	       	//	  $search_query .= ' and '.$str;
			$url_string .="&".$column[$i]."=".$customfield[$i];
	        }
	}
	//upto this added for Custom Field

	if (isset($productname) && $productname !='')
	{
		array_push($where_clauses, "productname like ".PearDatabase::quote($productname.'%'));
		//$search_query .= " and productname like '".$productname."%'";
		$url_string .= "&productname=".$productname;
	}
	
	if (isset($productcode) && $productcode !='')
	{
		array_push($where_clauses, "productcode like ".PearDatabase::quote($productcode.'%'));
		//$search_query .= " and productcode like '".$productcode."%'";
		$url_string .= "&productcode=".$productcode;
	}

	if (isset($commissionrate) && $commissionrate !='')
	{
		array_push($where_clauses, "commissionrate like ".PearDatabase::quote($commissionrate.'%'));
		 //$search_query .= " and commissionrate like '".$commissionrate."%'";
		 $url_string .= "&commissionrate=".$commissionrate;
	}
	
	if (isset($qtyperunit) && $qtyperunit !='')
	{
		array_push($where_clauses, "qty_per_unit like ".PearDatabase::quote($qtyperunit.'%'));
	 	//$search_query .= " and qty_per_unit like '".$qtyperunit."%'";
		$url_string .= "&qtyperunit=".$qtyperunit;
	}
	
	if (isset($unitprice) && $unitprice !='')
	{
		array_push($where_clauses, "unit_price like ".PearDatabase::quote($unitprice.'%'));
	 //	$search_query .= " and unit_price like '".$unitprice."%'";
		$url_string .= "&unitprice=".$unitprice;
	}
	if (isset($manufacturer) && $manufacturer !='' && $manufacturer !='--None--')
        {
		array_push($where_clauses, "manufacturer like ".PearDatabase::quote($manufacturer.'%'));
        	//$search_query .= " and manufacturer like '".$manufacturer."%'";
                $url_string .= "&manufacturer=".$manufacturer;
	}
	if (isset($productcategory) && $productcategory !='' && $productcategory !='--None--')
        {
		array_push($where_clauses, "productcategory like ".PearDatabase::quote($productcategory.'%'));
        	//$search_query .= " and productcategory like '".$productcategory."%'";
                $url_string .= "&productcategory=".$productcategory;
	}
	if (isset($start_date) && $start_date !='')
        {
		array_push($where_clauses, "start_date like ".PearDatabase::quote($start_date.'%'));
                //$search_query .= " and start_date = '".$start_date."%'";
                $url_string .= "&start_date=".$start_date;
        } 
	if (isset($expiry_date) && $expiry_date !='')
        {
		array_push($where_clauses, "expiry_date like ".PearDatabase::quote($expiry_date.'%'));
                //$search_query .= " and expiry_date = '".$expiry_date."%'";
                $url_string .= "&expiry_date=".$expiry_date;
        } 
	if (isset($purchase_date) && $purchase_date !='')
        {
		array_push($where_clauses, "purchase_date like ".PearDatabase::quote($purchase_date.'%'));
                //$search_query .= " and purchase_date = '".$purchase_date."%'";
                $url_string .= "&purchase_date=".$purchase_date;
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

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("Products");
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

//Constructing the Search Form
if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
        // Stick the form header out there.
	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'],'', false);
        $search_form=new XTemplate ('modules/Products/SearchForm.html');
        $search_form->assign("MOD", $mod_strings);
        $search_form->assign("APP", $app_strings);
	$clearsearch = 'true';

	if ($order_by !='') $search_form->assign("ORDER_BY", $order_by);
	if ($sorder !='') $search_form->assign("SORDER", $sorder);
	
	$search_form->assign("VIEWID",$viewid);

	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	if($order_by != '') {
		$ordby = "&order_by=".$order_by;
	}
	else
	{
		$ordby ='';
	}

	$search_form->assign("BASIC_LINK", "index.php?module=Products".$ordby."&action=index".$url_string."&sorder=".$sorder."&viewname=".$viewid);
	$search_form->assign("ADVANCE_LINK", "index.php?module=Products&action=index".$ordby."&advanced=true".$url_string."&sorder=".$sorder."&viewname=".$viewid);

	if ($productname !='') $search_form->assign("PRODUCT_NAME", $productname);
	if ($commissionrate !='') $search_form->assign("COMMISSION_RATE", $commissionrate);
	if ($productcode !='') $search_form->assign("PRODUCT_CODE", $productcode);
	if ($qtyperunit !='') $search_form->assign("QTYPERUNIT", $qtyperunit);
	if ($unitprice !='') $search_form->assign("UNITPRICE", $unitprice);
	if (isset($_REQUEST['manufacturer'])) $manufacturer = $_REQUEST['manufacturer'];
	if (isset($_REQUEST['productcategory'])) $productcategoty = $_REQUEST['productcategory'];
	if (isset($_REQUEST['start_date'])) $start_date = $_REQUEST['start_date'];
	if (isset($_REQUEST['expiry_date'])) $expiry_date = $_REQUEST['expiry_date'];
	if (isset($_REQUEST['purchase_date'])) $purchase_date = $_REQUEST['purchase_date'];

//Combo Fields for Manufacturer and Category are moved from advanced to Basic Search
        if (isset($manufacturer)) $search_form->assign("MANUFACTURER", get_select_options($comboFieldArray['manufacturer_dom'], $manufacturer, $clearsearch));
        else $search_form->assign("MANUFACTURER", get_select_options($comboFieldArray['manufacturer_dom'], '', $clearsearch));
        if (isset($productcategory)) $search_form->assign("PRODUCTCATEGORY", get_select_options($comboFieldArray['productcategory_dom'], $productcategoty, $clearsearch));
        else $search_form->assign("PRODUCTCATEGORY", get_select_options($comboFieldArray['productcategory_dom'], '', $clearsearch));

        if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true')
	{
		$url_string .="&advanced=true";
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Products','index','productname','true','advanced',"","","","",$viewid));

		$search_form->assign("SUPPORT_START_DATE",$_REQUEST['start_date']);
		$search_form->assign("SUPPORT_EXPIRY_DATE",$_REQUEST['expiry_date']);
		$search_form->assign("PURCHASE_DATE",$_REQUEST['purchase_date']);
		$search_form->assign("DATE_FORMAT", $current_user->date_format);

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
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Products','index','productname','true','basic',"","","","",$viewid));
		$search_form->parse("main");
	        $search_form->out("main");
	}
echo get_form_footer();
//echo '<br><br>';

}

if($viewid == 0)
{
$cvHTML = '<span class="bodyText disabled">'.$app_strings['LNK_CV_EDIT'].'</span>
<span class="sep">|</span>
<span class="bodyText disabled">'.$app_strings['LNK_CV_DELETE'].'</span><span class="sep">|</span>
<a href="index.php?module=Products&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}else
{
$cvHTML = '<a href="index.php?module=Products&action=CustomView&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_EDIT'].'</a>
<span class="sep">|</span>
<a href="index.php?module=CustomView&action=Delete&dmodule=Products&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_DELETE'].'</a>
<span class="sep">|</span>
<a href="index.php?module=Products&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}

$other_text = '<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<form name="massdelete" method="POST">
	<tr>
	<input name="idlist" type="hidden">
	<input name="viewname" type="hidden" value="'.$viewid.'">';
if(isPermitted('Products',2,'') == 'yes')
{
        $other_text .='<td><input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/></td>';
}
	$other_text .='<td align="right">'.$app_strings[LBL_VIEW].'
                        <SELECT NAME="view" onchange="showDefaultCustomView(this)">
                                <OPTION VALUE="0">'.$mod_strings[LBL_ALL].'</option>
				'.$customviewcombo_html.'
	                </SELECT>
			'.$cvHTML.'
                </td>
        </tr>
        </table>';

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

$xtpl->assign("PRODUCTLISTHEADER", get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'], $other_text, false ));

if(isset($order_by) && $order_by != '')
{
        $list_query .= ' ORDER BY '.$order_by.' '.$sorder;
}

$list_result = $adb->query($list_query);

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
$url_string .= "&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"Products",$url_string,$sorder,$order_by,"",$oCustomView);
$xtpl->assign("LISTHEADER", $listview_header);


$listview_entries = getListViewEntries($focus,"Products",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$xtpl->assign("LISTENTITY", $listview_entries);
$xtpl->assign("SELECT_SCRIPT", $view_script);

if($order_by !='')
$url_string .="&order_by=".$order_by;
if($sorder !='')
$url_string .="&sorder=".$sorder;

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Products","index",$viewid);
$xtpl->assign("NAVIGATION", $navigationOutput);
$xtpl->assign("RECORD_COUNTS", $record_string);

$xtpl->parse("main");
$xtpl->out("main");



?>
