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

$hidden = '<form border="0" action="index.php" method="post" name="form" id="form">';
  $hidden .= '<input type="hidden" name="module">';
  $hidden .= '<input type="hidden" name="mode">';
  $hidden .= '<input type="hidden" name="activity_mode" value="Events">';
  $hidden .= '<input type="hidden" name="return_module" value="Activities">';
  $hidden .= '<input type="hidden" name="return_action" value="CallRelatedList">';
  $hidden .= '<input type="hidden" name="return_id" value="'.$focus->id.'">';
  $hidden .= '<input type="hidden" name="parent_id" value="'.$focus->id.'">';
  $hidden .= '<input type="hidden" name="action">';
  $smarty->assign("HIDDEN",$hidden);

$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if (isset($focus->name)) $smarty->assign("NAME", $focus->name);

$related_array = getRelatedLists("Activities", $focus);
$smarty->assign("id",$focus->id);
$smarty->assign("RELATEDLISTS", $related_array);
$smarty->assign("ID", $RECORD);
$smarty->assign("CurrentModule", $MODULE);
$smarty->display("RelatedLists.tpl");

?>
