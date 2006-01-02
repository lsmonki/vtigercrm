<?php
require_once('Smarty_setup.php');
require_once('modules/Contacts/Contact.php');

$focus = new Contact();
$currentmodule = $_REQUEST['module'];
$RECORD = $_REQUEST['record'];

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve_entity_info($_REQUEST['record'],"Contacts");
    $focus->id = $_REQUEST['record'];
    $focus->name=$focus->column_fields['firstname'].' '.$focus->column_fields['lastname'];
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
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$smarty->assign("NAME",$focus->name);
$related_array = getRelatedLists("Contacts",$focus);
$smarty->assign("RELATEDLISTS", $related_array);
$smarty->assign("MODULE",$currentmodule);
$smarty->assign("ID",$RECORD );
$smarty->display("RelatedLists.tpl");

?>
