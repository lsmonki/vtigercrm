<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Potentials/DetailView.php,v 1.28 2005/04/18 10:37:49 samk Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('modules/Potentials/Opportunity.php');
require_once('modules/Potentials/Forms.php');
require_once('include/CustomFieldUtil.php');
require_once('include/utils/utils.php');

global $mod_strings;
global $app_strings;

$focus = new Potential();
$smarty = new vtigerCRM_Smarty;

if(isset($_REQUEST['record'])  && $_REQUEST['record']!='') {
    $focus->retrieve_entity_info($_REQUEST['record'],"Potentials");
    $focus->id = $_REQUEST['record'];	
    $focus->name=$focus->column_fields['potentialname'];
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
} 

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Potential detail view");

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("ID", $focus->id);
$smarty->assign("UPDATEINFO",updateInfo($focus->id));

$smarty->assign("ACCOUNTID",$focus->column_fields['account_id']);

if (isset($focus->name)) $smarty->assign("NAME", $focus->name);
else $smarty->assign("NAME", "");

$smarty->assign("BLOCKS", getBlocks("Potentials","detail_view",'',$focus->column_fields));

$smarty->assign("CUSTOMFIELD", $cust_fld);
$smarty->assign("SINGLE_MOD","Potential");
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$permissionData = $_SESSION['action_permission_set'];

if(isPermitted("Potentials",1,$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");
if(isPermitted("Invoice",1,$_REQUEST['record']) == 'yes')
	$smarty->assign("CONVERTINVOICE","permitted");
if(isPermitted("Potentials",2,$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");


//Security check for related list
global $profile_id;
$tab_per_Data = getAllTabsPermission($profile_id);
$permissionData = $_SESSION['action_permission_set'];
$smarty->assign("CONVERTMODE",'potentoinvoice');
$smarty->assign("MODULE","Potentials");
$smarty->display("DetailView.tpl");
?>
