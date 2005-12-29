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

	}
?>
     <td width="100%" class="tabEnd">&nbsp;</td>
   </tr>
 </table></td>
 </tr>
 </table>
 <br>
<?

global $app_strings;
global $app_list_strings;
global $mod_strings;

global $list_max_entries_per_page;
global $urlPrefix;

$current_module_strings = return_module_language($current_language, 'Emails');
$log = LoggerManager::getLogger('email_list');

global $currentModule;

global $image_path;
global $theme;

if(isset($_REQUEST['category']) && $_REQUEST['category'] !='')
{
	$category = $_REQUEST['category'];
}
else
{
	$category = getParentTabFromModule($currentModule);
}

$url_string = ''; // assigning http url string

$focus = new Email();

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

/*if(!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') 
{
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Emails/SearchForm.html');
	$search_form->assign("MOD", $mod_strings);
	$search_form->assign("APP", $app_strings);

	$search_form->assign("VIEWID",$viewid);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Emails','index','subject','true','basic',"","","","",$viewid));

	if(isset($_REQUEST['query'])) 
	{
		if(isset($_REQUEST['subject'])) $search_form->assign("NAME", $_REQUEST['subject']);
		if(isset($_REQUEST['contactname'])) $search_form->assign("CONTACT_NAME", $_REQUEST['contactname']);
		if(isset($_REQUEST['current_user_only'])) $search_form->assign("CURRENT_USER_ONLY", "checked");
	}
	$search_form->parse("main");

	echo get_form_header($mod_strings['LBL_SEARCH_FORM_TITLE'], "", false);
	$search_form->out("main");
	echo get_form_footer();
	echo "\n<BR>\n";
}
*/
// Buttons and View options
$other_text = '	<form name="massdelete" method="POST">
	<input name="idlist" type="hidden">
	<input name="viewname" type="hidden" value="'.$viewid.'">
	<input name="change_status" type="hidden">
		<td>';
if(isPermitted('Emails',2,'') == 'yes')
{
	$other_text .=	'<input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/></td></form>';
}
$other_text .= 	'</td>';

if($viewid == 0)
{
	$cvHTML = '<span class="bodyText disabled">'.$app_strings['LNK_CV_EDIT'].'</span>
		<span class="sep">|</span>
		<span class="bodyText disabled">'.$app_strings['LNK_CV_DELETE'].'</span><span class="sep">|</span>
		<a href="index.php?module=Emails&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}
else
{
	$cvHTML = '<a href="index.php?module=Emails&action=CustomView&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_EDIT'].'</a>
		<span class="sep">|</span>
		<a href="index.php?module=CustomView&action=Delete&dmodule=Emails&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_DELETE'].'</a>
		<span class="sep">|</span>
		<a href="index.php?module=Emails&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}

$customstrings = '<td align="right">'.$app_strings[LBL_VIEW].'
                        <SELECT NAME="view" onchange="showDefaultCustomView(this)">
                                <OPTION VALUE="0">'.$mod_strings[LBL_ALL].'</option>
				'.$customviewcombo_html.'
                        </SELECT>
			'.$cvHTML.'
                        </td>';


$where = "";


if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	// we have a query
	$url_string .="&query=true";
	if (isset($_REQUEST['subject'])) $name = $_REQUEST['subject'];
	if (isset($_REQUEST['contactname'])) $contactname = $_REQUEST['contactname'];
	if(isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];

	$where_clauses = Array();

	if(isset($current_user_only) && $current_user_only != "")
	{
		array_push($where_clauses, "crmentity.smownerid='$current_user->id'");
		$url_string .= "&current_user_only=".$current_user_only;
	}
	if(isset($name) && $name != '')
	{
		array_push($where_clauses, "activity.subject like ".PearDatabase::quote($name.'%')."");
		$url_string .= "&subject=".$name;

	}
	if(isset($contactname) && $contactname != '')
	{
		array_push($where_clauses, "(contactdetails.firstname like ".PearDatabase::quote($contactname.'%')." OR contactdetails.lastname like ".PearDatabase::quote($contactname.'%').")");
		$url_string .= "&contactname=".$contactname;
	}

	$where = "";
	if (isset($where_clauses)) 
	{
		foreach($where_clauses as $clause)
		{
			if($where != "")
				$where .= " and ";
			$where .= $clause;
		}
	}
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
		len=document.massdelete.view.length;
		for(i=0;i<len;i++)
		{
			if(document.massdelete.view[i].value == '$viewid')
				document.massdelete.view[i].selected = true;
		}
	}
	set_selected();
	</script>";

$customview= get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],$other_text, false);
$smarty = new vtigerCRM_Smarty;
$smarty->assign("CUSTOMVIEW",$customstrings);
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("BUTTONS",$other_text);
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

$listview_entries = getListViewEntries($focus,"Emails",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$smarty->assign("LISTENTITY", $listview_entries);                                                                          $smarty->assign("SELECT_SCRIPT", $view_script);

$navigationOutput = getTableHeaderNavigation($navigation_array,$url_string,"Emails","index",$viewid);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);


$smarty->display("ListView.tpl");
?>
