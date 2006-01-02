<?php

require_once('Smarty_setup.php');
require_once('modules/Activities/Activity.php');

$focus = new Activity();
$MODULE = $_REQUEST['module'];
$RECORD = $_REQUEST['record'];

if (isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
	$focus->retrieve_entity_info($_REQUEST['record'],"Activities");
	$focus->id = $_REQUEST['record'];
	$focus->name=$focus->column_fields['subject'];
	
	$log->debug("id is ".$focus->id);
	$log->debug("name is ".$focus->name);
}

$smarty = new vtigerCRM_Smarty;
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if (isset($focus->name)) $smarty->assign("NAME", $focus->name);

$related_array = getRelatedLists("Activities", $focus);
$smarty->assign("RELATEDLISTS", $related_array);
$smarty->assign("ID", $RECORD);
$smarty->assign("CurrentModule", $MODULE);
$smarty->display("RelatedLists.tpl");




?>
