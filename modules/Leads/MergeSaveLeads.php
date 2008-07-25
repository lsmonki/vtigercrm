<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
require_once('Smarty_setup.php');
require_once('include/database/PearDatabase.php');
require_once('database/DatabaseConnection.php');
require_once("modules/".$_REQUEST['module']."/".$_REQUEST['module'].".php");
require_once('modules/Users/Users.php');
require_once('include/utils/utils.php');
require_once('include/utils/CommonUtils.php');
require_once('themes/'.$theme.'/layout_utils.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;
global $current_language, $currentModule;

global $log,$adb;

$module=$_REQUEST['module'];
$return_module=$_REQUEST['return_module'];
$action=$_REQUEST['action'];
$return_action=$_REQUEST['return_action'];
$parenttab=$_REQUEST['parent'];
$merge_id=$_REQUEST['record'];
$focus = new Leads();
$recordids=$_REQUEST['pass_rec'];

mergeSave($module,$return_module,$parent_tab,$merge_id,$recordids);

$return_id = $focus->id;

?>
<script>
window.self.close();window.opener.location.href=window.opener.location.href;
</script>

