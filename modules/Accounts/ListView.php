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

require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('modules/Accounts/Accounts.php');
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

$log = LoggerManager::getLogger('account_list');

global $currentModule;
global $theme;

$category = getParentTab();

// focus_list is the means of passing data to a ListView.
global $focus_list;

if (!isset($where)) $where = "";

$url_string = '';

$focus = new Accounts();
// Initialize sort by fields
$focus->initSortbyField('Accounts');
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
        $smarty->assign("ERROR","The User does not have permission to Change/Delete ".$errormsg." ".$currentModule);
}else
{
        $smarty->assign("ERROR","");
}
					
//<<<<<<< sort ordering >>>>>>>>>>>>>
$sorder = $focus->getSortOrder();
$order_by = $focus->getOrderBy();

$_SESSION['ACCOUNTS_ORDER_BY'] = $order_by;
$_SESSION['ACCOUNTS_SORT_ORDER'] = $sorder;
//<<<<<<< sort ordering >>>>>>>>>>>>>

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView($currentModule);
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
		<td rowspan='2' width='11%'><img src= " . vtiger_imageurl('denied.gif', $theme) . " ></td>
		<td style='border-bottom: 1px solid rgb(204, 204, 204);' nowrap='nowrap' width='70%'>
			<span class='genHeaderSmall'>$app_strings[LBL_PERMISSION]</span></td>
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

if(isPermitted('Accounts','Delete','') == 'yes')
{
	$other_text['del'] = $app_strings[LBL_MASS_DELETE];
}
if(isPermitted('Accounts','EditView','') == 'yes')
{
	$other_text['mass_edit'] = $app_strings[LBL_MASS_EDIT];
	$other_text['c_owner'] = $app_strings[LBL_CHANGE_OWNER];
}
if(isPermitted('Emails','EditView','') == 'yes')
{
	$other_text['s_mail'] = $app_strings[LBL_SEND_MAIL_BUTTON];
}		
if(isset($CActionDtls))
{
	$other_text['s_cmail'] = $app_strings[LBL_SEND_CUSTOM_MAIL_BUTTON];
}
// mailer export 
if(isPermitted('Accounts','Export','') == 'yes')
{
  $other_text['mailer_exp'] = $mod_strings[LBL_MAILER_EXPORT];
}
// end of mailer export
if($viewnamedesc['viewname'] == 'All')
{
	$smarty->assign("ALL", 'All');
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("CUSTOMVIEW_OPTION",$customviewcombo_html);
$smarty->assign("VIEWID", $viewid);
$smarty->assign("BUTTONS",$other_text);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'Account');


//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("Accounts");
	$query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Accounts");
}else
{
	$query = getListQuery("Accounts");
}
//<<<<<<<<customview>>>>>>>>>

if(isset($where) && $where != '')
{
	$query .= ' and '.$where;
	$_SESSION['export_where'] = $where;
}
else
   unset($_SESSION['export_where']);

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
// mailer_export 
if (isset($other_text['mailer_exp']))
{
  $view_script .= "<script language='javascript'>
	function mailer_export()
	{
    document.massdelete.action.value=\"MailerExport\";
    document.massdelete.step.value=\"ask\";
    window.locate=\"index.php?module=Accounts&action=MailerExport&from=Accounts&step=ask\";
	}
	</script>";
}
// end of mailer export 
if(isset($order_by) && $order_by != '')
{	
	if($order_by == 'smownerid')
        {
		if( $adb->dbType == "pgsql")
 		    $query .= ' GROUP BY user_name';
                $query .= ' ORDER BY user_name '.$sorder;
        }
        else
        {
		$tablename = getTableNameForField('Accounts',$order_by);
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

//mass merge for word templates -- *Raj*17/11
while($row = $adb->fetch_array($list_result))
{
	$ids[] = $row["crmid"];
}
if(isset($ids))
{
	$smarty->assign("ALLIDS", implode($ids,";"));
}
if(isPermitted("Accounts","Merge") == 'yes') 
{
	$wordTemplateResult = fetchWordTemplateList("Accounts");
	$tempCount = $adb->num_rows($wordTemplateResult);
	$tempVal = $adb->fetch_array($wordTemplateResult);
	for($templateCount=0;$templateCount<$tempCount;$templateCount++)
	{
		$optionString .="<option value=\"".$tempVal["templateid"]."\">" .$tempVal["filename"] ."</option>";
		$tempVal = $adb->fetch_array($wordTemplateResult);
	}
	if($tempCount > 0)
	{
		$smarty->assign("WORDTEMPLATEOPTIONS","<td>".$app_strings['LBL_SELECT_TEMPLATE_TO_MAIL_MERGE']."</td><td style=\"padding-left:5px;padding-right:5px\"><select class=\"small\" name=\"mergefile\">".$optionString."</select></td>");

		$smarty->assign("MERGEBUTTON","<td><input title=\"$app_strings[LBL_MERGE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_MERGE_BUTTON_KEY]\" class=\"crmbutton small create\" onclick=\"return massMerge('Accounts')\" type=\"submit\" name=\"Merge\" value=\" $app_strings[LBL_MERGE_BUTTON_LABEL]\"></td>");
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

$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header
if($viewid !='')
$url_string .= "&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"Accounts",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);

$listview_header_search=getSearchListHeaderValues($focus,"Accounts",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("SEARCHLISTHEADER", $listview_header_search);

$listview_entries = getListViewEntries($focus,"Accounts",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);

$smarty->assign("LISTENTITY", $listview_entries);
$smarty->assign("SELECT_SCRIPT", $view_script);
$smarty->assign("CATEGORY",$category);

$smarty->assign("AVALABLE_FIELDS", getMergeFields($currentModule,"available_fields"));
$smarty->assign("FIELDS_TO_MERGE", getMergeFields($currentModule,"fileds_to_merge"));

//Added to select Multiple records in multiple pages
$smarty->assign("SELECTEDIDS", $_REQUEST['selobjs']);
$smarty->assign("ALLSELECTEDIDS", $_REQUEST['allselobjs']);
$smarty->assign("CURRENT_PAGE_BOXES", implode(array_keys($listview_entries),";"));

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Accounts","index",$viewid);
$alphabetical = AlphabeticalSearch($currentModule,'index','accountname','true','basic',"","","","",$viewid);
$fieldnames = getAdvSearchfields($currentModule);
$criteria = getcriteria_options();
$smarty->assign("CRITERIA", $criteria);
$smarty->assign("FIELDNAMES", $fieldnames);
$smarty->assign("ALPHABETICAL", $alphabetical);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);

$check_button = Button_Check($currentModule); 
$smarty->assign("CHECK", $check_button);

$_SESSION['accounts_listquery'] = $query;

if(isset($_REQUEST['ajax']) && $_REQUEST['ajax'] != '')
	$smarty->display("ListViewEntries.tpl");
else	
	$smarty->display("ListView.tpl");

?>
