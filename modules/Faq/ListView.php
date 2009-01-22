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
require_once('include/ListView/ListView.php');
require_once('modules/Faq/Faq.php');
require_once('modules/CustomView/CustomView.php');
require_once('include/database/Postgres8.php');
require_once('include/DatabaseUtil.php');

global $app_strings,$theme;
$current_module_strings = return_module_language($current_language, 'Faq');

global $currentModule;

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
// Initialize sort by fields
$focus->initSortbyField('Faq');
// END
$smarty = new vtigerCRM_Smarty;

$other_text = Array();

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
        $smarty->assign("ERROR","The User does not have permission to delete ".$errormsg." ".$currentModule);
}else
{
        $smarty->assign("ERROR","");
}
$url_string = ''; 
//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("Faq");
$viewid = $oCustomView->getViewId($currentModule);
$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid);
$viewnamedesc = $oCustomView->getCustomViewByCvid($viewid);

//Added to handle approving or denying status-public by the admin in CustomView
$statusdetails = $oCustomView->isPermittedChangeStatus($viewnamedesc['status']);
$smarty->assign("CUSTOMVIEW_PERMISSION",$statusdetails);

//To check if a user is able to edit/delete a customview
$edit_permit = $oCustomView->isPermittedCustomView($viewid,'EditView',$currentModule);
$delete_permit = $oCustomView->isPermittedCustomView($viewid,'Delete',$currentModule);
$smarty->assign("CV_EDIT_PERMIT",$edit_permit);
$smarty->assign("CV_DELETE_PERMIT",$delete_permit);

//<<<<<customview>>>>>

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

if($viewid != 0)
{
        $CActionDtls = $oCustomView->getCustomActionDetails($viewid);
}
elseif($viewid ==0)
{
	echo "<table border='0' cellpadding='5' cellspacing='0' width='100%' height='450px'><tr><td align='center'>";
	echo "<div style='border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 55%; position: relative; z-index: 10000000;'>

		<table border='0' cellpadding='5' cellspacing='0' width='98%'>
		<tbody><tr>
		<td rowspan='2' width='11%'><img src='". vtiger_imageurl('denied.gif', $theme)."' ></td>
		<td style='border-bottom: 1px solid rgb(204, 204, 204);' nowrap='nowrap' width='70%'><span clas
		s='genHeaderSmall'>$app_strings[LBL_PERMISSION]</span></td>
		</tr>
		<tr>
		<td class='small' align='right' nowrap='nowrap'>
		<a href='javascript:window.history.back();'>$app_strings[LBL_GO_BACK]</a><br>
		</td>
		</tr>
		</tbody></table>
		</div>";
	echo "</td></tr></table>";
	exit;
}

if(isPermitted('Faq','Delete','') == 'yes')
$other_text ['del'] = $app_strings[LBL_MASS_DELETE]; 


//Retreive the list from Database
//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("Faq");
	$list_query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Faq");
}else
{
	$list_query = getListQuery("Faq");
}

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	list($where, $ustring) = split("#@@#",getWhereCondition($currentModule));
	// we have a query
	$url_string .="&query=true".$ustring;
	$log->info("Here is the where clause for the list view: $where");
	$smarty->assign("SEARCH_URL",$url_string);
}
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
if(isset($order_by) && $order_by != '')
{
	$tablename = getTableNameForField('Faq',$order_by);
	$tablename = (($tablename != '')?($tablename."."):'');
	if( $adb->dbType == "pgsql")
 	    $list_query .= ' GROUP BY '.$tablename.$order_by;	
	
        $list_query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
}
//Constructing the list view 

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("BUTTONS",$other_text);
$smarty->assign("CATEGORY",$category);
$smarty->assign("SINGLE_MOD",'Document');
//Retreiving the no of rows
//Retreiving the no of rows
$count_result = $adb->query( mkCountQuery( $list_query));
$noofrows = $adb->query_result($count_result,0,"count");

if($viewnamedesc['viewname'] == 'All')
{
	$smarty->assign("ALL", 'All');
}

//Storing Listview session object
if($_SESSION['lvs'][$currentModule])
{
	setSessionVar($_SESSION['lvs'][$currentModule],$noofrows,$list_max_entries_per_page);
}
//added for 4600
                                                                                                                             
if($noofrows <= $list_max_entries_per_page)
        $_SESSION['lvs'][$currentModule]['start'] = 1;
//ends

$start = $_SESSION['lvs'][$currentModule]['start'];

//Retreive the Navigation array
$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);
 //Postgres 8 fixes
 if( $adb->dbType == "pgsql")
     $list_query = fixPostgresQuery( $list_query, $log, 0);



// Setting the record count string
//modified by rdhital
$start_rec = $navigation_array['start'];
$end_rec = $navigation_array['end_val']; 
// Raju Ends

//limiting the query
if ($start_rec ==0) 
	$limit_start_rec = 0;
else
	$limit_start_rec = $start_rec -1;
	
if( $adb->dbType == "pgsql")
     $list_result = $adb->pquery($list_query. " OFFSET $limit_start_rec LIMIT $list_max_entries_per_page", array());
else
     $list_result = $adb->pquery($list_query. " LIMIT $limit_start_rec, $list_max_entries_per_page", array());

$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

if($viewid !='')
$url_string .= "&viewname=".$viewid;

//Retreive the List View Table Header
$listview_header = getListViewHeader($focus,"Faq",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);

$listview_header_search = getSearchListHeaderValues($focus,"Faq",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("SEARCHLISTHEADER",$listview_header_search);

$listview_entries = getListViewEntries($focus,"Faq",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);
$smarty->assign("LISTENTITY", $listview_entries);
$smarty->assign("SELECT_SCRIPT", $view_script);

//Added to select Multiple records in multiple pages
$smarty->assign("SELECTEDIDS", $_REQUEST['selobjs']);
$smarty->assign("ALLSELECTEDIDS", $_REQUEST['allselobjs']);
$smarty->assign("CURRENT_PAGE_BOXES", implode(array_keys($listview_entries),";"));

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Faq","index",$viewid);
$alphabetical = AlphabeticalSearch($currentModule,'index','question','true','basic',"","","","",$viewid);
$fieldnames = getAdvSearchfields($module);
$criteria = getcriteria_options();
$smarty->assign("CRITERIA", $criteria);
$smarty->assign("FIELDNAMES", $fieldnames);
$smarty->assign("ALPHABETICAL", $alphabetical);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);
$smarty->assign("CUSTOMVIEW_OPTION",$customviewcombo_html);
$smarty->assign("VIEWID", $viewid);
$smarty->assign("SINGLE_MOD" ,'Faq');

if(isPermitted('Faq','EditView','') == 'yes') {
	$other_text['mass_edit'] = $app_strings[LBL_MASS_EDIT];
}
$smarty->assign("BUTTONS",$other_text);
$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

$_SESSION['faq_listquery'] = $list_query;

if(isset($_REQUEST['ajax']) && $_REQUEST['ajax'] != '')
	$smarty->display("ListViewEntries.tpl");
else	
	$smarty->display("ListView.tpl");
?>
