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
require_once('modules/Leads/Lead.php');
require_once('include/utils/utils.php');
$focus = new Lead();
$MODULE = $_REQUEST['module'];
$RECORD = $_REQUEST['record'];
$currentmodule = $_REQUEST['module'];
if(isset($_REQUEST['record']) && $_REQUEST['record']!='') {
    $focus->retrieve_entity_info($_REQUEST['record'],"Leads");
    $focus->id = $_REQUEST['record'];
    $focus->firstname=$focus->column_fields['firstname'];
    $focus->lastname=$focus->column_fields['lastname'];
$log->debug("id is ".$focus->id);
$log->debug("name is ".$focus->name);
}

global $mod_strings;
global $app_strings;
global $theme;
global $currentModule;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty = new vtigerCRM_Smarty;


if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
        $focus->id = "";
}

 if(!$_SESSION['rlvs'][$module])
 {
       unset($_SESSION['rlvs']);
 }
	
$sql1 = $adb->query('select vtiger_campaignid from vtiger_leaddetails where leadid='.$focus->id);
$campaignid = $adb->query_result($sql1,0,'campaignid');
if($campaignid == 0) $campaignid='';
$smarty->assign("campaignid",$campaignid);

$category = getParentTab();
$smarty->assign("CATEGORY",$category);
$parent_email = getEmailParentsList('Leads',$focus->id);
        $smarty->assign("HIDDEN_PARENTS_LIST",$parent_email);

$smarty->assign("id",$focus->id);
$smarty->assign("NAME",$focus->lastname.' '.$focus->firstname);
$related_array = getRelatedLists($currentModule,$focus);
$smarty->assign("RELATEDLISTS", $related_array);
$smarty->assign("SINGLE_MOD",$app_strings['Lead']);
$smarty->assign("REDIR_MOD","leads");
$smarty->assign("MODULE", $currentmodule);
$smarty->assign("ID",$RECORD );
$smarty->assign("UPDATEINFO",updateInfo($focus->id));
$smarty->assign("MOD",$mod_strings);
$smarty->assign("APP",$app_strings);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);
$smarty->display("RelatedLists.tpl");

?>
