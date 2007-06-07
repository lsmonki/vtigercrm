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
require_once('modules/Campaigns/Campaigns.php');
require_once('include/database/PearDatabase.php');
require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/ComboUtil.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');
require_once('include/database/Postgres8.php');
require_once('include/DatabaseUtil.php');

global $app_strings,$mod_strings,$current_language;
$current_module_strings = return_module_language($current_language, 'Campaigns');

global $currentModule,$theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$focus = new Campaigns();
$category = getParentTab();
$smarty = new vtigerCRM_Smarty;
$other_text = Array();
$url_string = ''; // assigning http url string

if(!$_SESSION['lvs'][$currentModule])
{
	unset($_SESSION['lvs']);
	$modObj = new ListViewSession();
	$modObj->sorder = $sorder;
	$modObj->sortby = $order_by;
	$_SESSION['lvs'][$currentModule] = get_object_vars($modObj);
}

if($_REQUEST['errormsg'] != '')
{
        $errormsg = $_REQUEST['errormsg'];
        $smarty->assign("ERROR","The User does not have permission to Change/Delete ".$errormsg." ".$currentModule);
}else
{
        $smarty->assign("ERROR","");
}

//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>
$sorder = $focus->getSortOrder();
$order_by = $focus->getOrderBy();

$_SESSION['CAMPAIGN_ORDER_BY'] = $order_by;
$_SESSION['CAMPAIGN_SORT_ORDER'] = $sorder;
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>



if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	list($where, $ustring) = split("#@@#",getWhereCondition($currentModule));
	// we have a query
	$url_string .="&query=true".$ustring;
	$log->info("Here is the where clause for the list view: $where");
	$smarty->assign("SEARCH_URL",$url_string);
}

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("Campaigns");
$viewid = $oCustomView->getViewId($currentModule);
$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid);
$viewnamedesc = $oCustomView->getCustomViewByCvid($viewid);
$smarty->assign("CHANGE_OWNER",getUserslist());
$smarty->assign("CHANGE_GROUP_OWNER",getGroupslist());
//<<<<<customview>>>>>

if($viewid != 0)
{
        $CActionDtls = $oCustomView->getCustomActionDetails($viewid);
}
// Buttons and View options
if(isPermitted('Campaigns','Delete','') == 'yes')
{
        $other_text ['del']=$app_strings[LBL_MASS_DELETE];
}
if(isPermitted('Campaigns','EditView','') == 'yes')
{
        $other_text['c_owner'] = $app_strings[LBL_CHANGE_OWNER];
}
if($viewnamedesc['viewname'] == 'All')
{
	$smarty->assign("ALL", 'All');
}

$customview= get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],$other_text, false);
$smarty->assign("CUSTOMVIEW_OPTION",$customviewcombo_html);
$smarty->assign("VIEWID", $viewid);
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("BUTTONS",$other_text);
$smarty->assign("CATEGORY",$category);
$smarty->assign("SINGLE_MOD",'Campaign');

//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("Campaigns");
	$list_query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Campaigns");
}
else
{
	$list_query = getListQuery("Campaigns");
}
//<<<<<<<<customview>>>>>>>>>

if(isset($where) && $where != '')
{
	$list_query .= ' and '.$where;
}

//sort by "assignedto" and default sort by "ticketid"(DESC)
if(isset($order_by) && $order_by != '')
{
	if($order_by == 'smownerid')
	{
		if( $adb->dbType == "pgsql")
 		    $list_query .= ' GROUP BY user_name';
		$list_query .= ' ORDER BY user_name '.$sorder;
	}
	else
	{
		$tablename = getTableNameForField('Campaigns',$order_by);
		$tablename = (($tablename != '')?($tablename."."):'');
		if( $adb->dbType == "pgsql")
 		    $list_query .= ' GROUP BY '.$tablename.$order_by;
		
	        $list_query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
	}
}
else
{
	if( $adb->dbType == "pgsql")
 	    $list_query .= ' GROUP BY vtiger_campaign.campaignid';
 	$list_query .= ' ORDER BY vtiger_campaign.campaignid DESC';	
}

//Constructing the list view

//Retreiving the no of rows
$count_result = $adb->query( mkCountQuery( $list_query));
$noofrows = $adb->query_result($count_result,0,"count");

//Storing Listview session object
if($_SESSION['lvs'][$currentModule])
{
	setSessionVar($_SESSION['lvs'][$currentModule],$noofrows,$list_max_entries_per_page);
}

$start = $_SESSION['lvs'][$currentModule]['start'];

//Retreive the Navigation array
$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);

// Setting the record count string
//modified by rdhital
$start_rec = $navigation_array['start'];
$end_rec = $navigation_array['end_val']; 
//By Raju Ends


 //Postgres 8 fixes
 if( $adb->dbType == "pgsql")
     $list_query = fixPostgresQuery( $list_query, $log, 0);


//limiting the query
if ($start_rec ==0) 
	$limit_start_rec = 0;
else
	$limit_start_rec = $start_rec -1;

 if( $adb->dbType == "pgsql")
     $list_result = $adb->query($list_query. " OFFSET ".$limit_start_rec." LIMIT ".$list_max_entries_per_page);
 else
     $list_result = $adb->query($list_query. " LIMIT ".$limit_start_rec.",".$list_max_entries_per_page);	
	
$list_result = $adb->query($list_query. " limit ".$limit_start_rec.",".$list_max_entries_per_page);

//mass merge for word templates -- *Raj*17/11
while($row = $adb->fetch_array($list_result))
{
	$ids[] = $row["crmid"];
}
if(isset($ids))
{
	$smarty->assign("ALLIDS", implode($ids,";"));
}

$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header
if($viewid !='')
	$url_string .="&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"Campaigns",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);

$listview_header_search = getSearchListHeaderValues($focus,"Campaigns",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("SEARCHLISTHEADER",$listview_header_search);

$listview_entries = getListViewEntries($focus,"Campaigns",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$smarty->assign("LISTENTITY", $listview_entries);
$smarty->assign("SELECT_SCRIPT", $view_script);
$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Campaigns","index",$viewid);
$alphabetical = AlphabeticalSearch($currentModule,'index','campaignname','true','basic',"","","","",$viewid);
$fieldnames = getAdvSearchfields($module);
$criteria = getcriteria_options();
$smarty->assign("CRITERIA", $criteria);
$smarty->assign("FIELDNAMES", $fieldnames);
$smarty->assign("ALPHABETICAL", $alphabetical);

$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

if(isset($_REQUEST['ajax']) && $_REQUEST['ajax'] != '')
	$smarty->display("ListViewEntries.tpl");
else	
	$smarty->display("ListView.tpl");
?>
