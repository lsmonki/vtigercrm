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
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');
require_once('modules/Faq/Faq.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/uifromdbutil.php');

global $app_strings;
global $mod_strings;
$current_module_strings = return_module_language($current_language, 'Faq');

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$focus = new Faq();

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

$url_string = ''; // assigning http url string
$sorder = 'ASC';  // Default sort order
if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
$sorder = $_REQUEST['sorder'];

//Constructing the Search Form
if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
        // Stick the form header out there.
        $search_form=new XTemplate ('modules/Faq/SearchForm.html');
        $search_form->assign("MOD", $mod_strings);
        $search_form->assign("APP", $app_strings);
	
	if ($order_by !='') $search_form->assign("ORDER_BY", $order_by);
	if ($sorder !='') $search_form->assign("SORDER", $sorder);
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
	foreach($where_clauses as $clause)                                                                            {
		$where .= " and ";
		$where .= $clause;                                                                                    }
}

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Faq','index','question','true','basic'));
       	$search_form->parse("main");
        $search_form->out("main");
	echo get_form_footer();
	echo "\n<BR>\n";

}
// Buttons and View options
$other_text = '<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<form name="massdelete" method="POST">
	<tr>
	<input name="idlist" type="hidden">';

        $other_text .='<td><input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/>
   		</td>
		<td align="right">&nbsp;</td>
	</tr>
	</table>';
//

//Retreive the list from Database
$list_query = getListQuery("Faq");
if(isset($where) && $where != '')
{
	$list_query .= $where;
}

if(isset($order_by) && $order_by != '')
{
        $list_query .= ' ORDER BY '.$order_by.' '.$sorder;
}

$list_result = $adb->query($list_query);

//Constructing the list view 

echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],$other_text, false);
$xtpl=new XTemplate ('modules/Faq/ListView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);

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

$listview_header = getListViewHeader($focus,"Faq",$url_string,$sorder,$order_by);
$xtpl->assign("LISTHEADER", $listview_header);

$listview_entries = getListViewEntries($focus,"Faq",$list_result,$navigation_array);
$xtpl->assign("LISTHEADER", $listview_header);
$xtpl->assign("LISTENTITY", $listview_entries);

if($order_by !='')
$url_string .="&order_by=".$order_by;
if($sorder !='')
$url_string .="&sorder=".$sorder;

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Faq");
$xtpl->assign("NAVIGATION", $navigationOutput);
$xtpl->assign("RECORD_COUNTS", $record_string);

$xtpl->parse("main");
$xtpl->out("main");
?>
