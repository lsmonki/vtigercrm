<?php

require_once('Smarty_setup.php');
require_once('modules/Quotes/Quote.php');
$focus = new Quote();
$currentmodule = $_REQUEST['module'];
$RECORD = $_REQUEST['record'];
if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve_entity_info($_REQUEST['record'],"Quote");
    $focus->id = $_REQUEST['record'];
    $focus->name=$focus->column_fields['subject'];
$log->debug("id is ".$focus->id);
$log->debug("name is ".$focus->name);
}

$smarty = new vtigerCRM_Smarty;

if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
$related_array=getRelatedLists("Quotes",$focus);
$smarty->assign("RELATEDLISTS", $related_array);
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$smarty->assign("ID",$RECORD );
$smarty->assign("MODULE",$currentmodule);
$smarty->display("RelatedLists.tpl");
?>
