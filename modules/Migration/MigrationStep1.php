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

global $app_strings,$app_list_strings,$mod_strings,$theme,$currentModule;

$smarty = new vtigerCRM_Smarty();


$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');


$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("MODULE","Migration");

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);

$smarty->assign("DB_DETAILS_CHECKED", 'checked');
$smarty->assign("SHOW_DB_DETAILS", 'block');
//this is to set the entered values when we could not proceed the migration and return to step1
if($_REQUEST['migration_option'] != '')
{
	if($_REQUEST['migration_option'] == 'db_details')
	{
		if($_REQUEST['old_host_name'] != '')
			$smarty->assign("OLD_HOST_NAME", $_REQUEST['old_host_name']);
		if($_REQUEST['old_port_no'] != '')
			$smarty->assign("OLD_PORT_NO", $_REQUEST['old_port_no']);
		if($_REQUEST['old_mysql_username'] != '')
			$smarty->assign("OLD_MYSQL_USERNAME", $_REQUEST['old_mysql_username']);
		if($_REQUEST['old_mysql_password'] != '')
			$smarty->assign("OLD_MYSQL_PASSWORD", $_REQUEST['old_mysql_password']);
		if($_REQUEST['old_dbname'] != '')
			$smarty->assign("OLD_DBNAME", $_REQUEST['old_dbname']);
	}
	else
	{
		$smarty->assign("DUMP_DETAILS_CHECKED", 'checked');
		$smarty->assign("DB_DETAILS_CHECKED", '');

		$smarty->assign("SHOW_DUMP_DETAILS", 'block');
		$smarty->assign("SHOW_DB_DETAILS", 'none');
	}
}

$smarty->display("MigrationStep1.tpl");


?>
