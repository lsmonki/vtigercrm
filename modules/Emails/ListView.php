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

$submenu = array('LBL_EMAILS_TITLE'=>'index.php?module=Emails&action=ListView.php','LBL_WEBMAILS_TITLE'=>'index.php?module=squirrelmail-1.4.4&action=redirect');
$sec_arr = array('index.php?module=Emails&action=ListView.php'=>'Emails','index.php?module=squirrelmail-1.4.4&action=redirect'=>'Emails'); 
echo '<br>';
?>
<!--table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
   <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
     <td class="tabStart">&nbsp;&nbsp;</td-->
<?
	/*if(isset($_REQUEST['smodule']) && $_REQUEST['smodule'] != '')
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
				echo '<td class="tabOn" nowrap><a href="index.php?module=Emails&action=ListView" class="tabLink">'.$mod_strings[$label].'</a></td>';
				$listView = $filename;
				$classname = "tabOff";
			}
			elseif(stristr($label,$_REQUEST['smodule']))
			{
				echo '<td class="tabOn" nowrap><a href="index.php?module=squirrelmail-1.4.4&action=redirect&smodule='.$_REQUEST['smodule'].'" class="tabLink">'.$mod_strings[$label].'</a></td>';	
				$listView = $filename;
				$classname = "tabOff";
			}
			else
			{
				echo '<td class="'.$classname.'" nowrap><a href="index.php?module=squirrelmail-1.4.4&action=redirect&smodule='.$sname.'" class="tabLink">'.$mod_strings[$label].'</a></td>';	
			}
			$classname = "tabOff";
		}

	}*/
?>
     <!--td width="100%" class="tabEnd">&nbsp;</td>
   </tr>
 </table></td>
 </tr>
 </table>
 <br-->
<?

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
$customviewcombo_html = $oCustomView->getCustomViewCombo();
$viewid = $oCustomView->getViewId($currentModule);
$viewnamedesc = $oCustomView->getCustomViewByCvid($viewid);
//<<<<<customview>>>>>

// Buttons and View options
if(isPermitted('Emails',2,'') == 'yes')
{
	$other_text['del'] = $app_strings[LBL_MASS_DELETE];
}

if($viewnamedesc['viewname'] == 'All')
{
	$cvHTML = '<td><a href="index.php?module=Emails&action=CustomView">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>
                <span class="small">|</span>
                <span class="small" disabled>'.$app_strings['LNK_CV_EDIT'].'</span>
                <span class="small">|</span>
                <span class="small" disabled>'.$app_strings['LNK_CV_DELETE'].'</span></td>';
}
else
{
	$cvHTML = '<td><a href="index.php?module=Emails&action=CustomView">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>
                <span class="small">|</span>
                <a href="index.php?module=Emails&action=CustomView&record='.$viewid.'">'.$app_strings['LNK_CV_EDIT'].'</a>
                <span class="small">|</span>
                <a href="index.php?module=CustomView&action=Delete&dmodule=Emails&record='.$viewid.'" >'.$app_strings['LNK_CV_DELETE'].'</a></td>';
}

$customstrings = '<td>'.$app_strings[LBL_VIEW].'</td>
                  <td style="padding-left:5px;padding-right:5px">
                        <SELECT NAME="viewname" class="small" onchange="showDefaultCustomView(this)">
                                '.$customviewcombo_html.'
                        </SELECT></td>
                        '.$cvHTML;


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

$smarty->assign("CUSTOMVIEW",$customstrings);
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
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);


$smarty->display("ListView.tpl");
?>
