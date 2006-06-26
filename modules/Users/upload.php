<?php

/***********************************************************************************
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
require_once('include/utils/utils.php');
require_once('modules/Users/add2db.php');

global $app_strings;
global $mod_strings;
global $app_list_strings;
global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty = new vtigerCRM_Smarty;
//error handling
if(isset($_REQUEST['flag']) && $_REQUEST['flag'] != '')
{
	$flag = $_REQUEST['flag'];
	switch($flag)
	{
		case 1:
			$smarty->assign("ERRORFLAG","<font color='red'><B>File has to be a Document of type doc/msword</B></font>");
			break;
		default:
			$smarty->assign("ERRORFLAG","");
	}		
}

$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("UMOD", $mod_strings);
$smarty->assign("PARENTTAB", $_REQUEST['parenttab']);

$smarty->display('CreateWordTemplate.tpl');

?>






