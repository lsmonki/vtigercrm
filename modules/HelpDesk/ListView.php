<?php
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
global $mod_strings;

require_once('modules/HelpDesk/HelpDesk.php');
require_once('include/database/PearDatabase.php');
require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('include/logging.php');
require_once('include/ComboUtil.php');
require_once('include/utils.php');
require_once('modules/HelpDesk/HelpDeskUtil.php');
require_once('themes/'.$theme.'/layout_utils.php');

global $app_strings;
global $mod_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'HelpDesk');

global $currentModule;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
//require_once($theme_path.'layout_utils.php');

$focus = new HelpDesk();

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

$query_val='';
if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	// we have a query
	if (isset($_REQUEST['title'])) $name = $_REQUEST['title'];
	if (isset($_REQUEST['contact_name'])) $contact_name = $_REQUEST['contact_name'];
	if (isset($_REQUEST['priority'])) $priority = $_REQUEST['priority'];
	if (isset($_REQUEST['status'])) $status = $_REQUEST['status'];
	if (isset($_REQUEST['category'])) $category = $_REQUEST['category'];
	if (isset($_REQUEST['date'])) $date = $_REQUEST['date'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];

	$where_clauses = Array();

	//Added for Custom Field Search
	$sql="select * from field where tablename='ticketcf' order by fieldlabel";
	$result=$adb->query($sql);
	for($i=0;$i<$adb->num_rows($result);$i++)
	{
	        $column[$i]=$adb->query_result($result,$i,'columnname');
	        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
	        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];
	
	        if(isset($customfield[$i]) && $customfield[$i] != '')
	        {
	                $str=" ticketcf.".$column[$i]." like '$customfield[$i]%'";
	                array_push($where_clauses, $str);
	        }
	}
	//upto this added for Custom Field


	if(isset($name) && $name != "")
	{
		array_push($where_clauses, "troubletickets.title like '".$name."%'");
		$query_val .= "&title=".$name;
	} 
	if(isset($contact_name) && $contact_name != "")
	{
		array_push($where_clauses, "contactdetails.lastname like '".$contact_name."%'"); 
		$query_val .= "&contact_name=".$contact_name;

	}
	if(isset($priority) && $priority != "")
	{
		array_push($where_clauses, "troubletickets.priority like '".$priority."%'");
		$query_val .= "&priority=".$priority;
	}
	if(isset($status) && $status != "")
	{
		array_push($where_clauses, "troubletickets.status like '".$status."%'");
		$query_val .= "&status=".$status;
	}
	if(isset($category) && $category != "")
	{
		array_push($where_clauses, "troubletickets.category like '".$category."%'");
		$query_val .= "&category=".$category;
	}
	if (isset($date) && $date !='')
	{
		$date_criteria = $_REQUEST['date_crit'];
		if($date_criteria == 'is')
		{
			array_push($where_clauses, "crmentity.createdtime like '".$date."%'");
		}
		if($date_criteria == 'isnot')
		{
			array_push($where_clauses, "troubletickets.date_created not like '".$date."%'");
		}
		if($date_criteria == 'before')
		{
			array_push($where_clauses,"troubletickets.date_created < '".$date." 23:59:59'");
		}
		if($date_criteria == 'after')
		{
			array_push($where_clauses, "troubletickets.date_created > '".$date." 00:00:00'");
		}
		$query_val .= "&date=".$date;
		$query_val .= "&date_crit=".$date_criteria;
	} 
	if (isset($current_user_only) && $current_user_only !='')
	{
		$search_query .= array_push($where_clauses,"crmentity.smownerid='".$current_user->id."'");
		$query_val .= "&current_user_only=".$current_user_only;
	}

	$where = "";
	foreach($where_clauses as $clause)                                                                            {
	{
		if($where != "")		
		$where .= " and ";
		$where .= $clause;                                                                                    }
	}
}

//Constructing the Search Form
if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
        // Stick the form header out there.
	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'],'', false);
        $search_form=new XTemplate ('modules/HelpDesk/SearchForm.html');
        $search_form->assign("MOD", $current_module_strings);
        $search_form->assign("APP", $app_strings);
	
	
	if (isset($name)) $search_form->assign("SUBJECT", $name);
	if (isset($contact_name)) $search_form->assign("CONTACT_NAME", $contact_name);
	if (isset($priority)) $search_form->assign("PRIORITY", $priority); 
	if (isset($status)) $search_form->assign("STATUS", $status); 
	if (isset($category)) $search_form->assign("CATEGORY", $category);
	if ($date_criteria == 'isnot' && $date != '') $search_form->assign("IS", 'selected');
	if ($date_criteria == 'before' && $date != '') $search_form->assign("BEFORE", 'selected');
	if ($date_criteria == 'after' && $date != '') $search_form->assign("AFTER", 'selected');
	if ($date != '') $search_form->assign("DATE", $date);
	if ($current_user_only != '')	$search_form->assign("CURRENT_USER_ONLY", "checked");

        if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') 
	{

		//Added for Custom Field Search
		$sql="select * from field where tablename='ticketcf' order by fieldlabel";
		$result=$adb->query($sql);
		for($i=0;$i<$adb->num_rows($result);$i++)
		{
		        $column[$i]=$adb->query_result($result,$i,'columnname');
		        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
		        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];
		}
		require_once('include/CustomFieldUtil.php');
		$custfld = CustomFieldSearch($customfield, "ticketcf", "ticketcf", "ticketid", $app_strings,$theme,$column,$fieldlabel);
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
echo '<br><br>';

}

$focus = new HelpDesk();

echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],'', false);
$xtpl=new XTemplate ('modules/HelpDesk/ListView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);

//Retreive the list from Database
$list_query = getListQuery("HelpDesk");
if(isset($where) && $where != '')
{
	$list_query .= ' and '.$where;
}


if(isset($_REQUEST['viewname']))
{
	if($_REQUEST['viewname'] == 'All')
	   {
           $defaultcv_criteria = '';
      }
        else
    {
           $defaultcv_criteria = $_REQUEST['viewname'];
       }

  $list_query .= " and priority like "."'%" .$defaultcv_criteria ."%'";
}


if(isset($order_by) && $order_by != '')
{
        $list_query .= ' ORDER BY '.$order_by;
        $query_val .="&order_by=".$order_by;
}

$list_result = $adb->query($list_query);

//Constructing the list view 

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

$listview_header = getListViewHeader($focus,"HelpDesk");
$xtpl->assign("LISTHEADER", $listview_header);



$listview_entries = getListViewEntries($focus,"HelpDesk",$list_result,$navigation_array);
//$xtpl->assign("LISTHEADER", $listview_header);
$xtpl->assign("LISTENTITY", $listview_entries);

if($_REQUEST['query'])
$query_val .="&query=true";

if(isset($navigation_array['start']))
{
	$startoutput = '<a href="index.php?action=index&module=HelpDesk&start=1'.$query_val.'"><b>Start</b></a>';
}
else
{
	$startoutput = '[ Start ]';
}
if(isset($navigation_array['end']))
{
	$endoutput = '<a href="index.php?action=index&module=HelpDesk'.$query_val.'&start='.$navigation_array['end'].'"><b>End</b></a>';
}
else
{
	$endoutput = '[ End ]';
}
if(isset($navigation_array['next']))
{
	$nextoutput = '<a href="index.php?action=index&module=HelpDesk'.$query_val.'&start='.$navigation_array['next'].'"><b>Next</b></a>';
}
else
{
	$nextoutput = '[ Next ]';
}
if(isset($navigation_array['prev']))
{
	$prevoutput = '<a href="index.php?action=index&module=HelpDesk'.$query_val.'&start='.$navigation_array['prev'].'"><b>Prev</b></a>';
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
