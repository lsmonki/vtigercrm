<?php

require_once('Smarty_setup.php');
require_once('modules/Leads/Lead.php');
$focus = new Lead();
$MODULE = $_REQUEST['module'];
$RECORD = $_REQUEST['record'];
$currentmodule = $_REQUEST['module'];
if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve_entity_info($_REQUEST['record'],"Leads");
    $focus->id = $_REQUEST['record'];
    $focus->firstname=$focus->column_fields['firstname'];
    $focus->lastname=$focus->column_fields['lastname'];
//$vtlog->logthis("id is ".$focus->id,'debug');
$log->debug("id is ".$focus->id);
//$vtlog->logthis("name is ".$focus->name,'debug');
$log->debug("name is ".$focus->name);
//$vtlog->logthis("name is ".$focus->name,'debug');
}

$smarty = new vtigerCRM_Smarty;

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
        $focus->id = "";
}
$smarty->assign("NAME",$focus->lastname.' '.$focus->firstname);
$related_array = getRelatedLists("Leads",$focus);
$smarty->assign("RELATEDLISTS", $related_array);
$smarty->assign("MODULE", $currentmodule);
$smarty->assign("ID",$RECORD );
$smarty->display("RelatedLists.tpl");

?>
