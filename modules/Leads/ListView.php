<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header$
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('modules/Leads/Lead.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');

global $app_strings;
global $list_max_entries_per_page;

$log = LoggerManager::getLogger('contact_list');

global $currentModule;
global $theme;

// Get _dom arrays from Database
$comboFieldNames = Array('leadstatus'=>'leadstatus_dom');
$comboFieldArray = getComboArray($comboFieldNames);

$category = getParentTab();

if (!isset($where)) $where = "";

$url_string = ''; // assigning http url string

$focus = new Lead();
$smarty = new vtigerCRM_Smarty;
$other_text=Array();
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>
if($_REQUEST['order_by'] != '')
	$order_by = $_REQUEST['order_by'];
else
	$order_by = (($_SESSION['LEADS_ORDER_BY'] != '')?($_SESSION['LEADS_ORDER_BY']):($focus->default_order_by));

if($_REQUEST['sorder'] != '')
	$sorder = $_REQUEST['sorder'];
else
	$sorder = (($_SESSION['LEADS_SORT_ORDER'] != '')?($_SESSION['LEADS_SORT_ORDER']):($focus->default_sort_order));

$_SESSION['LEADS_ORDER_BY'] = $order_by;
$_SESSION['LEADS_SORT_ORDER'] = $sorder;
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>

//for change owner and change status
$change_status = get_select_options_with_id($comboFieldArray['leadstatus_dom'], $focus->lead_status);
$smarty->assign("CHANGE_STATUS",$change_status);
$result=$adb->query("select * from users");
for($i=0;$i<$adb->num_rows($result);$i++)
{
       $useridlist[$i]=$adb->query_result($result,$i,'id');
       $usernamelist[$useridlist[$i]]=$adb->query_result($result,$i,'user_name');
}
$change_owner = get_select_options_with_id($usernamelist, $focus->lead_owner);
$smarty->assign("CHANGE_OWNER",$change_owner);
	


if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	if($_REQUEST['searchtype']=='advance')
	{
		$adv_string='';
		if(isset($_REQUEST['search_cnt']))
		$tot_no_criteria = $_REQUEST['search_cnt'];
		if($_REQUEST['matchtype'] == 'all')
			$matchtype = "or";
		else
			$matchtype = "and";
		
		for($i=0; $i<=$tot_no_criteria; $i++)
		{
			if($i == $tot_no_criteria-1)
			$matchtype= "";
			
			$table_colname = 'Fields'.$i;
			$search_condition = 'Condition'.$i;
			$search_value = 'Srch_value'.$i;

			$tab_col = str_replace('\'','',stripslashes($_REQUEST[$table_colname]));
			$srch_cond = str_replace('\'','',stripslashes($_REQUEST[$search_condition]));
			$srch_val = $_REQUEST[$search_value];
			$adv_string .= " ".getSearch_criteria($srch_cond,$srch_val,$tab_col)." ".$matchtype;	
		}
		$where=$adv_string;
	}
	else
	{
 		$where=Search($currentModule);
	}
	// we have a query
	$url_string .="&query=true";

	$log->info("Here is the where clause for the list view: $where");

}

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("Leads");
$viewid = $oCustomView->getViewId($currentModule);
$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid);
$viewnamedesc = $oCustomView->getCustomViewByCvid($viewid);
//<<<<<customview>>>>>

if($viewid != 0)
{
	$CActionDtls = $oCustomView->getCustomActionDetails($viewid);
}
// Buttons and View options
//Modified by Raju
//raju
if(isPermitted('Leads',2,'') == 'yes')
{
	$other_text['del'] =	$app_strings[LBL_MASS_DELETE];	

}
$other_text['s_mail'] = $app_strings[LBL_SEND_MAIL_BUTTON];

if(isPermitted('Leads',1,'') == 'yes')
{
	$other_text['c_owner'] = $app_strings[LBL_CHANGE_OWNER];
	$other_text['c_status'] = $app_strings[LBL_CHANGE_STATUS];
}
if(isset($CActionDtls))
{
	$other_text['s_cmail'] = $app_strings[LBL_SEND_CUSTOM_MAIL_BUTTON];
}

if($viewnamedesc['viewname'] == 'All')
{
	$smarty->assign("ALL", 'All');
}


$custom= get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],$other_text, false);
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("CUSTOMVIEW_OPTION",$customviewcombo_html);
$smarty->assign("VIEWID", $viewid);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'Lead');
$smarty->assign("BUTTONS",$other_text);
$smarty->assign("CATEGORY",$category);


//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("Leads");
	$query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Leads");
}else
{
	$query = getListQuery("Leads");
}
//<<<<<<<<customview>>>>>>>>>

if(isset($where) && $where != '')
{
        $query .= ' and '.$where;
}


if(isset($order_by) && $order_by != '')
{
	$tablename = getTableNameForField('Leads',$order_by);
	$tablename = (($tablename != '')?($tablename."."):'');

        $query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
}

$list_result = $adb->query($query);

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


//mass merge for word templates -- *Raj*17/11
while($row = $adb->fetch_array($list_result))
{
	$ids[] = $row["crmid"];
}
if(isset($ids))
{
	echo "<input name='allids' type='hidden' value='".implode($ids,";")."'>";
}
if(isPermitted("Leads",8,'') == 'yes') 
{
	$smarty->assign("MERGEBUTTON","<td><input title=\"$app_strings[LBL_MERGE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_MERGE_BUTTON_KEY]\" class=\"small\" onclick=\"return massMerge()\" type=\"submit\" name=\"Merge\" value=\" $app_strings[LBL_MERGE_BUTTON_LABEL]\"></td>");
	$wordTemplateResult = fetchWordTemplateList("Leads");
	$tempCount = $adb->num_rows($wordTemplateResult);
	$tempVal = $adb->fetch_array($wordTemplateResult);
	for($templateCount=0;$templateCount<$tempCount;$templateCount++)
	{
		$optionString .="<option value=\"".$tempVal["templateid"]."\">" .$tempVal["filename"] ."</option>";
		$tempVal = $adb->fetch_array($wordTemplateResult);
	}
	$smarty->assign("WORDTEMPLATEOPTIONS","<td>".$mod_strings['LBL_SELECT_TEMPLATE_TO_MAIL_MERGE']."</td><td style=\"padding-left:5px;padding-right:5px\"><select class=\"small\" name=\"mergefile\">".$optionString."</select></td>");
}
//mass merge for word templates

// Setting the record count string
//modified by rdhital
$start_rec = $navigation_array['start'];
$end_rec = $navigation_array['end_val']; 
//By Raju Ends

$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header
if($viewid !='')
$url_string .= "&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"Leads",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);

$listview_header_search=getSearchListHeaderValues($focus,"Leads",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("SEARCHLISTHEADER", $listview_header_search);

$listview_entries = getListViewEntries($focus,"Leads",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$smarty->assign("LISTENTITY", $listview_entries);
$smarty->assign("SELECT_SCRIPT", $view_script);

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Leads","index",$viewid);
$alphabetical = AlphabeticalSearch($currentModule,'index','lastname','true','basic',"","","","",$viewid);
$fieldnames = getAdvSearchfields($module);
$criteria = getcriteria_options();
$smarty->assign("CRITERIA", $criteria);
$smarty->assign("FIELDNAMES", $fieldnames);
$smarty->assign("ALPHABETICAL", $alphabetical);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);
if(isset($_REQUEST['ajax']) && $_REQUEST['ajax'] != '')
	$smarty->display("ListViewEntries.tpl");
else	
	$smarty->display("ListView.tpl");

?>
