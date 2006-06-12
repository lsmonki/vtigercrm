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
require_once('XTemplate/xtpl.php');
require_once('modules/Products/Vendor.php');
require_once('include/utils.php');
require_once('include/uifromdbutil.php');
require_once('include/ListView/ListView.php');
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
//echo get_module_title("Vendor", $mod_strings['LBL_MODULE_NAME'].": Home" , true);
echo "<br>";
//echo get_form_header("Product Search", "", false);

$xtpl=new XTemplate ('modules/Products/VendorListView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);

/*
$comboFieldNames = Array('manufacturer'=>'manufacturer_dom'
                      ,'productcategory'=>'productcategory_dom');
$comboFieldArray = getComboArray($comboFieldNames);
*/
$focus = new Vendor();

if (!isset($where)) $where = "";

$url_string = '&smodule=VENDOR'; // assigning http url string

if(isset($_REQUEST['query']) && $_REQUEST['query'] != '' && $_REQUEST['query'] == 'true')
{
	$url_string .="&query=true";
	if (isset($_REQUEST['vendorname'])) $vendorname = $_REQUEST['vendorname'];
	if (isset($_REQUEST['email'])) $email = $_REQUEST['email'];
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
			        $str="vendorcf.".$column[$i]." ".$adb->getLike()." '$customfield[$i]%'";
		        array_push($where_clauses, $str);
	       	//	  $search_query .= ' and '.$str;
			$url_string .="&".$column[$i]."=".$customfield[$i];
	        }
	}
	//upto this added for Custom Field

	if (isset($vendorname) && $vendorname !='')
	{
		array_push($where_clauses, "vendorname ".$adb->getLike()." ".$adb->quote($vendorname.'%'));
		//$search_query .= " and productname ".$adb->getLike()." '".$productname."%'";
		$url_string .= "&vendorname=".$vendorname;
	}

	if (isset($email) && $email !='')
	{
		array_push($where_clauses, "email =".$adb->quote($email));
		//$search_query .= " and productcode ".$adb->getLike()." '".$productcode."%'";
		$url_string .= "&email=".$email;
	}
	
	if (isset($category) && $category !='')
	{
		array_push($where_clauses, "category ".$adb->getLike()." ".$adb->quote($category.'%'));
		//$search_query .= " and productcode ".$adb->getLike()." '".$productcode."%'";
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

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("Vendor");
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
	echo get_form_header($current_module_strings['LBL_VENDOR_SEARCH_FORM_TITLE'],'', false);
        $search_form=new XTemplate ('modules/Products/VendorSearchForm.html');
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
	$search_form->assign("BASIC_LINK", "index.php?module=Products".$ordby."&action=index".$url_string."&sorder=".$sorder);
	$search_form->assign("ADVANCE_LINK", "index.php?module=Products&action=index".$ordby."&advanced=true".$url_string."&sorder=".$sorder);

	if ($vendorname !='') $search_form->assign("VENDOR_NAME", $vendorname);
	if ($email !='') $search_form->assign("EMAIL", $email);
	if ($category !='') $search_form->assign("CATEGORY", $category);
/*	if ($qtyperunit !='') $search_form->assign("QTYPERUNIT", $qtyperunit);
	if ($unitprice !='') $search_form->assign("UNITPRICE", $unitprice);
	if (isset($_REQUEST['manufacturer'])) $manufacturer = $_REQUEST['manufacturer'];
	if (isset($_REQUEST['productcategory'])) $productcategoty = $_REQUEST['productcategory'];
	if (isset($_REQUEST['start_date'])) $start_date = $_REQUEST['start_date'];
	if (isset($_REQUEST['expiry_date'])) $expiry_date = $_REQUEST['expiry_date'];
	if (isset($_REQUEST['purchase_date'])) $purchase_date = $_REQUEST['purchase_date'];
*/
//Combo Fields for Manufacturer and Category are moved from advanced to Basic Search
/*        if (isset($manufacturer)) $search_form->assign("MANUFACTURER", get_select_options($comboFieldArray['manufacturer_dom'], $manufacturer, $clearsearch));
        else $search_form->assign("MANUFACTURER", get_select_options($comboFieldArray['manufacturer_dom'], '', $clearsearch));
        if (isset($productcategory)) $search_form->assign("PRODUCTCATEGORY", get_select_options($comboFieldArray['productcategory_dom'], $productcategoty, $clearsearch));
        else $search_form->assign("PRODUCTCATEGORY", get_select_options($comboFieldArray['productcategory_dom'], '', $clearsearch));
*/
        if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true')
	{
		$url_string .="&advanced=true";
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Products','index','vendorname','true','advanced',"","","","",$viewid));

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
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Products','index&smodule=VENDOR','vendorname','true','basic',"","","","",$viewid));
		$search_form->parse("main");
	        $search_form->out("main");
	}
echo get_form_footer();
//echo '<br><br>';

}


// Buttons and View options
$other_text = '<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<form name="massdelete" method="POST">
	<tr>
	<input name="idlist" type="hidden">
	<input name="viewname" type="hidden" value="'.$viewid.'">
	<td>';
if(isPermitted('Vendor',2,'') == 'yes')
{
	$other_text .=	'<input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/>&nbsp;';
}

if($viewid == 0)
{
$cvHTML = '<span class="bodyText disabled">'.$app_strings['LNK_CV_EDIT'].'</span>
<span class="sep">|</span>
<span class="bodyText disabled">'.$app_strings['LNK_CV_DELETE'].'</span><span class="sep">|</span>
<a href="index.php?module=Products&action=CustomView&smodule=VENDOR" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}else
{
$cvHTML = '<a href="index.php?module=Products&action=CustomView&smodule=VENDOR&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_EDIT'].'</a>
<span class="sep">|</span>
<a href="index.php?module=CustomView&action=Delete&dmodule=Products&smodule=VENDOR&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_DELETE'].'</a>
<span class="sep">|</span>
<a href="index.php?module=Products&action=CustomView&smodule=VENDOR" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
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
	$listquery = getListQuery("Vendor");
	$list_query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Vendor");
}else
{
	$list_query = getListQuery("Vendor");
}
//<<<<<<<<customview>>>>>>>>>

if(isset($where) && $where != '')
{
        $list_query .= ' and '.$where;
}

$xtpl->assign("VENDORLISTHEADER", get_form_header($current_module_strings['LBL_LIST_VENDOR_FORM_TITLE'], $other_text, false ));

if(isset($order_by) && $order_by != '')
{
        $list_query .= ' ORDER BY '.$order_by.' '.$sorder;
}

$list_result = $adb->query($list_query);

//Retreiving the no of rows
$noofrows = $adb->num_rows($list_result);

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

//Retreiving the start value from request
if(isset($_REQUEST['start']) && $_REQUEST['start'] != '') {
        $start = $_REQUEST['start'];
} else {
        $start = 1;
}
//Retreive the Navigation array
$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);
$start_rec = $navigation_array['start'];
$end_rec = $navigation_array['end_val'];

$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header
if($viewid !='')
$url_string .="&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"Products",$url_string,$sorder,$order_by,"",$oCustomView);
$xtpl->assign("LISTHEADER", $listview_header);

$listview_entries = getListViewEntries($focus,"Vendor",$list_result,$navigation_array,'','&return_module=Products&return_action=index','VendorEditView','DeleteVendor',$oCustomView);
$xtpl->assign("LISTENTITY", $listview_entries);
$xtpl->assign("SELECT_SCRIPT", $view_script);

if($order_by !='')
$url_string .="&order_by=".$order_by;
if($sorder !='')
$url_string .="&sorder=".$sorder;

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Vendor","index",$viewid);
$xtpl->assign("NAVIGATION", $navigationOutput);
$xtpl->assign("RECORD_COUNTS", $record_string);

$xtpl->parse("main");
$xtpl->out("main");



?>
