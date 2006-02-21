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
 * Contributor(s): ______________________________________..
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Notes/ListView.php,v 1.13 2005/03/21 18:15:04 ray Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('modules/Notes/Note.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/utils/utils.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');

global $app_strings;
global $mod_strings;

global $list_max_entries_per_page;
$log = LoggerManager::getLogger('note_list');

global $currentModule;

global $image_path;
global $theme;
$category = getParentTab();


// focus_list is the means of passing data to a ListView.
global $focus_list;

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("Notes");
$customviewcombo_html = $oCustomView->getCustomViewCombo();
$viewid = $oCustomView->getViewId($currentModule);
$viewnamedesc = $oCustomView->getCustomViewByCvid($viewid);
//<<<<<customview>>>>>

if (!isset($where)) $where = "";

$url_string = ''; // assigning http url string

$focus = new Note();
$smarty = new vtigerCRM_Smarty;
$other_text = Array();

//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>
if($_REQUEST['order_by'] != '')
	$order_by = $_REQUEST['order_by'];
else
	$order_by = (($_SESSION['NOTES_ORDER_BY'] != '')?($_SESSION['NOTES_ORDER_BY']):($focus->default_order_by));

if($_REQUEST['sorder'] != '')
	$sorder = $_REQUEST['sorder'];
else
	$sorder = (($_SESSION['NOTES_SORT_ORDER'] != '')?($_SESSION['NOTES_SORT_ORDER']):($focus->default_sort_order));

$_SESSION['NOTES_ORDER_BY'] = $order_by;
$_SESSION['NOTES_SORT_ORDER'] = $sorder;
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>


if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	$where=Search($currentModule);
	// we have a query
	$url_string .="&query=true";
	
	$log->info("Here is the where clause for the list view: $where");

}
if(isPermitted('Notes',2,'') == 'yes')
{
	$other_text['del'] = $app_strings[LBL_MASS_DELETE];
}

if($viewnamedesc['viewname'] == 'All')
{
$cvHTML = '<td><a href="index.php?module=Notes&action=CustomView">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>
<span class="small">|</span>
<span class="small" disabled>'.$app_strings['LNK_CV_EDIT'].'</span>
<span class="small">|</span>
<span class="small" disabled>'.$app_strings['LNK_CV_DELETE'].'</span></td>';
}else
{
$cvHTML = '<td><a href="index.php?module=Notes&action=CustomView">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>
<span class="small">|</span>
<a href="index.php?module=Notes&action=CustomView&record='.$viewid.'">'.$app_strings['LNK_CV_EDIT'].'</a>
<span class="small">|</span>
<a href="index.php?module=CustomView&action=Delete&dmodule=Notes&record='.$viewid.'">'.$app_strings['LNK_CV_DELETE'].'</a></td>';
}

$customstrings = '<td>'.$app_strings[LBL_VIEW].'</td>
			<td style="padding-left:5px;padding-right:5px">
                        <SELECT NAME="viewname" class="small" onchange="showDefaultCustomView(this)">
				'.$customviewcombo_html.'
                        </SELECT></td>
			'.$cvHTML;


global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$smarty->assign("CUSTOMVIEW",$customstrings);
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'Note');
$smarty->assign("BUTTONS",$other_text);
$smarty->assign("CATEGORY",$category);

//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("Notes");
	$query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Notes");
}else
{
	$query = getListQuery("Notes");
}
//<<<<<<<<customview>>>>>>>>>

if(isset($where) && $where != '')
{
        $query .= ' and '.$where;
}

$query .= ' group by notes.notesid';

if(isset($order_by) && $order_by != '')
{
	$tablename = getTableNameForField('Notes',$order_by);
	$tablename = (($tablename != '')?($tablename."."):'');

        $query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
}

$list_result = $adb->query($query);

//Retreiving the no of rows
$noofrows = $adb->num_rows($list_result);

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


$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;


//Retreive the List View Table Header
if($viewid !='')
$url_string .="&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"Notes",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);

$listview_header_search = getSearchListHeaderValues($focus,"Notes",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("SEARCHLISTHEADER",$listview_header_search);

$listview_entries = getListViewEntries($focus,"Notes",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$smarty->assign("LISTENTITY", $listview_entries);
$smarty->assign("SELECT_SCRIPT", $view_script);

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Notes","index",$viewid);
$alphabetical = AlphabeticalSearch($currentModule,'index','title','true','basic',"","","","",$viewid);
$smarty->assign("ALPHABETICAL", $alphabetical);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);


$smarty->display("ListView.tpl");
?>
