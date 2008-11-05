<?php
require_once('Smarty_setup.php');
require_once('modules/PurchaseOrder/PurchaseOrder.php');
require_once('include/utils/utils.php');

global $mod_strings,$app_strings,$theme,$currentModule,$current_user;
$focus = new PurchaseOrder();
$focus->mode = '';

$disp_view = getView($focus->mode);

$smarty = new vtigerCRM_Smarty;
$smarty->assign("BLOCKS",getBlocks($currentModule,$disp_view,$mode,$focus->column_fields,'',$focus->non_mass_edit_fields));	
$smarty->assign("IDS",$_REQUEST['idstring']);
$smarty->assign("MASS_EDIT","1");
$smarty->assign("MODULE",$currentModule);
$smarty->assign("APP",$app_strings);

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);

$smarty->display('MassEditForm.tpl');

?>
