<?php

/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Users/LoginHistory.php');
require_once('modules/Users/User.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/database/PearDatabase.php');
#require_once('modules/Users/User.php');
require_once('include/utils.php');

global $app_strings;
global $mod_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Users');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('login_list');

global $currentModule;

global $theme;

$focus = new User();

if(isset($_REQUEST['record'])) {
	$focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

echo get_module_title($mod_strings['LBL_MODULE_NAME'], $current_module_strings['LBL_LOGIN_HISTORY_TITLE'], true); 
echo "\n<BR>\n";

// focus_list is the means of passing data to a ListView.
global $focus_list;

if (!isset($where)) $where = "";

$seedLogin = new LoginHistory();

$button  = "<table cellspacing='0' cellpadding='1' border='0'><form border='0' method='post' name='DetailView' action='index.php'>\n";

$button .= "<tr><td>\n";
$button .= "<input type='hidden' name='module' value='Users'>\n";
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input type='hidden' name='record' value='".$focus->id."'>\n";
$button .= "<input type='hidden' name='action'>\n";
$button .= "<input title='".$app_strings['LBL_CANCEL_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CANCEL_BUTTON_KEY']."' class='button' onclick=\"this.form.action.value='DetailView'; this.form.module.value='Users'; this.form.record.value='$focus->id'\" type='submit' name='button' value='".$app_strings['LBL_CANCEL_BUTTON_LABEL']."'></td></tr>";

$button .= "</form></table>";

$ListView = new ListView();
$ListView->initNewXTemplate('modules/Users/ShowHistory.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LOGIN_HISTORY_BUTTON_LABEL']);
$ListView->setHeaderText($button);
$ListView->setQuery($where, "", "login_id", "LOGIN");
$ListView->processListView($seedLogin, "main", "LOGIN");

?>
