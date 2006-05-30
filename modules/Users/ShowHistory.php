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
require_once('include/utils/utils.php');

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

// focus_list is the means of passing data to a ListView.
global $focus_list;

if (!isset($where)) $where = "";

$seedLogin = new LoginHistory();

$ListView = new ListView();
$ListView->initNewXTemplate('modules/Users/ShowHistory.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LOGIN_HISTORY_BUTTON_LABEL']);
$ListView->setHeaderText($button);
$ListView->setQuery($where, "", "login_id", "LOGIN");
$ListView->processListView($seedLogin, "main", "LOGIN");

?>
