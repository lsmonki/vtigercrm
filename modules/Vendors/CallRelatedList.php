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
require_once('modules/Vendors/Vendors.php');
//Redirecting Header for single page layout
require_once('user_privileges/default_module_view.php');
global $singlepane_view;
if($singlepane_view == 'true' && $_REQUEST['action'] == 'CallRelatedList' )
{
	header("Location:index.php?action=DetailView&module=".$_REQUEST['module']."&record=".$_REQUEST['record']."&parenttab=".$_REQUEST['parenttab']);
}
else
{
global $currentModule;
$focus = new Vendors();

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

$related_array=getRelatedLists($currentModule,$focus);

global $mod_strings;
global $app_strings;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$smarty = new vtigerCRM_Smarty;

$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if(isset($_REQUEST['mode']) && $_REQUEST['mode'] != ' ') {
        $smarty->assign("OP_MODE",$_REQUEST['mode']);
}
if(isset($focus->name))
$smarty->assign("UPDATEINFO",updateInfo($focus->id));
$smarty->assign("NAME", $focus->name);
$smarty->assign("ID",$focus->id);

// Module Sequence Numbering
$smarty->assign("MOD_SEQ_ID", $focus->column_fields['vendor_no']);
// END

$smarty->assign("MODULE",$currentmodule);
$smarty->assign("SINGLE_MOD",$app_strings['Vendor']);
$smarty->assign("RELATEDLISTS", $related_array);
$smarty->assign("MOD",$mod_strings);
$smarty->assign("APP",$app_strings);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);
$smarty->display("RelatedLists.tpl");
}
?>
