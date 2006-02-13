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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/themes/nature/header.php,v 1.23.2.1 2005/08/30 13:36:04 mickie Exp $
 * Description:  Contains a variety of utility functions used to display UI
 * components such as form headers and footers.  Intended to be modified on a per
 * theme basis.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once("include/utils.php");


global $currentModule;
global $moduleList;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

global $app_strings;

$module_path="modules/".$currentModule."/";
require_once('Menu.php');
global $module_menu;

require_once("include/Clock.php");
require_once("include/Calc.php");

$xtpl=new XTemplate ($theme_path."header.html");
$xtpl->assign("APP", $app_strings);


if(isset($app_strings['LBL_CHARSET']))
{
	$xtpl->assign("LBL_CHARSET", $app_strings['LBL_CHARSET']);
}
else
{
	$xtpl->assign("LBL_CHARSET", $default_charset);
}

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("MODULE_NAME", $currentModule);
$xtpl->assign("DATE", getDisplayDate(date("Y-m-d H:i")));
if ($current_user->first_name != '') $xtpl->assign("CURRENT_USER", $current_user->first_name);
else $xtpl->assign("CURRENT_USER", $current_user->user_name);

$xtpl->assign("CURRENT_USER_ID", $current_user->id);

if (is_admin($current_user)) $xtpl->assign("ADMIN_LINK", "<a class='toplink' href='index.php?module=Settings&action=index'><img src='".$image_path."/settings_top.gif' hspace='3' align='absmiddle' border='0'>".$app_strings['LBL_SETTINGS']."</a>");

if (isset($_REQUEST['query_string'])) $xtpl->assign("SEARCH", $_REQUEST['query_string']);

if ($action == "EditView" || $action == "Login") $xtpl->assign("ONLOAD", 'onload="set_focus()"');

// Loop through the module list.
// For each tab that is off, parse a tab_off.
// For the current tab, parse a tab_on
foreach($moduleList as $module_name)
{
	$xtpl->assign("MODULE_NAME", $app_list_strings['moduleList'][$module_name]);
	$xtpl->assign("MODULE_KEY", $module_name);
	if($module_name == $currentModule)
	{
		$xtpl->assign("CLASS_TABBG", "currentTabBg");
		$xtpl->assign("CLASS_TAB", "currentTab");
	}
	else
	{
		$xtpl->assign("CLASS_TABBG", "");
		$xtpl->assign("CLASS_TAB", "otherTab");
	}
	$xtpl->parse("main.tab");
}

// Assign the module name back to the current module.
$xtpl->assign("MODULE_NAME", $currentModule);

//Menu items to be displayed
$showmenu = 0;
foreach($module_menu as $menu_item)
{
	$after_this = current($module_menu);
	if($showmenu < 10)
	{

	if ($menu_item[1] != 'Deleted Items') {
		$xtpl->assign("URL", $menu_item[0]);
		$xtpl->assign("LABEL", $menu_item[1]);
		if (empty($after_this)) $xtpl->assign("SEPARATOR", "");
		else $xtpl->assign("SEPARATOR", "|");
	}
	else {
		$xtpl->assign("DELETED_ITEMS_URL", $menu_item[0]);
		$xtpl->assign("DELETED_ITEMS_LABEL", $menu_item[1]);
	}

	$xtpl->parse("main.sub_menu.sub_menu_item");
	}
        $showmenu++;
}
//Menu items to be displayed in drop down
$showmenu = 0;
$showmenu_drop = 10;
foreach($module_menu as $menu_item)
{
	if(($showmenu >= $showmenu_drop) && ($showmenu < count($module_menu)))
	{
		$after_this = current($module_menu);

		if ($menu_item[1] != 'Deleted Items') {
			$xtpl->assign("URL", $menu_item[0]);
			$xtpl->assign("LABEL", $menu_item[1]);
		}
		else {
			$xtpl->assign("DELETED_ITEMS_URL", $menu_item[0]);
			$xtpl->assign("DELETED_ITEMS_LABEL", $menu_item[1]);
		}

		$xtpl->parse("main.dropdown_sub_menu_item");
	}
	$showmenu++;
}

$xtpl->parse("main.sub_menu");

$xtpl->assign("TITLE", $app_strings['LBL_SEARCH']);
$xtpl->parse("main.left_form.left_form_search");
$xtpl->parse("main.left_form");

if($currentModule != "Rss")
{
$xtpl->assign("LASTVIEWED_TITLE", $app_strings['LBL_LAST_VIEWED']);

$tracker = new Tracker();
$history = $tracker->get_recently_viewed($current_user->id);

$current_row=1;

if (count($history) > 0) {
	foreach($history as $row)
	{
		$xtpl->assign("MODULE_NAME",$row['module_name']);
		$xtpl->assign("ROW_NUMBER",$current_row);
		$xtpl->assign("RECENT_LABEL",$row['item_summary']);

                if($row['module_name']=='Activities')
                {
                        $sql = 'select activitytype from activity where activityid = '.$row['item_id'];
                        $activitytype = $adb->query_result($adb->query($sql),0,'activitytype');
                        if($activitytype == 'Task')
                                $activity_mode = '&activity_mode=Task';
                        elseif($activitytype == 'Call' || $activitytype == 'Meeting')
                                $activity_mode = '&activity_mode=Events';
                } else {
                        $activity_mode = '';
                }

		$url_module = $row['module_name'];
		$url_action = 'DetailView';

		if($row['module_name']=='Orders')
		{		
			$xtpl->assign("MODULE_IMAGE_NAME","purchase_order");
		}
		elseif($row['module_name']=='SalesOrder')
		{		
			$xtpl->assign("MODULE_IMAGE_NAME","sales_order");
			$url_module = 'Orders';
			$url_action = 'SalesOrderDetailView';
		}
		elseif($row['module_name']=='Vendor')
		{		
			$xtpl->assign("MODULE_IMAGE_NAME","vendor");
			$url_module = 'Products';
			$url_action = 'VendorDetailView';
		}
		elseif($row['module_name']=='PriceBook')
		{		
			$xtpl->assign("MODULE_IMAGE_NAME","pricebook");
			$url_module = 'Products';
			$url_action = 'PriceBookDetailView';
		}
		else
		{
			$xtpl->assign("MODULE_IMAGE_NAME",$row['module_name']);
		}

		 

		$xtpl->assign("RECENT_URL","index.php?module=$url_module&action=$url_action&record=$row[item_id]$activity_mode");
		$activity_mode = '';
		$xtpl->parse("main.left_form.left_form_recent_view.left_form_recent_view_row");
		$current_row++;
	}
}
else {
		$xtpl->parse("main.left_form.left_form_recent_view.left_form_recent_view_empty");
}
$xtpl->parse("main.left_form.left_form_recent_view");
$xtpl->parse("main.left_form");
}





//check for the access for Create/Edit and enable or disable

//check for the presence of the currentModule and  also for EditView permission


$now_action =  $_REQUEST['action'];
$now_module = $_REQUEST['module'];

$tabid = getTabid($now_module);
$actionid = getActionid($now_action);

if($actionid == 3)
{
        $QuickCreateForm = getQuickCreate($tabid,$actionid);
}

if(isset($QuickCreateForm) && $QuickCreateForm == 'true')
{
	if($now_module == 'Faq')
                $currentModule = $now_module;

        require_once("modules/".$currentModule."/Forms.php");
        if (function_exists('get_new_record_form'))
        {
                $xtpl->assign("NEW_RECORD", get_new_record_form());
                $xtpl->parse("main.left_form_new_record");
        }
}

if($currentModule == "Rss")
{
	require_once("modules/".$currentModule."/Forms.php");
        if (function_exists('get_rssfeeds_form'))
        {
		$xtpl->assign("RSSFEEDS_TITLE","<div style='float:left'>".$app_strings['LBL_RSS_FEEDS'].":</div><div style='float:right;'><a href='javascript:openPopUp(\"addRssFeedIns\",this,\"index.php?action=Popup&module=Rss\",\"addRssFeedWin\",350,150,\"menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes\");' title='".$app_strings['LBL_ADD_RSS_FEEDS']."'>Add<img src='".$image_path."/addrss.gif' border=0 align=absmiddle></a>&nbsp;</div>");
		$xtpl->assign("RSSFEEDS", get_rssfeeds_form());
                $xtpl->parse("main.left_form_rss");
        }
}

$xtpl->assign("CLOCK_TITLE", $app_strings['LBL_WORLD_CLOCK']);
$xtpl->assign("WORLD_CLOCK", get_world_clock($image_path));
if($currentModule != "Rss" && $WORLD_CLOCK_DISPLAY == 'true')
{
	$xtpl->parse("main.left_form_clock");
}

$xtpl->assign("CALC_TITLE", $app_strings['LBL_CALCULATOR']);
$xtpl->assign("CALC", get_calc($image_path));
if($currentModule != "Rss" && $CALCULATOR_DISPLAY == 'true')
{
	$xtpl->parse("main.left_form_calculator");
}

$xtpl->parse("main");
$xtpl->out("main");

?>
