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
require_once('include/utils/utils.php');
require_once('modules/Activities/Activity.php');
global $mod_strings;
global $app_strings;
$focus = new Activity();
$MODULE = $_REQUEST['module'];
$RECORD = $_REQUEST['record'];

if (isset($_REQUEST['record']) && $_REQUEST['record']!='') {
	$focus->retrieve_entity_info($_REQUEST['record'],"Activities");
	$focus->id = $_REQUEST['record'];
	$focus->name=$focus->column_fields['subject'];
	
	$log->debug("id is ".$focus->id);
	$log->debug("name is ".$focus->name);
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty = new vtigerCRM_Smarty;

$category = getParentTab();
$smarty->assign("CATEGORY",$category);
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("UPDATEINFO",updateInfo($focus->id));
if (isset($focus->name)) $smarty->assign("NAME", $focus->name);

$related_array = getRelatedLists("Activities", $focus);
$smarty->assign("id",$focus->id);
$smarty->assign("RELATEDLISTS", $related_array);
$smarty->assign("ID", $RECORD);
$smarty->assign("SINGLE_MOD", $app_strings['Activity']);
$smarty->assign("NEW_EVENT",$app_strings['LNK_NEW_EVENT']);
$smarty->assign("NEW_TASK",$app_strings['LNK_NEW_TASK']);
$smarty->assign("ACTIVITY_MODE",'Events');
$smarty->assign("MODULE", $MODULE);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);


$smarty->display("RelatedLists.tpl");

?>
