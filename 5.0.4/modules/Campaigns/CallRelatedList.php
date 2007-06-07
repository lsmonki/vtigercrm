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
require_once('modules/Campaigns/Campaigns.php');
require_once('include/utils/utils.php');
//Redirecting Header for single page layout 
require_once('user_privileges/default_module_view.php');
global $singlepane_view;
if($singlepane_view == 'true' && $_REQUEST['action'] == 'CallRelatedList' )
{
	header("Location:index.php?action=DetailView&module=".$_REQUEST['module']."&record=".$_REQUEST['record']."&parenttab=".$_REQUEST['parenttab']);
}
else
{
$focus = new Campaigns();
$currentmodule = $_REQUEST['module'];
$RECORD = $_REQUEST['record'];
if(isset($_REQUEST['record']) && $_REQUEST['record']!='') {
    $focus->retrieve_entity_info($_REQUEST['record'],"Campaigns");
    $focus->id = $_REQUEST['record'];
    $focus->name=$focus->column_fields['campaignname'];

$log->debug("id is ".$focus->id);

$log->debug("name is ".$focus->name);

}

global $mod_strings;
global $app_strings,$adb;
global $theme;
global $currentModule;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty = new vtigerCRM_Smarty;

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
        $focus->id = "";
}
if(isset($_REQUEST['mode']) && $_REQUEST['mode'] != ' ') {
	$smarty->assign("OP_MODE",$_REQUEST['mode']);
}
if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
$related_array=getRelatedLists($currentModule,$focus);
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

$category = getParentTab();
$smarty->assign("CATEGORY",$category);
$smarty->assign("UPDATEINFO",updateInfo($focus->id));
$smarty->assign("ID",$focus->id);
$smarty->assign("MODULE",$currentmodule);
$smarty->assign("SINGLE_MOD",$app_strings['Campaign']);
$smarty->assign("MOD",$mod_strings);
$smarty->assign("APP",$app_strings);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

if(isset($_REQUEST['ajax']) && $_REQUEST['ajax'] != '')
        $smarty->display("RelatedListContents.tpl");
else
	$smarty->display("RelatedLists.tpl");
}
?>
