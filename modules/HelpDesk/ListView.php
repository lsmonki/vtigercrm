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
require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('include/logging.php');
require_once('include/ComboUtil.php');
require_once('include/utils.php');
require_once('modules/HelpDesk/HelpDeskUtil.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/uifromdbutil.php');
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

if(isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

$url_string = ''; // assigning http url string
$sorder = 'ASC';  // Default sort order
if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
	$sorder = $_REQUEST['sorder'];

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
if(isset($_REQUEST['viewname']) == false)
{
	if($oCustomView->setdefaultviewid != "")
	{
		$viewid = $oCustomView->setdefaultviewid;
	}
	else
	{
		$viewid = "0";
	}
}
else
{
	$viewid =  $_REQUEST['viewname'];
	$oCustomView->setdefaultviewid = $viewid;
}
//<<<<<customview>>>>>


//Constructing the Search Form
if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') 
{
        // Stick the form header out there.
	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'],'', false);
        $search_form=new XTemplate ('modules/HelpDesk/SearchForm.html');
        $search_form->assign("MOD", $current_module_strings);
        $search_form->assign("APP", $app_strings);
        $search_form->assign("IMAGE_PATH", $image_path);
	$clearsearch = 'true';

	if ($order_by !='') $search_form->assign("ORDER_BY", $order_by);
	if ($sorder !='') $search_form->assign("SORDER", $sorder);
	
	$search_form->assign("VIEWID",$viewid);

	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	$search_form->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
        $search_form->assign("DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));

	if($order_by != '') 
	{
		$ordby = "&order_by=".$order_by;
	}
	else
	{
		$ordby ='';
	}

	$search_form->assign("BASIC_LINK", "index.php?module=HelpDesk".$ordby."&action=index".$url_string."&sorder=".$sorder."&viewname=".$viewid);
	$search_form->assign("ADVANCE_LINK", "index.php?module=HelpDesk&action=index".$ordby."&advanced=true".$url_string."&sorder=".$sorder."&viewname=".$viewid);

	if (isset($name)) $search_form->assign("SUBJECT", $name);
	if (isset($ticket_id_val)) $search_form->assign("TICKETID", $ticket_id_val);
	if (isset($contact_name)) $search_form->assign("CONTACT_NAME", $contact_name);

	if (isset($priority)) 
		$search_form->assign("PRIORITY", get_select_options($comboFieldArray['ticketpriorities_dom'], $priority, $clearsearch));
        else
		$search_form->assign("PRIORITY", get_select_options($comboFieldArray['ticketpriorities_dom'], '', $clearsearch));
	if (isset($status)) 
		$search_form->assign("STATUS", get_select_options($comboFieldArray['ticketstatus_dom'], $status, $clearsearch));
        else 
		$search_form->assign("STATUS", get_select_options($comboFieldArray['ticketstatus_dom'], '', $clearsearch));
	if (isset($category)) 
		$search_form->assign("CATEGORY", get_select_options($comboFieldArray['ticketcategories_dom'], $category, $clearsearch));
        else 
		$search_form->assign("CATEGORY", get_select_options($comboFieldArray['ticketcategories_dom'], '', $clearsearch));

	if ($date_criteria == 'is' && $date != '') $search_form->assign("IS", 'selected');
	if ($date_criteria == 'isnot' && $date != '') $search_form->assign("ISNOT", 'selected');
	if ($date_criteria == 'before' && $date != '') $search_form->assign("BEFORE", 'selected');
	if ($date_criteria == 'after' && $date != '') $search_form->assign("AFTER", 'selected');
	if ($date != '') $search_form->assign("DATE", $date);
	if ($current_user_only != '')	$search_form->assign("CURRENT_USER_ONLY", "checked");

        if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true')
	{

		$url_string .="&advanced=true";
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('HelpDesk','index','ticket_title','true','advanced',"","","","",$viewid));

		//Added for Custom Field Search
		$sql="select * from field where tablename='ticketcf' order by fieldlabel";
		$result=$adb->query($sql);
		for($i=0;$i<$adb->num_rows($result);$i++)
		{
		        $column[$i]=$adb->query_result($result,$i,'columnname');
		        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
		        if (isset($_REQUEST[$column[$i]])) 
				$customfield[$i] = $_REQUEST[$column[$i]];
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
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('HelpDesk','index','ticket_title','true','basic',"","","","",$viewid));
		$search_form->parse("main");
	        $search_form->out("main");
	}

	echo get_form_footer();
	echo '<br>';
}
if($viewid != 0)
{
        $CActionDtls = $oCustomView->getCustomActionDetails($viewid);
}
// Buttons and View options
$other_text = '<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<form name="massdelete" method="POST">
	<tr>
	<input name="idlist" type="hidden">
	<input name="viewname" type="hidden" value="'.$viewid.'">';
if(isPermitted('HelpDesk',2,'') == 'yes')
{
        $other_text .='<td><input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/></td>';
}

if($viewid == 0)
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

$other_text .='<td align="right">'.$app_strings[LBL_VIEW].'
			<SELECT NAME="view" onchange="showDefaultCustomView(this)">
				<OPTION VALUE="0">'.$mod_strings[LBL_ALL].'</option>
				'.$customviewcombo_html.'
			</SELECT>
			'.$cvHTML.'
		</td>
	</tr>
	</table>';

$focus = new HelpDesk();

echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],$other_text, false);
$xtpl=new XTemplate ('modules/HelpDesk/ListView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);

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
				len=document.massdelete.view.length;
				for(i=0;i<len;i++)
				{
					if(document.massdelete.view[i].value == '$viewid')
						document.massdelete.view[i].selected = true;
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
	        $list_query .= ' ORDER BY '.$order_by.' '.$sorder;
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
	$xtpl->assign("MERGEBUTTON","<input title=\"$app_strings[LBL_MERGE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_MERGE_BUTTON_KEY]\" class=\"button\" onclick=\"return massMerge()\" type=\"submit\" name=\"Merge\" value=\" $app_strings[LBL_MERGE_BUTTON_LABEL]\"></td>");
	$wordTemplateResult = fetchWordTemplateList("HelpDesk");
	$tempCount = $adb->num_rows($wordTemplateResult);
	$tempVal = $adb->fetch_array($wordTemplateResult);
	for($templateCount=0;$templateCount<$tempCount;$templateCount++)
	{
		$optionString .="<option value=\"".$tempVal["templateid"]."\">" .$tempVal["filename"] ."</option>";
		$tempVal = $adb->fetch_array($wordTemplateResult);
	}
	$xtpl->assign("WORDTEMPLATEOPTIONS","<td align=right>&nbsp;&nbsp;".$mod_strings['LBL_SELECT_TEMPLATE_TO_MAIL_MERGE']."<select name=\"mergefile\">".$optionString."</select>");
}
//mass merge for word templates

$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header
if($viewid !='')
	$url_string .="&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"HelpDesk",$url_string,$sorder,$order_by,"",$oCustomView);
$xtpl->assign("LISTHEADER", $listview_header);

$listview_entries = getListViewEntries($focus,"HelpDesk",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$xtpl->assign("LISTENTITY", $listview_entries);
$xtpl->assign("SELECT_SCRIPT", $view_script);

if($order_by !='')
	$url_string .="&order_by=".$order_by;
if($sorder !='')
	$url_string .="&sorder=".$sorder;

$xtpl->assign("SELECT_SCRIPT", $view_script);
$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"HelpDesk","index",$viewid);
$xtpl->assign("NAVIGATION", $navigationOutput);
$xtpl->assign("RECORD_COUNTS", $record_string);

$xtpl->parse("main");

$xtpl->out("main");
?>
