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
require_once('modules/PriceBooks/PriceBook.php');

$focus = new PriceBook();

$currentmodule = $_REQUEST['module'];
$RECORD = $_REQUEST['record'];

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) 
{
	$focus->retrieve_entity_info($_REQUEST['record'],"PriceBooks");
	$focus->id = $_REQUEST['record'];
	$focus->name=$focus->column_fields['bookname'];
	$log->debug("PriceBook id =".$focus->id);
	$log->debug("PriceBook Name =".$focus->name);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
        $focus->id = "";
}

$related_array=getRelatedLists("PriceBooks",$focus);

$smarty = new vtigerCRM_Smarty;

if(isset($focus->name))
	$smarty->assign("NAME", $focus->name);
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$smarty->assign("id",$focus->id);
$smarty->assign("ID",$RECORD );
$smarty->assign("MODULE",$currentmodule);
$smarty->assign("RELATEDLISTS", $related_array);
$smarty->assign("SINGLE_MOD","PriceBook");
$smarty->display("RelatedLists.tpl");

?>
