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

require_once('modules/HelpDesk/HelpDesk.php');
require_once('include/database/PearDatabase.php');
require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('include/logging.php');
require_once('include/ComboUtil.php');
require_once('include/utils.php');
require_once('modules/HelpDesk/HelpDeskUtil.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/uifromdbutil.php');

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

$url_string = ''; // assigning http url string
$sorder = 'ASC';  // Default sort order
if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
$sorder = $_REQUEST['sorder'];

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	// we have a query
	$url_string .="&query=true";
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
			$url_string .="&".$column[$i]."=".$customfield[$i];
	        }
	}
	//upto this added for Custom Field


	if(isset($name) && $name != "")
	{
		array_push($where_clauses, "troubletickets.title like '%".$name."%'");
		$url_string .= "&title=".$name;
	} 
	if(isset($contact_name) && $contact_name != "")
	{
		array_push($where_clauses, "(contactdetails.firstname like".PearDatabase::quote($contact_name.'%')." OR contactdetails.lastname like ".PearDatabase::quote($contact_name.'%').")"); 
		$url_string .= "&contact_name=".$contact_name;

	}
	if(isset($priority) && $priority != "")
	{
		array_push($where_clauses, "troubletickets.priority like '%".$priority."%'");
		$url_string .= "&priority=".$priority;
	}
	if(isset($status) && $status != "")
	{
		array_push($where_clauses, "troubletickets.status like '%".$status."%'");
		$url_string .= "&status=".$status;
	}
	if(isset($category) && $category != "")
	{
		array_push($where_clauses, "troubletickets.category like '%".$category."%'");
		$url_string .= "&category=".$category;
	}
	if (isset($date) && $date !='')
	{
		$date_criteria = $_REQUEST['date_crit'];
		if($date_criteria == 'is')
		{
			array_push($where_clauses, "crmentity.createdtime like '%".$date."%'");
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
		$url_string .= "&date=".$date;
		$url_string .= "&date_crit=".$date_criteria;
	} 
	if (isset($current_user_only) && $current_user_only !='')
	{
		$search_query .= array_push($where_clauses,"crmentity.smownerid='".$current_user->id."'");
		$url_string .= "&current_user_only=".$current_user_only;
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
	$search_form->assign("BASIC_LINK", "index.php?module=HelpDesk".$ordby."&action=index".$url_string."&sorder=".$sorder);
	$search_form->assign("ADVANCE_LINK", "index.php?module=HelpDesk&action=index".$ordby."&advanced=true".$url_string."&sorder=".$sorder);

	if (isset($name)) $search_form->assign("SUBJECT", $name);
	if (isset($contact_name)) $search_form->assign("CONTACT_NAME", $contact_name);
	if (isset($priority)) $search_form->assign("PRIORITY", $priority); 
	if (isset($status)) $search_form->assign("STATUS", $status); 
	if (isset($category)) $search_form->assign("CATEGORY", $category);
	if ($date_criteria == 'is' && $date != '') $search_form->assign("IS", 'selected');
	if ($date_criteria == 'isnot' && $date != '') $search_form->assign("ISNOT", 'selected');
	if ($date_criteria == 'before' && $date != '') $search_form->assign("BEFORE", 'selected');
	if ($date_criteria == 'after' && $date != '') $search_form->assign("AFTER", 'selected');
	if ($date != '') $search_form->assign("DATE", $date);
	if ($current_user_only != '')	$search_form->assign("CURRENT_USER_ONLY", "checked");

        if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') 
	{

		$url_string .="&advanced=true";
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('HelpDesk','index','title','true','advanced'));	

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
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('HelpDesk','index','title','true','basic'));	
		$search_form->parse("main");
	        $search_form->out("main");
	}
echo get_form_footer();
echo '<br>';

}

// Buttons and View options
$other_text = '<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<form name="massdelete" method="POST">
	<tr>
	<input name="idlist" type="hidden">
	<input name="viewname" type="hidden">';
if(isPermitted('HelpDesk',2,'') == 'yes')
{
        $other_text .='<td><input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/></td>';
}
		$other_text .='<td align="right">'.$app_strings[LBL_VIEW].' 
			<SELECT NAME="view" onchange="showDefaultCustomView(this)">
				<OPTION VALUE="'.$mod_strings[MOD.LBL_ALL].'">'.$mod_strings[LBL_ALL].'</option>
				<OPTION VALUE="'.$mod_strings[LBL_LOW].'">'.$mod_strings[LBL_LOW].'</option>
				<OPTION VALUE="'.$mod_strings[LBL_MEDIUM].'">'.$mod_strings[LBL_MEDIUM].'</option>
				<OPTION VALUE="'.$mod_strings[LBL_HIGH].'">'.$mod_strings[LBL_HIGH].'</option>
				<OPTION VALUE="'.$mod_strings[LBL_CRITICAL].'">'.$mod_strings[LBL_CRITICAL].'</option>
			</SELECT>
		</td>
	</tr>
	</table>';
//

$focus = new HelpDesk();

echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],$other_text, false);
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
	$viewname = $_REQUEST['viewname'];
  	$view_script = "<script language='javascript'>
		function set_selected()
		{
			len=document.massdelete.view.length;
			for(i=0;i<len;i++)
			{
				if(document.massdelete.view[i].value == '$viewname')
					document.massdelete.view[i].selected = true;
			}
		}
		set_selected();
		</script>";

}

if(isset($order_by) && $order_by != '')
{
        $list_query .= ' ORDER BY '.$order_by.' '.$sorder;
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

$listview_header = getListViewHeader($focus,"HelpDesk",$url_string,$sorder,$order_by);
$xtpl->assign("LISTHEADER", $listview_header);

$listview_entries = getListViewEntries($focus,"HelpDesk",$list_result,$navigation_array);
$xtpl->assign("LISTENTITY", $listview_entries);
$xtpl->assign("SELECT_SCRIPT", $view_script);

if($order_by !='')
$url_string .="&order_by=".$order_by;
if($sorder !='')
$url_string .="&sorder=".$sorder;

$xtpl->assign("SELECT_SCRIPT", $view_script);
$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"HelpDesk");
$xtpl->assign("NAVIGATION", $navigationOutput);
$xtpl->assign("RECORD_COUNTS", $record_string);

$xtpl->parse("main");

$xtpl->out("main");
?>
