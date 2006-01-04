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

require_once('modules/HelpDesk/HelpDesk.php');
require_once('include/database/PearDatabase.php');
require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('include/logging.php');
require_once('include/ComboUtil.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');

global $app_strings;
global $mod_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'HelpDesk');

$comboFieldNames = Array('ticketpriorities'=>'ticketpriorities_dom'
			,'ticketstatus'=>'ticketstatus_dom'
			,'ticketcategories'=>'ticketcategories_dom');
$comboFieldArray = getComboArray($comboFieldNames);

global $currentModule;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$focus = new HelpDesk();
$category = getParentTab();

$url_string = ''; // assigning http url string

//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>
if($_REQUEST['order_by'] != '')
	$order_by = $_REQUEST['order_by'];
else
	$order_by = (($_SESSION['HELPDESK_ORDER_BY'] != '')?($_SESSION['HELPDESK_ORDER_BY']):($focus->default_order_by));

if($_REQUEST['sorder'] != '')
	$sorder = $_REQUEST['sorder'];
else
	$sorder = (($_SESSION['HELPDESK_SORT_ORDER'] != '')?($_SESSION['HELPDESK_SORT_ORDER']):($focus->default_sort_order));

$_SESSION['HELPDESK_ORDER_BY'] = $order_by;
$_SESSION['HELPDESK_SORT_ORDER'] = $sorder;
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>



if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	$url_string .="&query=true";
	if (isset($_REQUEST['ticket_title'])) $name = $_REQUEST['ticket_title'];
	if (isset($_REQUEST['ticket_id'])) $ticket_id_val = $_REQUEST['ticket_id'];
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
	        $uitype[$i]=$adb->query_result($result,$i,'uitype');
	        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];

	        if(isset($customfield[$i]) && $customfield[$i] != '')
	        {
			if($uitype[$i] == 56)
				$str = " ticketcf.".$column[$i]." = 1";
			elseif($uitype[$i] == 15)
	                        $str = " ticketcf.".$column[$i]." = '".$customfield[$i]."'";
			else
		                $str = " ticketcf.".$column[$i]." like '$customfield[$i]%'";
	                array_push($where_clauses, $str);
			$url_string .="&".$column[$i]."=".$customfield[$i];
	        }
	}
	//upto this added for Custom Field


	if(isset($name) && $name != "")
	{
		if($_REQUEST['button'] == 'Search')
			array_push($where_clauses, "troubletickets.title like '%".$name."%'");
		else
			array_push($where_clauses, "troubletickets.title like '".$name."%'");
		$url_string .= "&ticket_title=".$name;
	}
	if(isset($contact_name) && $contact_name != "")
	{
		array_push($where_clauses, "(contactdetails.firstname like".PearDatabase::quote($contact_name.'%')." OR contactdetails.lastname like ".PearDatabase::quote($contact_name.'%').")");
		$url_string .= "&contact_name=".$contact_name;

	}
	if(isset($priority) && $priority != "")
	{
		array_push($where_clauses, "troubletickets.priority = '".$priority."'");
		$url_string .= "&priority=".$priority;
	}
	if(isset($status) && $status != "")
	{
		array_push($where_clauses, "troubletickets.status = '".$status."'");
		$url_string .= "&status=".$status;
	}
	if(isset($category) && $category != "")
	{
		array_push($where_clauses, "troubletickets.category = '".$category."'");
		$url_string .= "&category=".$category;
	}
	if (isset($date) && $date !='')
	{
		$date_criteria = $_REQUEST['date_crit'];
		$format_date = getDBInsertDateValue($date);
		if($date_criteria == 'is')
		{
			array_push($where_clauses, "crmentity.createdtime like '%".$format_date."%'");
		}
		if($date_criteria == 'isnot')
		{
			array_push($where_clauses, "crmentity.createdtime not like '".$format_date."%'");
		}
		if($date_criteria == 'before')
		{
			array_push($where_clauses,"crmentity.createdtime < '".$format_date."'");
		}
		if($date_criteria == 'after')
		{
			array_push($where_clauses, "crmentity.createdtime > '".++$format_date."'");
		}
		$url_string .= "&date=".$date;
		$url_string .= "&date_crit=".$date_criteria;
	}
	if (isset($current_user_only) && $current_user_only !='')
	{
		$search_query .= array_push($where_clauses,"crmentity.smownerid='".$current_user->id."'");
		$url_string .= "&current_user_only=".$current_user_only;
	}
	if(isset($_REQUEST['my_open_tickets']) && $_REQUEST['my_open_tickets'] == true)
	{
		$search_query .= array_push($where_clauses," troubletickets.status != 'Closed'");
		$search_query .= array_push($where_clauses,"crmentity.smownerid='".$current_user->id."'");
	}
	if(isset($_REQUEST['ticket_id']) && $_REQUEST['ticket_id'] != '')
	{
		array_push($where_clauses, "troubletickets.ticketid = ".$_REQUEST['ticket_id']);
		$url_string .= "&ticket_id=".$_REQUEST['ticket_id'];
	}

	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
			$where .= " and ";
		$where .= $clause;
	}
}

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("HelpDesk");
$customviewcombo_html = $oCustomView->getCustomViewCombo();
$viewid = $oCustomView->getViewId($currentModule);
$viewnamedesc = $oCustomView->getCustomViewByCvid($viewid);
//<<<<<customview>>>>>

if($viewid != 0)
{
        $CActionDtls = $oCustomView->getCustomActionDetails($viewid);
}
// Buttons and View options
if(isPermitted('HelpDesk',2,'') == 'yes')
{
        $other_text ='<td><input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/></td>';
}

if($viewnamedesc['viewname'] == 'All')
{
	$cvHTML = '<span class="bodyText disabled">'.$app_strings['LNK_CV_EDIT'].'</span>
		<span class="sep">|</span>
		<span class="bodyText disabled">'.$app_strings['LNK_CV_DELETE'].'</span><span class="sep">|</span>
		<a href="index.php?module=HelpDesk&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}
else
{
	$cvHTML = '<a href="index.php?module=HelpDesk&action=CustomView&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_EDIT'].'</a>
		<span class="sep">|</span>
		<a href="index.php?module=CustomView&action=Delete&dmodule=HelpDesk&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_DELETE'].'</a>
		<span class="sep">|</span>
		<a href="index.php?module=HelpDesk&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}

$customstrings ='<td align="right">'.$app_strings[LBL_VIEW].'
			<SELECT NAME="viewname" onchange="showDefaultCustomView(this)">
				'.$customviewcombo_html.'
			</SELECT>
			'.$cvHTML.'
		</td>';

$customview= get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],$other_text, false);
$smarty = new vtigerCRM_Smarty;
$smarty->assign("CUSTOMVIEW",$customstrings);
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("BUTTONS",$other_text);
$smarty->assign("CATEGORY",$category);
$smarty->assign("SINGLE_MOD",'HelpDesk');

//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("HelpDesk");
	$list_query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"HelpDesk");
}
else
{
	$list_query = getListQuery("HelpDesk");
}
//<<<<<<<<customview>>>>>>>>>

if(isset($where) && $where != '')
{
	$list_query .= ' and '.$where;
}

$view_script = "<script language='javascript'>
			function set_selected()
			{
				len=document.massdelete.viewname.length;
				for(i=0;i<len;i++)
				{
					if(document.massdelete.viewname[i].value == '$viewid')
						document.massdelete.viewname[i].selected = true;
				}
			}
			set_selected();
		</script>";

//sort by "assignedto" and default sort by "ticketid"(DESC)
if(isset($order_by) && $order_by != '')
{
	if($order_by == 'smownerid')
	{
		$list_query .= ' ORDER BY users.user_name '.$sorder;
	}
	else
	{
		$tablename = getTableNameForField('HelpDesk',$order_by);
		$tablename = (($tablename != '')?($tablename."."):'');

	        $list_query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
	}
}
else
{
	$list_query .= ' order by troubletickets.ticketid DESC';
}


$list_result = $adb->query($list_query);

//Constructing the list view

//Retreiving the no of rows
$noofrows = $adb->num_rows($list_result);

//Retreiving the start value from request
if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
{
	$start = $_REQUEST['start'];

	//added to remain the navigation when sort
	$url_string = "&start=".$_REQUEST['start'];
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
//By Raju Ends

//mass merge for word templates -- *Raj*17/11
while($row = $adb->fetch_array($list_result))
{
	$ids[] = $row["crmid"];
}
if(isset($ids))
{
	echo "<input name='allids' type='hidden' value='".implode($ids,";")."'>";
}
if(isPermitted("HelpDesk",8,'') == 'yes') 
{
        $smarty->assign("MERGEBUTTON","<input title=\"$app_strings[LBL_MERGE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_MERGE_BUTTON_KEY]\" class=\"button\" onclick=\"return massMerge()\" type=\"submit\" name=\"Merge\" value=\" $app_strings[LBL_MERGE_BUTTON_LABEL]\"></td>");
	$wordTemplateResult = fetchWordTemplateList("HelpDesk");
	$tempCount = $adb->num_rows($wordTemplateResult);
	$tempVal = $adb->fetch_array($wordTemplateResult);
	for($templateCount=0;$templateCount<$tempCount;$templateCount++)
	{
		$optionString .="<option value=\"".$tempVal["templateid"]."\">" .$tempVal["filename"] ."</option>";
		$tempVal = $adb->fetch_array($wordTemplateResult);
	}
        $smarty->assign("WORDTEMPLATEOPTIONS","<td align=right>&nbsp;&nbsp;".$mod_strings['LBL_SELECT_TEMPLATE_TO_MAIL_MERGE']."<select name=\"mergefile\">".$optionString."</select>");
}
//mass merge for word templates

$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header
if($viewid !='')
	$url_string .="&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"HelpDesk",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);
$listview_entries = getListViewEntries($focus,"HelpDesk",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$smarty->assign("LISTENTITY", $listview_entries);
$smarty->assign("SELECT_SCRIPT", $view_script);
$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"HelpDesk","index",$viewid);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);

$smarty->display("ListView.tpl");
?>
