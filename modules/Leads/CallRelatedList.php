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
$log->debug("id is ".$focus->id);
$log->debug("name is ".$focus->name);
}

$smarty = new vtigerCRM_Smarty;

$hidden = '<form border="0" action="index.php" method="post" name="form" id="form">';
        $hidden .= '<input type="hidden" name="module">';
        $hidden .= '<input type="hidden" name="mode">';
        $hidden .= '<input type="hidden" name="'.$parent_id.'" value="'.$focus->id.'">';
        $hidden .= '<input type="hidden" name="return_module" value="Leads">';
        $hidden .= '<input type="hidden" name="return_action" value="DetailView">';
        $hidden .= '<input type="hidden" name="return_id" value="'.$focus->id.'">';
        $hidden .= '<input type="hidden" name="action">';
	$smarty->assign("HIDDEN",$hidden);

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
        $focus->id = "";
}
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$smarty->assign("id",$focus->id);
$smarty->assign("NAME",$focus->lastname.' '.$focus->firstname);
$related_array = getRelatedLists("Leads",$focus);
$smarty->assign("RELATEDLISTS", $related_array);
$smarty->assign("MODULE", $currentmodule);
$smarty->assign("ID",$RECORD );
$smarty->display("RelatedLists.tpl");

?>
