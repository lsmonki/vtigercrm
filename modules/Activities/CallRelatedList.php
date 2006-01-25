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

$smarty = new vtigerCRM_Smarty;

$category = getParentTab();
$smarty->assign("CATEGORY",$category);
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

if (isset($focus->name)) $smarty->assign("NAME", $focus->name);

$related_array = getRelatedLists("Activities", $focus);
$smarty->assign("id",$focus->id);
$smarty->assign("RELATEDLISTS", $related_array);
$smarty->assign("ID", $RECORD);
$smarty->assign("SINGLE_MOD", "Activity");
$smarty->assign("ACTIVITY_MODE",'Events');
$smarty->assign("MODULE", $MODULE);
$smarty->display("RelatedLists.tpl");

?>
