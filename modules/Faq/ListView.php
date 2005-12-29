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

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
global $mod_strings;

require_once('modules/Faq/Faq.php');
require_once('include/database/PearDatabase.php');
require_once('Smarty_setup.php');
require_once('include/utils/utils.php');
require_once('modules/Faq/Faq.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/utils/utils.php');

global $app_strings;
global $mod_strings;
$current_module_strings = return_module_language($current_language, 'Faq');

global $theme;
global $currentModule;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

if(isset($_REQUEST['category']) && $_REQUEST['category'] !='')
{
	$category = $_REQUEST['category'];
}
else
{
	$category = getParentTabFromModule($currentModule);
}

$focus = new Faq();

$url_string = ''; 

//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>
if($_REQUEST['order_by'] != '')
	$order_by = $_REQUEST['order_by'];
else
	$order_by = (($_SESSION['FAQ_ORDER_BY'] != '')?($_SESSION['FAQ_ORDER_BY']):($focus->default_order_by));

if($_REQUEST['sorder'] != '')
	$sorder = $_REQUEST['sorder'];
else
	$sorder = (($_SESSION['FAQ_SORT_ORDER'] != '')?($_SESSION['FAQ_SORT_ORDER']):($focus->default_sort_order));

$_SESSION['FAQ_ORDER_BY'] = $order_by;
$_SESSION['FAQ_SORT_ORDER'] = $sorder;
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>

//Constructing the Search Form
/*
if(!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') 
{
        // Stick the form header out there.
        $search_form=new XTemplate ('modules/Faq/SearchForm.html');
        $search_form->assign("MOD", $mod_strings);
        $search_form->assign("APP", $app_strings);
	
	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);
}


if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	$where_clauses = Array();
	$url_string .="&query=true";
	// we have a query
	if (isset($_REQUEST['question'])) $question = $_REQUEST['question'];
	if (isset($_REQUEST['faqcategories'])) $faqcategories = $_REQUEST['faqcategories'];

	if(isset($question) && $question != "")
	{
		array_push($where_clauses, "faq.question like '%".$question."%'");
		$url_string .= "&question=".$question;
		$search_form->assign("QUESTION", $question);
	}
	if(isset($faqcategories) && $faqcategories != "")
	{
		array_push($where_clauses, "faq.category like '%".$faqcategories."%'"); 
		$url_string .= "&faqcategories=".$faqcategories;
		$search_form->assign("FAQCATEGORIES", $faqcategories);
	}

	$where = "";
	foreach($where_clauses as $clause)
	{
		$where .= " and ";
		$where .= $clause;
	}
}

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') 
{
	$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Faq','index','question','true','basic'));
       	$search_form->parse("main");
        $search_form->out("main");
	echo get_form_footer();
	echo "\n<BR>\n";
}
*/
// Buttons and View options
$other_text = '	<form name="massdelete" method="POST">
		<input name="idlist" type="hidden">';

$other_text .='   <td><input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/></td></form>
		  <td align="right">&nbsp;</td>';

//Retreive the list from Database
$list_query = getListQuery("Faq");
if(isset($where) && $where != '')
{
	$list_query .= $where;
}

if(isset($order_by) && $order_by != '')
{
	$tablename = getTableNameForField('Faq',$order_by);
	$tablename = (($tablename != '')?($tablename."."):'');

        $list_query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
}

$list_result = $adb->query($list_query);

//Constructing the list view 

//echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],$other_text, false);
//$customView = get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],$other_text, false);

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MODULE",$currentModule);
//$smarty->assign("CUSTOMVIEW",$customView);
$smarty->assign("BUTTONS",$other_text);
$smarty->assign("CATEGORY",$category);

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
// Raju Ends

$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header
$listview_header = getListViewHeader($focus,"Faq",$url_string,$sorder,$order_by);
$smarty->assign("LISTHEADER", $listview_header);
$listview_entries = getListViewEntries($focus,"Faq",$list_result,$navigation_array);
$smarty->assign("LISTHEADER", $listview_header);
$smarty->assign("LISTENTITY", $listview_entries);
$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Faq");
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);

$smarty->display("ListView.tpl");
?>
