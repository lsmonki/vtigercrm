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
require_once('modules/Leads/Leads.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');
require_once('include/database/Postgres8.php');
require_once('include/DatabaseUtil.php');


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

$focus = new Leads();
$smarty = new vtigerCRM_Smarty;
$other_text=Array();

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

$_SESSION['LEADS_ORDER_BY'] = $order_by;
$_SESSION['LEADS_SORT_ORDER'] = $sorder;
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>

//for change owner and change status
$change_status = get_select_options_with_id($comboFieldArray['leadstatus_dom'], $focus->lead_status);
$smarty->assign("CHANGE_STATUS",$change_status);

$smarty->assign("CHANGE_OWNER",getUserslist());
$smarty->assign("CHANGE_GROUP_OWNER",getGroupslist());
	


if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	list($where, $ustring) = split("#@@#",getWhereCondition($currentModule));
	// we have a query
	$url_string .="&query=true".$ustring;
	$log->info("Here is the where clause for the list view: $where");
	$smarty->assign("SEARCH_URL",$url_string);
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
if(isPermitted('Leads','Delete','') == 'yes')
{
	$other_text['del'] =	$app_strings[LBL_MASS_DELETE];	

}
if(isPermitted('Emails','EditView','') == 'yes')
	$other_text['s_mail'] = $app_strings[LBL_SEND_MAIL_BUTTON];

if(isPermitted('Leads','EditView','') == 'yes')
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
	$_SESSION['export_where'] = $where;
}
else
	unset($_SESSION['export_where']);
/*
if(isset($order_by) && $order_by != '')
{
	$tablename = getTableNameForField('Leads',$order_by);
	$tablename = (($tablename != '')?($tablename."."):'');
	if( $adb->dbType == "pgsql")
 	    $query .= ' GROUP BY '.$tablename.$order_by;

	
        $query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
}
*/
if(isset($order_by) && $order_by != '')
{
	if($order_by == 'smownerid')
	{
		$query .= ' ORDER BY user_name '.$sorder;
	}
	else
	{
		$tablename = getTableNameForField('Leads',$order_by);
		$tablename = (($tablename != '')?($tablename."."):'');
		if( $adb->dbType == "pgsql")
			$query .= ' GROUP BY '.$tablename.$order_by;


		$query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
	}
}

//Retreiving the no of rows
$count_result = $adb->query( mkCountQuery( $query));
$noofrows = $adb->query_result($count_result,0,"count");

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
    $query = fixPostgresQuery( $query, $log, 0);


//limiting the query
if ($start_rec ==0) 
	$limit_start_rec = 0;
else
	$limit_start_rec = $start_rec -1;
	
 if( $adb->dbType == "pgsql")
     $list_result = $adb->pquery($query. " OFFSET $limit_start_rec LIMIT $list_max_entries_per_page", array());
 else
     $list_result = $adb->pquery($query. " LIMIT $limit_start_rec, $list_max_entries_per_page", array());


//mass merge for word templates -- *Raj*17/11
while($row = $adb->fetch_array($list_result))
{
	$ids[] = $row["crmid"];
}
if(isset($ids))
{
	$smarty->assign("ALLIDS", implode($ids,";"));
}
if(isPermitted("Leads","Merge") == 'yes') 
{
	$wordTemplateResult = fetchWordTemplateList("Leads");
	$tempCount = $adb->num_rows($wordTemplateResult);
	$tempVal = $adb->fetch_array($wordTemplateResult);
	for($templateCount=0;$templateCount<$tempCount;$templateCount++)
	{
		$optionString .="<option value=\"".$tempVal["templateid"]."\">" .$tempVal["filename"] ."</option>";
		$tempVal = $adb->fetch_array($wordTemplateResult);
	}
	if($tempCount > 0)
	{
		$smarty->assign("WORDTEMPLATEOPTIONS","<td>".$mod_strings['LBL_SELECT_TEMPLATE_TO_MAIL_MERGE']."</td><td style=\"padding-left:5px;padding-right:5px\"><select class=\"small\" name=\"mergefile\">".$optionString."</select></td>");

		$smarty->assign("MERGEBUTTON","<td><input title=\"$app_strings[LBL_MERGE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_MERGE_BUTTON_KEY]\" class=\"crmbutton small create\" onclick=\"return massMerge('Leads')\" type=\"submit\" name=\"Merge\" value=\" $app_strings[LBL_MERGE_BUTTON_LABEL]\"></td>");
	}
	else
        {
		global $current_user;
                require("user_privileges/user_privileges_".$current_user->id.".php");
                if($is_admin == true)
                {
			$smarty->assign("MERGEBUTTON",'<td><a href=index.php?module=Settings&action=upload&tempModule='.$currentModule.'&parenttab=Settings>'. $app_strings["LBL_CREATE_MERGE_TEMPLATE"].'</td>');
                }
        }


}
//mass merge for word templates

// Setting the record count string
//modified by rdhital
$start_rec = $navigation_array['start'];
$end_rec = $navigation_array['end_val']; 
//By Raju Ends
$_SESSION['nav_start']=$start_rec;
$_SESSION['nav_end']=$end_rec;

//limiting the query
if ($start_rec ==0) 
	$limit_start_rec = 0;
else
	$limit_start_rec = $start_rec -1;
	
 if( $adb->dbType == "pgsql")
     $list_result = $adb->pquery($query. " OFFSET $limit_start_rec LIMIT $list_max_entries_per_page", array());
 else
     $list_result = $adb->pquery($query. " LIMIT $limit_start_rec, $list_max_entries_per_page", array());

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

//Added to select Multiple records in multiple pages
$smarty->assign("SELECTEDIDS", $_REQUEST['selobjs']);
$smarty->assign("ALLSELECTEDIDS", $_REQUEST['allselobjs']);
$smarty->assign("CURRENT_PAGE_BOXES", implode(array_keys($listview_entries),";"));

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Leads","index",$viewid);
$alphabetical = AlphabeticalSearch($currentModule,'index','lastname','true','basic',"","","","",$viewid);
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
