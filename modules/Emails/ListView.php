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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Emails/ListView.php,v 1.12 2005/04/18 10:37:49 samk Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('modules/Emails/Email.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');

$submenu = array('LBL_EMAILS_TITLE'=>'index.php?module=Emails&action=ListView.php','LBL_WEBMAILS_TITLE'=>'index.php?module=Webmails&action=index&parenttab=My Home Page');

$sec_arr = array('index.php?module=Emails&action=ListView.php'=>'Emails','index.php?module=Webmails&action=index&parenttab=parenttab=My Home Page'=>'Emails'); 

if($_REQUEST['ajax'] == '')
{
echo '<br>';

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
   <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
     <td class="tabStart">&nbsp;&nbsp;</td>
<?
	if(isset($_REQUEST['smodule']) && $_REQUEST['smodule'] != '')
	{
		$classname = "tabOff";
	}
	else
	{
		$classname = "tabOn";
	}
	$listView = "ListView.php";
	foreach($submenu as $label=>$filename)
	{
		$cur_mod = $sec_arr[$filename];
		$cur_tabid = getTabid($cur_mod);

		if($tab_per_Data[$cur_tabid] == 0)
		{
			list($lbl,$sname,$title)=split("_",$label);
			if(stristr($label,"EMAILS"))
			{
				echo '<td class="tabOn" nowrap><a href="index.php?module=Emails&action=ListView" class="tabLink">'.$mod_strings[$label].'</a>&nbsp;&nbsp;&nbsp;</td>';
				$listView = $filename;
				$classname = "tabOff";
			}
			elseif(stristr($label,$_REQUEST['smodule']))
			{
				echo '<td class="tabOn" nowrap><a href="index.php?module=Webmails&action=index&smodule='.$_REQUEST['smodule'].'&parenttab=My Home Page" class="tabLink">'.$mod_strings[$label].'</a></td>';	
				$listView = $filename;
				$classname = "tabOff";
			}
			else
			{
				echo '<td class="'.$classname.'" nowrap><a href="index.php?module=Webmails&action=index&smodule='.$sname.'&parenttab=My Home Page" class="tabLink">'.$mod_strings[$label].'</a></td>';	
			}
			$classname = "tabOff";
		}

	}
?>
     <td width="100%" class="tabEnd">&nbsp;</td>
   </tr>
 </table></td>
 </tr>
 </table>
 <br>
<?
}
global $app_strings;
global $mod_strings;

global $list_max_entries_per_page;

$log = LoggerManager::getLogger('email_list');

global $currentModule;

global $image_path;
global $theme;

$url_string = ''; // assigning http url string

$focus = new Email();
$smarty = new vtigerCRM_Smarty;
$other_text = Array();

//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>
if($_REQUEST['order_by'] != '')
	$order_by = $_REQUEST['order_by'];
else
	$order_by = (($_SESSION['EMAILS_ORDER_BY'] != '')?($_SESSION['EMAILS_ORDER_BY']):($focus->default_order_by));

if($_REQUEST['sorder'] != '')
	$sorder = $_REQUEST['sorder'];
else
	$sorder = (($_SESSION['EMAILS_SORT_ORDER'] != '')?($_SESSION['EMAILS_SORT_ORDER']):($focus->default_sort_order));

$_SESSION['EMAILS_ORDER_BY'] = $order_by;
$_SESSION['EMAILS_SORT_ORDER'] = $sorder;
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>


// focus_list is the means of passing data to a ListView.
global $focus_list;

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("Emails");
$viewid = $oCustomView->getViewId($currentModule);
$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid);
$viewnamedesc = $oCustomView->getCustomViewByCvid($viewid);
//<<<<<customview>>>>>

// Buttons and View options
if(isPermitted('Emails',2,'') == 'yes')
{
	$other_text['del'] = $app_strings[LBL_MASS_DELETE];
}

if($viewnamedesc['viewname'] == 'All')
{
	$smarty->assign("ALL", 'All');
}

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	$where=Search($currentModule);
	// we have a query
	$url_string .="&query=true";
	if (isset($_REQUEST['subject'])) $name = $_REQUEST['subject'];
	if (isset($_REQUEST['contactname'])) $contactname = $_REQUEST['contactname'];
	if(isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];

	$log->info("Here is the where clause for the list view: $where");
}

global $email_title;
$display_title = $mod_strings['LBL_LIST_FORM_TITLE'];
if($email_title)$display_title = $email_title;

//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("Emails");
	$list_query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Emails");
}
else
{
	$list_query = getListQuery("Emails");
}
//<<<<<<<<customview>>>>>>>>>


if(isset($where) && $where != '')
{
	$list_query .= " AND " .$where;
}

if(isset($order_by) && $order_by != '')
{
	$tablename = getTableNameForField('Emails',$order_by);
	$tablename = (($tablename != '')?($tablename."."):'');

        $list_query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
}
$list_result = $adb->query($list_query);

//Constructing the list view
$smarty->assign("CUSTOMVIEW_OPTION",$customviewcombo_html);
$smarty->assign("VIEWID", $viewid);
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'Email');
$smarty->assign("BUTTONS",$other_text);
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

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

$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header
if($viewid !='')
	$url_string .="&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"Emails",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);

$listview_header = getSearchListHeaderValues($focus,"Emails",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("SEARCHLISTHEADER",$listview_header_search);

$listview_entries = getListViewEntries($focus,"Emails",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$smarty->assign("LISTENTITY", $listview_entries);                                                                          $smarty->assign("SELECT_SCRIPT", $view_script);

$navigationOutput = getTableHeaderNavigation($navigation_array,$url_string,"Emails","index",$viewid);
$alphabetical = AlphabeticalSearch($currentModule,'index','subject','true','basic',"","","","",$viewid);
$smarty->assign("ALPHABETICAL", $alphabetical);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);


if(isset($_REQUEST['ajax']) && $_REQUEST['ajax'] != '')
	$smarty->display("ListViewEntries.tpl");
else	
	$smarty->display("ListView.tpl");
?>
