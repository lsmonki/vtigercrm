<?php
require_once('Smarty_setup.php');
require_once('modules/Contacts/Contact.php');

$focus = new Contact();
$currentmodule = $_REQUEST['module'];
$RECORD = $_REQUEST['record'];

global $adb;
$sql = $adb->query('select accountid from contactdetails where contactid='.$id);
$accountid = $adb->query_result($sql,0,'accountid');
if($accountid == 0) $accountid='';


if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    $focus->retrieve_entity_info($_REQUEST['record'],"Contacts");
    $focus->id = $_REQUEST['record'];
    $focus->name=$focus->column_fields['firstname'].' '.$focus->column_fields['lastname'];

$log->debug("id is ".$focus->id);

$log->debug("name is ".$focus->name);

}

$smarty = new vtigerCRM_Smarty;

$hidden = '<form border="0" action="index.php" method="post" name="form" id="form">';
	$hidden .= '<input type="hidden" name="module">';
	$hidden .= '<input type="hidden" name="mode">';
	$hidden .= '<input type="hidden" name="contact_id" value="'.$focus->id.'">';
	$hidden .= '<input type="hidden" name="account_id" value="'.$accountid.'">';
	$hidden .= '<input type="hidden" name="return_module" value="Contacts">';
	$hidden .= '<input type="hidden" name="return_action" value="CallRelatedList">';
	$hidden .= '<input type="hidden" name="return_id" value="'.$focus->id.'">';
	$hidden .= '<input type="hidden" name="parent_id" value="'.$focus->id.'">';
	$hidden .= '<input type="hidden" name="action">';
	$smarty->assign("HIDDEN",$hidden);
	

if(isset($_request['isduplicate']) && $_request['isduplicate'] == 'true') {
        $focus->id = "";
}
$category = getparenttab();
$smarty->assign("CATEGORY",$category);

$smarty->assign("id",$focus->id);
$smarty->assign("NAME",$focus->name);
$related_array = getrelatedlists("Contacts",$focus);
$smarty->assign("RELATEDLISTS", $related_array);
$smarty->assign("MODULE",$currentmodule);
$smarty->assign("ID",$record );
$smarty->display("RelatedLists.tpl");
?>
