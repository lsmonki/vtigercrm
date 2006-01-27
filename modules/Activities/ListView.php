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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Activities/ListView.php,v 1.14 2005/03/26 09:45:00 samk Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('modules/Activities/Activity.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');

global $app_strings;
global $list_max_entries_per_page;

$log = LoggerManager::getLogger('task_list');

global $currentModule;

global $image_path;
global $theme;

// focus_list is the means of passing data to a ListView.
global $focus_list;

if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];

$focus = new Activity();
$smarty = new vtigerCRM_Smarty;
$other_text = Array();

//<<<<<<< sort ordering >>>>>>>>>>>>>
$sorder = $focus->getSortOrder();
$order_by = $focus->getOrderBy();

$_SESSION['ACTIVITIES_ORDER_BY'] = $order_by;
$_SESSION['ACTIVITIES_SORT_ORDER'] = $sorder;
//<<<<<<< sort ordering >>>>>>>>>>>>>


//<<<<cutomview>>>>>>>
$oCustomView = new CustomView($currentModule);
$customviewcombo_html = $oCustomView->getCustomViewCombo();
$viewid = $oCustomView->getViewId($currentModule);
$viewnamedesc = $oCustomView->getCustomViewByCvid($viewid);
//<<<<<customview>>>>>


$where = "";

$url_string = ''; // assigning http url string

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	$where=Search($currentModule);
	// we have a query
	$url_string .="&query=true";
	
	$log->info("Here is the where clause for the list view: $where");

}


if($viewnamedesc['viewname'] == 'All')
{
$cvHTML = '<td><a href="index.php?module=Activities&action=CustomView">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>
<span class="small">|</span>
<span class="small" disabled>'.$app_strings['LNK_CV_EDIT'].'</span>
<span class="small">|</span>
<span class="small" disabled>'.$app_strings['LNK_CV_DELETE'].'</span></td>';
}
else
{
$cvHTML = '<td><a href="index.php?module=Activities&action=CustomView">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>
<span class="small">|</span>
<a href="index.php?module=Activities&action=CustomView&record='.$viewid.'">'.$app_strings['LNK_CV_EDIT'].'</a>
<span class="small">|</span>
<a href="index.php?module=CustomView&action=Delete&dmodule=Activities&record='.$viewid.'">'.$app_strings['LNK_CV_DELETE'].'</a></td>';
}

if(isPermitted("Activities",2,$_REQUEST['record']) == 'yes')
{
	$other_text['del'] = $app_strings[LBL_MASS_DELETE];
}
	$customviewstrings='<td>'.$app_strings['LBL_VIEW'].'</td>
			<td style="padding-left:5px;padding-right:5px">
			<SELECT NAME="viewname" class="small" onchange="showDefaultCustomView(this)">
				'.$customviewcombo_html.'
			</SELECT></td>
			'.$cvHTML;
//

global  $task_title;
$title_display = $current_module_strings['LBL_LIST_FORM_TITLE'];
if ($task_title) $title_display= $task_title;

//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("Activities");
	$list_query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Activities");
}else
{
	$list_query = getListQuery("Activities");
}
//<<<<<<<<customview>>>>>>>>>

if(isset($where) && $where != '')
{
	$list_query .= " AND " .$where;
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

$list_query .= ' GROUP BY crmentity.crmid'; //Appeding for the recurring event by jaguar

if(isset($order_by) && $order_by != '')
{
	$tablename = getTableNameForField('Activities',$order_by);
	$tablename = (($tablename != '')?($tablename."."):'');

        $list_query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
}

$list_result = $adb->query($list_query);

//Constructing the list view

$smarty->assign("CUSTOMVIEW", $customviewstrings);
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'Activity');
$smarty->assign("BUTTONS",$other_text);
$smarty->assign("NEW_EVENT",$app_strings['LNK_NEW_EVENT']);
$smarty->assign("NEW_TASK",$app_strings['LNK_NEW_TASK']);


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
//By raju Ends


$record_string= $app_strings['LBL_SHOWING']." " .$start_rec." - ".$end_rec." " .$app_strings['LBL_LIST_OF'] ." ".$noofrows;

//Retreive the List View Table Header
if($viewid !='')
$url_string .="&viewname=".$viewid;

//Cambiado code to add close button in custom field
if (($viewid!=0)&&($viewid!="")){
  if (!isset($oCustomView->list_fields['Close'])) $oCustomView->list_fields['Close']=array ( 'activity' => 'status' );
  if (!isset($oCustomView->list_fields_name['Close'])) $oCustomView->list_fields_name['Close']='status';
}
$listview_header = getListViewHeader($focus,"Activities",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);

$listview_header_search=getSearchListHeaderValues($focus,"Activities",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("SEARCHLISTHEADER", $listview_header_search);

$listview_entries = getListViewEntries($focus,"Activities",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$smarty->assign("LISTENTITY", $listview_entries);
$smarty->assign("SELECT_SCRIPT", $view_script);

$navigationOutput = getTableHeaderNavigation($navigation_array,$url_string,"Activities","index",$viewid);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);
$category = getParentTab();
$smarty->assign("CATEGORY",$category);



$smarty->display("ListView.tpl");
?>
