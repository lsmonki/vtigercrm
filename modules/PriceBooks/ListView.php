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

global $app_strings;
global $mod_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'PriceBooks');

global $list_max_entries_per_page;
global $urlPrefix;
global $currentModule;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
//echo get_module_title("PriceBooks", "PriceBooks: Home" , true);
echo "<br>";
//echo get_form_header("Product Search", "", false);

if(isset($_REQUEST['category']) && $_REQUEST['category'] !='')
{
	$category = $_REQUEST['category'];
}
else
{
	$category = getParentTabFromModule($currentModule);
}

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("CATEGORY", $category);

/*
$comboFieldNames = Array('manufacturer'=>'manufacturer_dom'
                      ,'productcategory'=>'productcategory_dom');
$comboFieldArray = getComboArray($comboFieldNames);
*/
$focus = new PriceBook();

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
	$url_string .="&query=true";
	if (isset($_REQUEST['bookname'])) $bookname = $_REQUEST['bookname'];
        if (isset($_REQUEST['active'])) $active = $_REQUEST['active'];

	$where_clauses = Array();
	//$search_query='';

	//Added for Custom Field Search
	$sql="select * from field where tablename='pricebookcf' order by fieldlabel";
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
                                $str=" pricebookcf.".$column[$i]." = 1";
                        else
			        $str=" pricebookcf.".$column[$i]." like '$customfield[$i]%'";
		        array_push($where_clauses, $str);
	       	//	  $search_query .= ' and '.$str;
			$url_string .="&".$column[$i]."=".$customfield[$i];
	        }
	}
	//upto this added for Custom Field

	if (isset($bookname) && $bookname !='')
	{
		array_push($where_clauses, "bookname like ".PearDatabase::quote($bookname.'%'));
		//$search_query .= " and productname like '".$productname."%'";
		$url_string .= "&bookname=".$bookname;
	}
	
	if (isset($active) && $active !='')
	{
		array_push($where_clauses, "active = 1");
		//$search_query .= " and productcode like '".$productcode."%'";
		$url_string .= "&active=".$active;
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
/*
if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
        // Stick the form header out there.
	echo get_form_header($current_module_strings['LBL_PRICEBOOK_SEARCH_FORM_TITLE'],'', false);
        $search_form=new XTemplate ('modules/PriceBooks/SearchForm.html');
        $search_form->assign("MOD", $mod_strings);
        $search_form->assign("APP", $app_strings);
	$clearsearch = 'true';
	
	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	$search_form->assign("BASIC_LINK", "index.php?module=PriceBooks&action=index".$url_string);
	$search_form->assign("ADVANCE_LINK", "index.php?module=PriceBooks&action=index&advanced=true".$url_string);

	if ($bookname !='') $search_form->assign("BOOKNAME", $bookname);
	if ($active !='') $search_form->assign("ACTIVE", "CHECKED");

        if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') 
	{
		$url_string .="&advanced=true";
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('PriceBooks','index&smodule=PRICEBOOK','bookname','true','advanced'));

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
		$custfld = CustomFieldSearch($customfield, "pricebookcf", "pricebookcf", "pricebookid", $app_strings,$theme,$column,$fieldlabel);
		$search_form->assign("CUSTOMFIELD", $custfld);
		//upto this added for Custom Field

                $search_form->parse("advanced");
                $search_form->out("advanced");
	}
	else
	{        
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('PriceBooks','index&smodule=PRICEBOOK','bookname','true','basic'));
		$search_form->parse("main");
	        $search_form->out("main");
	}
echo get_form_footer();
//echo '<br><br>';

}
*/

//<<<<cutomview>>>>>>>
/*$oCustomView = new CustomView("PriceBooks");
$customviewcombo_html = $oCustomView->getCustomViewCombo();
if(isset($_REQUEST['viewname']))
{
        $viewid =  $_REQUEST['viewname'];
}else
{
	$viewid = "0";
}
if(isset($_REQUEST['viewname']) == false)
{
	if($oCustomView->setdefaultviewid != "")
	{
		$viewid = $oCustomView->setdefaultviewid;
	}
}*/
//<<<<<customview>>>>>

$other_text = '	<form name="massdelete" method="POST">
	<input name="idlist" type="hidden">
	<input name="viewname" type="hidden">';
$other_text .='<td><input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/></td></form>';

$customstrings = '<td align="right">'.$app_strings[LBL_VIEW].'
                        <SELECT NAME="view" onchange="showDefaultCustomView(this)">
                                <OPTION VALUE="0">'.$mod_strings[LBL_ALL].'</option>
				'.$customviewcombo_html.'
                        </SELECT>
                        <!--<a href="index.php?module=PriceBooks&action=CustomView&record='.$viewid.'" class="link">Edit</a>
                        <span class="sep">|</span>
                        <span class="bodyText disabled">Delete</span><span class="sep">|</span>
                        <a href="index.php?module=PriceBooks&action=CustomView" class="link">Create View</a>-->
                </td>';

//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
/*if($viewid != "0")
{
	$listquery = getListQuery("PriceBooks");
	$list_query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"PriceBooks");
}else
{
	$list_query = getListQuery("PriceBooks");
}*/
$list_query = getListQuery("PriceBooks");
//<<<<<<<<customview>>>>>>>>>


if(isset($where) && $where != '')
{
        $list_query .= ' and '.$where;
}

$smarty->assign("PRICEBOOKLISTHEADER", get_form_header($current_module_strings['LBL_LIST_PRICEBOOK_FORM_TITLE'], $other_text, false ));
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
/*
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
*/

// Setting the record count string
//modified by rdhital
$start_rec = $navigation_array['start'];
$end_rec = $navigation_array['end_val']; 
//By Raju Ends

$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header

$listview_header = getListViewHeader($focus,"PriceBooks",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);

$listview_entries = getListViewEntries($focus,"PriceBooks",$list_result,$navigation_array,'','&return_module=PriceBooks&return_action=index','EditView','Delete',$oCustomView);
$smarty->assign("LISTENTITY", $listview_entries);
$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"PriceBooks","index",$viewid);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);

$smarty->display("ListView.tpl");



?>
