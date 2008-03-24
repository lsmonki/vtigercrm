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
require_once('modules/Campaigns/Campaigns.php');
require_once('include/utils/utils.php');
require_once('user_privileges/default_module_view.php');

$focus = new Campaigns();

if(isset($_REQUEST['record']) && $_REQUEST['record']!= null ) 
{
    $focus->retrieve_entity_info($_REQUEST['record'],"Campaigns");
    $focus->name=$focus->column_fields['campaignname'];
    $focus->id = $_REQUEST['record'];
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
        $focus->id = "";
}
global $app_strings,$mod_strings,$theme,$currentModule,$default_module_view,$adb;

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
$smarty->assign("SINGLE_MOD",'Campaign');
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

if(isPermitted("Campaigns","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");

if(isPermitted("Campaigns","Delete",$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");

$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("UPDATEINFO",updateInfo($focus->id));
$smarty->assign("ID", $_REQUEST['record']);


$tabid = getTabid("Campaigns");
$validationData = getDBValidationData($focus->tab_name,$tabid);
$data = split_validationdataArray($validationData);

$smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);
$smarty->assign("IS_REL_LIST",isPresentRelatedLists($currentModule));

if($singlepane_view == 'true')
{
	$related_array = getRelatedLists($currentModule,$focus);
	$smarty->assign("RELATEDLISTS", $related_array);
	/* To get Contacts CustomView -START */
	$sql = "select vtiger_customview.* from vtiger_customview inner join vtiger_tab on vtiger_tab.name = vtiger_customview.entitytype where vtiger_tab.tabid=".getTabid('Contacts');
	$result = $adb->query($sql);
	$chtml = "<select id='cont_cv_list'><option value='None'>-- ".$mod_strings['Select One']." --</option>";
	while($cvrow=$adb->fetch_array($result))
	{
		$chtml .= "<option value=\"".$cvrow['cvid']."\">".$cvrow['viewname']."</option>";
	}
	$chtml .= "</select>";
	$smarty->assign("CONTCVCOMBO",$chtml);
	/* To get Contacts CustomView -END */
	
	/* To get Leads CustomView -START */
	$sql = "select vtiger_customview.* from vtiger_customview inner join vtiger_tab on vtiger_tab.name = vtiger_customview.entitytype where vtiger_tab.tabid=".getTabid('Leads');
	$result = $adb->query($sql);
	$lhtml = "<select id='lead_cv_list'><option value='None'>-- ".$mod_strings['Select One']." --</option>";
	while($cvrow=$adb->fetch_array($result))
	{
		$lhtml .= "<option value=\"".$cvrow['cvid']."\">".$cvrow['viewname']."</option>";
	}
	$lhtml .= "</select>";
	$smarty->assign("LEADCVCOMBO",$lhtml);
	/* To get Leads CustomView -END */

}

$smarty->assign("SinglePane_View", $singlepane_view);

$smarty->assign("MODULE",$currentModule);
$smarty->assign("EDIT_PERMISSION",isPermitted($currentModule,'EditView',$_REQUEST[record]));
$smarty->display("DetailView.tpl");

$focus->id = $_REQUEST['record'];

?>
