<?php
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

global $app_strings;
global $mod_strings;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$focus = new Faq();
$query_val = 'false';
//Constructing the Search Form
if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
        // Stick the form header out there.
        $search_form=new XTemplate ('modules/Faq/SearchForm.html');
        $search_form->assign("MOD", $mod_strings);
        $search_form->assign("APP", $app_strings);
}

if(isset($_REQUEST['query']))
{
	$query_val = 'true';
	$where_clauses = Array();
	// we have a query
	if (isset($_REQUEST['name'])) $name = $_REQUEST['name'];
	if (isset($_REQUEST['question'])) $question = $_REQUEST['question'];
	if (isset($_REQUEST['faqcategories'])) $faqcategories = $_REQUEST['faqcategories'];

	if(isset($name) && $name != "")
	{
		array_push($where_clauses, "faq.question like '".$name."%'");
		$query_val .= "&name=".$name;
	} 
	if(isset($question) && $question != "")
	{
		array_push($where_clauses, "faq.question like '".$question."%'");
		$query_val .= "&question=".$question;
		$search_form->assign("QUESTION", $question);

	}
	if(isset($faqcategories) && $faqcategories != "")
	{
		array_push($where_clauses, "faq.category like '".$faqcategories."%'"); 
		$query_val .= "&faqcategories=".$faqcategories;
		$search_form->assign("FAQCATEGORIES", $faqcategories);

	}

	$where = "";
	foreach($where_clauses as $clause)                                                                            {
		$where .= " and ";
		$where .= $clause;                                                                                    }
}

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
       	$search_form->parse("main");
        $search_form->out("main");
}

//Retreive the list from Database
$list_query = getListQuery("Faq");
if(isset($where) && $where != '')
{
	$list_query .= $where;
}
$list_result = $adb->query($list_query);

//Constructing the list view 


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

//Retreive the List View Table Header

$listview_header = getListViewHeader($focus,"Faq");
$xtpl->assign("LISTHEADER", $listview_header);



$listview_entries = getListViewEntries($focus,"Faq",$list_result,$navigation_array);
$xtpl->assign("LISTHEADER", $listview_header);
$xtpl->assign("LISTENTITY", $listview_entries);


if(isset($navigation_array['start']))
{
	$startoutput = '<a href="index.php?action=index&module=Faq&start='.$navigation_array['start'].'&query='.$query_val.'"><b>Start</b></a>';
}
else
{
	$startoutput = '[ Start ]';
}
if(isset($navigation_array['end']))
{
	$endoutput = '<a href="index.php?action=index&module=Faq&start='.$navigation_array['end'].'&query='.$query_val.'"><b>End</b></a>';
}
else
{
	$endoutput = '[ End ]';
}
if(isset($navigation_array['next']))
{
	$nextoutput = '<a href="index.php?action=index&module=Faq&start='.$navigation_array['next'].'&query='.$query_val.'"><b>Next</b></a>';
}
else
{
	$nextoutput = '[ Next ]';
}
if(isset($navigation_array['prev']))
{
	$prevoutput = '<a href="index.php?action=index&module=Faq&start='.$navigation_array['prev'].'&query='.$query_val.'"><b>Prev</b></a>';
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
