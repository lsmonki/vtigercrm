<?php

require_once('Smarty_setup.php');
require_once('modules/HelpDesk/HelpDesk.php');
$focus = new HelpDesk();
$currentmodule = $_REQUEST['module'];
$RECORD = $_REQUEST['record'];
if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve_entity_info($_REQUEST['record'],"HelpDesk");
    $focus->id = $_REQUEST['record'];
    $focus->name=$focus->column_fields['groupname'];
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

if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
$related_array=getRelatedLists("HelpDesk",$focus);
$smarty->assign("RELATEDLISTS", $related_array);
//echo '<pre>';print_r($related_array);echo '</pre>';
$smarty->assign("ID",$RECORD );
$smarty->assign("MODULE",$currentmodule);
$smarty->display("RelatedLists.tpl");
?>
