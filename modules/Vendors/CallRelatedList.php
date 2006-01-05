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
require_once('modules/Vendors/Vendor.php');

$focus = new Vendor();

$currentmodule = $_REQUEST['module'];
$RECORD = $_REQUEST['record'];

if(isset($_REQUEST['record']) && $_REQUEST['record'] != '') 
{
	$focus->retrieve_entity_info($_REQUEST['record'],"Vendors");
	$focus->id = $_REQUEST['record'];
	$focus->name=$focus->column_fields['vendorname'];
	$log->debug("Vendor id =".$focus->id);
	$log->debug("Vendor name =".$focus->name);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
        $focus->id = "";
}

$related_array=getRelatedLists("Vendors",$focus);

$smarty = new vtigerCRM_Smarty;

$hidden = '<form border="0" action="index.php" method="post" name="form" id="form">';
        $hidden .= '<input type="hidden" name="module">';
        $hidden .= '<input type="hidden" name="mode">';
        $hidden .= '<input type="hidden" name="'.$sid.'" value="'.$focus->id.'">';
        $hidden .= '<input type="hidden" name="return_module" value="Vendors">';
        $hidden .= '<input type="hidden" name="return_action" value="CallRelatedList">';
        $hidden .= '<input type="hidden" name="return_id" value="'.$focus->id.'">';
        $hidden .= '<input type="hidden" name="parent_id" value="'.$focus->id.'">';
        $hidden .= '<input type="hidden" name="action">';
	$smarty->assign("HIDDEN",$hidden);

$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if(isset($focus->name))

$smarty->assign("NAME", $focus->name);
$smarty->assign("id",$focus->id);
$smarty->assign("ID",$RECORD );
$smarty->assign("MODULE",$currentmodule);
$smarty->assign("RELATEDLISTS", $related_array);
$smarty->display("RelatedLists.tpl");

?>
