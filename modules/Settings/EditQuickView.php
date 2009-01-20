<?php
/**
 * Created on 09-Oct-08
 */

require_once 'Smarty_setup.php';
require_once 'include/database/PearDatabase.php';
require_once 'include/utils/utils.php';
require_once 'include/utils/TooltipUtils.php';

global $mod_strings;
global $app_strings;
global $app_list_strings;

global $adb;
global $theme;

$smarty=new vtigerCRM_Smarty;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$module_name = $_REQUEST['module_name'];
$field_name = $_REQUEST['field_name'];

$related_fields = getFieldList($module_name,$field_name);

$fieldlist = array();
$tabid = getTabid($module_name);

$sql = "select * from vtiger_field where fieldname='$field_name' and tabid=$tabid and vtiger_field.presence in (0,2)";
$result = $adb->pquery($sql,array());
$fieldid = $adb->query_result($result,0,"fieldid");

$fieldlist[$module_name] = getRelatedFieldslist($fieldid, $related_fields);
if($_REQUEST['module_name'] != ''){
	$smarty->assign("DEF_MODULE",$_REQUEST['module_name']);
}else{
	$smarty->assign("DEF_MODULE",'Accounts');
}

$smarty->assign("FIELDID",$fieldid);
$smarty->assign("FIELD_INFO",$module_name);
$smarty->assign("FIELD_LISTS",$fieldlist);
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);
echo $smarty->fetch("QuickView/EditQuickView.tpl");

?>
