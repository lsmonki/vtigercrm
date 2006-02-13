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

 * $Header: /cvsroot/vtigercrm/vtiger_crm/themes/blue/header.php,v 1.16 2006/01/03 05:48:06 jerrydgeorge Exp $

 * Description:  Contains a variety of utility functions used to display UI

 * components such as form headers and footers.  Intended to be modified on a per

 * theme basis.

 ********************************************************************************/

require_once('Smarty_setup.php');

require_once("data/Tracker.php");

require_once("include/utils/utils.php");



global $currentModule;

global $moduleList;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty = new vtigerCRM_Smarty;
$header_array = getHeaderArray();
$smarty->assign("HEADERS",$header_array);
$smarty->assign("THEME",$theme);
$smarty->assign("IMAGEPATH",$image_path);

$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);

$smarty->assign("MODULE_NAME", $currentModule);

$smarty->assign("DATE", getDisplayDate(date("Y-m-d H:i")));

if ($current_user->first_name != '') $smarty->assign("CURRENT_USER", $current_user->first_name);

else $smarty->assign("CURRENT_USER", $current_user->user_name);

$smarty->assign("CURRENT_USER_ID", $current_user->id);

$smarty->assign("CATEGORY",getParentTab());

if (is_admin($current_user)) $smarty->assign("ADMIN_LINK", "<a href='index.php?module=Settings&action=index'>".$app_strings['LBL_SETTINGS']."</a>");



$module_path="modules/".$currentModule."/";

require_once('include/Menu.php');


global $module_menu;
$smarty->display("Header.tpl");
?>
