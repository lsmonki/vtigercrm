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

require_once('include/database/PearDatabase.php');
require_once('Smarty_setup.php');
require_once('modules/Campaigns/Campaign.php');
require_once('include/utils/utils.php');

$focus = new Campaign();

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) 
{
    $focus->retrieve_entity_info($_REQUEST['record'],"Campaigns");
    $focus->name=$focus->column_fields['campaignname'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
        $focus->id = "";
}
global $app_strings,$mod_strings,$theme,$currentModule;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
else $smarty->assign("NAME", "");
$smarty->assign("BLOCKS", getBlocks($currentModule,"detail_view",'',$focus->column_fields));

$smarty->assign("CUSTOMFIELD", $cust_fld);
$smarty->assign("SINGLE_MOD",$app_strings['Campaign']);
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if(isPermitted("Campaigns","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");

if(isPermitted("Campaigns","Delete",$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");

$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("ID", $_REQUEST['record']);


$campaign_tables = Array('campaign','crmentity','campaignscf');
$tabid = getTabid("Campaigns");
$validationData = getDBValidationData($campaign_tables,$tabid);
$data = split_validationdataArray($validationData);

$smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

$smarty->assign("MODULE",$app_strings['Campaigns']);
$smarty->display("DetailView.tpl");
$focus->id = $_REQUEST['record'];

?>
