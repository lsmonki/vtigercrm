<?php

require_once('Smarty_setup.php');
require_once('modules/Emails/Email.php');
$focus = new Email();
$currentmodule = $_REQUEST['module'];
$RECORD = $_REQUEST['record'];
if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve_entity_info($_REQUEST['record'],"Emails");
    $focus->id = $_REQUEST['record'];
    $focus->name=$focus->column_fields['subject'];
//$vtlog->logthis("id is ".$focus->id,'debug');
$log->debug("id is ".$focus->id);
//$vtlog->logthis("name is ".$focus->name,'debug');
$log->debug("name is ".$focus->name);
//$vtlog->logthis("name is ".$focus->name,'debug');
}

$smarty = new vtigerCRM_Smarty;
if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
$related_array=getRelatedLists("Emails",$focus);
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$smarty->assign("RELATEDLISTS", $related_array);
$smarty->assign("ID",$RECORD );
$smarty->assign("MODULE",$currentmodule);
$smarty->display("RelatedLists.tpl");
?>
